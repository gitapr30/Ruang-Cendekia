/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module utils/mix
 */
/**
 * Copies enumerable properties and symbols from the objects given as 2nd+ parameters to the
 * prototype of first object (a constructor).
 *
 * ```
 * class Editor {
 * 	...
 * }
 *
 * const SomeMixin = {
 * 	a() {
 * 		return 'a';
 * 	}
 * };
 *
 * mix( Editor, SomeMixin, ... );
 *
 * new Editor().a(); // -> 'a'
 * ```
 *
 * Note: Properties which already exist in the base class will not be overriden.
 *
 * @deprecated Use mixin pattern, see: https://www.typescriptlang.org/docs/handbook/mixins.html.
 * @param baseClass Class which prototype will be extended.
 * @param mixins Objects from which to get properties.
 */
export default function mix(baseClass: Function, ...mixins: Array<object>): void;
/**
 * Helper type that represents constructor creating given objects. Can be used as a type constraint.
 *
 * ```ts
 * // The function accepts any class constructor.
 * function MyFunction<Ctor extends Constructor>( ctor: Ctor ) {
 * 	// ...
 * }
 *
 * // The function accepts any class constructor of type derived from `MyBase`.
 * function MyFunction<Ctor extends Constructor<MyBase>>( ctor: Ctor ) {
 * 	// ...
 * }
 * ```
 */
export type Constructor<Instance = object> = abstract new (...args: Array<any>) => Instance;
/**
 * Helper type that creates constructor types from a base class and a mixin interface.
 *
 * ```ts
 * interface MyMixinInterface {
 * 	mixinMethod(): void;
 * }
 *
 * function MyMixin<Base extends Constructor>( base: Base ): Mixed<Base, MyMixinInterface> {
 * 	// ...
 * }
 *
 * class BaseClass {
 * 	baseMethod(): void {
 * 		// ...
 * 	}
 * }
 *
 * const MixedClass = MyMixin( BaseClass );
 *
 * // Contains both `mixinMethod()` and `baseMethod()`.
 * const myObject = new MixedClass();
 * myObject.mixinMethod();
 * myObject.baseMethod();
 * ```
 *
 * @typeParam Base A type of constructor of a class to apply mixin to.
 * @typeParam Mixin An interface representing mixin.
 */
export type Mixed<Base extends Constructor, Mixin extends object> = {
    new (...args: ConstructorParameters<Base>): Mixin & InstanceType<Base>;
    prototype: Mixin & InstanceType<Base>;
} & {
    [K in keyof Base]: Base[K];
};
