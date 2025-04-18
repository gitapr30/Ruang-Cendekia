/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * A facade over the native [`DataTransfer`](https://developer.mozilla.org/en-US/docs/Web/API/DataTransfer) object.
 */
export default class DataTransfer {
    /**
     * @param nativeDataTransfer The native [`DataTransfer`](https://developer.mozilla.org/en-US/docs/Web/API/DataTransfer) object.
     * @param options.cacheFiles Whether `files` list should be initialized in the constructor.
     */
    constructor(nativeDataTransfer, options = {}) {
        // We should store references to the File instances in case someone would like to process this files
        // outside the event handler. Files are stored only for `drop` and `paste` events because they are not usable
        // in other events and are generating a huge delay on Firefox while dragging.
        // See https://github.com/ckeditor/ckeditor5/issues/13366.
        this._files = options.cacheFiles ? getFiles(nativeDataTransfer) : null;
        this._native = nativeDataTransfer;
    }
    /**
     * The array of files created from the native `DataTransfer#files` or `DataTransfer#items`.
     */
    get files() {
        if (!this._files) {
            this._files = getFiles(this._native);
        }
        return this._files;
    }
    /**
     * Returns an array of available native content types.
     */
    get types() {
        return this._native.types;
    }
    /**
     * Gets the data from the data transfer by its MIME type.
     *
     * ```ts
     * dataTransfer.getData( 'text/plain' );
     * ```
     *
     * @param type The MIME type. E.g. `text/html` or `text/plain`.
     */
    getData(type) {
        return this._native.getData(type);
    }
    /**
     * Sets the data in the data transfer.
     *
     * @param type The MIME type. E.g. `text/html` or `text/plain`.
     */
    setData(type, data) {
        this._native.setData(type, data);
    }
    /**
     * The effect that is allowed for a drag operation.
     */
    set effectAllowed(value) {
        this._native.effectAllowed = value;
    }
    get effectAllowed() {
        return this._native.effectAllowed;
    }
    /**
     * The actual drop effect.
     */
    set dropEffect(value) {
        this._native.dropEffect = value;
    }
    get dropEffect() {
        return this._native.dropEffect;
    }
    /**
     * Set a preview image of the dragged content.
     */
    setDragImage(image, x, y) {
        this._native.setDragImage(image, x, y);
    }
    /**
     * Whether the dragging operation was canceled.
     */
    get isCanceled() {
        return this._native.dropEffect == 'none' || !!this._native.mozUserCancelled;
    }
}
function getFiles(nativeDataTransfer) {
    // DataTransfer.files and items are array-like and might not have an iterable interface.
    const files = Array.from(nativeDataTransfer.files || []);
    const items = Array.from(nativeDataTransfer.items || []);
    if (files.length) {
        return files;
    }
    // Chrome has empty DataTransfer.files, but allows getting files through the items interface.
    return items
        .filter(item => item.kind === 'file')
        .map(item => item.getAsFile());
}
