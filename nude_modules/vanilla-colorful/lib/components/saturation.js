import { Slider } from './slider.js';
import { hsvaToHslString } from '../utils/convert.js';
import { clamp, round } from '../utils/math.js';
export class Saturation extends Slider {
    constructor(root) {
        super(root, 'saturation', 'aria-label="Color"', true);
    }
    update(hsva) {
        this.hsva = hsva;
        this.style([
            {
                top: `${100 - hsva.v}%`,
                left: `${hsva.s}%`,
                color: hsvaToHslString(hsva)
            },
            {
                'background-color': hsvaToHslString({ h: hsva.h, s: 100, v: 100, a: 1 })
            }
        ]);
        this.el.setAttribute('aria-valuetext', `Saturation ${round(hsva.s)}%, Brightness ${round(hsva.v)}%`);
    }
    getMove(offset, key) {
        // Saturation and brightness always fit into [0, 100] range
        return {
            s: key ? clamp(this.hsva.s + offset.x * 100, 0, 100) : offset.x * 100,
            v: key ? clamp(this.hsva.v - offset.y * 100, 0, 100) : Math.round(100 - offset.y * 100)
        };
    }
}
//# sourceMappingURL=saturation.js.map