/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
import { BalloonPanelView } from 'ckeditor5/src/ui.js';
/**
 * A helper utility that positions the
 * {@link module:ui/panel/balloon/contextualballoon~ContextualBalloon contextual balloon} instance
 * with respect to the image in the editor content, if one is selected.
 *
 * @param editor The editor instance.
 */
export function repositionContextualBalloon(editor) {
    const balloon = editor.plugins.get('ContextualBalloon');
    const imageUtils = editor.plugins.get('ImageUtils');
    if (imageUtils.getClosestSelectedImageWidget(editor.editing.view.document.selection)) {
        const position = getBalloonPositionData(editor);
        balloon.updatePosition(position);
    }
}
/**
 * Returns the positioning options that control the geometry of the
 * {@link module:ui/panel/balloon/contextualballoon~ContextualBalloon contextual balloon} with respect
 * to the selected element in the editor content.
 *
 * @param editor The editor instance.
 */
export function getBalloonPositionData(editor) {
    const editingView = editor.editing.view;
    const defaultPositions = BalloonPanelView.defaultPositions;
    const imageUtils = editor.plugins.get('ImageUtils');
    return {
        target: editingView.domConverter.mapViewToDom(imageUtils.getClosestSelectedImageWidget(editingView.document.selection)),
        positions: [
            defaultPositions.northArrowSouth,
            defaultPositions.northArrowSouthWest,
            defaultPositions.northArrowSouthEast,
            defaultPositions.southArrowNorth,
            defaultPositions.southArrowNorthWest,
            defaultPositions.southArrowNorthEast,
            defaultPositions.viewportStickyNorth
        ]
    };
}
