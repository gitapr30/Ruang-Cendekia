/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module select-all/selectall
 */
import { Plugin } from '@ckeditor/ckeditor5-core';
import SelectAllEditing from './selectallediting.js';
import SelectAllUI from './selectallui.js';
/**
 * The select all feature.
 *
 * This is a "glue" plugin which loads the {@link module:select-all/selectallediting~SelectAllEditing select all editing feature}
 * and the {@link module:select-all/selectallui~SelectAllUI select all UI feature}.
 *
 * Please refer to the documentation of individual features to learn more.
 */
export default class SelectAll extends Plugin {
    /**
     * @inheritDoc
     */
    static get requires(): readonly [typeof SelectAllEditing, typeof SelectAllUI];
    /**
     * @inheritDoc
     */
    static get pluginName(): "SelectAll";
    /**
     * @inheritDoc
     */
    static get isOfficialPlugin(): true;
}
