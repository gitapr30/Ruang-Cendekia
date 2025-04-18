/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module engine/view/renderer
 */
import ViewText from './text.js';
import ViewPosition from './position.js';
import { INLINE_FILLER, INLINE_FILLER_LENGTH, startsWithFiller, isInlineFiller } from './filler.js';
import { CKEditorError, ObservableMixin, diff, env, fastDiff, insertAt, isComment, isNode, isText, remove, indexOf } from '@ckeditor/ckeditor5-utils';
import '../../theme/renderer.css';
/**
 * Renderer is responsible for updating the DOM structure and the DOM selection based on
 * the {@link module:engine/view/renderer~Renderer#markToSync information about updated view nodes}.
 * In other words, it renders the view to the DOM.
 *
 * Its main responsibility is to make only the necessary, minimal changes to the DOM. However, unlike in many
 * virtual DOM implementations, the primary reason for doing minimal changes is not the performance but ensuring
 * that native editing features such as text composition, autocompletion, spell checking, selection's x-index are
 * affected as little as possible.
 *
 * Renderer uses {@link module:engine/view/domconverter~DomConverter} to transform view nodes and positions
 * to and from the DOM.
 */
export default class Renderer extends /* #__PURE__ */ ObservableMixin() {
    /**
     * Creates a renderer instance.
     *
     * @param domConverter Converter instance.
     * @param selection View selection.
     */
    constructor(domConverter, selection) {
        super();
        /**
         * Set of DOM Documents instances.
         */
        this.domDocuments = new Set();
        /**
         * Set of nodes which attributes changed and may need to be rendered.
         */
        this.markedAttributes = new Set();
        /**
         * Set of elements which child lists changed and may need to be rendered.
         */
        this.markedChildren = new Set();
        /**
         * Set of text nodes which text data changed and may need to be rendered.
         */
        this.markedTexts = new Set();
        /**
         * The text node in which the inline filler was rendered.
         */
        this._inlineFiller = null;
        /**
         * DOM element containing fake selection.
         */
        this._fakeSelectionContainer = null;
        this.domConverter = domConverter;
        this.selection = selection;
        this.set('isFocused', false);
        this.set('isSelecting', false);
        this.set('isComposing', false);
        // Rendering the selection and inline filler manipulation should be postponed in (non-Android) Blink until the user finishes
        // creating the selection in DOM to avoid accidental selection collapsing
        // (https://github.com/ckeditor/ckeditor5/issues/10562, https://github.com/ckeditor/ckeditor5/issues/10723).
        // When the user stops selecting, all pending changes should be rendered ASAP, though.
        if (env.isBlink && !env.isAndroid) {
            this.on('change:isSelecting', () => {
                if (!this.isSelecting) {
                    this.render();
                }
            });
        }
    }
    /**
     * Marks a view node to be updated in the DOM by {@link #render `render()`}.
     *
     * Note that only view nodes whose parents have corresponding DOM elements need to be marked to be synchronized.
     *
     * @see #markedAttributes
     * @see #markedChildren
     * @see #markedTexts
     *
     * @param type Type of the change.
     * @param node ViewNode to be marked.
     */
    markToSync(type, node) {
        if (type === 'text') {
            if (this.domConverter.mapViewToDom(node.parent)) {
                this.markedTexts.add(node);
            }
        }
        else {
            // If the node has no DOM element it is not rendered yet,
            // its children/attributes do not need to be marked to be sync.
            if (!this.domConverter.mapViewToDom(node)) {
                return;
            }
            if (type === 'attributes') {
                this.markedAttributes.add(node);
            }
            else if (type === 'children') {
                this.markedChildren.add(node);
            }
            else {
                // eslint-disable-next-line @typescript-eslint/no-unused-vars
                const unreachable = type;
                /**
                 * Unknown type passed to Renderer.markToSync.
                 *
                 * @error view-renderer-unknown-type
                 */
                throw new CKEditorError('view-renderer-unknown-type', this);
            }
        }
    }
    /**
     * Renders all buffered changes ({@link #markedAttributes}, {@link #markedChildren} and {@link #markedTexts}) and
     * the current view selection (if needed) to the DOM by applying a minimal set of changes to it.
     *
     * Renderer tries not to break the text composition (e.g. IME) and x-index of the selection,
     * so it does as little as it is needed to update the DOM.
     *
     * Renderer also handles {@link module:engine/view/filler fillers}. Especially, it checks if the inline filler is needed
     * at the selection position and adds or removes it. To prevent breaking text composition inline filler will not be
     * removed as long as the selection is in the text node which needed it at first.
     */
    render() {
        // Ignore rendering while in the composition mode. Composition events are not cancellable and browser will modify the DOM tree.
        // All marked elements, attributes, etc. will wait until next render after the composition ends.
        // On Android composition events are immediately applied to the model, so we don't need to skip rendering,
        // and we should not do it because the difference between view and DOM could lead to position mapping problems.
        if (this.isComposing && !env.isAndroid) {
            // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
            // @if CK_DEBUG_TYPING // 	console.info( '%c[Renderer]%c Rendering aborted while isComposing.',
            // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', 'font-style: italic'
            // @if CK_DEBUG_TYPING // 	);
            // @if CK_DEBUG_TYPING // }
            return;
        }
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
        // @if CK_DEBUG_TYPING // 	console.group( '%c[Renderer]%c Rendering',
        // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', 'font-weight: bold'
        // @if CK_DEBUG_TYPING // 	);
        // @if CK_DEBUG_TYPING // }
        let inlineFillerPosition = null;
        const isInlineFillerRenderingPossible = env.isBlink && !env.isAndroid ? !this.isSelecting : true;
        // Refresh mappings.
        for (const element of this.markedChildren) {
            this._updateChildrenMappings(element);
        }
        // Don't manipulate inline fillers while the selection is being made in (non-Android) Blink to prevent accidental
        // DOM selection collapsing
        // (https://github.com/ckeditor/ckeditor5/issues/10562, https://github.com/ckeditor/ckeditor5/issues/10723).
        if (isInlineFillerRenderingPossible) {
            // There was inline filler rendered in the DOM but it's not
            // at the selection position any more, so we can remove it
            // (cause even if it's needed, it must be placed in another location).
            if (this._inlineFiller && !this._isSelectionInInlineFiller()) {
                this._removeInlineFiller();
            }
            // If we've got the filler, let's try to guess its position in the view.
            if (this._inlineFiller) {
                inlineFillerPosition = this._getInlineFillerPosition();
            }
            // Otherwise, if it's needed, create it at the selection position.
            else if (this._needsInlineFillerAtSelection()) {
                inlineFillerPosition = this.selection.getFirstPosition();
                // Do not use `markToSync` so it will be added even if the parent is already added.
                this.markedChildren.add(inlineFillerPosition.parent);
            }
        }
        // Make sure the inline filler has any parent, so it can be mapped to view position by DomConverter.
        else if (this._inlineFiller && this._inlineFiller.parentNode) {
            // While the user is making selection, preserve the inline filler at its original position.
            inlineFillerPosition = this.domConverter.domPositionToView(this._inlineFiller);
            // While down-casting the document selection attributes, all existing empty
            // attribute elements (for selection position) are removed from the view and DOM,
            // so make sure that we were able to map filler position.
            // https://github.com/ckeditor/ckeditor5/issues/12026
            if (inlineFillerPosition && inlineFillerPosition.parent.is('$text')) {
                // The inline filler position is expected to be before the text node.
                inlineFillerPosition = ViewPosition._createBefore(inlineFillerPosition.parent);
            }
        }
        for (const element of this.markedAttributes) {
            this._updateAttrs(element);
        }
        for (const element of this.markedChildren) {
            this._updateChildren(element, { inlineFillerPosition });
        }
        for (const node of this.markedTexts) {
            if (!this.markedChildren.has(node.parent) && this.domConverter.mapViewToDom(node.parent)) {
                this._updateText(node, { inlineFillerPosition });
            }
        }
        // * Check whether the inline filler is required and where it really is in the DOM.
        //   At this point in most cases it will be in the DOM, but there are exceptions.
        //   For example, if the inline filler was deep in the created DOM structure, it will not be created.
        //   Similarly, if it was removed at the beginning of this function and then neither text nor children were updated,
        //   it will not be present. Fix those and similar scenarios.
        // * Don't manipulate inline fillers while the selection is being made in (non-Android) Blink to prevent accidental
        //   DOM selection collapsing
        //   (https://github.com/ckeditor/ckeditor5/issues/10562, https://github.com/ckeditor/ckeditor5/issues/10723).
        if (isInlineFillerRenderingPossible) {
            if (inlineFillerPosition) {
                const fillerDomPosition = this.domConverter.viewPositionToDom(inlineFillerPosition);
                const domDocument = fillerDomPosition.parent.ownerDocument;
                if (!startsWithFiller(fillerDomPosition.parent)) {
                    // Filler has not been created at filler position. Create it now.
                    this._inlineFiller = addInlineFiller(domDocument, fillerDomPosition.parent, fillerDomPosition.offset);
                }
                else {
                    // Filler has been found, save it.
                    this._inlineFiller = fillerDomPosition.parent;
                }
            }
            else {
                // There is no filler needed.
                this._inlineFiller = null;
            }
        }
        // First focus the new editing host, then update the selection.
        // Otherwise, FF may throw an error (https://github.com/ckeditor/ckeditor5/issues/721).
        this._updateFocus();
        this._updateSelection();
        this.domConverter._clearTemporaryCustomProperties();
        this.markedTexts.clear();
        this.markedAttributes.clear();
        this.markedChildren.clear();
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
        // @if CK_DEBUG_TYPING // 	console.groupEnd();
        // @if CK_DEBUG_TYPING // }
    }
    /**
     * Updates mappings of view element's children.
     *
     * Children that were replaced in the view structure by similar elements (same tag name) are treated as 'replaced'.
     * This means that their mappings can be updated so the new view elements are mapped to the existing DOM elements.
     * Thanks to that these elements do not need to be re-rendered completely.
     *
     * @param viewElement The view element whose children mappings will be updated.
     */
    _updateChildrenMappings(viewElement) {
        const domElement = this.domConverter.mapViewToDom(viewElement);
        if (!domElement) {
            // If there is no `domElement` it means that it was already removed from DOM and there is no need to process it.
            return;
        }
        // Removing nodes from the DOM as we iterate can cause `actualDomChildren`
        // (which is a live-updating `NodeList`) to get out of sync with the
        // indices that we compute as we iterate over `actions`.
        // This would produce incorrect element mappings.
        //
        // Converting live list to an array to make the list static.
        const actualDomChildren = Array.from(domElement.childNodes);
        const expectedDomChildren = Array.from(this.domConverter.viewChildrenToDom(viewElement, { withChildren: false }));
        const diff = this._diffNodeLists(actualDomChildren, expectedDomChildren);
        const actions = this._findUpdateActions(diff, actualDomChildren, expectedDomChildren, areSimilarElements);
        if (actions.indexOf('update') !== -1) {
            const counter = { equal: 0, insert: 0, delete: 0 };
            for (const action of actions) {
                if (action === 'update') {
                    const insertIndex = counter.equal + counter.insert;
                    const deleteIndex = counter.equal + counter.delete;
                    const viewChild = viewElement.getChild(insertIndex);
                    // UIElement and RawElement are special cases. Their children are not stored in a view (#799)
                    // so we cannot use them with replacing flow (since they use view children during rendering
                    // which will always result in rendering empty elements).
                    if (viewChild && !viewChild.is('uiElement') && !viewChild.is('rawElement')) {
                        this._updateElementMappings(viewChild, actualDomChildren[deleteIndex]);
                    }
                    remove(expectedDomChildren[insertIndex]);
                    counter.equal++;
                }
                else {
                    counter[action]++;
                }
            }
        }
    }
    /**
     * Updates mappings of a given view element.
     *
     * @param viewElement The view element whose mappings will be updated.
     * @param domElement The DOM element representing the given view element.
     */
    _updateElementMappings(viewElement, domElement) {
        // Remap 'DomConverter' bindings.
        this.domConverter.unbindDomElement(domElement);
        this.domConverter.bindElements(domElement, viewElement);
        // View element may have children which needs to be updated, but are not marked, mark them to update.
        this.markedChildren.add(viewElement);
        // Because we replace new view element mapping with the existing one, the corresponding DOM element
        // will not be rerendered. The new view element may have different attributes than the previous one.
        // Since its corresponding DOM element will not be rerendered, new attributes will not be added
        // to the DOM, so we need to mark it here to make sure its attributes gets updated. See #1427 for more
        // detailed case study.
        // Also there are cases where replaced element is removed from the view structure and then has
        // its attributes changed or removed. In such cases the element will not be present in `markedAttributes`
        // and also may be the same (`element.isSimilar()`) as the reused element not having its attributes updated.
        // To prevent such situations we always mark reused element to have its attributes rerenderd (#1560).
        this.markedAttributes.add(viewElement);
    }
    /**
     * Gets the position of the inline filler based on the current selection.
     * Here, we assume that we know that the filler is needed and
     * {@link #_isSelectionInInlineFiller is at the selection position}, and, since it is needed,
     * it is somewhere at the selection position.
     *
     * Note: The filler position cannot be restored based on the filler's DOM text node, because
     * when this method is called (before rendering), the bindings will often be broken. View-to-DOM
     * bindings are only dependable after rendering.
     */
    _getInlineFillerPosition() {
        const firstPos = this.selection.getFirstPosition();
        if (firstPos.parent.is('$text')) {
            return ViewPosition._createBefore(firstPos.parent);
        }
        else {
            return firstPos;
        }
    }
    /**
     * Returns `true` if the selection has not left the inline filler's text node.
     * If it is `true`, it means that the filler had been added for a reason and the selection did not
     * leave the filler's text node. For example, the user can be in the middle of a composition so it should not be touched.
     *
     * @returns `true` if the inline filler and selection are in the same place.
     */
    _isSelectionInInlineFiller() {
        if (this.selection.rangeCount != 1 || !this.selection.isCollapsed) {
            return false;
        }
        // Note, we can't check if selection's position equals position of the
        // this._inlineFiller node, because of #663. We may not be able to calculate
        // the filler's position in the view at this stage.
        // Instead, we check it the other way – whether selection is anchored in
        // that text node or next to it.
        // Possible options are:
        // "FILLER{}"
        // "FILLERadded-text{}"
        const selectionPosition = this.selection.getFirstPosition();
        const position = this.domConverter.viewPositionToDom(selectionPosition);
        if (position && isText(position.parent) && startsWithFiller(position.parent)) {
            return true;
        }
        return false;
    }
    /**
     * Removes the inline filler.
     */
    _removeInlineFiller() {
        const domFillerNode = this._inlineFiller;
        // Something weird happened and the stored node doesn't contain the filler's text.
        if (!startsWithFiller(domFillerNode)) {
            /**
             * The inline filler node was lost. Most likely, something overwrote the filler text node
             * in the DOM.
             *
             * @error view-renderer-filler-was-lost
             */
            throw new CKEditorError('view-renderer-filler-was-lost', this);
        }
        if (isInlineFiller(domFillerNode)) {
            domFillerNode.remove();
        }
        else {
            domFillerNode.data = domFillerNode.data.substr(INLINE_FILLER_LENGTH);
        }
        this._inlineFiller = null;
    }
    /**
     * Checks if the inline {@link module:engine/view/filler filler} should be added.
     *
     * @returns `true` if the inline filler should be added.
     */
    _needsInlineFillerAtSelection() {
        if (this.selection.rangeCount != 1 || !this.selection.isCollapsed) {
            return false;
        }
        const selectionPosition = this.selection.getFirstPosition();
        const selectionParent = selectionPosition.parent;
        const selectionOffset = selectionPosition.offset;
        // If there is no DOM root we do not care about fillers.
        if (!this.domConverter.mapViewToDom(selectionParent.root)) {
            return false;
        }
        if (!(selectionParent.is('element'))) {
            return false;
        }
        // Prevent adding inline filler inside elements with contenteditable=false.
        // https://github.com/ckeditor/ckeditor5-engine/issues/1170
        if (!isEditable(selectionParent)) {
            return false;
        }
        const nodeBefore = selectionPosition.nodeBefore;
        const nodeAfter = selectionPosition.nodeAfter;
        if (nodeBefore instanceof ViewText || nodeAfter instanceof ViewText) {
            return false;
        }
        // We have block filler, we do not need inline one.
        if (selectionOffset === selectionParent.getFillerOffset() && (!nodeBefore || !nodeBefore.is('element', 'br'))) {
            return false;
        }
        // Do not use inline filler while typing outside inline elements on Android.
        // The deleteContentBackward would remove part of the inline filler instead of removing last letter in a link.
        if (env.isAndroid && (nodeBefore || nodeAfter)) {
            return false;
        }
        return true;
    }
    /**
     * Checks if text needs to be updated and possibly updates it.
     *
     * @param viewText View text to update.
     * @param options.inlineFillerPosition The position where the inline filler should be rendered.
     */
    _updateText(viewText, options) {
        const domText = this.domConverter.findCorrespondingDomText(viewText);
        const newDomText = this.domConverter.viewToDom(viewText);
        let expectedText = newDomText.data;
        const filler = options.inlineFillerPosition;
        if (filler && filler.parent == viewText.parent && filler.offset == viewText.index) {
            expectedText = INLINE_FILLER + expectedText;
        }
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
        // @if CK_DEBUG_TYPING // 	console.group( '%c[Renderer]%c Update text',
        // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', 'font-weight: normal'
        // @if CK_DEBUG_TYPING // 	);
        // @if CK_DEBUG_TYPING // }
        this._updateTextNode(domText, expectedText);
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
        // @if CK_DEBUG_TYPING // 	console.groupEnd();
        // @if CK_DEBUG_TYPING // }
    }
    /**
     * Checks if attribute list needs to be updated and possibly updates it.
     *
     * @param viewElement The view element to update.
     */
    _updateAttrs(viewElement) {
        const domElement = this.domConverter.mapViewToDom(viewElement);
        if (!domElement) {
            // If there is no `domElement` it means that 'viewElement' is outdated as its mapping was updated
            // in 'this._updateChildrenMappings()'. There is no need to process it as new view element which
            // replaced old 'viewElement' mapping was also added to 'this.markedAttributes'
            // in 'this._updateChildrenMappings()' so it will be processed separately.
            return;
        }
        // Remove attributes from DOM elements if they do not exist in the view.
        //
        // Note: It is important to first remove DOM attributes and then set new ones, because some view attributes may be renamed
        // as they are set on DOM (due to unsafe attributes handling). If we set the view attribute first, and then remove
        // non-existing DOM attributes, then we would remove the attribute that we just set.
        //
        // Note: The domElement.attributes is a live collection, so we need to convert it to an array to avoid issues.
        for (const domAttr of Array.from(domElement.attributes)) {
            const key = domAttr.name;
            // All other attributes not present in the DOM should be removed.
            if (!viewElement.hasAttribute(key)) {
                this.domConverter.removeDomElementAttribute(domElement, key);
            }
        }
        // Add or overwrite attributes.
        for (const key of viewElement.getAttributeKeys()) {
            this.domConverter.setDomElementAttribute(domElement, key, viewElement.getAttribute(key), viewElement);
        }
    }
    /**
     * Checks if elements child list needs to be updated and possibly updates it.
     *
     * Note that on Android, to reduce the risk of composition breaks, it tries to update data of an existing
     * child text nodes instead of replacing them completely.
     *
     * @param viewElement View element to update.
     * @param options.inlineFillerPosition The position where the inline filler should be rendered.
     */
    _updateChildren(viewElement, options) {
        const domElement = this.domConverter.mapViewToDom(viewElement);
        if (!domElement) {
            // If there is no `domElement` it means that it was already removed from DOM.
            // There is no need to process it. It will be processed when re-inserted.
            return;
        }
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
        // @if CK_DEBUG_TYPING // 	console.group( '%c[Renderer]%c Update children',
        // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', 'font-weight: normal'
        // @if CK_DEBUG_TYPING // 	);
        // @if CK_DEBUG_TYPING // }
        // IME on Android inserts a new text node while typing after a link
        // instead of updating an existing text node that follows the link.
        // We must normalize those text nodes so the diff won't get confused.
        // https://github.com/ckeditor/ckeditor5/issues/12574.
        if (env.isAndroid) {
            let previousDomNode = null;
            for (const domNode of Array.from(domElement.childNodes)) {
                if (previousDomNode && isText(previousDomNode) && isText(domNode)) {
                    domElement.normalize();
                    break;
                }
                previousDomNode = domNode;
            }
        }
        const inlineFillerPosition = options.inlineFillerPosition;
        const actualDomChildren = domElement.childNodes;
        const expectedDomChildren = Array.from(this.domConverter.viewChildrenToDom(viewElement, { bind: true }));
        // Inline filler element has to be created as it is present in the DOM, but not in the view. It is required
        // during diffing so text nodes could be compared correctly and also during rendering to maintain
        // proper order and indexes while updating the DOM.
        if (inlineFillerPosition && inlineFillerPosition.parent === viewElement) {
            addInlineFiller(domElement.ownerDocument, expectedDomChildren, inlineFillerPosition.offset);
        }
        const diff = this._diffNodeLists(actualDomChildren, expectedDomChildren);
        // We need to make sure that we update the existing text node and not replace it with another one.
        // The composition and different "language" browser extensions are fragile to text node being completely replaced.
        const actions = this._findUpdateActions(diff, actualDomChildren, expectedDomChildren, areTextNodes);
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping && actions.every( a => a == 'equal' ) ) {
        // @if CK_DEBUG_TYPING // 	console.info( '%c[Renderer]%c Nothing to update.',
        // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', 'font-style: italic'
        // @if CK_DEBUG_TYPING // 	);
        // @if CK_DEBUG_TYPING // }
        let i = 0;
        const nodesToUnbind = new Set();
        // Handle deletions first.
        // This is to prevent a situation where an element that already exists in `actualDomChildren` is inserted at a different
        // index in `actualDomChildren`. Since `actualDomChildren` is a `NodeList`, this works like move, not like an insert,
        // and it disrupts the whole algorithm. See https://github.com/ckeditor/ckeditor5/issues/6367.
        //
        // It doesn't matter in what order we remove or add nodes, as long as we remove and add correct nodes at correct indexes.
        for (const action of actions) {
            if (action === 'delete') {
                // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
                // @if CK_DEBUG_TYPING //	const node = actualDomChildren[ i ];
                // @if CK_DEBUG_TYPING // 	if ( isText( node ) ) {
                // @if CK_DEBUG_TYPING // 		console.info( '%c[Renderer]%c Remove text node' +
                // @if CK_DEBUG_TYPING // 			`${ this.isComposing ? ' while composing (may break composition)' : '' }: ` +
                // @if CK_DEBUG_TYPING // 			`%c${ _escapeTextNodeData( node.data ) }%c (${ node.data.length })`,
                // @if CK_DEBUG_TYPING // 			'color: green; font-weight: bold',
                // @if CK_DEBUG_TYPING // 			this.isComposing ? 'color: red; font-weight: bold' : '', 'color: blue', ''
                // @if CK_DEBUG_TYPING // 		);
                // @if CK_DEBUG_TYPING // 	} else {
                // @if CK_DEBUG_TYPING // 		console.info( '%c[Renderer]%c Remove element' +
                // @if CK_DEBUG_TYPING // 			`${ this.isComposing ? ' while composing (may break composition)' : '' }: `,
                // @if CK_DEBUG_TYPING // 			'color: green; font-weight: bold',
                // @if CK_DEBUG_TYPING // 			this.isComposing ? 'color: red; font-weight: bold' : '',
                // @if CK_DEBUG_TYPING // 			node
                // @if CK_DEBUG_TYPING // 		);
                // @if CK_DEBUG_TYPING // 	}
                // @if CK_DEBUG_TYPING // }
                nodesToUnbind.add(actualDomChildren[i]);
                remove(actualDomChildren[i]);
            }
            else if (action === 'equal' || action === 'update') {
                i++;
            }
        }
        i = 0;
        for (const action of actions) {
            if (action === 'insert') {
                // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
                // @if CK_DEBUG_TYPING //	const node = expectedDomChildren[ i ];
                // @if CK_DEBUG_TYPING //	if ( isText( node ) ) {
                // @if CK_DEBUG_TYPING //		console.info( '%c[Renderer]%c Insert text node' +
                // @if CK_DEBUG_TYPING //			`${ this.isComposing ? ' while composing (may break composition)' : '' }: ` +
                // @if CK_DEBUG_TYPING //			`%c${ _escapeTextNodeData( node.data ) }%c (${ node.data.length })`,
                // @if CK_DEBUG_TYPING //			'color: green; font-weight: bold',
                // @if CK_DEBUG_TYPING //			this.isComposing ? 'color: red; font-weight: bold' : '',
                // @if CK_DEBUG_TYPING //			'color: blue', ''
                // @if CK_DEBUG_TYPING //		);
                // @if CK_DEBUG_TYPING //	} else {
                // @if CK_DEBUG_TYPING //		console.info( '%c[Renderer]%c Insert element:',
                // @if CK_DEBUG_TYPING //			'color: green; font-weight: bold', 'font-weight: normal',
                // @if CK_DEBUG_TYPING //			node
                // @if CK_DEBUG_TYPING //		);
                // @if CK_DEBUG_TYPING //	}
                // @if CK_DEBUG_TYPING // }
                insertAt(domElement, i, expectedDomChildren[i]);
                i++;
            }
            // Update the existing text node data.
            else if (action === 'update') {
                this._updateTextNode(actualDomChildren[i], expectedDomChildren[i].data);
                i++;
            }
            else if (action === 'equal') {
                // Force updating text nodes inside elements which did not change and do not need to be re-rendered (#1125).
                // Do it here (not in the loop above) because only after insertions the `i` index is correct.
                this._markDescendantTextToSync(this.domConverter.domToView(expectedDomChildren[i]));
                i++;
            }
        }
        // Unbind removed nodes. When node does not have a parent it means that it was removed from DOM tree during
        // comparison with the expected DOM. We don't need to check child nodes, because if child node was reinserted,
        // it was moved to DOM tree out of the removed node.
        for (const node of nodesToUnbind) {
            if (!node.parentNode) {
                this.domConverter.unbindDomElement(node);
            }
        }
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
        // @if CK_DEBUG_TYPING // 	console.groupEnd();
        // @if CK_DEBUG_TYPING // }
    }
    /**
     * Shorthand for diffing two arrays or node lists of DOM nodes.
     *
     * @param actualDomChildren Actual DOM children
     * @param expectedDomChildren Expected DOM children.
     * @returns The list of actions based on the {@link module:utils/diff~diff} function.
     */
    _diffNodeLists(actualDomChildren, expectedDomChildren) {
        actualDomChildren = filterOutFakeSelectionContainer(actualDomChildren, this._fakeSelectionContainer);
        return diff(actualDomChildren, expectedDomChildren, sameNodes.bind(null, this.domConverter));
    }
    /**
     * Finds DOM nodes that were replaced with the similar nodes (same tag name) in the view. All nodes are compared
     * within one `insert`/`delete` action group, for example:
     *
     * ```
     * Actual DOM:		<p><b>Foo</b>Bar<i>Baz</i><b>Bax</b></p>
     * Expected DOM:	<p>Bar<b>123</b><i>Baz</i><b>456</b></p>
     * Input actions:	[ insert, insert, delete, delete, equal, insert, delete ]
     * Output actions:	[ insert, replace, delete, equal, replace ]
     * ```
     *
     * @param actions Actions array which is a result of the {@link module:utils/diff~diff} function.
     * @param actualDom Actual DOM children
     * @param expectedDom Expected DOM children.
     * @param comparator A comparator function that should return `true` if the given node should be reused
     * (either by the update of a text node data or an element children list for similar elements).
     * @returns Actions array modified with the `update` actions.
     */
    _findUpdateActions(actions, actualDom, expectedDom, comparator) {
        // If there is no both 'insert' and 'delete' actions, no need to check for replaced elements.
        if (actions.indexOf('insert') === -1 || actions.indexOf('delete') === -1) {
            return actions;
        }
        let newActions = [];
        let actualSlice = [];
        let expectedSlice = [];
        const counter = { equal: 0, insert: 0, delete: 0 };
        for (const action of actions) {
            if (action === 'insert') {
                expectedSlice.push(expectedDom[counter.equal + counter.insert]);
            }
            else if (action === 'delete') {
                actualSlice.push(actualDom[counter.equal + counter.delete]);
            }
            else { // equal
                newActions = newActions.concat(diff(actualSlice, expectedSlice, comparator)
                    .map(action => action === 'equal' ? 'update' : action));
                newActions.push('equal');
                // Reset stored elements on 'equal'.
                actualSlice = [];
                expectedSlice = [];
            }
            counter[action]++;
        }
        return newActions.concat(diff(actualSlice, expectedSlice, comparator)
            .map(action => action === 'equal' ? 'update' : action));
    }
    /**
     * Checks if text needs to be updated and possibly updates it by removing and inserting only parts
     * of the data from the existing text node to reduce impact on the IME composition.
     *
     * @param domText DOM text node to update.
     * @param expectedText The expected data of a text node.
     */
    _updateTextNode(domText, expectedText) {
        const actualText = domText.data;
        if (actualText == expectedText) {
            // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
            // @if CK_DEBUG_TYPING // 	console.info( '%c[Renderer]%c Text node does not need update:%c ' +
            // @if CK_DEBUG_TYPING // 		`${ _escapeTextNodeData( actualText ) }%c (${ actualText.length })`,
            // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', 'font-style: italic', 'color: blue', ''
            // @if CK_DEBUG_TYPING // 	);
            // @if CK_DEBUG_TYPING // }
            return;
        }
        // Our approach to interleaving space character with NBSP might differ with the one implemented by the browser.
        // Avoid modifying the text node in the DOM if only NBSPs and spaces are interchanged.
        // We should avoid DOM modifications while composing to avoid breakage of composition.
        // See: https://github.com/ckeditor/ckeditor5/issues/13994.
        if (env.isAndroid && this.isComposing && actualText.replace(/\u00A0/g, ' ') == expectedText.replace(/\u00A0/g, ' ')) {
            // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
            // @if CK_DEBUG_TYPING // 	console.info( '%c[Renderer]%c Text node ignore NBSP changes while composing: ' +
            // @if CK_DEBUG_TYPING // 		`%c${ _escapeTextNodeData( actualText ) }%c (${ actualText.length }) ->` +
            // @if CK_DEBUG_TYPING // 		` %c${ _escapeTextNodeData( expectedText ) }%c (${ expectedText.length })`,
            // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', 'font-style: italic', 'color: blue', '', 'color: blue', ''
            // @if CK_DEBUG_TYPING // 	);
            // @if CK_DEBUG_TYPING // }
            return;
        }
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
        // @if CK_DEBUG_TYPING // 	console.info( '%c[Renderer]%c Update text node' +
        // @if CK_DEBUG_TYPING // 		`${ this.isComposing ? ' while composing (may break composition)' : '' }: ` +
        // @if CK_DEBUG_TYPING // 		`%c${ _escapeTextNodeData( actualText ) }%c (${ actualText.length }) ->` +
        // @if CK_DEBUG_TYPING // 		` %c${ _escapeTextNodeData( expectedText ) }%c (${ expectedText.length })`,
        // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', this.isComposing ? 'color: red; font-weight: bold' : '',
        // @if CK_DEBUG_TYPING // 		'color: blue', '', 'color: blue', ''
        // @if CK_DEBUG_TYPING // 	);
        // @if CK_DEBUG_TYPING // }
        this._updateTextNodeInternal(domText, expectedText);
    }
    /**
     * Part of the `_updateTextNode` method extracted for easier testing.
     */
    _updateTextNodeInternal(domText, expectedText) {
        const actions = fastDiff(domText.data, expectedText);
        for (const action of actions) {
            if (action.type === 'insert') {
                domText.insertData(action.index, action.values.join(''));
            }
            else { // 'delete'
                domText.deleteData(action.index, action.howMany);
            }
        }
    }
    /**
     * Marks text nodes to be synchronized.
     *
     * If a text node is passed, it will be marked. If an element is passed, all descendant text nodes inside it will be marked.
     *
     * @param viewNode View node to sync.
     */
    _markDescendantTextToSync(viewNode) {
        if (!viewNode) {
            return;
        }
        if (viewNode.is('$text')) {
            this.markedTexts.add(viewNode);
        }
        else if (viewNode.is('element')) {
            for (const child of viewNode.getChildren()) {
                this._markDescendantTextToSync(child);
            }
        }
    }
    /**
     * Checks if the selection needs to be updated and possibly updates it.
     */
    _updateSelection() {
        // Block updating DOM selection in (non-Android) Blink while the user is selecting to prevent accidental selection collapsing.
        // Note: Structural changes in DOM must trigger selection rendering, though. Nodes the selection was anchored
        // to, may disappear in DOM which would break the selection (e.g. in real-time collaboration scenarios).
        // https://github.com/ckeditor/ckeditor5/issues/10562, https://github.com/ckeditor/ckeditor5/issues/10723
        if (env.isBlink && !env.isAndroid && this.isSelecting && !this.markedChildren.size) {
            return;
        }
        // If there is no selection - remove DOM and fake selections.
        if (this.selection.rangeCount === 0) {
            this._removeDomSelection();
            this._removeFakeSelection();
            return;
        }
        const domRoot = this.domConverter.mapViewToDom(this.selection.editableElement);
        // Do nothing if there is no focus, or there is no DOM element corresponding to selection's editable element.
        if (!this.isFocused || !domRoot) {
            return;
        }
        // Render fake selection - create the fake selection container (if needed) and move DOM selection to it.
        if (this.selection.isFake) {
            this._updateFakeSelection(domRoot);
        }
        // There was a fake selection so remove it and update the DOM selection.
        // This is especially important on Android because otherwise IME will try to compose over the fake selection container.
        else if (this._fakeSelectionContainer && this._fakeSelectionContainer.isConnected) {
            this._removeFakeSelection();
            this._updateDomSelection(domRoot);
        }
        // Update the DOM selection in case of a plain selection change (no fake selection is involved).
        // On non-Android the whole rendering is disabled in composition mode (including DOM selection update),
        // but updating DOM selection should be also disabled on Android if in the middle of the composition
        // (to not interrupt it).
        else if (!(this.isComposing && env.isAndroid)) {
            this._updateDomSelection(domRoot);
        }
    }
    /**
     * Updates the fake selection.
     *
     * @param domRoot A valid DOM root where the fake selection container should be added.
     */
    _updateFakeSelection(domRoot) {
        const domDocument = domRoot.ownerDocument;
        if (!this._fakeSelectionContainer) {
            this._fakeSelectionContainer = createFakeSelectionContainer(domDocument);
        }
        const container = this._fakeSelectionContainer;
        // Bind fake selection container with the current selection *position*.
        this.domConverter.bindFakeSelection(container, this.selection);
        if (!this._fakeSelectionNeedsUpdate(domRoot)) {
            return;
        }
        if (!container.parentElement || container.parentElement != domRoot) {
            domRoot.appendChild(container);
        }
        container.textContent = this.selection.fakeSelectionLabel || '\u00A0';
        const domSelection = domDocument.getSelection();
        const domRange = domDocument.createRange();
        domSelection.removeAllRanges();
        domRange.selectNodeContents(container);
        domSelection.addRange(domRange);
    }
    /**
     * Updates the DOM selection.
     *
     * @param domRoot A valid DOM root where the DOM selection should be rendered.
     */
    _updateDomSelection(domRoot) {
        const domSelection = domRoot.ownerDocument.defaultView.getSelection();
        // Let's check whether DOM selection needs updating at all.
        if (!this._domSelectionNeedsUpdate(domSelection)) {
            return;
        }
        // Multi-range selection is not available in most browsers, and, at least in Chrome, trying to
        // set such selection, that is not continuous, throws an error. Because of that, we will just use anchor
        // and focus of view selection.
        // Since we are not supporting multi-range selection, we also do not need to check if proper editable is
        // selected. If there is any editable selected, it is okay (editable is taken from selection anchor).
        const anchor = this.domConverter.viewPositionToDom(this.selection.anchor);
        const focus = this.domConverter.viewPositionToDom(this.selection.focus);
        // @if CK_DEBUG_TYPING // if ( ( window as any ).logCKETyping ) {
        // @if CK_DEBUG_TYPING // 	console.info( '%c[Renderer]%c Update DOM selection:',
        // @if CK_DEBUG_TYPING // 		'color: green; font-weight: bold', '', anchor, focus
        // @if CK_DEBUG_TYPING // 	);
        // @if CK_DEBUG_TYPING // }
        domSelection.setBaseAndExtent(anchor.parent, anchor.offset, focus.parent, focus.offset);
        // Firefox–specific hack (https://github.com/ckeditor/ckeditor5-engine/issues/1439).
        if (env.isGecko) {
            fixGeckoSelectionAfterBr(focus, domSelection);
        }
    }
    /**
     * Checks whether a given DOM selection needs to be updated.
     *
     * @param domSelection The DOM selection to check.
     */
    _domSelectionNeedsUpdate(domSelection) {
        if (!this.domConverter.isDomSelectionCorrect(domSelection)) {
            // Current DOM selection is in incorrect position. We need to update it.
            return true;
        }
        const oldViewSelection = domSelection && this.domConverter.domSelectionToView(domSelection);
        if (oldViewSelection && this.selection.isEqual(oldViewSelection)) {
            return false;
        }
        // If selection is not collapsed, it does not need to be updated if it is similar.
        if (!this.selection.isCollapsed && this.selection.isSimilar(oldViewSelection)) {
            // Selection did not changed and is correct, do not update.
            return false;
        }
        // Selections are not similar.
        return true;
    }
    /**
     * Checks whether the fake selection needs to be updated.
     *
     * @param domRoot A valid DOM root where a new fake selection container should be added.
     */
    _fakeSelectionNeedsUpdate(domRoot) {
        const container = this._fakeSelectionContainer;
        const domSelection = domRoot.ownerDocument.getSelection();
        // Fake selection needs to be updated if there's no fake selection container, or the container currently sits
        // in a different root.
        if (!container || container.parentElement !== domRoot) {
            return true;
        }
        // Make sure that the selection actually is within the fake selection.
        if (domSelection.anchorNode !== container && !container.contains(domSelection.anchorNode)) {
            return true;
        }
        return container.textContent !== this.selection.fakeSelectionLabel;
    }
    /**
     * Removes the DOM selection.
     */
    _removeDomSelection() {
        for (const doc of this.domDocuments) {
            const domSelection = doc.getSelection();
            if (domSelection.rangeCount) {
                const activeDomElement = doc.activeElement;
                const viewElement = this.domConverter.mapDomToView(activeDomElement);
                if (activeDomElement && viewElement) {
                    domSelection.removeAllRanges();
                }
            }
        }
    }
    /**
     * Removes the fake selection.
     */
    _removeFakeSelection() {
        const container = this._fakeSelectionContainer;
        if (container) {
            container.remove();
        }
    }
    /**
     * Checks if focus needs to be updated and possibly updates it.
     */
    _updateFocus() {
        if (this.isFocused) {
            const editable = this.selection.editableElement;
            if (editable) {
                this.domConverter.focus(editable);
            }
        }
    }
}
/**
 * Checks if provided element is editable.
 */
