/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module ui/panel/balloon/contextualballoon
 */
import BalloonPanelView from './balloonpanelview.js';
import View from '../../view.js';
import ButtonView from '../../button/buttonview.js';
import { Plugin, icons } from '@ckeditor/ckeditor5-core';
import { CKEditorError, FocusTracker, Rect, toUnit } from '@ckeditor/ckeditor5-utils';
import '../../../theme/components/panel/balloonrotator.css';
import '../../../theme/components/panel/fakepanel.css';
const toPx = /* #__PURE__ */ toUnit('px');
/**
 * Provides the common contextual balloon for the editor.
 *
 * The role of this plugin is to unify the contextual balloons logic, simplify views management and help
 * avoid the unnecessary complexity of handling multiple {@link module:ui/panel/balloon/balloonpanelview~BalloonPanelView}
 * instances in the editor.
 *
 * This plugin allows for creating single or multiple panel stacks.
 *
 * Each stack may have multiple views, with the one on the top being visible. When the visible view is removed from the stack,
 * the previous view becomes visible.
 *
 * It might be useful to implement nested navigation in a balloon. For instance, a toolbar view may contain a link button.
 * When you click it, a link view (which lets you set the URL) is created and put on top of the toolbar view, so the link panel
 * is displayed. When you finish editing the link and close (remove) the link view, the toolbar view is visible again.
 *
 * However, there are cases when there are multiple independent balloons to be displayed, for instance, if the selection
 * is inside two inline comments at the same time. For such cases, you can create two independent panel stacks.
 * The contextual balloon plugin will create a navigation bar to let the users switch between these panel stacks using the "Next"
 * and "Previous" buttons.
 *
 * If there are no views in the current stack, the balloon panel will try to switch to the next stack. If there are no
 * panels in any stack, the balloon panel will be hidden.
 *
 * **Note**: To force the balloon panel to show only one view, even if there are other stacks, use the `singleViewMode=true` option
 * when {@link module:ui/panel/balloon/contextualballoon~ContextualBalloon#add adding} a view to a panel.
 *
 * From the implementation point of view, the contextual ballon plugin is reusing a single
 * {@link module:ui/panel/balloon/balloonpanelview~BalloonPanelView} instance to display multiple contextual balloon
 * panels in the editor. It also creates a special {@link module:ui/panel/balloon/contextualballoon~RotatorView rotator view},
 * used to manage multiple panel stacks. Rotator view is a child of the balloon panel view and the parent of the specific
 * view you want to display. If there is more than one panel stack to be displayed, the rotator view will add a
 * navigation bar. If there is only one stack, the rotator view is transparent (it does not add any UI elements).
 */
