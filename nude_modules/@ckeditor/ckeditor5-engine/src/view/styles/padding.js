/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
import { getPositionShorthandNormalizer, getBoxSidesValueReducer } from './utils.js';
/**
 * Adds a padding CSS styles processing rules.
 *
 * ```ts
 * editor.data.addStyleProcessorRules( addPaddingRules );
 * ```
 *
 * The normalized value is stored as:
 *
 * ```ts
 * const styles = {
 * 	padding: {
 * 		top,
 * 		right,
 * 		bottom,
 * 		left
 * 	}
 * };
 * ```
 */
export function addPaddingRules(stylesProcessor) {
    stylesProcessor.setNormalizer('padding', getPositionShorthandNormalizer('padding'));
    stylesProcessor.setNormalizer('padding-top', value => ({ path: 'padding.top', value }));
    stylesProcessor.setNormalizer('padding-right', value => ({ path: 'padding.right', value }));
    stylesProcessor.setNormalizer('padding-bottom', value => ({ path: 'padding.bottom', value }));
    stylesProcessor.setNormalizer('padding-left', value => ({ path: 'padding.left', value }));
    stylesProcessor.setReducer('padding', getBoxSidesValueReducer('padding'));
    stylesProcessor.setStyleRelation('padding', ['padding-top', 'padding-right', 'padding-bottom', 'padding-left']);
}
