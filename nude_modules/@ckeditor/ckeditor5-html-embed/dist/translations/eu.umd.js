/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

( e => {
const { [ 'eu' ]: { dictionary, getPluralForm } } = {"eu":{"dictionary":{"Insert HTML":"","HTML snippet":"","Paste raw HTML here...":"","Edit source":"","Save changes":"","No preview available":"","Empty snippet content":""},getPluralForm(n){return (n != 1);}}};
e[ 'eu' ] ||= { dictionary: {}, getPluralForm: null };
e[ 'eu' ].dictionary = Object.assign( e[ 'eu' ].dictionary, dictionary );
e[ 'eu' ].getPluralForm = getPluralForm;
} )( window.CKEDITOR_TRANSLATIONS ||= {} );
