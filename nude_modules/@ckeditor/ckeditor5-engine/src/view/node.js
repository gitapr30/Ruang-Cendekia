/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module engine/view/node
 */
import TypeCheckable from './typecheckable.js';
import { CKEditorError, EmitterMixin, compareArrays } from '@ckeditor/ckeditor5-utils';
import { clone } from 'lodash-es';
/**
 * Abstract view node class.
 *
 * This is an abstract class. Its constructor should not be used directly.
 * Use the {@link module:engine/view/downcastwriter~DowncastWriter} or {@link module:engine/view/upcastwriter~UpcastWriter}
 * to create new instances of view nodes.
 */
export default class Node extends /* #__PURE__ */ EmitterMixin(TypeCheckable) {
    /**
     * Creates a tree view node.
     *
     * @param document The document instance to which this node belongs.
     */
    constructor(document) {
        super();
        this.document = document;
        this.parent = null;
    }
    /**
     * Index of the node in the parent element or null if the node has no parent.
     *
     * Accessing this property throws an error if this node's parent element does not contain it.
     * This means that view tree got broken.
     */
    get index() {
        let pos;
        if (!this.parent) {
            return null;
        }
        // No parent or child doesn't exist in parent's children.
        if ((pos = this.parent.getChildIndex(this)) == -1) {
            /**
             * The node's parent does not contain this node. It means that the document tree is corrupted.
             *
             * @error view-node-not-found-in-parent
             */
            throw new CKEditorError('view-node-not-found-in-parent', this);
        }
        return pos;
    }
    /**
     * Node's next sibling, or `null` if it is the last child.
     */
    get nextSibling() {
        const index = this.index;
        return (index !== null && this.parent.getChild(index + 1)) || null;
    }
    /**
     * Node's previous sibling, or `null` if it is the first child.
     */
    get previousSibling() {
        const index = this.index;
        return (index !== null && this.parent.getChild(index - 1)) || null;
    }
    /**
     * Top-most ancestor of the node. If the node has no parent it is the root itself.
     */
    get root() {
        // eslint-disable-next-line @typescript-eslint/no-this-alias, consistent-this
        let root = this;
        while (root.parent) {
            root = root.parent;
        }
        return root;
    }
    /**
     * Returns true if the node is in a tree rooted in the document (is a descendant of one of its roots).
     */
    isAttached() {
        return this.root.is('rootElement');
    }
    /**
     * Gets a path to the node. The path is an array containing indices of consecutive ancestors of this node,
     * beginning from {@link module:engine/view/node~Node#root root}, down to this node's index.
     *
     * ```ts
     * const abc = downcastWriter.createText( 'abc' );
     * const foo = downcastWriter.createText( 'foo' );
     * const h1 = downcastWriter.createElement( 'h1', null, downcastWriter.createText( 'header' ) );
     * const p = downcastWriter.createElement( 'p', null, [ abc, foo ] );
     * const div = downcastWriter.createElement( 'div', null, [ h1, p ] );
     * foo.getPath(); // Returns [ 1, 3 ]. `foo` is in `p` which is in `div`. `p` starts at offset 1, while `foo` at 3.
     * h1.getPath(); // Returns [ 0 ].
     * div.getPath(); // Returns [].
     * ```
     *
     * @returns The path.
     */
    getPath() {
        const path = [];
        // eslint-disable-next-line @typescript-eslint/no-this-alias, consistent-this
        let node = this;
        while (node.parent) {
            path.unshift(node.index);
            node = node.parent;
        }
        return path;
    }
    /**
     * Returns ancestors array of this node.
     *
     * @param options Options object.
     * @param options.includeSelf When set to `true` this node will be also included in parent's array.
     * @param options.parentFirst When set to `true`, array will be sorted from node's parent to root element,
     * otherwise root element will be the first item in the array.
     * @returns Array with ancestors.
     */
    getAncestors(options = {}) {
        const ancestors = [];
        let parent = options.includeSelf ? this : this.parent;
        while (parent) {
            ancestors[options.parentFirst ? 'push' : 'unshift'](parent);
            parent = parent.parent;
        }
        return ancestors;
    }
    /**
     * Returns a {@link module:engine/view/element~Element} or {@link module:engine/view/documentfragment~DocumentFragment}
     * which is a common ancestor of both nodes.
     *
     * @param node The second node.
     * @param options Options object.
     * @param options.includeSelf When set to `true` both nodes will be considered "ancestors" too.
     * Which means that if e.g. node A is inside B, then their common ancestor will be B.
     */
    getCommonAncestor(node, options = {}) {
        const ancestorsA = this.getAncestors(options);
        const ancestorsB = node.getAncestors(options);
        let i = 0;
        while (ancestorsA[i] == ancestorsB[i] && ancestorsA[i]) {
            i++;
        }
        return i === 0 ? null : ancestorsA[i - 1];
    }
    /**
     * Returns whether this node is before given node. `false` is returned if nodes are in different trees (for example,
     * in different {@link module:engine/view/documentfragment~DocumentFragment}s).
     *
     * @param node Node to compare with.
     */
    isBefore(node) {
        // Given node is not before this node if they are same.
        if (this == node) {
            return false;
        }
        // Return `false` if it is impossible to compare nodes.
        if (this.root !== node.root) {
            return false;
        }
        const thisPath = this.getPath();
        const nodePath = node.getPath();
        const result = compareArrays(thisPath, nodePath);
        switch (result) {
            case 'prefix':
                return true;
            case 'extension':
                return false;
            default:
                return thisPath[result] < nodePath[result];
        }
    }
    /**
     * Returns whether this node is after given node. `false` is returned if nodes are in different trees (for example,
     * in different {@link module:engine/view/documentfragment~DocumentFragment}s).
     *
     * @param node Node to compare with.
     */
    isAfter(node) {
        // Given node is not before this node if they are same.
        if (this == node) {
            return false;
        }
        // Return `false` if it is impossible to compare nodes.
        if (this.root !== node.root) {
            return false;
        }
        // In other cases, just check if the `node` is before, and return the opposite.
        return !this.isBefore(node);
    }
    /**
     * Removes node from parent.
     *
     * @internal
     */
    _remove() {
        this.parent._removeChildren(this.index);
    }
    /**
     * @internal
     * @param type Type of the change.
     * @param node Changed node.
     * @fires change
     */
    _fireChange(type, node) {
        this.fire(`change:${type}`, node);
        if (this.parent) {
            this.parent._fireChange(type, node);
        }
    }
    /**
     * Custom toJSON method to solve child-parent circular dependencies.
     *
     * @returns Clone of this object with the parent property removed.
     */
    toJSON() {
        const json = clone(this);
        // Due to circular references we need to remove parent reference.
        delete json.parent;
        return json;
    }
}
// The magic of type inference using `is` method is centralized in `TypeCheckable` class.
// Proper overload would interfere with that.
Node.prototype.is = function (type) {
    return type === 'node' || type === 'view:node';
};
