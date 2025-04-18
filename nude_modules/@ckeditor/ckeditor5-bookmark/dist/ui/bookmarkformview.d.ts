/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module bookmark/ui/bookmarkformview
 */
import { ButtonView, LabeledFieldView, View, ViewCollection, type InputTextView } from 'ckeditor5/src/ui.js';
import { FocusTracker, KeystrokeHandler, type Locale } from 'ckeditor5/src/utils.js';
import '@ckeditor/ckeditor5-ui/theme/components/responsive-form/responsiveform.css';
import '../../theme/bookmarkform.css';
/**
 * The bookmark form view controller class.
 *
 * See {@link module:bookmark/ui/bookmarkformview~BookmarkFormView}.
 */
export default class BookmarkFormView extends View {
    /**
     * Tracks information about DOM focus in the form.
     */
    readonly focusTracker: FocusTracker;
    /**
     * An instance of the {@link module:utils/keystrokehandler~KeystrokeHandler}.
     */
    readonly keystrokes: KeystrokeHandler;
    /**
     * The ID input view.
     */
    idInputView: LabeledFieldView<InputTextView>;
    /**
     * The Submit button view.
     */
    buttonView: ButtonView;
    /**
     * A collection of form child views in the form.
     */
    readonly children: ViewCollection;
    /**
     * An array of form validators used by {@link #isValid}.
     */
    private readonly _validators;
    /**
     * A collection of views that can be focused in the form.
     */
    private readonly _focusables;
    /**
     * Helps cycling over {@link #_focusables} in the form.
     */
    private readonly _focusCycler;
    /**
     * Creates an instance of the {@link module:bookmark/ui/bookmarkformview~BookmarkFormView} class.
     *
     * Also see {@link #render}.
     *
     * @param locale The localization services instance.
     * @param validators  Form validators used by {@link #isValid}.
     */
    constructor(locale: Locale, validators: Array<BookmarkFormValidatorCallback>);
    /**
     * @inheritDoc
     */
    render(): void;
    /**
     * @inheritDoc
     */
    destroy(): void;
    /**
     * Focuses the fist {@link #_focusables} in the form.
     */
    focus(): void;
    /**
     * Validates the form and returns `false` when some fields are invalid.
     */
    isValid(): boolean;
    /**
     * Cleans up the supplementary error and information text of the {@link #idInputView}
     * bringing them back to the state when the form has been displayed for the first time.
     *
     * See {@link #isValid}.
     */
    resetFormStatus(): void;
    /**
     * Creates header and form view.
     */
    private _createViewChildren;
    /**
     * Creates form content view with input and button.
     */
    private _createFormContentView;
    /**
     * Creates a labeled input view.
     *
     * @returns Labeled field view instance.
     */
    private _createIdInput;
    /**
     * Creates a button view.
     *
     * @param label The button label.
     * @param className The additional button CSS class name.
     * @returns The button view instance.
     */
    private _createButton;
    /**
     * The native DOM `value` of the {@link #idInputView} element.
     *
     * **Note**: Do not confuse it with the {@link module:ui/inputtext/inputtextview~InputTextView#value}
     * which works one way only and may not represent the actual state of the component in the DOM.
     */
    get id(): string | null;
}
/**
 * Callback used by {@link ~BookmarkFormView} to check if passed form value is valid.
 *
 * If `undefined` is returned, it is assumed that the form value is correct and there is no error.
 * If string is returned, it is assumed that the form value is incorrect and the returned string is displayed in the error label
 */
export type BookmarkFormValidatorCallback = (form: BookmarkFormView) => string | undefined;