export default class ContextualBalloon extends Plugin {
    /**
     * @inheritDoc
     */
    static get pluginName() {
        return 'ContextualBalloon';
    }
    /**
     * @inheritDoc
     */
    static get isOfficialPlugin() {
        return true;
    }
    /**
     * @inheritDoc
     */
    constructor(editor) {
        super(editor);
        /**
         * The map of views and their stacks.
         */
        this._viewToStack = new Map();
        /**
         * The map of IDs and stacks.
         */
        this._idToStack = new Map();
        /**
         * The common balloon panel view.
         */
        this._view = null;
        /**
         * Rotator view embedded in the contextual balloon.
         * Displays the currently visible view in the balloon and provides navigation for switching stacks.
         */
        this._rotatorView = null;
        /**
         * Displays fake panels under the balloon panel view when multiple stacks are added to the balloon.
         */
        this._fakePanelsView = null;
        this.positionLimiter = () => {
            const view = this.editor.editing.view;
            const viewDocument = view.document;
            const editableElement = viewDocument.selection.editableElement;
            if (editableElement) {
                return view.domConverter.mapViewToDom(editableElement.root);
            }
            return null;
        };
        this.decorate('getPositionOptions');
        this.set('visibleView', null);
        this.set('_numberOfStacks', 0);
        this.set('_singleViewMode', false);
    }
    /**
     * @inheritDoc
     */
    destroy() {
        super.destroy();
        if (this._view) {
            this._view.destroy();
        }
        if (this._rotatorView) {
            this._rotatorView.destroy();
        }
        if (this._fakePanelsView) {
            this._fakePanelsView.destroy();
        }
    }
    /**
     * The common balloon panel view.
     */
    get view() {
        if (!this._view) {
            this._createPanelView();
        }
        return this._view;
    }
    /**
     * Returns `true` when the given view is in one of the stacks. Otherwise returns `false`.
     */
    hasView(view) {
        return Array.from(this._viewToStack.keys()).includes(view);
    }
    /**
     * Adds a new view to the stack and makes it visible if the current stack is visible
     * or it is the first view in the balloon.
     *
     * @param data The configuration of the view.
     * @param data.stackId The ID of the stack that the view is added to. Defaults to `'main'`.
     * @param data.view The content of the balloon.
     * @param data.position Positioning options.
     * @param data.balloonClassName An additional CSS class added to the {@link #view balloon} when visible.
     * @param data.withArrow Whether the {@link #view balloon} should be rendered with an arrow. Defaults to `true`.
     * @param data.singleViewMode Whether the view should be the only visible view even if other stacks were added. Defaults to `false`.
     */
    add(data) {
        if (!this._view) {
            this._createPanelView();
        }
        if (this.hasView(data.view)) {
            /**
             * Trying to add configuration of the same view more than once.
             *
             * @error contextualballoon-add-view-exist
             */
            throw new CKEditorError('contextualballoon-add-view-exist', [this, data]);
        }
        const stackId = data.stackId || 'main';
        // If new stack is added, creates it and show view from this stack.
        if (!this._idToStack.has(stackId)) {
            this._idToStack.set(stackId, new Map([[data.view, data]]));
            this._viewToStack.set(data.view, this._idToStack.get(stackId));
            this._numberOfStacks = this._idToStack.size;
            if (!this._visibleStack || data.singleViewMode) {
                this.showStack(stackId);
            }
            return;
        }
        const stack = this._idToStack.get(stackId);
        if (data.singleViewMode) {
            this.showStack(stackId);
        }
        // Add new view to the stack.
        stack.set(data.view, data);
        this._viewToStack.set(data.view, stack);
        // And display it if is added to the currently visible stack.
        if (stack === this._visibleStack) {
            this._showView(data);
        }
    }
    /**
     * Removes the given view from the stack. If the removed view was visible,
     * the view preceding it in the stack will become visible instead.
     * When there is no view in the stack, the next stack will be displayed.
     * When there are no more stacks, the balloon will hide.
     *
     * @param view A view to be removed from the balloon.
     */
    remove(view) {
        if (!this.hasView(view)) {
            /**
             * Trying to remove the configuration of the view not defined in the stack.
             *
             * @error contextualballoon-remove-view-not-exist
             */
            throw new CKEditorError('contextualballoon-remove-view-not-exist', [this, view]);
        }
        const stack = this._viewToStack.get(view);
        if (this._singleViewMode && this.visibleView === view) {
            this._singleViewMode = false;
        }
        // When visible view will be removed we need to show a preceding view or next stack
        // if a view is the only view in the stack.
        if (this.visibleView === view) {
            if (stack.size === 1) {
                if (this._idToStack.size > 1) {
                    this._showNextStack();
                }
                else {
                    this.view.hide();
                    this.visibleView = null;
                    this._rotatorView.hideView();
                }
            }
            else {
                this._showView(Array.from(stack.values())[stack.size - 2]);
            }
        }
        if (stack.size === 1) {
            this._idToStack.delete(this._getStackId(stack));
            this._numberOfStacks = this._idToStack.size;
        }
        else {
            stack.delete(view);
        }
        this._viewToStack.delete(view);
    }
    /**
     * Updates the position of the balloon using the position data of the first visible view in the stack.
     * When new position data is given, the position data of the currently visible view will be updated.
     *
     * @param position Position options.
     */
    updatePosition(position) {
        if (position) {
            this._visibleStack.get(this.visibleView).position = position;
        }
        this.view.pin(this.getPositionOptions());
        this._fakePanelsView.updatePosition();
    }
    /**
     * Returns position options of the last view in the stack.
     * This keeps the balloon in the same position when the view is changed.
     */
    getPositionOptions() {
        let position = Array.from(this._visibleStack.values()).pop().position;
        if (position) {
            // Use the default limiter if none has been specified.
            if (!position.limiter) {
                // Don't modify the original options object.
                position = Object.assign({}, position, {
                    limiter: this.positionLimiter
                });
            }
            // Don't modify the original options object.
            position = Object.assign({}, position, {
                viewportOffsetConfig: this.editor.ui.viewportOffset
            });
        }
        return position;
    }
    /**
     * Shows the last view from the stack of a given ID.
     */
    showStack(id) {
        this.visibleStack = id;
        const stack = this._idToStack.get(id);
        if (!stack) {
            /**
             * Trying to show a stack that does not exist.
             *
             * @error contextualballoon-showstack-stack-not-exist
             */
            throw new CKEditorError('contextualballoon-showstack-stack-not-exist', this);
        }
        if (this._visibleStack === stack) {
            return;
        }
        this._showView(Array.from(stack.values()).pop());
    }
    /**
     * Initializes view instances.
     */
    _createPanelView() {
        this._view = new BalloonPanelView(this.editor.locale);
        this.editor.ui.view.body.add(this._view);
        this._rotatorView = this._createRotatorView();
        this._fakePanelsView = this._createFakePanelsView();
    }
    /**
     * Returns the stack of the currently visible view.
     */
    get _visibleStack() {
        return this._viewToStack.get(this.visibleView);
    }
    /**
     * Returns the ID of the given stack.
     */
    _getStackId(stack) {
        const entry = Array.from(this._idToStack.entries()).find(entry => entry[1] === stack);
        return entry[0];
    }
    /**
     * Shows the last view from the next stack.
     */
    _showNextStack() {
        const stacks = Array.from(this._idToStack.values());
        let nextIndex = stacks.indexOf(this._visibleStack) + 1;
        if (!stacks[nextIndex]) {
            nextIndex = 0;
        }
        this.showStack(this._getStackId(stacks[nextIndex]));
    }
    /**
     * Shows the last view from the previous stack.
     */
    _showPrevStack() {
        const stacks = Array.from(this._idToStack.values());
        let nextIndex = stacks.indexOf(this._visibleStack) - 1;
        if (!stacks[nextIndex]) {
            nextIndex = stacks.length - 1;
        }
        this.showStack(this._getStackId(stacks[nextIndex]));
    }
    /**
     * Creates a rotator view.
     */
    _createRotatorView() {
        const view = new RotatorView(this.editor.locale);
        const t = this.editor.locale.t;
        this.view.content.add(view);
        // Hide navigation when there is only a one stack & not in single view mode.
        view.bind('isNavigationVisible').to(this, '_numberOfStacks', this, '_singleViewMode', (value, isSingleViewMode) => {
            return !isSingleViewMode && value > 1;
        });
        // Update balloon position after toggling navigation.
        view.on('change:isNavigationVisible', () => (this.updatePosition()), { priority: 'low' });
        // Update stacks counter value.
        view.bind('counter').to(this, 'visibleView', this, '_numberOfStacks', (visibleView, numberOfStacks) => {
            if (numberOfStacks < 2) {
                return '';
            }
            const current = Array.from(this._idToStack.values()).indexOf(this._visibleStack) + 1;
            return t('%0 of %1', [current, numberOfStacks]);
        });
        view.buttonNextView.on('execute', () => {
            // When current view has a focus then move focus to the editable before removing it,
            // otherwise editor will lost focus.
            if (view.focusTracker.isFocused) {
                this.editor.editing.view.focus();
            }
            this._showNextStack();
        });
        view.buttonPrevView.on('execute', () => {
            // When current view has a focus then move focus to the editable before removing it,
            // otherwise editor will lost focus.
            if (view.focusTracker.isFocused) {
                this.editor.editing.view.focus();
            }
            this._showPrevStack();
        });
        return view;
    }
    /**
     * Creates a fake panels view.
     */
    _createFakePanelsView() {
        const view = new FakePanelsView(this.editor.locale, this.view);
        view.bind('numberOfPanels').to(this, '_numberOfStacks', this, '_singleViewMode', (number, isSingleViewMode) => {
            const showPanels = !isSingleViewMode && number >= 2;
            return showPanels ? Math.min(number - 1, 2) : 0;
        });
        view.listenTo(this.view, 'change:top', () => view.updatePosition());
        view.listenTo(this.view, 'change:left', () => view.updatePosition());
        this.editor.ui.view.body.add(view);
        return view;
    }
    /**
     * Sets the view as the content of the balloon and attaches the balloon using position
     * options of the first view.
     *
     * @param data Configuration.
     * @param data.view The view to show in the balloon.
     * @param data.balloonClassName Additional class name which will be added to the {@link #view balloon}.
     * @param data.withArrow Whether the {@link #view balloon} should be rendered with an arrow.
     */
    _showView({ view, balloonClassName = '', withArrow = true, singleViewMode = false }) {
        this.view.class = balloonClassName;
        this.view.withArrow = withArrow;
        this._rotatorView.showView(view);
        this.visibleView = view;
        this.view.pin(this.getPositionOptions());
        this._fakePanelsView.updatePosition();
        if (singleViewMode) {
            this._singleViewMode = true;
        }
    }
}
/**
 * Rotator view is a helper class for the {@link module:ui/panel/balloon/contextualballoon~ContextualBalloon ContextualBalloon}.
 * It is used for displaying the last view from the current stack and providing navigation buttons for switching stacks.
 * See the {@link module:ui/panel/balloon/contextualballoon~ContextualBalloon ContextualBalloon} documentation to learn more.
 */
