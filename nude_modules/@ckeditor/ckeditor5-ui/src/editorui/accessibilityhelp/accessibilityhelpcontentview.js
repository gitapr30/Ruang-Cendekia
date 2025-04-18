/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module ui/editorui/accessibilityhelp/accessibilityhelpcontentview
 */
import { createElement, env, getEnvKeystrokeText } from '@ckeditor/ckeditor5-utils';
import View from '../../view.js';
import LabelView from '../../label/labelview.js';
/**
 * The view displaying keystrokes in the Accessibility help dialog.
 */
export default class AccessibilityHelpContentView extends View {
    /**
     * @inheritDoc
     */
    constructor(locale, keystrokes) {
        super(locale);
        const t = locale.t;
        const helpLabel = new LabelView();
        helpLabel.text = t('Help Contents. To close this dialog press ESC.');
        this.setTemplate({
            tag: 'div',
            attributes: {
                class: ['ck', 'ck-accessibility-help-dialog__content'],
                'aria-labelledby': helpLabel.id,
                role: 'document',
                tabindex: -1
            },
            children: [
                createElement(document, 'p', {}, t('Below, you can find a list of keyboard shortcuts that can be used in the editor.')),
                ...this._createCategories(Array.from(keystrokes.values())),
                helpLabel
            ]
        });
    }
    /**
     * @inheritDoc
     */
    focus() {
        this.element.focus();
    }
    /**
     * Creates `<section><h3>Category label</h3>...</section>` elements for each category of keystrokes.
     */
    _createCategories(categories) {
        return categories.map(categoryDefinition => {
            const elements = [
                // Category header.
                createElement(document, 'h3', {}, categoryDefinition.label),
                // Category definitions (<dl>) and their optional headers (<h4>).
                ...Array.from(categoryDefinition.groups.values())
                    .map(groupDefinition => this._createGroup(groupDefinition))
                    .flat()
            ];
            // Category description (<p>).
            if (categoryDefinition.description) {
                elements.splice(1, 0, createElement(document, 'p', {}, categoryDefinition.description));
            }
            return createElement(document, 'section', {}, elements);
        });
    }
    /**
     * Creates `[<h4>Optional label</h4>]<dl>...</dl>` elements for each group of keystrokes in a category.
     */
    _createGroup(groupDefinition) {
        const definitionAndDescriptionElements = groupDefinition.keystrokes
            .sort((a, b) => a.label.localeCompare(b.label))
            .map(keystrokeDefinition => this._createGroupRow(keystrokeDefinition))
            .flat();
        const elements = [
            createElement(document, 'dl', {}, definitionAndDescriptionElements)
        ];
        if (groupDefinition.label) {
            elements.unshift(createElement(document, 'h4', {}, groupDefinition.label));
        }
        return elements;
    }
    /**
     * Creates `<dt>Keystroke label</dt><dd>Keystroke definition</dd>` elements for each keystroke in a group.
     */
    _createGroupRow(keystrokeDefinition) {
        const t = this.locale.t;
        const dt = createElement(document, 'dt');
        const dd = createElement(document, 'dd');
        const normalizedKeystrokeDefinition = normalizeKeystrokeDefinition(keystrokeDefinition.keystroke);
        const keystrokeAlternativeHTMLs = [];
        for (const keystrokeAlternative of normalizedKeystrokeDefinition) {
            keystrokeAlternativeHTMLs.push(keystrokeAlternative.map(keystrokeToEnvKbd).join(''));
        }
        dt.innerHTML = keystrokeDefinition.label;
        dd.innerHTML = keystrokeAlternativeHTMLs.join(', ') +
            (keystrokeDefinition.mayRequireFn && env.isMac ? ` ${t('(may require <kbd>Fn</kbd>)')}` : '');
        return [dt, dd];
    }
}
function keystrokeToEnvKbd(keystroke) {
    return getEnvKeystrokeText(keystroke)
        .split('+')
        .map(part => `<kbd>${part}</kbd>`)
        .join('+');
}
function normalizeKeystrokeDefinition(definition) {
    if (typeof definition === 'string') {
        return [[definition]];
    }
    if (typeof definition[0] === 'string') {
        return [definition];
    }
    return definition;
}
