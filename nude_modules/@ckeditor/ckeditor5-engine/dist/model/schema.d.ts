/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module engine/model/schema
 */
import Element from './element.js';
import Position from './position.js';
import Range from './range.js';
import type DocumentFragment from './documentfragment.js';
import type DocumentSelection from './documentselection.js';
import type Item from './item.js';
import type Node from './node.js';
import type Selection from './selection.js';
import type Writer from './writer.js';
declare const Schema_base: {
    new (): import("@ckeditor/ckeditor5-utils").Observable;
    prototype: import("@ckeditor/ckeditor5-utils").Observable;
};
/**
 * The model's schema. It defines the allowed and disallowed structures of nodes as well as nodes' attributes.
 * The schema is usually defined by the features and based on them, the editing framework and features
 * make decisions on how to change and process the model.
 *
 * The instance of schema is available in {@link module:engine/model/model~Model#schema `editor.model.schema`}.
 *
 * Read more about the schema in:
 *
 * * The {@glink framework/architecture/editing-engine#schema schema section} of the
 * {@glink framework/architecture/editing-engine Introduction to the Editing engine architecture} guide.
 * * The {@glink framework/deep-dive/schema Schema deep-dive} guide.
 */
export default class Schema extends /* #__PURE__ */ Schema_base {
    private readonly _sourceDefinitions;
    /**
     * A dictionary containing attribute properties.
     */
    private readonly _attributeProperties;
    /**
     * Stores additional callbacks registered for schema items, which are evaluated when {@link ~Schema#checkChild} is called.
     *
     * Keys are schema item names for which the callbacks are registered. Values are arrays with the callbacks.
     *
     * Some checks are added under {@link ~Schema#_genericCheckSymbol} key, these are evaluated for every {@link ~Schema#checkChild} call.
     */
    private readonly _customChildChecks;
    /**
     * Stores additional callbacks registered for attribute names, which are evaluated when {@link ~Schema#checkAttribute} is called.
     *
     * Keys are schema attribute names for which the callbacks are registered. Values are arrays with the callbacks.
     *
     * Some checks are added under {@link ~Schema#_genericCheckSymbol} key, these are evaluated for every
     * {@link ~Schema#checkAttribute} call.
     */
    private readonly _customAttributeChecks;
    private readonly _genericCheckSymbol;
    private _compiledDefinitions?;
    /**
     * Creates a schema instance.
     */
    constructor();
    /**
     * Registers a schema item. Can only be called once for every item name.
     *
     * ```ts
     * schema.register( 'paragraph', {
     * 	inheritAllFrom: '$block'
     * } );
     * ```
     */
    register(itemName: string, definition?: SchemaItemDefinition): void;
    /**
     * Extends a {@link #register registered} item's definition.
     *
     * Extending properties such as `allowIn` will add more items to the existing properties,
     * while redefining properties such as `isBlock` will override the previously defined ones.
     *
     * ```ts
     * schema.register( 'foo', {
     * 	allowIn: '$root',
     * 	isBlock: true;
     * } );
     * schema.extend( 'foo', {
     * 	allowIn: 'blockQuote',
     * 	isBlock: false
     * } );
     *
     * schema.getDefinition( 'foo' );
     * //	{
     * //		allowIn: [ '$root', 'blockQuote' ],
     * // 		isBlock: false
     * //	}
     * ```
     */
    extend(itemName: string, definition: SchemaItemDefinition): void;
    /**
     * Returns data of all registered items.
     *
     * This method should normally be used for reflection purposes (e.g. defining a clone of a certain element,
     * checking a list of all block elements, etc).
     * Use specific methods (such as {@link #checkChild `checkChild()`} or {@link #isLimit `isLimit()`})
     * in other cases.
     */
    getDefinitions(): Record<string, SchemaCompiledItemDefinition>;
    /**
     * Returns a definition of the given item or `undefined` if an item is not registered.
     *
     * This method should normally be used for reflection purposes (e.g. defining a clone of a certain element,
     * checking a list of all block elements, etc).
     * Use specific methods (such as {@link #checkChild `checkChild()`} or {@link #isLimit `isLimit()`})
     * in other cases.
     */
    getDefinition(item: string | Item | DocumentFragment | SchemaContextItem): SchemaCompiledItemDefinition | undefined;
    /**
     * Returns `true` if the given item is registered in the schema.
     *
     * ```ts
     * schema.isRegistered( 'paragraph' ); // -> true
     * schema.isRegistered( editor.model.document.getRoot() ); // -> true
     * schema.isRegistered( 'foo' ); // -> false
     * ```
     */
    isRegistered(item: string | Item | DocumentFragment | SchemaContextItem): boolean;
    /**
     * Returns `true` if the given item is defined to be
     * a block by the {@link module:engine/model/schema~SchemaItemDefinition}'s `isBlock` property.
     *
     * ```ts
     * schema.isBlock( 'paragraph' ); // -> true
     * schema.isBlock( '$root' ); // -> false
     *
     * const paragraphElement = writer.createElement( 'paragraph' );
     * schema.isBlock( paragraphElement ); // -> true
     * ```
     *
     * See the {@glink framework/deep-dive/schema#block-elements Block elements} section of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide for more details.
     */
    isBlock(item: string | Item | DocumentFragment | SchemaContextItem): boolean;
    /**
     * Returns `true` if the given item should be treated as a limit element.
     *
     * It considers an item to be a limit element if its
     * {@link module:engine/model/schema~SchemaItemDefinition}'s
     * {@link module:engine/model/schema~SchemaItemDefinition#isLimit `isLimit`} or
     * {@link module:engine/model/schema~SchemaItemDefinition#isObject `isObject`} property
     * was set to `true`.
     *
     * ```ts
     * schema.isLimit( 'paragraph' ); // -> false
     * schema.isLimit( '$root' ); // -> true
     * schema.isLimit( editor.model.document.getRoot() ); // -> true
     * schema.isLimit( 'imageBlock' ); // -> true
     * ```
     *
     * See the {@glink framework/deep-dive/schema#limit-elements Limit elements} section of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide for more details.
     */
    isLimit(item: string | Item | DocumentFragment | SchemaContextItem): boolean;
    /**
     * Returns `true` if the given item should be treated as an object element.
     *
     * It considers an item to be an object element if its
     * {@link module:engine/model/schema~SchemaItemDefinition}'s
     * {@link module:engine/model/schema~SchemaItemDefinition#isObject `isObject`} property
     * was set to `true`.
     *
     * ```ts
     * schema.isObject( 'paragraph' ); // -> false
     * schema.isObject( 'imageBlock' ); // -> true
     *
     * const imageElement = writer.createElement( 'imageBlock' );
     * schema.isObject( imageElement ); // -> true
     * ```
     *
     * See the {@glink framework/deep-dive/schema#object-elements Object elements} section of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide for more details.
     */
    isObject(item: string | Item | DocumentFragment | SchemaContextItem): boolean;
    /**
     * Returns `true` if the given item is defined to be
     * an inline element by the {@link module:engine/model/schema~SchemaItemDefinition}'s `isInline` property.
     *
     * ```ts
     * schema.isInline( 'paragraph' ); // -> false
     * schema.isInline( 'softBreak' ); // -> true
     *
     * const text = writer.createText( 'foo' );
     * schema.isInline( text ); // -> true
     * ```
     *
     * See the {@glink framework/deep-dive/schema#inline-elements Inline elements} section of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide for more details.
     */
    isInline(item: string | Item | DocumentFragment | SchemaContextItem): boolean;
    /**
     * Returns `true` if the given item is defined to be
     * a selectable element by the {@link module:engine/model/schema~SchemaItemDefinition}'s `isSelectable` property.
     *
     * ```ts
     * schema.isSelectable( 'paragraph' ); // -> false
     * schema.isSelectable( 'heading1' ); // -> false
     * schema.isSelectable( 'imageBlock' ); // -> true
     * schema.isSelectable( 'tableCell' ); // -> true
     *
     * const text = writer.createText( 'foo' );
     * schema.isSelectable( text ); // -> false
     * ```
     *
     * See the {@glink framework/deep-dive/schema#selectable-elements Selectable elements section} of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide for more details.
     */
    isSelectable(item: string | Item | DocumentFragment | SchemaContextItem): boolean;
    /**
     * Returns `true` if the given item is defined to be
     * a content by the {@link module:engine/model/schema~SchemaItemDefinition}'s `isContent` property.
     *
     * ```ts
     * schema.isContent( 'paragraph' ); // -> false
     * schema.isContent( 'heading1' ); // -> false
     * schema.isContent( 'imageBlock' ); // -> true
     * schema.isContent( 'horizontalLine' ); // -> true
     *
     * const text = writer.createText( 'foo' );
     * schema.isContent( text ); // -> true
     * ```
     *
     * See the {@glink framework/deep-dive/schema#content-elements Content elements section} of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide for more details.
     */
    isContent(item: string | Item | DocumentFragment | SchemaContextItem): boolean;
    /**
     * Checks whether the given node can be a child of the given context.
     *
     * ```ts
     * schema.checkChild( model.document.getRoot(), paragraph ); // -> false
     *
     * schema.register( 'paragraph', {
     * 	allowIn: '$root'
     * } );
     *
     * schema.checkChild( model.document.getRoot(), paragraph ); // -> true
     * ```
     *
     * Both {@link module:engine/model/schema~Schema#addChildCheck callback checks} and declarative rules (added when
     * {@link module:engine/model/schema~Schema#register registering} and {@link module:engine/model/schema~Schema#extend extending} items)
     * are evaluated when this method is called.
     *
     * Note that callback checks have bigger priority than declarative rules checks and may overwrite them.
     *
     * Note that when verifying whether the given node can be a child of the given context, the schema also verifies the entire
     * context &ndash; from its root to its last element. Therefore, it is possible for `checkChild()` to return `false` even though
     * the `context` last element can contain the checked child. It happens if one of the `context` elements does not allow its child.
     * When `context` is verified, {@link module:engine/model/schema~Schema#addChildCheck custom checks} are considered as well.
     *
     * @fires checkChild
     * @param context The context in which the child will be checked.
     * @param def The child to check.
     */
    checkChild(context: SchemaContextDefinition, def: string | Node | DocumentFragment): boolean;
    /**
     * Checks whether the given attribute can be applied in the given context (on the last item of the context).
     *
     * ```ts
     * schema.checkAttribute( textNode, 'bold' ); // -> false
     *
     * schema.extend( '$text', {
     * 	allowAttributes: 'bold'
     * } );
     *
     * schema.checkAttribute( textNode, 'bold' ); // -> true
     * ```
     *
     * Both {@link module:engine/model/schema~Schema#addAttributeCheck callback checks} and declarative rules (added when
     * {@link module:engine/model/schema~Schema#register registering} and {@link module:engine/model/schema~Schema#extend extending} items)
     * are evaluated when this method is called.
     *
     * Note that callback checks have bigger priority than declarative rules checks and may overwrite them.
     *
     * @fires checkAttribute
     * @param context The context in which the attribute will be checked.
     * @param attributeName Name of attribute to check in the given context.
     */
    checkAttribute(context: SchemaContextDefinition, attributeName: string): boolean;
    checkMerge(position: Position): boolean;
    checkMerge(baseElement: Element, elementToMerge: Element): boolean;
    /**
     * Allows registering a callback to the {@link #checkChild} method calls.
     *
     * Callbacks allow you to implement rules which are not otherwise possible to achieve
     * by using the declarative API of {@link module:engine/model/schema~SchemaItemDefinition}.
     *
     * Note that callback checks have bigger priority than declarative rules checks and may overwrite them.
     *
     * For example, by using this method you can disallow elements in specific contexts:
     *
     * ```ts
     * // Disallow `heading1` inside a `blockQuote` that is inside a table.
     * schema.addChildCheck( ( context, childDefinition ) => {
     * 	if ( context.endsWith( 'tableCell blockQuote' ) ) {
     * 		return false;
     * 	}
     * }, 'heading1' );
     * ```
     *
     * You can skip the optional `itemName` parameter to evaluate the callback for every `checkChild()` call.
     *
     * ```ts
     * // Inside specific custom element, allow only children, which allows for a specific attribute.
     * schema.addChildCheck( ( context, childDefinition ) => {
     * 	if ( context.endsWith( 'myElement' ) ) {
     * 		return childDefinition.allowAttributes.includes( 'myAttribute' );
     * 	}
     * } );
     * ```
     *
     * Please note that the generic callbacks may affect the editor performance and should be avoided if possible.
     *
     * When one of the callbacks makes a decision (returns `true` or `false`) the processing is finished and other callbacks are not fired.
     * Callbacks are fired in the order they were added, however generic callbacks are fired before callbacks added for a specified item.
     *
     * You can also use `checkChild` event, if you need even better control. The result from the example above could also be
     * achieved with following event callback:
     *
     * ```ts
     * schema.on( 'checkChild', ( evt, args ) => {
     * 	const context = args[ 0 ];
     * 	const childDefinition = args[ 1 ];
     *
     * 	if ( context.endsWith( 'myElement' ) ) {
     * 		// Prevent next listeners from being called.
     * 		evt.stop();
     * 		// Set the `checkChild()` return value.
     * 		evt.return = childDefinition.allowAttributes.includes( 'myAttribute' );
     * 	}
     * }, { priority: 'high' } );
     * ```
     *
     * Note that the callback checks and declarative rules checks are processed on `normal` priority.
     *
     * Adding callbacks this way can also negatively impact editor performance.
     *
     * @param callback The callback to be called. It is called with two parameters:
     * {@link module:engine/model/schema~SchemaContext} (context) instance and
     * {@link module:engine/model/schema~SchemaCompiledItemDefinition} (definition). The callback may return `true/false` to override
     * `checkChild()`'s return value. If it does not return a boolean value, the default algorithm (or other callbacks) will define
     * `checkChild()`'s return value.
     * @param itemName Name of the schema item for which the callback is registered. If specified, the callback will be run only for
     * `checkChild()` calls which `def` parameter matches the `itemName`. Otherwise, the callback will run for every `checkChild` call.
     */
    addChildCheck(callback: SchemaChildCheckCallback, itemName?: string): void;
    /**
     * Allows registering a callback to the {@link #checkAttribute} method calls.
     *
     * Callbacks allow you to implement rules which are not otherwise possible to achieve
     * by using the declarative API of {@link module:engine/model/schema~SchemaItemDefinition}.
     *
     * Note that callback checks have bigger priority than declarative rules checks and may overwrite them.
     *
     * For example, by using this method you can disallow setting attributes on nodes in specific contexts:
     *
     * ```ts
     * // Disallow setting `bold` on text inside `heading1` element:
     * schema.addAttributeCheck( context => {
     * 	if ( context.endsWith( 'heading1 $text' ) ) {
     * 		return false;
     * 	}
     * }, 'bold' );
     * ```
     *
     * You can skip the optional `attributeName` parameter to evaluate the callback for every `checkAttribute()` call.
     *
     * ```ts
     * // Disallow formatting attributes on text inside custom `myTitle` element:
     * schema.addAttributeCheck( ( context, attributeName ) => {
     * 	if ( context.endsWith( 'myTitle $text' ) && schema.getAttributeProperties( attributeName ).isFormatting ) {
     * 		return false;
     * 	}
     * } );
     * ```
     *
     * Please note that the generic callbacks may affect the editor performance and should be avoided if possible.
     *
     * When one of the callbacks makes a decision (returns `true` or `false`) the processing is finished and other callbacks are not fired.
     * Callbacks are fired in the order they were added, however generic callbacks are fired before callbacks added for a specified item.
     *
     * You can also use {@link #event:checkAttribute} event, if you need even better control. The result from the example above could also
     * be achieved with following event callback:
     *
     * ```ts
     * schema.on( 'checkAttribute', ( evt, args ) => {
     * 	const context = args[ 0 ];
     * 	const attributeName = args[ 1 ];
     *
     * 	if ( context.endsWith( 'myTitle $text' ) && schema.getAttributeProperties( attributeName ).isFormatting ) {
     * 		// Prevent next listeners from being called.
     * 		evt.stop();
     * 		// Set the `checkAttribute()` return value.
     * 		evt.return = false;
     * 	}
     * }, { priority: 'high' } );
     * ```
     *
     * Note that the callback checks and declarative rules checks are processed on `normal` priority.
     *
     * Adding callbacks this way can also negatively impact editor performance.
     *
     * @param callback The callback to be called. It is called with two parameters:
     * {@link module:engine/model/schema~SchemaContext `context`} and attribute name. The callback may return `true` or `false`, to
     * override `checkAttribute()`'s return value. If it does not return a boolean value, the default algorithm (or other callbacks)
     * will define `checkAttribute()`'s return value.
     * @param attributeName Name of the attribute for which the callback is registered. If specified, the callback will be run only for
     * `checkAttribute()` calls with matching `attributeName`. Otherwise, the callback will run for every `checkAttribute()` call.
     */
    addAttributeCheck(callback: SchemaAttributeCheckCallback, attributeName?: string): void;
    /**
     * This method allows assigning additional metadata to the model attributes. For example,
     * {@link module:engine/model/schema~AttributeProperties `AttributeProperties#isFormatting` property} is
     * used to mark formatting attributes (like `bold` or `italic`).
     *
     * ```ts
     * // Mark bold as a formatting attribute.
     * schema.setAttributeProperties( 'bold', {
     * 	isFormatting: true
     * } );
     *
     * // Override code not to be considered a formatting markup.
     * schema.setAttributeProperties( 'code', {
     * 	isFormatting: false
     * } );
     * ```
     *
     * Properties are not limited to members defined in the
     * {@link module:engine/model/schema~AttributeProperties `AttributeProperties` type} and you can also use custom properties:
     *
     * ```ts
     * schema.setAttributeProperties( 'blockQuote', {
     * 	customProperty: 'value'
     * } );
     * ```
     *
     * Subsequent calls with the same attribute will extend its custom properties:
     *
     * ```ts
     * schema.setAttributeProperties( 'blockQuote', {
     * 	one: 1
     * } );
     *
     * schema.setAttributeProperties( 'blockQuote', {
     * 	two: 2
     * } );
     *
     * console.log( schema.getAttributeProperties( 'blockQuote' ) );
     * // Logs: { one: 1, two: 2 }
     * ```
     *
     * @param attributeName A name of the attribute to receive the properties.
     * @param properties A dictionary of properties.
     */
    setAttributeProperties(attributeName: string, properties: AttributeProperties): void;
    /**
     * Returns properties associated with a given model attribute. See {@link #setAttributeProperties `setAttributeProperties()`}.
     *
     * @param attributeName A name of the attribute.
     */
    getAttributeProperties(attributeName: string): AttributeProperties;
    /**
     * Returns the lowest {@link module:engine/model/schema~Schema#isLimit limit element} containing the entire
     * selection/range/position or the root otherwise.
     *
     * @param selectionOrRangeOrPosition The selection/range/position to check.
     * @returns The lowest limit element containing the entire `selectionOrRangeOrPosition`.
     */
    getLimitElement(selectionOrRangeOrPosition: Selection | DocumentSelection | Range | Position): Element;
    /**
     * Checks whether the attribute is allowed in selection:
     *
     * * if the selection is not collapsed, then checks if the attribute is allowed on any of nodes in that range,
     * * if the selection is collapsed, then checks if on the selection position there's a text with the
     * specified attribute allowed.
     *
     * @param selection Selection which will be checked.
     * @param attribute The name of the attribute to check.
     */
    checkAttributeInSelection(selection: Selection | DocumentSelection, attribute: string): boolean;
    /**
     * Transforms the given set of ranges into a set of ranges where the given attribute is allowed (and can be applied).
     *
     * @param ranges Ranges to be validated.
     * @param attribute The name of the attribute to check.
     * @returns Ranges in which the attribute is allowed.
     */
    getValidRanges(ranges: Iterable<Range>, attribute: string): IterableIterator<Range>;
    /**
     * Basing on given `position`, finds and returns a {@link module:engine/model/range~Range range} which is
     * nearest to that `position` and is a correct range for selection.
     *
     * The correct selection range might be collapsed when it is located in a position where the text node can be placed.
     * Non-collapsed range is returned when selection can be placed around element marked as an "object" in
     * the {@link module:engine/model/schema~Schema schema}.
     *
     * Direction of searching for the nearest correct selection range can be specified as:
     *
     * * `both` - searching will be performed in both ways,
     * * `forward` - searching will be performed only forward,
     * * `backward` - searching will be performed only backward.
     *
     * When valid selection range cannot be found, `null` is returned.
     *
     * @param position Reference position where new selection range should be looked for.
     * @param direction Search direction.
     * @returns Nearest selection range or `null` if one cannot be found.
     */
    getNearestSelectionRange(position: Position, direction?: 'both' | 'forward' | 'backward'): Range | null;
    /**
     * Tries to find position ancestors that allow to insert a given node.
     * It starts searching from the given position and goes node by node to the top of the model tree
     * as long as a {@link module:engine/model/schema~Schema#isLimit limit element}, an
     * {@link module:engine/model/schema~Schema#isObject object element} or a topmost ancestor is not reached.
     *
     * @param position The position that the search will start from.
     * @param node The node for which an allowed parent should be found or its name.
     * @returns Allowed parent or null if nothing was found.
     */
    findAllowedParent(position: Position, node: Node | string): Element | null;
    /**
     * Sets attributes allowed by the schema on a given node.
     *
     * @param node A node to set attributes on.
     * @param attributes Attributes keys and values.
     * @param writer An instance of the model writer.
     */
    setAllowedAttributes(node: Node, attributes: Record<string, unknown>, writer: Writer): void;
    /**
     * Removes attributes disallowed by the schema.
     *
     * @param nodes Nodes that will be filtered.
     */
    removeDisallowedAttributes(nodes: Iterable<Node>, writer: Writer): void;
    /**
     * Gets attributes of a node that have a given property.
     *
     * @param node Node to get attributes from.
     * @param propertyName Name of the property that attribute must have to return it.
     * @param propertyValue Desired value of the property that we want to check.
     * When `undefined` attributes will be returned if they have set a given property no matter what the value is. If specified it will
     * return attributes which given property's value is equal to this parameter.
     * @returns Object with attributes' names as key and attributes' values as value.
     */
    getAttributesWithProperty(node: Node, propertyName: string, propertyValue: unknown): Record<string, unknown>;
    /**
     * Creates an instance of the schema context.
     */
    createContext(context: SchemaContextDefinition): SchemaContext;
    private _clearCache;
    private _compile;
    private _checkContextMatch;
    /**
     * Calls child check callbacks to decide whether `def` is allowed in `context`. It uses both generic and specific (defined for `def`
     * item) callbacks. If neither callback makes a decision, `undefined` is returned.
     *
     * Note that the first callback that makes a decision "wins", i.e., if any callback returns `true` or `false`, then the processing
     * is over and that result is returned.
     */
    private _evaluateChildChecks;
    /**
     * Calls attribute check callbacks to decide whether `attributeName` can be set on the last element of `context`. It uses both
     * generic and specific (defined for `attributeName`) callbacks. If neither callback makes a decision, `undefined` is returned.
     *
     * Note that the first callback that makes a decision "wins", i.e., if any callback returns `true` or `false`, then the processing
     * is over and that result is returned.
     */
    private _evaluateAttributeChecks;
    /**
     * Takes a flat range and an attribute name. Traverses the range recursively and deeply to find and return all ranges
     * inside the given range on which the attribute can be applied.
     *
     * This is a helper function for {@link ~Schema#getValidRanges}.
     *
     * @param range The range to process.
     * @param attribute The name of the attribute to check.
     * @returns Ranges in which the attribute is allowed.
     */
    private _getValidRangesForRange;
    /**
     * Returns a model range which is optimal (in terms of UX) for inserting a widget block.
     *
     * For instance, if a selection is in the middle of a paragraph, the collapsed range before this paragraph
     * will be returned so that it is not split. If the selection is at the end of a paragraph,
     * the collapsed range after this paragraph will be returned.
     *
     * Note: If the selection is placed in an empty block, the range in that block will be returned. If that range
     * is then passed to {@link module:engine/model/model~Model#insertContent}, the block will be fully replaced
     * by the inserted widget block.
     *
     * @internal
     * @param selection The selection based on which the insertion position should be calculated.
     * @param place The place where to look for optimal insertion range.
     * The `auto` value will determine itself the best position for insertion.
     * The `before` value will try to find a position before selection.
     * The `after` value will try to find a position after selection.
     * @returns The optimal range.
     */
    findOptimalInsertionRange(selection: Selection | DocumentSelection, place?: 'auto' | 'before' | 'after'): Range;
}
/**
 * Event fired when the {@link ~Schema#checkChild} method is called. It allows plugging in
 * additional behavior, for example implementing rules which cannot be defined using the declarative
 * {@link module:engine/model/schema~SchemaItemDefinition} interface.
 *
 * **Note:** The {@link ~Schema#addChildCheck} method is a more handy way to register callbacks. Internally,
 * it registers a listener to this event but comes with a simpler API and it is the recommended choice
 * in most of the cases.
 *
 * The {@link ~Schema#checkChild} method fires an event because it is
 * {@link module:utils/observablemixin~Observable#decorate decorated} with it. Thanks to that you can
 * use this event in various ways, but the most important use case is overriding standard behavior of the
 * `checkChild()` method. Let's see a typical listener template:
 *
 * ```ts
 * schema.on( 'checkChild', ( evt, args ) => {
 * 	const context = args[ 0 ];
 * 	const childDefinition = args[ 1 ];
 * }, { priority: 'high' } );
 * ```
 *
 * The listener is added with a `high` priority to be executed before the default method is really called. The `args` callback
 * parameter contains arguments passed to `checkChild( context, child )`. However, the `context` parameter is already
 * normalized to a {@link module:engine/model/schema~SchemaContext} instance and `child` to a
 * {@link module:engine/model/schema~SchemaCompiledItemDefinition} instance, so you do not have to worry about
 * the various ways how `context` and `child` may be passed to `checkChild()`.
 *
 * **Note:** `childDefinition` may be `undefined` if `checkChild()` was called with a non-registered element.
 *
 * So, in order to implement a rule "disallow `heading1` in `blockQuote`", you can add such a listener:
 *
 * ```ts
 * schema.on( 'checkChild', ( evt, args ) => {
 * 	const context = args[ 0 ];
 * 	const childDefinition = args[ 1 ];
 *
 * 	if ( context.endsWith( 'blockQuote' ) && childDefinition && childDefinition.name == 'heading1' ) {
 * 		// Prevent next listeners from being called.
 * 		evt.stop();
 * 		// Set the checkChild()'s return value.
 * 		evt.return = false;
 * 	}
 * }, { priority: 'high' } );
 * ```
 *
 * Allowing elements in specific contexts will be a far less common use case, because it is normally handled by the
 * `allowIn` rule from {@link module:engine/model/schema~SchemaItemDefinition}. But if you have a complex scenario
 * where `listItem` should be allowed only in element `foo` which must be in element `bar`, then this would be the way:
 *
 * ```ts
 * schema.on( 'checkChild', ( evt, args ) => {
 * 	const context = args[ 0 ];
 * 	const childDefinition = args[ 1 ];
 *
 * 	if ( context.endsWith( 'bar foo' ) && childDefinition.name == 'listItem' ) {
 * 		// Prevent next listeners from being called.
 * 		evt.stop();
 * 		// Set the checkChild()'s return value.
 * 		evt.return = true;
 * 	}
 * }, { priority: 'high' } );
 * ```
 *
 * @eventName ~Schema#checkChild
 * @param args The `checkChild()`'s arguments.
 */
