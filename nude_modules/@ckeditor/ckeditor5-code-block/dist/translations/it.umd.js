/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

( e => {
const { [ 'it' ]: { dictionary, getPluralForm } } = {"it":{"dictionary":{"Insert code block":"Inserisci blocco di codice","Plain text":"Testo semplice","Leaving %0 code snippet":"Esci da snippet di codice %0","Entering %0 code snippet":"Inserisci snippet di codice %0","Entering code snippet":"Inserisci snippet di codice","Leaving code snippet":"Esci da snippet di codice","Code block":"Blocco di codice"},getPluralForm(n){return (n != 1);}}};
e[ 'it' ] ||= { dictionary: {}, getPluralForm: null };
e[ 'it' ].dictionary = Object.assign( e[ 'it' ].dictionary, dictionary );
e[ 'it' ].getPluralForm = getPluralForm;
} )( window.CKEDITOR_TRANSLATIONS ||= {} );
