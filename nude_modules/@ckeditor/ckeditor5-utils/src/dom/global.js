/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * A helper (module) giving an access to the global DOM objects such as `window` and
 * `document`. Accessing these objects using this helper allows easy and bulletproof
 * testing, i.e. stubbing native properties:
 *
 * ```ts
 * import { global } from 'ckeditor5/utils';
 *
 * // This stub will work for any code using global module.
 * testUtils.sinon.stub( global, 'window', {
 * 	innerWidth: 10000
 * } );
 *
 * console.log( global.window.innerWidth );
 * ```
 */
let globalVar; // named globalVar instead of global: https://github.com/ckeditor/ckeditor5/issues/12971
// In some environments window and document API might not be available.
try {
    globalVar = { window, document };
}
catch (e) {
    // It's not possible to mock a window object to simulate lack of a window object without writing extremely convoluted code.
    /* istanbul ignore next -- @preserve */
    // Let's cast it to not change module's API.
    // We only handle this so loading editor in environments without window and document doesn't fail.
    // For better DX we shouldn't introduce mixed types and require developers to check the type manually.
    // This module should not be used on purpose in any environment outside browser.
    globalVar = { window: {}, document: {} };
}
export default globalVar;
