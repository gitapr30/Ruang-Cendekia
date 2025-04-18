/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module bookmark/ui/bookmarkformview
 */
import { ButtonView, FocusCycler, LabeledFieldView, View, ViewCollection, createLabeledInputText, submitHandler, FormHeaderView } from 'ckeditor5/src/ui.js';
import { FocusTracker, KeystrokeHandler } from 'ckeditor5/src/utils.js';
// See: #8833.
// eslint-disable-next-line ckeditor5-rules/ckeditor-imports
import '@ckeditor/ckeditor5-ui/theme/components/responsive-form/responsiveform.css';
import '../../theme/bookmarkform.css';
/**
 * The bookmark form view controller class.
 *
 * See {@link module:bookmark/ui/bookmarkformview~BookmarkFormView}.
 */
export default class BookmarkFormView extends View {
    /**
     * Creates an instance of the {@link module:bookmark/ui/bookmarkformview~BookmarkFormView} class.
     *
     * Also see {@link #render}.
     *
     * @param locale The localization services instance.
     * @param validators  Form validators used by {@link #isValid}.
     */
    constructor(locale, validators) {
        super(locale);
        /**
         * Tracks information about DOM focus in the form.
         */
        this.focusTracker = new FocusTracker();
        /**
         * An instance of the {@link module:utils/keystrokehandler~KeystrokeHandler}.
         */
        this.keystrokes = new KeystrokeHandler();
        /**
         * A collection of views that can be focused in the form.
         */
        this._focusables = new ViewCollection();
        const t = locale.t;
        this._validators = validators;
        this.idInputView = this._createIdInput();
        this.buttonView = this._createButton(t('Insert'), 'ck-button-action ck-button-bold');
        this.buttonView.type = 'submit';
        this.children = this._createViewChildren();
        this._focusCycler = new FocusCycler({
            focusables: this._focusables,
            focusTracker: this.focusTracker,
            keystrokeHandler: this.keystrokes,
            actions: {
                // Navigate form fields backwards using the Shift + Tab keystroke.
                focusPrevious: 'shift + tab',
                // Navigate form fields forwards using the Tab key.
                focusNext: 'tab'
            }
        });
        const classList = ['ck', 'ck-bookmark-view'];
        this.setTemplate({
            tag: 'form',
            attributes: {
                class: classList,
                // https://github.com/ckeditor/ckeditor5-link/issues/90
                tabindex: '-1'
            },
            children: this.children
        });
    }
    /**
     * @inheritDoc
     */
    render() {
        super.render();
        submitHandler({
            view: this
        });
        const childViews = [
            this.idInputView,
            this.buttonView
        ];
        childViews.forEach(v => {
            // Register the view as focusable.
            this._focusables.add(v);
            // Register the view in the focus tracker.
            this.focusTracker.add(v.element);
        });
        // Start listening for the keystrokes coming from #element.
        this.keystrokes.listenTo(this.element);
    }
    /**
     * @inheritDoc
     */
    destroy() {
        super.destroy();
        this.focusTracker.destroy();
        this.keystrokes.destroy();
    }
    /**
     * Focuses the fist {@link #_focusables} in the form.
     */
    focus() {
        this._focusCycler.focusFirst();
    }
    /**
     * Validates the form and returns `false` when some fields are invalid.
     */
    isValid() {
        this.resetFormStatus();
        for (const validator of this._validators) {
            const errorText = validator(this);
            // One error per field is enough.
            if (errorText) {
                // Apply updated error.
                this.idInputView.errorText = errorText;
                return false;
            }
        }
        return true;
    }
    /**
     * Cleans up the supplementary error and information text of the {@link #idInputView}
     * bringing them back to the state when the form has been displayed for the first time.
     *
     * See {@link #isValid}.
     */
    resetFormStatus() {
        this.idInputView.errorText = null;
    }
    /**
     * Creates header and form view.
     */
    _createViewChildren() {
        const children = this.createCollection();
        const t = this.t;
        children.add(new FormHeaderView(this.locale, { label: t('Bookmark') }));
        children.add(this._createFormContentView());
        return children;
    }
    /**
     * Creates form content view with input and button.
     */
    _createFormContentView() {
        const view = new View(this.locale);
        const children = this.createCollection();
        const classList = ['ck', 'ck-bookmark-form', 'ck-responsive-form'];
        children.add(this.idInputView);
        children.add(this.buttonView);
        view.setTemplate({
            tag: 'div',
            attributes: {
                class: classList
            },
            children
        });
        return view;
    }
    /**
     * Creates a labeled input view.
     *
     * @returns Labeled field view instance.
     */
    _createIdInput() {
        const t = this.locale.t;
        const labeledInput = new LabeledFieldView(this.locale, createLabeledInputText);
        labeledInput.label = t('Bookmark name');
        labeledInput.infoText = t('Enter the bookmark name without spaces.');
        return labeledInput;
    }
    /**
     * Creates a button view.
     *
     * @param label The button label.
     * @param className The additional button CSS class name.
     * @returns The button view instance.
     */
    _createButton(label, className) {
        const button = new ButtonView(this.locale);
        button.set({
            label,
            withText: true
        });
        button.extendTemplate({
            attributes: {
                class: className
            }
        });
        return button;
    }
    /**
     * The native DOM `value` of the {@link #idInputView} element.
     *
     * **Note**: Do not confuse it with the {@link module:ui/inputtext/inputtextview~InputTextView#value}
     * which works one way only and may not represent the actual state of the component in the DOM.
     */
    get id() {
        const { element } = this.idInputView.fieldView;
        if (!element) {
            return null;
        }
        return element.value.trim();
    }
}
