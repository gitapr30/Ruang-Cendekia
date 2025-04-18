/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module font/fontfamily
 */
import { Plugin } from 'ckeditor5/src/core.js';
import FontFamilyEditing from './fontfamily/fontfamilyediting.js';
import FontFamilyUI from './fontfamily/fontfamilyui.js';
/**
 * The font family plugin.
 *
 * For a detailed overview, check the {@glink features/font font feature} documentatiom
 * and the {@glink api/font package page}.
 *
 * This is a "glue" plugin which loads the {@link module:font/fontfamily/fontfamilyediting~FontFamilyEditing} and
 * {@link module:font/fontfamily/fontfamilyui~FontFamilyUI} features in the editor.
 */
export default class FontFamily extends Plugin {
    /**
     * @inheritDoc
     */
    static get requires(): readonly [typeof FontFamilyEditing, typeof FontFamilyUI];
    /**
     * @inheritDoc
     */
    static get pluginName(): "FontFamily";
    /**
     * @inheritDoc
     */
    static get isOfficialPlugin(): true;
}
