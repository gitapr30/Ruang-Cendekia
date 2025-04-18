/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module language/utils
 */
import { type LanguageDirection } from 'ckeditor5/src/utils.js';
/**
 * Returns the language attribute value in a human-readable text format:
 *
 * ```
 * <languageCode>:<textDirection>
 * ```
 *
 * * `languageCode` - The language code used for the `lang` attribute in the [ISO 639-1](https://en.wikipedia.org/wiki/ISO_639-1) format.
 * * `textDirection` - One of the following values: `rtl` or `ltr`, indicating the reading direction of the language.
 *
 * See the {@link module:core/editor/editorconfig~LanguageConfig#textPartLanguage text part language configuration}
 * for more information about language properties.
 *
 * If the `textDirection` argument is omitted, it will be automatically detected based on `languageCode`.
 *
 * @param languageCode The language code in the ISO 639-1 format.
 * @param textDirection The language text direction. Automatically detected if omitted.
 */
export declare function stringifyLanguageAttribute(languageCode: string, textDirection?: LanguageDirection): string;
/**
 * Retrieves language properties converted to attribute value by the
 * {@link module:language/utils~stringifyLanguageAttribute stringifyLanguageAttribute} function.
 *
 * @param str The attribute value.
 * @returns The object with properties:
 * * languageCode - The language code in the ISO 639 format.
 * * textDirection - The language text direction.
 */
export declare function parseLanguageAttribute(str: string): {
    languageCode: string;
    textDirection: string;
};