export class RotatorView extends View {
    /**
     * @inheritDoc
     */
    constructor(locale) {
        super(locale);
        const t = locale.t;
        const bind = this.bindTemplate;
        this.set('isNavigationVisible', true);
        this.focusTracker = new FocusTracker();
        this.buttonPrevView = this._createButtonView(t('Previous'), icons.previousArrow);
        this.buttonNextView = this._createButtonView(t('Next'), icons.nextArrow);
        this.content = this.createCollection();
        this.setTemplate({
            tag: 'div',
            attributes: {
                class: [
                    'ck',
                    'ck-balloon-rotator'
                ],
                'z-index': '-1'
            },
            children: [
                {
                    tag: 'div',
                    attributes: {
                        class: [
                            'ck-balloon-rotator__navigation',
                            bind.to('isNavigationVisible', value => value ? '' : 'ck-hidden')
                        ]
                    },
                    children: [
                        this.buttonPrevView,
                        {
                            tag: 'span',
                            attributes: {
                                class: [
                                    'ck-balloon-rotator__counter'
                                ]
                            },
                            children: [
                                {
                                    text: bind.to('counter')
                                }
                            ]
                        },
                        this.buttonNextView
                    ]
                },
                {
                    tag: 'div',
                    attributes: {
                        class: 'ck-balloon-rotator__content'
                    },
                    children: this.content
                }
            ]
        });
    }
    /**
     * @inheritDoc
     */
    render() {
        super.render();
        this.focusTracker.add(this.element);
    }
    /**
     * @inheritDoc
     */
    destroy() {
        super.destroy();
        this.focusTracker.destroy();
    }
    /**
     * Shows a given view.
     *
     * @param view The view to show.
     */
    showView(view) {
        this.hideView();
        this.content.add(view);
    }
    /**
     * Hides the currently displayed view.
     */
    hideView() {
        this.content.clear();
    }
    /**
     * Creates a navigation button view.
     *
     * @param label The button label.
     * @param icon The button icon.
     */
    _createButtonView(label, icon) {
        const view = new ButtonView(this.locale);
        view.set({
            label,
            icon,
            tooltip: true
        });
        return view;
    }
}
/**
 * Displays additional layers under the balloon when multiple stacks are added to the balloon.
 */
