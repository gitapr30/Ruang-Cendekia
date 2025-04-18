/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

( e => {
const { [ 'cs' ]: { dictionary, getPluralForm } } = {"cs":{"dictionary":{"Find and replace":"Najít a nahradit","Find in text…":"Najít v textu...","Find":"Najít","Previous result":"Předchozí výskyt","Next result":"Další výskyt","Replace":"Nahradit","Replace all":"Nahradit vše","Match case":"Rozlišovat velikost písmen","Whole words only":"Pouze celá slova","Replace with…":"Nahradit čím...","Text to find must not be empty.":"Hledaný text nesmí být prázdný.","Tip: Find some text first in order to replace it.":"Tip: Nejprve najděte nějaký text, abyste jej mohli nahradit.","Advanced options":"Pokročilé možnosti","Find in the document":"Najít v dokumentu"},getPluralForm(n){return (n == 1 ? 0 : (n >= 2 && n <= 4) ? 1 : 2);}}};
e[ 'cs' ] ||= { dictionary: {}, getPluralForm: null };
e[ 'cs' ].dictionary = Object.assign( e[ 'cs' ].dictionary, dictionary );
e[ 'cs' ].getPluralForm = getPluralForm;
} )( window.CKEDITOR_TRANSLATIONS ||= {} );