function isEditable(element) {
    if (element.getAttribute('contenteditable') == 'false') {
        return false;
    }
    const parent = element.findAncestor(element => element.hasAttribute('contenteditable'));
    return !parent || parent.getAttribute('contenteditable') == 'true';
}
/**
 * Adds inline filler at a given position.
 *
 * The position can be given as an array of DOM nodes and an offset in that array,
 * or a DOM parent element and an offset in that element.
 *
 * @returns The DOM text node that contains an inline filler.
 */
function addInlineFiller(domDocument, domParentOrArray, offset) {
    const childNodes = domParentOrArray instanceof Array ? domParentOrArray : domParentOrArray.childNodes;
    const nodeAfterFiller = childNodes[offset];
    if (isText(nodeAfterFiller)) {
        nodeAfterFiller.data = INLINE_FILLER + nodeAfterFiller.data;
        return nodeAfterFiller;
    }
    else {
        const fillerNode = domDocument.createTextNode(INLINE_FILLER);
        if (Array.isArray(domParentOrArray)) {
            childNodes.splice(offset, 0, fillerNode);
        }
        else {
            insertAt(domParentOrArray, offset, fillerNode);
        }
        return fillerNode;
    }
}
/**
 * Whether two DOM nodes should be considered as similar.
 * Nodes are considered similar if they have the same tag name.
 */
