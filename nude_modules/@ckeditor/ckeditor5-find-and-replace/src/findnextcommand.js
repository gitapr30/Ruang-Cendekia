/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module find-and-replace/findnextcommand
*/
import { Command } from 'ckeditor5/src/core.js';
/**
 * The find next command. Moves the highlight to the next search result.
 *
 * It is used by the {@link module:find-and-replace/findandreplace~FindAndReplace find and replace feature}.
 */
export default class FindNextCommand extends Command {
    /**
     * Creates a new `FindNextCommand` instance.
     *
     * @param editor The editor on which this command will be used.
     * @param state An object to hold plugin state.
     */
    constructor(editor, state) {
        super(editor);
        // It does not affect data so should be enabled in read-only mode.
        this.affectsData = false;
        this._state = state;
        this.isEnabled = false;
        this.listenTo(this._state.results, 'change', () => {
            this.isEnabled = this._state.results.length > 1;
        });
    }
    /**
     * @inheritDoc
     */
    refresh() {
        this.isEnabled = this._state.results.length > 1;
    }
    /**
     * @inheritDoc
     */
    execute() {
        const results = this._state.results;
        const currentIndex = results.getIndex(this._state.highlightedResult);
        const nextIndex = currentIndex + 1 >= results.length ?
            0 : currentIndex + 1;
        this._state.highlightedResult = this._state.results.get(nextIndex);
    }
}
