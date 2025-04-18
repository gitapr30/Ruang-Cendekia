/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module ui/highlightedtext/labelwithhighlightview
 */
import HighlightedTextView from './highlightedtextview.js';
import { uid } from '@ckeditor/ckeditor5-utils';
/**
 * A label view that can highlight a text fragment.
 */
export default class LabelWithHighlightView extends HighlightedTextView {
    /**
     * @inheritDoc
     */
    constructor() {
        super();
        this.set('for', undefined);
        const bind = this.bindTemplate;
        this.id = `ck-editor__label_${uid()}`;
        this.extendTemplate({
            attributes: {
                class: [
                    'ck',
                    'ck-label'
                ],
                id: this.id,
                for: bind.to('for')
            }
        });
    }
}