export type SchemaCheckChildEvent = {
    name: 'checkChild';
    args: [[context: SchemaContext, def: SchemaCompiledItemDefinition]];
};
/**
 * Event fired when the {@link ~Schema#checkAttribute} method is called. It allows plugging in
 * additional behavior, for example implementing rules which cannot be defined using the declarative
 * {@link module:engine/model/schema~SchemaItemDefinition} interface.
 *
 * **Note:** The {@link ~Schema#addAttributeCheck} method is a more handy way to register callbacks. Internally,
 * it registers a listener to this event but comes with a simpler API and it is the recommended choice
 * in most of the cases.
 *
 * The {@link ~Schema#checkAttribute} method fires an event because it is
 * {@link module:utils/observablemixin~Observable#decorate decorated} with it. Thanks to that you can
 * use this event in various ways, but the most important use case is overriding the standard behavior of the
 * `checkAttribute()` method. Let's see a typical listener template:
 *
 * ```ts
 * schema.on( 'checkAttribute', ( evt, args ) => {
 * 	const context = args[ 0 ];
 * 	const attributeName = args[ 1 ];
 * }, { priority: 'high' } );
 * ```
 *
 * The listener is added with a `high` priority to be executed before the default method is really called. The `args` callback
 * parameter contains arguments passed to `checkAttribute( context, attributeName )`. However, the `context` parameter is already
 * normalized to a {@link module:engine/model/schema~SchemaContext} instance, so you do not have to worry about
 * the various ways how `context` may be passed to `checkAttribute()`.
 *
 * So, in order to implement a rule "disallow `bold` in a text which is in a `heading1`, you can add such a listener:
 *
 * ```ts
 * schema.on( 'checkAttribute', ( evt, args ) => {
 * 	const context = args[ 0 ];
 * 	const attributeName = args[ 1 ];
 *
 * 	if ( context.endsWith( 'heading1 $text' ) && attributeName == 'bold' ) {
 * 		// Prevent next listeners from being called.
 * 		evt.stop();
 * 		// Set the checkAttribute()'s return value.
 * 		evt.return = false;
 * 	}
 * }, { priority: 'high' } );
 * ```
 *
 * Allowing attributes in specific contexts will be a far less common use case, because it is normally handled by the
 * `allowAttributes` rule from {@link module:engine/model/schema~SchemaItemDefinition}. But if you have a complex scenario
 * where `bold` should be allowed only in element `foo` which must be in element `bar`, then this would be the way:
 *
 * ```ts
 * schema.on( 'checkAttribute', ( evt, args ) => {
 * 	const context = args[ 0 ];
 * 	const attributeName = args[ 1 ];
 *
 * 	if ( context.endsWith( 'bar foo $text' ) && attributeName == 'bold' ) {
 * 		// Prevent next listeners from being called.
 * 		evt.stop();
 * 		// Set the checkAttribute()'s return value.
 * 		evt.return = true;
 * 	}
 * }, { priority: 'high' } );
 * ```
 *
 * @eventName ~Schema#checkAttribute
 * @param args The `checkAttribute()`'s arguments.
 */
