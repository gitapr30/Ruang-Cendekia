/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module utils
 */
export { default as env } from './env.js';
export { default as diff } from './diff.js';
export { default as fastDiff } from './fastdiff.js';
export { default as diffToChanges } from './difftochanges.js';
export { default as mix } from './mix.js';
export { default as EmitterMixin } from './emittermixin.js';
export { default as EventInfo } from './eventinfo.js';
export { default as ObservableMixin } from './observablemixin.js';
export { default as CKEditorError, logError, logWarning } from './ckeditorerror.js';
export { default as ElementReplacer } from './elementreplacer.js';
export { default as abortableDebounce } from './abortabledebounce.js';
export { default as count } from './count.js';
export { default as compareArrays } from './comparearrays.js';
export { default as createElement } from './dom/createelement.js';
export { default as Config } from './config.js';
export { default as isIterable } from './isiterable.js';
export { default as DomEmitterMixin } from './dom/emittermixin.js';
export { default as findClosestScrollableAncestor } from './dom/findclosestscrollableancestor.js';
export { default as global } from './dom/global.js';
export { default as getAncestors } from './dom/getancestors.js';
export { default as getDataFromElement } from './dom/getdatafromelement.js';
export { default as getBorderWidths } from './dom/getborderwidths.js';
export { default as getRangeFromMouseEvent } from './dom/getrangefrommouseevent.js';
export { default as isText } from './dom/istext.js';
export { default as Rect } from './dom/rect.js';
export { default as ResizeObserver } from './dom/resizeobserver.js';
export { default as setDataInElement } from './dom/setdatainelement.js';
export { default as toUnit } from './dom/tounit.js';
export { default as indexOf } from './dom/indexof.js';
export { default as insertAt } from './dom/insertat.js';
export { default as isComment } from './dom/iscomment.js';
export { default as isNode } from './dom/isnode.js';
export { default as isRange } from './dom/isrange.js';
export { default as isValidAttributeName } from './dom/isvalidattributename.js';
export { default as isVisible } from './dom/isvisible.js';
export { getOptimalPosition } from './dom/position.js';
export { default as remove } from './dom/remove.js';
export * from './dom/scroll.js';
export * from './keyboard.js';
export * from './language.js';
export { default as Locale } from './locale.js';
export { default as Collection } from './collection.js';
export { default as first } from './first.js';
export { default as FocusTracker, isViewWithFocusTracker } from './focustracker.js';
export { default as KeystrokeHandler } from './keystrokehandler.js';
export { default as toArray } from './toarray.js';
export { default as toMap } from './tomap.js';
export { default as priorities } from './priorities.js';
export { default as retry, exponentialDelay } from './retry.js';
export { default as insertToPriorityArray } from './inserttopriorityarray.js';
export { default as spliceArray } from './splicearray.js';
export { default as uid } from './uid.js';
export { default as delay } from './delay.js';
export { default as wait } from './wait.js';
export { default as parseBase64EncodedObject } from './parsebase64encodedobject.js';
export { default as crc32 } from './crc32.js';
export * from './unicode.js';
export { default as version, releaseDate } from './version.js';
