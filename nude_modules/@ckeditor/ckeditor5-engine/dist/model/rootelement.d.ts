/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module engine/model/rootelement
 */
import Element from './element.js';
import type Document from './document.js';
/**
 * Type of {@link module:engine/model/element~Element} that is a root of a model tree.
 */
export default class RootElement extends Element {
    /**
     * Unique root name used to identify this root element by {@link module:engine/model/document~Document}.
     */
    readonly rootName: string;
    /**
     * Document that is an owner of this root.
     */
    private readonly _document;
    /**
     * @internal
     */
    _isAttached: boolean;
    /**
     * Informs if the root element is loaded (default).
     *
     * @internal
     */
    _isLoaded: boolean;
    /**
     * Creates root element.
     *
     * @param document Document that is an owner of this root.
     * @param name Node name.
     * @param rootName Unique root name used to identify this root element by {@link module:engine/model/document~Document}.
     */
    constructor(document: Document, name: string, rootName?: string);
    /**
     * {@link module:engine/model/document~Document Document} that owns this root element.
     */
    get document(): Document;
    /**
     * Informs if the root element is currently attached to the document, or not.
     *
     * A detached root is equivalent to being removed and cannot contain any children or markers.
     *
     * By default, a newly added root is attached. It can be detached using
     * {@link module:engine/model/writer~Writer#detachRoot `Writer#detachRoot`}. A detached root can be re-attached again using
     * {@link module:engine/model/writer~Writer#addRoot `Writer#addRoot`}.
     */
    isAttached(): boolean;
    /**
     * Converts `RootElement` instance to `string` containing its name.
     *
     * @returns `RootElement` instance converted to `string`.
     */
    toJSON(): unknown;
}
