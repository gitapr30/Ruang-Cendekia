/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module engine/model/markercollection
 */
import TypeCheckable from './typecheckable.js';
import LiveRange from './liverange.js';
import { CKEditorError, EmitterMixin } from '@ckeditor/ckeditor5-utils';
/**
 * The collection of all {@link module:engine/model/markercollection~Marker markers} attached to the document.
 * It lets you {@link module:engine/model/markercollection~MarkerCollection#get get} markers or track them using
 * {@link module:engine/model/markercollection~MarkerCollection#event:update} event.
 *
 * To create, change or remove makers use {@link module:engine/model/writer~Writer model writers'} methods:
 * {@link module:engine/model/writer~Writer#addMarker} or {@link module:engine/model/writer~Writer#removeMarker}. Since
 * the writer is the only proper way to change the data model it is not possible to change markers directly using this
 * collection. All markers created by the writer will be automatically added to this collection.
 *
 * By default there is one marker collection available as {@link module:engine/model/model~Model#markers model property}.
 *
 * @see module:engine/model/markercollection~Marker
 */
export default class MarkerCollection extends /* #__PURE__ */ EmitterMixin() {
    constructor() {
        super(...arguments);
        /**
         * Stores {@link ~Marker markers} added to the collection.
         */
        this._markers = new Map();
    }
    /**
     * Iterable interface.
     *
     * Iterates over all {@link ~Marker markers} added to the collection.
     */
    [Symbol.iterator]() {
        return this._markers.values();
    }
    /**
     * Checks if given {@link ~Marker marker} or marker name is in the collection.
     *
     * @param markerOrName Name of marker or marker instance to check.
     * @returns `true` if marker is in the collection, `false` otherwise.
     */
    has(markerOrName) {
        const markerName = markerOrName instanceof Marker ? markerOrName.name : markerOrName;
        return this._markers.has(markerName);
    }
    /**
     * Returns {@link ~Marker marker} with given `markerName`.
     *
     * @param markerName Name of marker to get.
     * @returns Marker with given name or `null` if such marker was
     * not added to the collection.
     */
    get(markerName) {
        return this._markers.get(markerName) || null;
    }
    /**
     * Creates and adds a {@link ~Marker marker} to the `MarkerCollection` with given name on given
     * {@link module:engine/model/range~Range range}.
     *
     * If `MarkerCollection` already had a marker with given name (or {@link ~Marker marker} was passed), the marker in
     * collection is updated and {@link module:engine/model/markercollection~MarkerCollection#event:update} event is fired
     * but only if there was a change (marker range or {@link module:engine/model/markercollection~Marker#managedUsingOperations}
     * flag has changed.
     *
     * @internal
     * @fires update
     * @param markerOrName Name of marker to set or marker instance to update.
     * @param range Marker range.
     * @param managedUsingOperations Specifies whether the marker is managed using operations.
     * @param affectsData Specifies whether the marker affects the data produced by the data pipeline
     * (is persisted in the editor's data).
     * @returns `Marker` instance which was added or updated.
     */
    _set(markerOrName, range, managedUsingOperations = false, affectsData = false) {
        const markerName = markerOrName instanceof Marker ? markerOrName.name : markerOrName;
        if (markerName.includes(',')) {
            /**
             * Marker name cannot contain the "," character.
             *
             * @error markercollection-incorrect-marker-name
             */
            throw new CKEditorError('markercollection-incorrect-marker-name', this);
        }
        const oldMarker = this._markers.get(markerName);
        if (oldMarker) {
            const oldMarkerData = oldMarker.getData();
            const oldRange = oldMarker.getRange();
            let hasChanged = false;
            if (!oldRange.isEqual(range)) {
                oldMarker._attachLiveRange(LiveRange.fromRange(range));
                hasChanged = true;
            }
            if (managedUsingOperations != oldMarker.managedUsingOperations) {
                oldMarker._managedUsingOperations = managedUsingOperations;
                hasChanged = true;
            }
            if (typeof affectsData === 'boolean' && affectsData != oldMarker.affectsData) {
                oldMarker._affectsData = affectsData;
                hasChanged = true;
            }
            if (hasChanged) {
                this.fire(`update:${markerName}`, oldMarker, oldRange, range, oldMarkerData);
            }
            return oldMarker;
        }
        const liveRange = LiveRange.fromRange(range);
        const marker = new Marker(markerName, liveRange, managedUsingOperations, affectsData);
        this._markers.set(markerName, marker);
        this.fire(`update:${markerName}`, marker, null, range, { ...marker.getData(), range: null });
        return marker;
    }
    /**
     * Removes given {@link ~Marker marker} or a marker with given name from the `MarkerCollection`.
     *
     * @internal
     * @fires update
     * @param markerOrName Marker or name of a marker to remove.
     * @returns `true` if marker was found and removed, `false` otherwise.
     */
    _remove(markerOrName) {
        const markerName = markerOrName instanceof Marker ? markerOrName.name : markerOrName;
        const oldMarker = this._markers.get(markerName);
        if (oldMarker) {
            this._markers.delete(markerName);
            this.fire(`update:${markerName}`, oldMarker, oldMarker.getRange(), null, oldMarker.getData());
            this._destroyMarker(oldMarker);
            return true;
        }
        return false;
    }
    /**
     * Fires an {@link module:engine/model/markercollection~MarkerCollection#event:update} event for the given {@link ~Marker marker}
     * but does not change the marker. Useful to force {@link module:engine/conversion/downcastdispatcher~DowncastDispatcher downcast
     * conversion} for the marker.
     *
     * @internal
     * @fires update
     * @param markerOrName Marker or name of a marker to refresh.
     */
    _refresh(markerOrName) {
        const markerName = markerOrName instanceof Marker ? markerOrName.name : markerOrName;
        const marker = this._markers.get(markerName);
        if (!marker) {
            /**
             * Marker with provided name does not exists.
             *
             * @error markercollection-refresh-marker-not-exists
             */
            throw new CKEditorError('markercollection-refresh-marker-not-exists', this);
        }
        const range = marker.getRange();
        this.fire(`update:${markerName}`, marker, range, range, marker.getData());
    }
    /**
     * Returns iterator that iterates over all markers, which ranges contain given {@link module:engine/model/position~Position position}.
     */
    *getMarkersAtPosition(position) {
        for (const marker of this) {
            if (marker.getRange().containsPosition(position)) {
                yield marker;
            }
        }
    }
    /**
     * Returns iterator that iterates over all markers, which intersects with given {@link module:engine/model/range~Range range}.
     */
    *getMarkersIntersectingRange(range) {
        for (const marker of this) {
            if (marker.getRange().getIntersection(range) !== null) {
                yield marker;
            }
        }
    }
    /**
     * Destroys marker collection and all markers inside it.
     */
    destroy() {
        for (const marker of this._markers.values()) {
            this._destroyMarker(marker);
        }
        this._markers = null;
        this.stopListening();
    }
    /**
     * Iterates over all markers that starts with given `prefix`.
     *
     * ```ts
     * const markerFooA = markersCollection._set( 'foo:a', rangeFooA );
     * const markerFooB = markersCollection._set( 'foo:b', rangeFooB );
     * const markerBarA = markersCollection._set( 'bar:a', rangeBarA );
     * const markerFooBarA = markersCollection._set( 'foobar:a', rangeFooBarA );
     * Array.from( markersCollection.getMarkersGroup( 'foo' ) ); // [ markerFooA, markerFooB ]
     * Array.from( markersCollection.getMarkersGroup( 'a' ) ); // []
     * ```
     */
    *getMarkersGroup(prefix) {
        for (const marker of this._markers.values()) {
            if (marker.name.startsWith(prefix + ':')) {
                yield marker;
            }
        }
    }
    /**
     * Destroys the marker.
     */
    _destroyMarker(marker) {
        marker.stopListening();
        marker._detachLiveRange();
    }
}
/**
 * `Marker` is a continuous part of the model (like a range), is named and represents some kind of information about the
 * marked part of the model document. In contrary to {@link module:engine/model/node~Node nodes}, which are building blocks of
 * the model document tree, markers are not stored directly in the document tree but in the
 * {@link module:engine/model/model~Model#markers model markers' collection}. Still, they are document data, by giving
 * additional meaning to the part of a model document between marker start and marker end.
 *
 * In this sense, markers are similar to adding and converting attributes on nodes. The difference is that attribute is
 * connected with a given node (e.g. a character is bold no matter if it gets moved or content around it changes).
 * Markers on the other hand are continuous ranges and are characterized by their start and end position. This means that
 * any character in the marker is marked by the marker. For example, if a character is moved outside of marker it stops being
 * "special" and the marker is shrunk. Similarly, when a character is moved into the marker from other place in document
 * model, it starts being "special" and the marker is enlarged.
 *
 * Another upside of markers is that finding marked part of document is fast and easy. Using attributes to mark some nodes
 * and then trying to find that part of document would require traversing whole document tree. Marker gives instant access
 * to the range which it is marking at the moment.
 *
 * Markers are built from a name and a range.
 *
 * Range of the marker is updated automatically when document changes, using
 * {@link module:engine/model/liverange~LiveRange live range} mechanism.
 *
 * Name is used to group and identify markers. Names have to be unique, but markers can be grouped by
 * using common prefixes, separated with `:`, for example: `user:john` or `search:3`. That's useful in term of creating
 * namespaces for custom elements (e.g. comments, highlights). You can use this prefixes in
 * {@link module:engine/model/markercollection~MarkerCollection#event:update} listeners to listen on changes in a group of markers.
 * For instance: `model.markers.on( 'update:user', callback );` will be called whenever any `user:*` markers changes.
 *
 * There are two types of markers.
 *
 * 1. Markers managed directly, without using operations. They are added directly by {@link module:engine/model/writer~Writer}
 * to the {@link module:engine/model/markercollection~MarkerCollection} without any additional mechanism. They can be used
 * as bookmarks or visual markers. They are great for showing results of the find, or select link when the focus is in the input.
 *
 * 1. Markers managed using operations. These markers are also stored in {@link module:engine/model/markercollection~MarkerCollection}
 * but changes in these markers is managed the same way all other changes in the model structure - using operations.
 * Therefore, they are handled in the undo stack and synchronized between clients if the collaboration plugin is enabled.
 * This type of markers is useful for solutions like spell checking or comments.
 *
 * Both type of them should be added / updated by {@link module:engine/model/writer~Writer#addMarker}
 * and removed by {@link module:engine/model/writer~Writer#removeMarker} methods.
 *
 * ```ts
 * model.change( ( writer ) => {
 * 	const marker = writer.addMarker( name, { range, usingOperation: true } );
 *
 * 	// ...
 *
 * 	writer.removeMarker( marker );
 * } );
 * ```
 *
 * See {@link module:engine/model/writer~Writer} to find more examples.
 *
 * Since markers need to track change in the document, for efficiency reasons, it is best to create and keep as little
 * markers as possible and remove them as soon as they are not needed anymore.
 *
 * Markers can be downcasted and upcasted.
 *
 * Markers downcast happens on {@link module:engine/conversion/downcastdispatcher~DowncastDispatcher#event:addMarker} and
 * {@link module:engine/conversion/downcastdispatcher~DowncastDispatcher#event:removeMarker} events.
 * Use {@link module:engine/conversion/downcasthelpers downcast converters} or attach a custom converter to mentioned events.
 * For {@link module:engine/controller/datacontroller~DataController data pipeline}, marker should be downcasted to an element.
 * Then, it can be upcasted back to a marker. Again, use {@link module:engine/conversion/upcasthelpers upcast converters} or
 * attach a custom converter to {@link module:engine/conversion/upcastdispatcher~UpcastDispatcher#event:element}.
 *
 * `Marker` instances are created and destroyed only by {@link ~MarkerCollection MarkerCollection}.
 */
