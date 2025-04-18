/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
import { Plugin } from 'ckeditor5/src/core.js';
import ImageUtils from '../imageutils.js';
import ResizeImageCommand from './resizeimagecommand.js';
import { widthAndHeightStylesAreBothSet } from '../image/utils.js';
/**
 * The image resize editing feature.
 *
 * It adds the ability to resize each image using handles or manually by
 * {@link module:image/imageresize/imageresizebuttons~ImageResizeButtons} buttons.
 */
export default class ImageResizeEditing extends Plugin {
    /**
     * @inheritDoc
     */
    static get requires() {
        return [ImageUtils];
    }
    /**
     * @inheritDoc
     */
    static get pluginName() {
        return 'ImageResizeEditing';
    }
    /**
     * @inheritDoc
     */
    static get isOfficialPlugin() {
        return true;
    }
    /**
     * @inheritDoc
     */
    constructor(editor) {
        super(editor);
        editor.config.define('image', {
            resizeUnit: '%',
            resizeOptions: [
                {
                    name: 'resizeImage:original',
                    value: null,
                    icon: 'original'
                },
                {
                    name: 'resizeImage:custom',
                    value: 'custom',
                    icon: 'custom'
                },
                {
                    name: 'resizeImage:25',
                    value: '25',
                    icon: 'small'
                },
                {
                    name: 'resizeImage:50',
                    value: '50',
                    icon: 'medium'
                },
                {
                    name: 'resizeImage:75',
                    value: '75',
                    icon: 'large'
                }
            ]
        });
    }
    /**
     * @inheritDoc
     */
    init() {
        const editor = this.editor;
        const resizeImageCommand = new ResizeImageCommand(editor);
        this._registerConverters('imageBlock');
        this._registerConverters('imageInline');
        // Register `resizeImage` command and add `imageResize` command as an alias for backward compatibility.
        editor.commands.add('resizeImage', resizeImageCommand);
        editor.commands.add('imageResize', resizeImageCommand);
    }
    /**
     * @inheritDoc
     */
    afterInit() {
        this._registerSchema();
    }
    _registerSchema() {
        if (this.editor.plugins.has('ImageBlockEditing')) {
            this.editor.model.schema.extend('imageBlock', { allowAttributes: ['resizedWidth', 'resizedHeight'] });
        }
        if (this.editor.plugins.has('ImageInlineEditing')) {
            this.editor.model.schema.extend('imageInline', { allowAttributes: ['resizedWidth', 'resizedHeight'] });
        }
    }
    /**
     * Registers image resize converters.
     *
     * @param imageType The type of the image.
     */
    _registerConverters(imageType) {
        const editor = this.editor;
        const imageUtils = editor.plugins.get('ImageUtils');
        // Dedicated converter to propagate image's attribute to the img tag.
        editor.conversion.for('downcast').add(dispatcher => dispatcher.on(`attribute:resizedWidth:${imageType}`, (evt, data, conversionApi) => {
            if (!conversionApi.consumable.consume(data.item, evt.name)) {
                return;
            }
            const viewWriter = conversionApi.writer;
            const viewImg = conversionApi.mapper.toViewElement(data.item);
            if (data.attributeNewValue !== null) {
                viewWriter.setStyle('width', data.attributeNewValue, viewImg);
                viewWriter.addClass('image_resized', viewImg);
            }
            else {
                viewWriter.removeStyle('width', viewImg);
                viewWriter.removeClass('image_resized', viewImg);
            }
        }));
        editor.conversion.for('dataDowncast').attributeToAttribute({
            model: {
                name: imageType,
                key: 'resizedHeight'
            },
            view: modelAttributeValue => ({
                key: 'style',
                value: {
                    'height': modelAttributeValue
                }
            })
        });
        editor.conversion.for('editingDowncast').add(dispatcher => dispatcher.on(`attribute:resizedHeight:${imageType}`, (evt, data, conversionApi) => {
            if (!conversionApi.consumable.consume(data.item, evt.name)) {
                return;
            }
            const viewWriter = conversionApi.writer;
            const viewImg = conversionApi.mapper.toViewElement(data.item);
            const target = imageType === 'imageInline' ? imageUtils.findViewImgElement(viewImg) : viewImg;
            if (data.attributeNewValue !== null) {
                viewWriter.setStyle('height', data.attributeNewValue, target);
            }
            else {
                viewWriter.removeStyle('height', target);
            }
        }));
        editor.conversion.for('upcast')
            .attributeToAttribute({
            view: {
                name: imageType === 'imageBlock' ? 'figure' : 'img',
                styles: {
                    width: /.+/
                }
            },
            model: {
                key: 'resizedWidth',
                value: (viewElement) => {
                    if (widthAndHeightStylesAreBothSet(viewElement)) {
                        return null;
                    }
                    return viewElement.getStyle('width');
                }
            }
        });
        editor.conversion.for('upcast')
            .attributeToAttribute({
            view: {
                name: imageType === 'imageBlock' ? 'figure' : 'img',
                styles: {
                    height: /.+/
                }
            },
            model: {
                key: 'resizedHeight',
                value: (viewElement) => {
                    if (widthAndHeightStylesAreBothSet(viewElement)) {
                        return null;
                    }
                    return viewElement.getStyle('height');
                }
            }
        });
    }
}