class FakePanelsView extends View {
    /**
     * @inheritDoc
     */
    constructor(locale, balloonPanelView) {
        super(locale);
        const bind = this.bindTemplate;
        this.set('top', 0);
        this.set('left', 0);
        this.set('height', 0);
        this.set('width', 0);
        this.set('numberOfPanels', 0);
        this.content = this.createCollection();
        this._balloonPanelView = balloonPanelView;
        this.setTemplate({
            tag: 'div',
            attributes: {
                class: [
                    'ck-fake-panel',
                    bind.to('numberOfPanels', number => number ? '' : 'ck-hidden')
                ],
                style: {
                    top: bind.to('top', toPx),
                    left: bind.to('left', toPx),
                    width: bind.to('width', toPx),
                    height: bind.to('height', toPx)
                }
            },
            children: this.content
        });
        this.on('change:numberOfPanels', (evt, name, next, prev) => {
            if (next > prev) {
                this._addPanels(next - prev);
            }
            else {
                this._removePanels(prev - next);
            }
            this.updatePosition();
        });
    }
    _addPanels(number) {
        while (number--) {
            const view = new View();
            view.setTemplate({ tag: 'div' });
            this.content.add(view);
            this.registerChild(view);
        }
    }
    _removePanels(number) {
        while (number--) {
            const view = this.content.last;
            this.content.remove(view);
            this.deregisterChild(view);
            view.destroy();
        }
    }
    /**
     * Updates coordinates of fake panels.
     */
    updatePosition() {
        if (this.numberOfPanels) {
            const { top, left } = this._balloonPanelView;
            const { width, height } = new Rect(this._balloonPanelView.element);
            Object.assign(this, { top, left, width, height });
        }
    }
}