function areSimilarElements(node1, node2) {
    return isNode(node1) && isNode(node2) &&
        !isText(node1) && !isText(node2) &&
        !isComment(node1) && !isComment(node2) &&
        node1.tagName.toLowerCase() === node2.tagName.toLowerCase();
}
/**
 * Whether two DOM nodes are text nodes.
 */
function areTextNodes(node1, node2) {
    return isNode(node1) && isNode(node2) &&
        isText(node1) && isText(node2);
}
/**
 * Whether two dom nodes should be considered as the same.
 * Two nodes which are considered the same are:
 *
 * * Text nodes with the same text.
 * * Element nodes represented by the same object.
 * * Two block filler elements.
 *
 * @param blockFillerMode Block filler mode, see {@link module:engine/view/domconverter~DomConverter#blockFillerMode}.
 */
function sameNodes(domConverter, actualDomChild, expectedDomChild) {
    // Elements.
    if (actualDomChild === expectedDomChild) {
        return true;
    }
    // Texts.
    else if (isText(actualDomChild) && isText(expectedDomChild)) {
        return actualDomChild.data === expectedDomChild.data;
    }
    // Block fillers.
    else if (domConverter.isBlockFiller(actualDomChild) &&
        domConverter.isBlockFiller(expectedDomChild)) {
        return true;
    }
    // Not matching types.
    return false;
}
/**
 * The following is a Firefox–specific hack (https://github.com/ckeditor/ckeditor5-engine/issues/1439).
 * When the native DOM selection is at the end of the block and preceded by <br /> e.g.
 *
 * ```html
 * <p>foo<br/>[]</p>
 * ```
 *
 * which happens a lot when using the soft line break, the browser fails to (visually) move the
 * caret to the new line. A quick fix is as simple as force–refreshing the selection with the same range.
 */
