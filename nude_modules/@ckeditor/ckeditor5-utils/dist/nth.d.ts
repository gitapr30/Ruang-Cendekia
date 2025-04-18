/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module utils/nth
 */
/**
 * Returns `nth` (starts from `0` of course) item of the given `iterable`.
 *
 * If the iterable is a generator, then it consumes **all its items**.
 * If it's a normal iterator, then it consumes **all items up to the given index**.
 * Refer to the [Iterators and Generators](https://developer.mozilla.org/en/docs/Web/JavaScript/Guide/Iterators_and_Generators)
 * guide to learn differences between these interfaces.
 */
export default function nth<T>(index: number, iterable: Iterable<T>): T | null;