class Marker extends /* #__PURE__ */ EmitterMixin(TypeCheckable) {
    /**
     * Creates a marker instance.
     *
     * @param name Marker name.
     * @param liveRange Range marked by the marker.
     * @param managedUsingOperations Specifies whether the marker is managed using operations.
     * @param affectsData Specifies whether the marker affects the data produced by the data pipeline (is persisted in the editor's data).
     */
    constructor(name, liveRange, managedUsingOperations, affectsData) {
        super();
        this.name = name;
        this._liveRange = this._attachLiveRange(liveRange);
        this._managedUsingOperations = managedUsingOperations;
        this._affectsData = affectsData;
    }
    /**
     * A value indicating if the marker is managed using operations.
     * See {@link ~Marker marker class description} to learn more about marker types.
     * See {@link module:engine/model/writer~Writer#addMarker}.
     */
    get managedUsingOperations() {
        if (!this._liveRange) {
            throw new CKEditorError('marker-destroyed', this);
        }
        return this._managedUsingOperations;
    }
    /**
     * A value indicating if the marker changes the data.
     */
    get affectsData() {
        if (!this._liveRange) {
            throw new CKEditorError('marker-destroyed', this);
        }
        return this._affectsData;
    }
    /**
     * Returns the marker data (properties defining the marker).
     */
    getData() {
        return {
            range: this.getRange(),
            affectsData: this.affectsData,
            managedUsingOperations: this.managedUsingOperations
        };
    }
    /**
     * Returns current marker start position.
     */
    getStart() {
        if (!this._liveRange) {
            throw new CKEditorError('marker-destroyed', this);
        }
        return this._liveRange.start.clone();
    }
    /**
     * Returns current marker end position.
     */
    getEnd() {
        if (!this._liveRange) {
            throw new CKEditorError('marker-destroyed', this);
        }
        return this._liveRange.end.clone();
    }
    /**
     * Returns a range that represents the current state of the marker.
     *
     * Keep in mind that returned value is a {@link module:engine/model/range~Range Range}, not a
     * {@link module:engine/model/liverange~LiveRange LiveRange}. This means that it is up-to-date and relevant only
     * until next model document change. Do not store values returned by this method. Instead, store {@link ~Marker#name}
     * and get `Marker` instance from {@link module:engine/model/markercollection~MarkerCollection MarkerCollection} every
     * time there is a need to read marker properties. This will guarantee that the marker has not been removed and
     * that it's data is up-to-date.
     */
    getRange() {
        if (!this._liveRange) {
            throw new CKEditorError('marker-destroyed', this);
        }
        return this._liveRange.toRange();
    }
    /**
     * Binds new live range to the marker and detach the old one if is attached.
     *
     * @internal
     * @param liveRange Live range to attach
     * @returns Attached live range.
     */
    _attachLiveRange(liveRange) {
        if (this._liveRange) {
            this._detachLiveRange();
        }
        // Delegating does not work with namespaces. Alternatively, we could delegate all events (using `*`).
        liveRange.delegate('change:range').to(this);
        liveRange.delegate('change:content').to(this);
        this._liveRange = liveRange;
        return liveRange;
    }
    /**
     * Unbinds and destroys currently attached live range.
     *
     * @internal
     */
    _detachLiveRange() {
        this._liveRange.stopDelegating('change:range', this);
        this._liveRange.stopDelegating('change:content', this);
        this._liveRange.detach();
        this._liveRange = null;
    }
}
// The magic of type inference using `is` method is centralized in `TypeCheckable` class.
// Proper overload would interfere with that.
Marker.prototype.is = function (type) {
    return type === 'marker' || type === 'model:marker';
};
/**
 * Cannot use a {@link module:engine/model/markercollection~MarkerCollection#destroy destroyed marker} instance.
 *
 * @error marker-destroyed
 */