export type SchemaCheckAttributeEvent = {
    name: 'checkAttribute';
    args: [[context: SchemaContext, attributeName: string]];
};
/**
 * A definition of a {@link module:engine/model/schema~Schema schema} item.
 *
 * You can define the following rules:
 *
 * * {@link ~SchemaItemDefinition#allowIn `allowIn`} &ndash; Defines in which other items this item will be allowed.
 * * {@link ~SchemaItemDefinition#allowChildren `allowChildren`} &ndash; Defines which other items are allowed inside this item.
 * * {@link ~SchemaItemDefinition#allowAttributes `allowAttributes`} &ndash; Defines allowed attributes of the given item.
 * * {@link ~SchemaItemDefinition#disallowIn `disallowIn`} &ndash; Defines in which other items this item will be disallowed.
 * * {@link ~SchemaItemDefinition#disallowChildren `disallowChildren`} &ndash; Defines which other items are disallowed inside this item.
 * * {@link ~SchemaItemDefinition#disallowAttributes `disallowAttributes`} &ndash; Defines disallowed attributes of the given item.
 * * {@link ~SchemaItemDefinition#allowContentOf `allowContentOf`} &ndash; Makes this item allow children that are also allowed in the
 * specified items. This acknowledges disallow rules.
 * * {@link ~SchemaItemDefinition#allowWhere `allowWhere`} &ndash; Makes this item allowed where the specified items are allowed. This
 * acknowledges disallow rules.
 * * {@link ~SchemaItemDefinition#allowAttributesOf `allowAttributesOf`} &ndash; Inherits attributes from other items. This acknowledges
 * disallow rules.
 * * {@link ~SchemaItemDefinition#inheritTypesFrom `inheritTypesFrom`} &ndash; Inherits `is*` properties of other items.
 * * {@link ~SchemaItemDefinition#inheritAllFrom `inheritAllFrom`} &ndash;
 * A shorthand for `allowContentOf`, `allowWhere`, `allowAttributesOf`, `inheritTypesFrom`.
 *
 * # The `is*` properties
 *
 * There are a couple commonly used `is*` properties. Their role is to assign additional semantics to schema items.
 *
 * * {@link ~SchemaItemDefinition#isBlock `isBlock`} &ndash; Whether this item is paragraph-like.
 * Generally speaking, content is usually made out of blocks like paragraphs, list items, images, headings, etc.
 * * {@link ~SchemaItemDefinition#isInline `isInline`} &ndash; Whether an item is "text-like" and should be treated as an inline node.
 * Examples of inline elements: `$text`, `softBreak` (`<br>`), etc.
 * * {@link ~SchemaItemDefinition#isLimit `isLimit`} &ndash; It can be understood as whether this element
 * should not be split by <kbd>Enter</kbd>. Examples of limit elements: `$root`, table cell, image caption, etc.
 * In other words, all actions that happen inside a limit element are limited to its content.
 * All objects are treated as limit elements, too.
 * * {@link ~SchemaItemDefinition#isObject `isObject`} &ndash; Whether an item is "self-contained" and should be treated as a whole.
 * Examples of object elements: `imageBlock`, `table`, `video`, etc. An object is also a limit, so
 * {@link module:engine/model/schema~Schema#isLimit `isLimit()`} returns `true` for object elements automatically.
 *
 * Read more about the meaning of these types in the
 * {@glink framework/deep-dive/schema#defining-additional-semantics dedicated section of the Schema deep-dive} guide.
 *
 * # Generic items
 *
 * There are several generic items (classes of elements) available: `$root`, `$container`, `$block`, `$blockObject`,
 * `$inlineObject`, and `$text`. They are defined as follows:
 *
 * ```ts
 * schema.register( '$root', {
 * 	isLimit: true
 * } );
 *
 * schema.register( '$container', {
 * 	allowIn: [ '$root', '$container' ]
 * } );
 *
 * schema.register( '$block', {
 * 	allowIn: [ '$root', '$container' ],
 * 	isBlock: true
 * } );
 *
 * schema.register( '$blockObject', {
 * 	allowWhere: '$block',
 * 	isBlock: true,
 * 	isObject: true
 * } );
 *
 * schema.register( '$inlineObject', {
 * 	allowWhere: '$text',
 * 	allowAttributesOf: '$text',
 * 	isInline: true,
 * 	isObject: true
 * } );
 *
 * schema.register( '$text', {
 * 	allowIn: '$block',
 * 	isInline: true,
 * 	isContent: true
 * } );
 * ```
 *
 * They reflect typical editor content that is contained within one root, consists of several blocks
 * (paragraphs, lists items, headings, images) which, in turn, may contain text inside.
 *
 * By inheriting from the generic items you can define new items which will get extended by other editor features.
 * Read more about generic types in the {@glink framework/deep-dive/schema Schema deep-dive} guide.
 *
 * # Example definitions
 *
 * Allow `paragraph` in roots and block quotes:
 *
 * ```ts
 * schema.register( 'paragraph', {
 * 	allowIn: [ '$root', 'blockQuote' ],
 * 	isBlock: true
 * } );
 * ```
 *
 * Allow `paragraph` everywhere where `$block` is allowed (i.e. in `$root`):
 *
 * ```ts
 * schema.register( 'paragraph', {
 * 	allowWhere: '$block',
 * 	isBlock: true
 * } );
 * ```
 *
 * Allow `paragraph` inside a `$root` and allow `$text` as a `paragraph` child:
 *
 * ```ts
 * schema.register( 'paragraph', {
 * 	allowIn: '$root',
 * 	allowChildren: '$text',
 * 	isBlock: true
 * } );
 * ```
 *
 * The previous rule can be written in a shorter form using inheritance:
 *
 * ```ts
 * schema.register( 'paragraph', {
 * 	inheritAllFrom: '$block'
 * } );
 * ```
 *
 * Make `imageBlock` a block object, which is allowed everywhere where `$block` is.
 * Also, allow `src` and `alt` attributes in it:
 *
 * ```ts
 * schema.register( 'imageBlock', {
 * 	inheritAllFrom: '$blockObject',
 * 	allowAttributes: [ 'src', 'alt' ],
 * } );
 * ```
 *
 * Make `caption` allowed in `imageBlock` and make it allow all the content of `$block`s (usually, `$text`).
 * Also, mark it as a limit element so it cannot be split:
 *
 * ```ts
 * schema.register( 'caption', {
 * 	allowIn: 'imageBlock',
 * 	allowContentOf: '$block',
 * 	isLimit: true
 * } );
 * ```
 *
 * Register `inlineImage` as a kind of an inline object but disallow it inside captions:
 *
 * ```ts
 * schema.register( 'imageInline', {
 * 	inheritAllFrom: '$inlineObject',
 * 	disallowIn: [ 'caption' ]
 * } );
 * ```
 *
 * Make `listItem` inherit all from `$block` but also allow additional attributes:
 *
 * ```ts
 * schema.register( 'listItem', {
 * 	inheritAllFrom: '$block',
 * 	allowAttributes: [ 'listType', 'listIndent' ]
 * } );
 * ```
 *
 * Which translates to:
 *
 * ```ts
 * schema.register( 'listItem', {
 * 	allowWhere: '$block',
 * 	allowContentOf: '$block',
 * 	allowAttributesOf: '$block',
 * 	inheritTypesFrom: '$block',
 * 	allowAttributes: [ 'listType', 'listIndent' ]
 * } );
 * ```
 *
 * # Tips
 *
 * * Check schema definitions of existing features to see how they are defined.
 * * If you want to publish your feature so other developers can use it, try to use
 * generic items as much as possible.
 * * Keep your model clean. Limit it to the actual data and store information in a normalized way.
 * * Remember about defining the `is*` properties. They do not affect the allowed structures, but they can
 * affect how the editor features treat your elements.
 */
