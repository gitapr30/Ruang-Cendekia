/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
import { Command } from 'ckeditor5/src/core.js';
import { findOptimalInsertionRange } from 'ckeditor5/src/widget.js';
/**
 * The horizontal line command.
 *
 * The command is registered by {@link module:horizontal-line/horizontallineediting~HorizontalLineEditing} as `'horizontalLine'`.
 *
 * To insert a horizontal line at the current selection, execute the command:
 *
 * ```ts
 * editor.execute( 'horizontalLine' );
 * ```
 */
export default class HorizontalLineCommand extends Command {
    /**
     * @inheritDoc
     */
    refresh() {
        const model = this.editor.model;
        const schema = model.schema;
        const selection = model.document.selection;
        this.isEnabled = isHorizontalLineAllowedInParent(selection, schema, model);
    }
    /**
     * Executes the command.
     *
     * @fires execute
     */
    execute() {
        const model = this.editor.model;
        model.change(writer => {
            const horizontalElement = writer.createElement('horizontalLine');
            model.insertObject(horizontalElement, null, null, { setSelection: 'after' });
        });
    }
}
/**
 * Checks if a horizontal line is allowed by the schema in the optimal insertion parent.
 *
 * @param model Model instance.
 */
function isHorizontalLineAllowedInParent(selection, schema, model) {
    const parent = getInsertHorizontalLineParent(selection, model);
    return schema.checkChild(parent, 'horizontalLine');
}
/**
 * Returns a node that will be used to insert a horizontal line with `model.insertContent` to check if the horizontal line can be
 * placed there.
 *
 * @param model Model instance.
 */
function getInsertHorizontalLineParent(selection, model) {
    const insertionRange = findOptimalInsertionRange(selection, model);
    const parent = insertionRange.start.parent;
    if (parent.isEmpty && !parent.is('element', '$root')) {
        return parent.parent;
    }
    return parent;
}
