/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
* @module table/tablecaption/toggletablecaptioncommand
*/
import { Command } from 'ckeditor5/src/core.js';
import { getCaptionFromTableModelElement } from './utils.js';
import { getSelectionAffectedTable } from '../utils/common.js';
/**
 * The toggle table caption command.
 *
 * This command is registered by {@link module:table/tablecaption/tablecaptionediting~TableCaptionEditing} as the
 * `'toggleTableCaption'` editor command.
 *
 * Executing this command:
 *
 * * either adds or removes the table caption of a selected table (depending on whether the caption is present or not),
 * * removes the table caption if the selection is anchored in one.
 *
 * ```ts
 * // Toggle the presence of the caption.
 * editor.execute( 'toggleTableCaption' );
 * ```
 *
 * **Note**: You can move the selection to the caption right away as it shows up upon executing this command by using
 * the `focusCaptionOnShow` option:
 *
 * ```ts
 * editor.execute( 'toggleTableCaption', { focusCaptionOnShow: true } );
 * ```
 */
export default class ToggleTableCaptionCommand extends Command {
    /**
     * @inheritDoc
     */
    refresh() {
        const editor = this.editor;
        const tableElement = getSelectionAffectedTable(editor.model.document.selection);
        this.isEnabled = !!tableElement;
        if (!this.isEnabled) {
            this.value = false;
        }
        else {
            this.value = !!getCaptionFromTableModelElement(tableElement);
        }
    }
    /**
     * Executes the command.
     *
     * ```ts
     * editor.execute( 'toggleTableCaption' );
     * ```
     *
     * @param options Options for the executed command.
     * @param options.focusCaptionOnShow When true and the caption shows up, the selection will be moved into it straight away.
     * @fires execute
     */
    execute({ focusCaptionOnShow = false } = {}) {
        this.editor.model.change(writer => {
            if (this.value) {
                this._hideTableCaption(writer);
            }
            else {
                this._showTableCaption(writer, focusCaptionOnShow);
            }
        });
    }
    /**
     * Shows the table caption. Also:
     *
     * * it attempts to restore the caption content from the `TableCaptionEditing` caption registry,
     * * it moves the selection to the caption right away, it the `focusCaptionOnShow` option was set.
     *
     * @param focusCaptionOnShow Default focus behavior when showing the caption.
     */
    _showTableCaption(writer, focusCaptionOnShow) {
        const model = this.editor.model;
        const tableElement = getSelectionAffectedTable(model.document.selection);
        const tableCaptionEditing = this.editor.plugins.get('TableCaptionEditing');
        const savedCaptionElement = tableCaptionEditing._getSavedCaption(tableElement);
        // Try restoring the caption from the TableCaptionEditing plugin storage.
        const newCaptionElement = savedCaptionElement || writer.createElement('caption');
        model.insertContent(newCaptionElement, tableElement, 'end');
        if (focusCaptionOnShow) {
            writer.setSelection(newCaptionElement, 'in');
        }
    }
    /**
     * Hides the caption of a selected table (or an table caption the selection is anchored to).
     *
     * The content of the caption is stored in the `TableCaptionEditing` caption registry to make this
     * a reversible action.
     */
    _hideTableCaption(writer) {
        const model = this.editor.model;
        const tableElement = getSelectionAffectedTable(model.document.selection);
        const tableCaptionEditing = this.editor.plugins.get('TableCaptionEditing');
        const captionElement = getCaptionFromTableModelElement(tableElement);
        // Store the caption content so it can be restored quickly if the user changes their mind.
        tableCaptionEditing._saveCaption(tableElement, captionElement);
        model.deleteContent(writer.createSelection(captionElement, 'on'));
    }
}