function fixGeckoSelectionAfterBr(focus, domSelection) {
    let parent = focus.parent;
    let offset = focus.offset;
    if (isText(parent) && isInlineFiller(parent)) {
        offset = indexOf(parent) + 1;
        parent = parent.parentNode;
    }
    // This fix works only when the focus point is at the very end of an element.
    // There is no point in running it in cases unrelated to the browser bug.
    if (parent.nodeType != Node.ELEMENT_NODE || offset != parent.childNodes.length - 1) {
        return;
    }
    const childAtOffset = parent.childNodes[offset];
    // To stay on the safe side, the fix being as specific as possible, it targets only the
    // selection which is at the very end of the element and preceded by <br />.
    if (childAtOffset && childAtOffset.tagName == 'BR') {
        domSelection.addRange(domSelection.getRangeAt(0));
    }
}
function filterOutFakeSelectionContainer(domChildList, fakeSelectionContainer) {
    const childList = Array.from(domChildList);
    if (childList.length == 0 || !fakeSelectionContainer) {
        return childList;
    }
    const last = childList[childList.length - 1];
    if (last == fakeSelectionContainer) {
        childList.pop();
    }
    return childList;
}
/**
 * Creates a fake selection container for a given document.
 */
function createFakeSelectionContainer(domDocument) {
    const container = domDocument.createElement('div');
    container.className = 'ck-fake-selection-container';
    Object.assign(container.style, {
        position: 'fixed',
        top: 0,
        left: '-9999px',
        // See https://github.com/ckeditor/ckeditor5/issues/752.
        width: '42px'
    });
    // Fill it with a text node so we can update it later.
    container.textContent = '\u00A0';
    return container;
}
// @if CK_DEBUG_TYPING // function _escapeTextNodeData( text ) {
// @if CK_DEBUG_TYPING // 	const escapedText = text
// @if CK_DEBUG_TYPING // 		.replace( /&/g, '&amp;' )
// @if CK_DEBUG_TYPING // 		.replace( /\u00A0/g, '&nbsp;' )
// @if CK_DEBUG_TYPING // 		.replace( /\u2060/g, '&NoBreak;' );
// @if CK_DEBUG_TYPING //
// @if CK_DEBUG_TYPING // 	return `"${ escapedText }"`;
// @if CK_DEBUG_TYPING // }