export interface SchemaItemDefinition {
    /**
     * Defines in which other items this item will be allowed.
     */
    allowIn?: string | Array<string>;
    /**
     * Defines which other items are allowed inside this item.
     */
    allowChildren?: string | Array<string>;
    /**
     * Defines allowed attributes of the given item.
     */
    allowAttributes?: string | Array<string>;
    /**
     * Defines in which other items this item will be disallowed. Takes precedence over allow rules.
     */
    disallowIn?: string | Array<string>;
    /**
     * Defines which other items are disallowed inside this item. Takes precedence over allow rules.
     */
    disallowChildren?: string | Array<string>;
    /**
     * Defines disallowed attributes for this item. Takes precedence over allow rules.
     */
    disallowAttributes?: string | Array<string>;
    /**
     * Inherits "allowed children" from other items.
     *
     * Note that the item's "own" rules take precedence over "inherited" rules and can overwrite them.
     */
    allowContentOf?: string | Array<string>;
    /**
     * Inherits "allowed in" from other items.
     *
     * Note that the item's "own" rules take precedence over "inherited" rules and can overwrite them.
     */
    allowWhere?: string | Array<string>;
    /**
     * Inherits "allowed attributes" from other items.
     *
     * Note that the item's "own" rules take precedence over "inherited" rules and can overwrite them.
     */
    allowAttributesOf?: string | Array<string>;
    /**
     * Inherits `is*` properties of other items.
     *
     * Note that the item's "own" rules take precedence over "inherited" rules and can overwrite them.
     */
    inheritTypesFrom?: string | Array<string>;
    /**
     * A shorthand for `allowContentOf`, `allowWhere`, `allowAttributesOf`, `inheritTypesFrom`.
     *
     * Note that the item's "own" rules take precedence over "inherited" rules and can overwrite them.
     */
    inheritAllFrom?: string;
    /**
     * Whether this item is paragraph-like. Generally speaking, content is usually made out of blocks
     * like paragraphs, list items, images, headings, etc. All these elements are marked as blocks. A block
     * should not allow another block inside. Note: There is also the `$block` generic item which has `isBlock` set to `true`.
     * Most block type items will inherit from `$block` (through `inheritAllFrom`).
     *
     * Read more about the block elements in the
     * {@glink framework/deep-dive/schema#block-elements Block elements section} of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide.
     */
    isBlock?: boolean;
    /**
     * Whether an item is "text-like" and should be treated as an inline node. Examples of inline elements:
     * `$text`, `softBreak` (`<br>`), etc.
     *
     * Read more about the inline elements in the
     * {@glink framework/deep-dive/schema#inline-elements Inline elements section} of the Schema deep-dive guide.
     */
    isInline?: boolean;
    /**
     * It can be understood as whether this element should not be split by <kbd>Enter</kbd>.
     * Examples of limit elements: `$root`, table cell, image caption, etc. In other words, all actions that happen inside
     * a limit element are limited to its content.
     *
     * Read more about the limit elements in the
     * {@glink framework/deep-dive/schema#limit-elements Limit elements section} of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide.
     */
    isLimit?: boolean;
    /**
     * Whether an item is "self-contained" and should be treated as a whole. Examples of object elements:
     * `imageBlock`, `table`, `video`, etc.
     *
     * **Note:** An object is also a limit, so
     * {@link module:engine/model/schema~Schema#isLimit `isLimit()`} returns `true` for object elements automatically.
     *
     * Read more about the object elements in the
     * {@glink framework/deep-dive/schema#object-elements Object elements section} of the Schema deep-dive guide.
     */
    isObject?: boolean;
    /**
     * `true` when an element should be selectable as a whole by the user.
     * Examples of selectable elements: `imageBlock`, `table`, `tableCell`, etc.
     *
     * **Note:** An object is also a selectable element, so
     * {@link module:engine/model/schema~Schema#isSelectable `isSelectable()`} returns `true` for object elements automatically.
     *
     * Read more about selectable elements in the
     * {@glink framework/deep-dive/schema#selectable-elements Selectable elements section} of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide.
     */
    isSelectable?: boolean;
    /**
     * An item is a content when it always finds its way to the editor data output regardless of the number and type of its descendants.
     * Examples of content elements: `$text`, `imageBlock`, `table`, etc. (but not `paragraph`, `heading1` or `tableCell`).
     *
     * **Note:** An object is also a content element, so
     * {@link module:engine/model/schema~Schema#isContent `isContent()`} returns `true` for object elements automatically.
     *
     * Read more about content elements in the
     * {@glink framework/deep-dive/schema#content-elements Content elements section} of
     * the {@glink framework/deep-dive/schema Schema deep-dive} guide.
     */
    isContent?: boolean;
}
/**
 * A simplified version of {@link module:engine/model/schema~SchemaItemDefinition} after
 * compilation by the {@link module:engine/model/schema~Schema schema}.
 * Rules fed to the schema by {@link module:engine/model/schema~Schema#register}
 * and {@link module:engine/model/schema~Schema#extend} methods are defined in the
 * {@link module:engine/model/schema~SchemaItemDefinition} format.
 * Later on, they are compiled to `SchemaCompiledItemDefinition` so when you use e.g.
 * the {@link module:engine/model/schema~Schema#getDefinition} method you get the compiled version.
 *
 * The compiled version contains only the following properties:
 *
 * * The `name` property,
 * * The `is*` properties,
 * * The `allowIn` array,
 * * The `allowChildren` array,
 * * The `allowAttributes` array.
 */
