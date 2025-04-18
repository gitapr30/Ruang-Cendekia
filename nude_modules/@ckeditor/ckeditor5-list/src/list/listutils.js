/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
import { Plugin } from 'ckeditor5/src/core.js';
import { expandListBlocksToCompleteItems, expandListBlocksToCompleteList, isFirstBlockOfListItem, isListItemBlock, isNumberedListType } from './utils/model.js';
/**
 * A set of helpers related to document lists.
 */
export default class ListUtils extends Plugin {
    /**
     * @inheritDoc
     */
    static get pluginName() {
        return 'ListUtils';
    }
    /**
     * @inheritDoc
     */
    static get isOfficialPlugin() {
        return true;
    }
    /**
     * Expands the given list of selected blocks to include all the items of the lists they're in.
     *
     * @param blocks The list of selected blocks.
     */
    expandListBlocksToCompleteList(blocks) {
        return expandListBlocksToCompleteList(blocks);
    }
    /**
     * Check if the given block is the first in the list item.
     *
     * @param listBlock The list block element.
     */
    isFirstBlockOfListItem(listBlock) {
        return isFirstBlockOfListItem(listBlock);
    }
    /**
     * Returns true if the given model node is a list item block.
     *
     * @param node A model node.
     */
    isListItemBlock(node) {
        return isListItemBlock(node);
    }
    /**
     * Expands the given list of selected blocks to include the leading and tailing blocks of partially selected list items.
     *
     * @param blocks The list of selected blocks.
     * @param options.withNested Whether should include nested list items.
     */
    expandListBlocksToCompleteItems(blocks, options = {}) {
        return expandListBlocksToCompleteItems(blocks, options);
    }
    /**
     * Returns true if listType is of type `numbered` or `customNumbered`.
     */
    isNumberedListType(listType) {
        return isNumberedListType(listType);
    }
}
