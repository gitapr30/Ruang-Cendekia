/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module minimap/minimapconfig
 */
/**
 * The configuration of the {@link module:minimap/minimap~Minimap} feature.
 *
 * ```ts
 * ClassicEditor
 * 	.create( {
 * 		minimap: ... // Minimap feature config.
 * 	} )
 * 	.then( ... )
 * 	.catch( ... );
 * ```
 *
 * See {@link module:core/editor/editorconfig~EditorConfig all editor options}.
 */
export interface MinimapConfig {
    /**
     * The DOM element container for the minimap.
     *
     * **Note**: The container must have a fixed `width` and `overflow: hidden` for the minimap to work correctly.
     */
    container: HTMLElement;
    /**
     * When set to `true`, the minimap will render content as simple boxes instead of replicating the look of the content (default).
     */
    useSimplePreview?: boolean;
    /**
     * Extra CSS class (or classes) that will be set internally on the `<body>` element of the `<iframe>` enclosing the minimap.
     *
     * By default, the minimap feature will attempt to clone all website styles and re-apply them in the `<iframe>` for the best accuracy.
     * However, this may not work if the content of your editor inherits the styles from parent containers, resulting in inconsistent
     * look and imprecise scrolling of the minimap.
     *
     * This optional configuration can address these issues by ensuring the same CSS rules apply to the content of the minimap
     * and the original content of the editor.
     *
     * For instance, consider the following DOM structure:
     *
     * ```html
     * <div class="website">
     * 	<!-- ... -->
     * 	<div class="styled-container">
     * 		 <!-- ... -->
     * 		<div id="editor">
     * 			<!-- content of the editor -->
     * 		</div>
     * 	</div>
     * 	<!-- ... -->
     * </div>
     * ```
     *
     * and the following CSS styles:
     *
     * ```css
     * .website p {
     * 	font-size: 13px;
     * }
     *
     * .styled-container p {
     * 	color: #ccc;
     * }
     * ```
     *
     * To maintain the consistency of styling (`font-size` and `color` of paragraphs), you will need to pass the CSS class names
     * of these containers:
     *
     * ```ts
     * ClassicEditor
     * 	.create( document.getElementById( 'editor' ), {
     * 		minimap: {
     * 			extraClasses: 'website styled-container'
     * 		}
     * 	} )
     * 	.then( ... )
     * 	.catch( ... );
     * ```
     */
    extraClasses?: string;
}