export interface SchemaCompiledItemDefinition {
    name: string;
    isBlock: boolean;
    isContent: boolean;
    isInline: boolean;
    isLimit: boolean;
    isObject: boolean;
    isSelectable: boolean;
    allowIn: Array<string>;
    allowChildren: Array<string>;
    allowAttributes: Array<string>;
}
/**
 * A schema context &ndash; a list of ancestors of a given position in the document.
 *
 * Considering such position:
 *
 * ```xml
 * <$root>
 * 	<blockQuote>
 * 		<paragraph>
 * 			^
 * 		</paragraph>
 * 	</blockQuote>
 * </$root>
 * ```
 *
 * The context of this position is its {@link module:engine/model/position~Position#getAncestors lists of ancestors}:
 *
 *		[ rootElement, blockQuoteElement, paragraphElement ]
 *
 * Contexts are used in the {@link module:engine/model/schema~Schema#event:checkChild `Schema#checkChild`} and
 * {@link module:engine/model/schema~Schema#event:checkAttribute `Schema#checkAttribute`} events as a definition
 * of a place in the document where the check occurs. The context instances are created based on the first arguments
 * of the {@link module:engine/model/schema~Schema#checkChild `Schema#checkChild()`} and
 * {@link module:engine/model/schema~Schema#checkAttribute `Schema#checkAttribute()`} methods so when
 * using these methods you need to use {@link module:engine/model/schema~SchemaContextDefinition}s.
 */
