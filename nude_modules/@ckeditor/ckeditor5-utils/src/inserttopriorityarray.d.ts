/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
import { type PriorityString } from './priorities.js';
/**
 * @module utils/inserttopriorityarray
 */
/**
 * The priority object descriptor.
 *
 * ```ts
 * const objectWithPriority = {
 * 	priority: 'high'
 * }
 * ```
 */
export interface ObjectWithPriority {
    /**
     * Priority of the object.
     */
    priority: PriorityString;
}
/**
 * Inserts any object with priority at correct index by priority so registered objects are always sorted from highest to lowest priority.
 *
 * @param objects Array of objects with priority to insert object to.
 * @param objectToInsert Object with `priority` property.
 */
export default function insertToPriorityArray<T extends ObjectWithPriority>(objects: Array<T>, objectToInsert: T): void;
