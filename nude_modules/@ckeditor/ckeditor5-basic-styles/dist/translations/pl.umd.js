/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

( e => {
const { [ 'pl' ]: { dictionary, getPluralForm } } = {"pl":{"dictionary":{"Bold":"Pogrubienie","Italic":"Kursywa","Underline":"Podkreślenie","Code":"Kod","Strikethrough":"Przekreślenie","Subscript":"Indeks dolny","Superscript":"Indeks górny","Italic text":"Zmienia tekst na kursywę","Move out of an inline code style":"Przenosi zaznaczenie poza styl kodu inline","Bold text":"Pogrubia tekst","Underline text":"Podkreśla tekst","Strikethrough text":"Przekreśla tekst"},getPluralForm(n){return (n == 1 ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2);}}};
e[ 'pl' ] ||= { dictionary: {}, getPluralForm: null };
e[ 'pl' ].dictionary = Object.assign( e[ 'pl' ].dictionary, dictionary );
e[ 'pl' ].getPluralForm = getPluralForm;
} )( window.CKEDITOR_TRANSLATIONS ||= {} );
