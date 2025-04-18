export interface RgbColor {
    r: number;
    g: number;
    b: number;
}
export interface RgbaColor extends RgbColor {
    a: number;
}
export interface HslColor {
    h: number;
    s: number;
    l: number;
}
export interface HslaColor extends HslColor {
    a: number;
}
export interface HsvColor {
    h: number;
    s: number;
    v: number;
}
export interface HsvaColor extends HsvColor {
    a: number;
}
export declare type ObjectColor = RgbColor | HslColor | HsvColor | RgbaColor | HslaColor | HsvaColor;
export declare type AnyColor = string | ObjectColor;
export interface ColorModel<T extends AnyColor> {
    defaultColor: T;
    toHsva: (color: T) => HsvaColor;
    fromHsva: (hsva: HsvaColor) => T;
    equal: (first: T, second: T) => boolean;
    fromAttr: (attr: string) => T;
}
export interface ColorChangedEventListener<T> {
    (evt: T): void;
}
export interface ColorChangedEventListenerObject<T> {
    handleEvent(evt: T): void;
}
export interface ColorPickerEventMap<T> extends HTMLElementEventMap {
    'color-changed': CustomEvent<{
        value: T;
    }>;
}
export declare type ColorPickerEventListener<T> = ColorChangedEventListener<T> | ColorChangedEventListenerObject<T>;
//# sourceMappingURL=types.d.ts.map