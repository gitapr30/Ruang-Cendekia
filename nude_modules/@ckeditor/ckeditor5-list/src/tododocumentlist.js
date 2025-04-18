/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module list/tododocumentlist
 */
import { Plugin } from 'ckeditor5/src/core.js';
import { logWarning } from 'ckeditor5/src/utils.js';
import TodoList from './todolist.js';
/**
 * The to-do list feature.
 *
 * This is an obsolete plugin that exists for backward compatibility only.
 * Use the {@link module:list/todolist~TodoList `TodoList`} instead.
 *
 * @deprecated
 */
export default class TodoDocumentList extends Plugin {
    /**
     * @inheritDoc
     */
    static get requires() {
        return [TodoList];
    }
    /**
     * @inheritDoc
     */
    static get pluginName() {
        return 'TodoDocumentList';
    }
    /**
     * @inheritDoc
     */
    static get isOfficialPlugin() {
        return true;
    }
    constructor(editor) {
        super(editor);
        /**
         * The `TodoDocumentList` plugin is obsolete. Use `TodoList` instead.
         *
         * @error plugin-obsolete-tododocumentlist
         */
        logWarning('plugin-obsolete-tododocumentlist', { pluginName: 'TodoDocumentList' });
    }
}
