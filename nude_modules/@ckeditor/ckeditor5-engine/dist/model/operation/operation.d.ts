/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module engine/model/operation/operation
 */
import type Batch from '../batch.js';
import type Document from '../document.js';
import type { Selectable } from '../selection.js';
/**
 * Abstract base operation class.
 */
export default abstract class Operation {
    /**
     * {@link module:engine/model/document~Document#version} on which operation can be applied. If you try to
     * {@link module:engine/model/model~Model#applyOperation apply} operation with different base version than the
     * {@link module:engine/model/document~Document#version document version} the
     * {@link module:utils/ckeditorerror~CKEditorError model-document-applyOperation-wrong-version} error is thrown.
     */
    baseVersion: number | null;
    /**
     * Defines whether operation is executed on attached or detached {@link module:engine/model/item~Item items}.
     */
    readonly isDocumentOperation: boolean;
    /**
     * {@link module:engine/model/batch~Batch Batch} to which the operation is added or `null` if the operation is not
     * added to any batch yet.
     */
    batch: Batch | null;
    /**
     * Operation type.
     */
    abstract readonly type: string;
    /**
     * Base operation constructor.
     *
     * @param baseVersion Document {@link module:engine/model/document~Document#version} on which operation
     * can be applied or `null` if the operation operates on detached (non-document) tree.
     */
    constructor(baseVersion: number | null);
    /**
     * A selectable that will be affected by the operation after it is executed.
     *
     * The exact returned parameter differs between operation types.
     */
    abstract get affectedSelectable(): Selectable;
    /**
     * Creates and returns an operation that has the same parameters as this operation.
     *
     * @returns Clone of this operation.
     */
    abstract clone(): Operation;
    /**
     * Creates and returns a reverse operation. Reverse operation when executed right after
     * the original operation will bring back tree model state to the point before the original
     * operation execution. In other words, it reverses changes done by the original operation.
     *
     * Keep in mind that tree model state may change since executing the original operation,
     * so reverse operation will be "outdated". In that case you will need to transform it by
     * all operations that were executed after the original operation.
     *
     * @returns Reversed operation.
     */
    abstract getReversed(): Operation;
    /**
     * Executes the operation - modifications described by the operation properties will be applied to the model tree.
     *
     * @internal
     */
    abstract _execute(): void;
    /**
     * Checks whether the operation's parameters are correct and the operation can be correctly executed. Throws
     * an error if operation is not valid.
     *
     * @internal
     */
    _validate(): void;
    /**
     * Custom toJSON method to solve child-parent circular dependencies.
     *
     * @returns Clone of this object with the operation property replaced with string.
     */
    toJSON(): unknown;
    /**
     * Name of the operation class used for serialization.
     */
    static get className(): string;
    /**
     * Creates `Operation` object from deserialized object, i.e. from parsed JSON string.
     *
     * @param json Deserialized JSON object.
     * @param doc Document on which this operation will be applied.
     */
    static fromJSON(json: any, document: Document): Operation;
}
