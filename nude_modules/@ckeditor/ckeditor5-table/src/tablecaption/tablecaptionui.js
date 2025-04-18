/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
* @module table/tablecaption/tablecaptionui
*/
import { Plugin, icons } from 'ckeditor5/src/core.js';
import { ButtonView } from 'ckeditor5/src/ui.js';
import { getCaptionFromModelSelection } from './utils.js';
/**
  * The table caption UI plugin. It introduces the `'toggleTableCaption'` UI button.
  */
export default class TableCaptionUI extends Plugin {
    /**
     * @inheritDoc
     */
    static get pluginName() {
        return 'TableCaptionUI';
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
    init() {
        const editor = this.editor;
        const editingView = editor.editing.view;
        const t = editor.t;
        editor.ui.componentFactory.add('toggleTableCaption', locale => {
            const command = editor.commands.get('toggleTableCaption');
            const view = new ButtonView(locale);
            view.set({
                icon: icons.caption,
                tooltip: true,
                isToggleable: true
            });
            view.bind('isOn', 'isEnabled').to(command, 'value', 'isEnabled');
            view.bind('label').to(command, 'value', value => value ? t('Toggle caption off') : t('Toggle caption on'));
            this.listenTo(view, 'execute', () => {
                editor.execute('toggleTableCaption', { focusCaptionOnShow: true });
                // Scroll to the selection and highlight the caption if the caption showed up.
                if (command.value) {
                    const modelCaptionElement = getCaptionFromModelSelection(editor.model.document.selection);
                    const figcaptionElement = editor.editing.mapper.toViewElement(modelCaptionElement);
                    if (!figcaptionElement) {
                        return;
                    }
                    editingView.scrollToTheSelection();
                    editingView.change(writer => {
                        writer.addClass('table__caption_highlighted', figcaptionElement);
                    });
                }
                editor.editing.view.focus();
            });
            return view;
        });
    }
}