export declare class SchemaContext implements Iterable<SchemaContextItem> {
    private _items;
    /**
     * Creates an instance of the context.
     */
    constructor(context: SchemaContextDefinition);
    /**
     * The number of items.
     */
    get length(): number;
    /**
     * The last item (the lowest node).
     */
    get last(): SchemaContextItem;
    /**
     * Iterable interface.
     *
     * Iterates over all context items.
     */
    [Symbol.iterator](): IterableIterator<SchemaContextItem>;
    /**
     * Returns a new schema context instance with an additional item.
     *
     * Item can be added as:
     *
     * ```ts
     * const context = new SchemaContext( [ '$root' ] );
     *
     * // An element.
     * const fooElement = writer.createElement( 'fooElement' );
     * const newContext = context.push( fooElement ); // [ '$root', 'fooElement' ]
     *
     * // A text node.
     * const text = writer.createText( 'foobar' );
     * const newContext = context.push( text ); // [ '$root', '$text' ]
     *
     * // A string (element name).
     * const newContext = context.push( 'barElement' ); // [ '$root', 'barElement' ]
     * ```
     *
     * **Note** {@link module:engine/model/node~Node} that is already in the model tree will be added as the only item
     * (without ancestors).
     *
     * @param item An item that will be added to the current context.
     * @returns A new schema context instance with an additional item.
     */
    push(item: string | Node): SchemaContext;
    /**
     * Returns a new schema context that is based on this context but has the last item removed.
     *
     * ```ts
     * const ctxParagraph = new SchemaContext( [ '$root', 'blockQuote', 'paragraph' ] );
     * const ctxBlockQuote = ctxParagraph.trimLast(); // Items in `ctxBlockQuote` are: `$root` an `blockQuote`.
     * const ctxRoot = ctxBlockQuote.trimLast(); // Items in `ctxRoot` are: `$root`.
     * ```
     *
     * @returns A new reduced schema context instance.
     */
    trimLast(): SchemaContext;
    /**
     * Gets an item on the given index.
     */
    getItem(index: number): SchemaContextItem;
    /**
     * Returns the names of items.
     */
    getNames(): IterableIterator<string>;
    /**
     * Checks whether the context ends with the given nodes.
     *
     * ```ts
     * const ctx = new SchemaContext( [ rootElement, paragraphElement, textNode ] );
     *
     * ctx.endsWith( '$text' ); // -> true
     * ctx.endsWith( 'paragraph $text' ); // -> true
     * ctx.endsWith( '$root' ); // -> false
     * ctx.endsWith( 'paragraph' ); // -> false
     * ```
     */
    endsWith(query: string): boolean;
    /**
     * Checks whether the context starts with the given nodes.
     *
     * ```ts
     * const ctx = new SchemaContext( [ rootElement, paragraphElement, textNode ] );
     *
     * ctx.endsWith( '$root' ); // -> true
     * ctx.endsWith( '$root paragraph' ); // -> true
     * ctx.endsWith( '$text' ); // -> false
     * ctx.endsWith( 'paragraph' ); // -> false
     * ```
     */
    startsWith(query: string): boolean;
}
/**
 * The definition of a {@link module:engine/model/schema~SchemaContext schema context}.
 *
 * Contexts can be created in multiple ways:
 *
 * * By defining a **node** – in this cases this node and all its ancestors will be used.
 * * By defining a **position** in the document – in this case all its ancestors will be used.
 * * By defining an **array of nodes** – in this case this array defines the entire context.
 * * By defining a **name of node** - in this case node will be "mocked". It is not recommended because context
 * will be unrealistic (e.g. attributes of these nodes are not specified). However, at times this may be the only
 * way to define the context (e.g. when checking some hypothetical situation).
 * * By defining an **array of node names** (potentially, mixed with real nodes) – The same as **name of node**
 * but it is possible to create a path.
 * * By defining a {@link module:engine/model/schema~SchemaContext} instance - in this case the same instance as provided
 * will be returned.
 *
 * Examples of context definitions passed to the {@link module:engine/model/schema~Schema#checkChild `Schema#checkChild()`}
 * method:
 *
 * ```ts
 * // Assuming that we have a $root > blockQuote > paragraph structure, the following code
 * // will check node 'foo' in the following context:
 * // [ rootElement, blockQuoteElement, paragraphElement ]
 * const contextDefinition = paragraphElement;
 * const childToCheck = 'foo';
 * schema.checkChild( contextDefinition, childToCheck );
 *
 * // Also check in [ rootElement, blockQuoteElement, paragraphElement ].
 * schema.checkChild( model.createPositionAt( paragraphElement, 0 ), 'foo' );
 *
 * // Check in [ rootElement, paragraphElement ].
 * schema.checkChild( [ rootElement, paragraphElement ], 'foo' );
 *
 * // Check only fakeParagraphElement.
 * schema.checkChild( 'paragraph', 'foo' );
 *
 * // Check in [ fakeRootElement, fakeBarElement, paragraphElement ].
 * schema.checkChild( [ '$root', 'bar', paragraphElement ], 'foo' );
 * ```
 *
 * All these `checkChild()` calls will fire {@link module:engine/model/schema~Schema#event:checkChild `Schema#checkChild`}
 * events in which `args[ 0 ]` is an instance of the context. Therefore, you can write a listener like this:
 *
 * ```ts
 * schema.on( 'checkChild', ( evt, args ) => {
 * 	const ctx = args[ 0 ];
 *
 * 	console.log( Array.from( ctx.getNames() ) );
 * } );
 * ```
 *
 * Which will log the following:
 *
 * ```ts
 * [ '$root', 'blockQuote', 'paragraph' ]
 * [ '$root', 'paragraph' ]
 * [ '$root', 'bar', 'paragraph' ]
 * ```
 *
 * Note: When using the {@link module:engine/model/schema~Schema#checkAttribute `Schema#checkAttribute()`} method
 * you may want to check whether a text node may have an attribute. A {@link module:engine/model/text~Text} is a
 * correct way to define a context so you can do this:
 *
 * ```ts
 * schema.checkAttribute( textNode, 'bold' );
 * ```
 *
 * But sometimes you want to check whether a text at a given position might've had some attribute,
 * in which case you can create a context by mixing in an array of elements with a `'$text'` string:
 *
 * ```ts
 * // Check in [ rootElement, paragraphElement, textNode ].
 * schema.checkChild( [ ...positionInParagraph.getAncestors(), '$text' ], 'bold' );
 * ```
 */
