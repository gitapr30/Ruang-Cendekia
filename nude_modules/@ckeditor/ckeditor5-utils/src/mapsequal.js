/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module utils/mapsequal
 */
/**
 * Checks whether given `Map`s are equal, that is has same size and same key-value pairs.
 *
 * @param mapA The first map to compare.
 * @param mapB The second map to compare.
 * @returns `true` if given maps are equal, `false` otherwise.
 */
export default function mapsEqual(mapA, mapB) {
    if (mapA.size != mapB.size) {
        return false;
    }
    for (const attr of mapA.entries()) {
        const valA = JSON.stringify(attr[1]);
        const valB = JSON.stringify(mapB.get(attr[0]));
        if (valA !== valB) {
            return false;
        }
    }
    return true;
}
