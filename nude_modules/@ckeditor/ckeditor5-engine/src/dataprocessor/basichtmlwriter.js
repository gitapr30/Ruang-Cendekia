/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
import { global } from '@ckeditor/ckeditor5-utils';
/**
 * Basic HTML writer. It uses the native `innerHTML` property for basic conversion
 * from a document fragment to an HTML string.
 */
export default class BasicHtmlWriter {
    /**
     * Returns an HTML string created from the document fragment.
     */
    getHtml(fragment) {
        const doc = global.document.implementation.createHTMLDocument('');
        const container = doc.createElement('div');
        container.appendChild(fragment);
        return container.innerHTML;
    }
}