export type SchemaContextDefinition = Item | Position | SchemaContext | string | Array<string | Item>;
/**
 * An item of the {@link module:engine/model/schema~SchemaContext schema context}.
 *
 * It contains 3 properties:
 *
 * * `name` – the name of this item,
 * * `* getAttributeKeys()` – a generator of keys of item attributes,
 * * `getAttribute( keyName )` – a method to get attribute values.
 *
 * The context item interface is a highly simplified version of {@link module:engine/model/node~Node} and its role
 * is to expose only the information which schema checks are able to provide (which is the name of the node and
 * node's attributes).
 *
 * ```ts
 * schema.on( 'checkChild', ( evt, args ) => {
 * 	const ctx = args[ 0 ];
 * 	const firstItem = ctx.getItem( 0 );
 *
 * 	console.log( firstItem.name ); // -> '$root'
 * 	console.log( firstItem.getAttribute( 'foo' ) ); // -> 'bar'
 * 	console.log( Array.from( firstItem.getAttributeKeys() ) ); // -> [ 'foo', 'faa' ]
 * } );
 * ```
 */
export interface SchemaContextItem {
    name: string;
    getAttributeKeys(): Generator<string>;
    getAttribute(keyName: string): unknown;
}
/**
 * A structure containing additional metadata describing the attribute.
 *
 * See {@link module:engine/model/schema~Schema#setAttributeProperties `Schema#setAttributeProperties()`} for usage examples.
 */
export interface AttributeProperties {
    /**
     * Indicates that the attribute should be considered as a visual formatting, like `bold`, `italic` or
     * `fontSize` rather than semantic attribute (such as `src`, `listType`, etc.). For example, it is used by the "Remove format" feature.
     */
    isFormatting?: boolean;
    /**
     * Indicates that given text attribute should be copied to the next block when enter is pressed.
     */
    copyOnEnter?: boolean;
    /**
     * Indicates that given attribute should be preserved while replacing the element.
     */
    copyOnReplace?: boolean;
    /**
     * Indicates that given text attribute should be copied from an inline object to the next inserted inline content.
     *
     * @default true
     */
    copyFromObject?: boolean;
    [name: string]: unknown;
}
export type SchemaAttributeCheckCallback = (context: SchemaContext, attributeName: string) => boolean | undefined;
export type SchemaChildCheckCallback = (context: SchemaContext, definition: SchemaCompiledItemDefinition) => boolean | undefined;
export {};
