/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module table/tableproperties/commands/tableborderstylecommand
 */
import type { Element } from 'ckeditor5/src/engine.js';
import TablePropertyCommand from './tablepropertycommand.js';
import type { Editor } from 'ckeditor5/src/core.js';
/**
 * The table style border command.
 *
 * The command is registered by the {@link module:table/tableproperties/tablepropertiesediting~TablePropertiesEditing} as
 * the `'tableBorderStyle'` editor command.
 *
 * To change the border style of the selected table, execute the command:
 *
 * ```ts
 * editor.execute( 'tableBorderStyle', {
 *   value: 'dashed'
 * } );
 * ```
 */
export default class TableBorderStyleCommand extends TablePropertyCommand {
    /**
     * Creates a new `TableBorderStyleCommand` instance.
     *
     * @param editor An editor in which this command will be used.
     * @param defaultValue The default value of the attribute.
     */
    constructor(editor: Editor, defaultValue: string);
    /**
     * @inheritDoc
     */
    protected _getValue(table: Element): unknown;
}
