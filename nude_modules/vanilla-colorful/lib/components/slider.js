import { fire, tpl } from '../utils/dom.js';
import { clamp } from '../utils/math.js';
let hasTouched = false;
// Check if an event was triggered by touch
const isTouch = (e) => 'touches' in e;
// Prevent mobile browsers from handling mouse events (conflicting with touch ones).
// If we detected a touch interaction before, we prefer reacting to touch events only.
const isValid = (event) => {
    if (hasTouched && !isTouch(event))
        return false;
    if (!hasTouched)
        hasTouched = isTouch(event);
    return true;
};
const pointerMove = (target, event) => {
    const pointer = isTouch(event) ? event.touches[0] : event;
    const rect = target.el.getBoundingClientRect();
    fire(target.el, 'move', target.getMove({
        x: clamp((pointer.pageX - (rect.left + window.pageXOffset)) / rect.width),
        y: clamp((pointer.pageY - (rect.top + window.pageYOffset)) / rect.height)
    }));
};
const keyMove = (target, event) => {
    // We use `keyCode` instead of `key` to reduce the size of the library.
    const keyCode = event.keyCode;
    // Ignore all keys except arrow ones, Page Up, Page Down, Home and End.
    if (keyCode > 40 || (target.xy && keyCode < 37) || keyCode < 33)
        return;
    // Do not scroll page by keys when color picker element has focus.
    event.preventDefault();
    // Send relative offset to the parent component.
    fire(target.el, 'move', target.getMove({
        x: keyCode === 39 // Arrow Right
            ? 0.01
            : keyCode === 37 // Arrow Left
                ? -0.01
                : keyCode === 34 // Page Down
                    ? 0.05
                    : keyCode === 33 // Page Up
                        ? -0.05
                        : keyCode === 35 // End
                            ? 1
                            : keyCode === 36 // Home
                                ? -1
                                : 0,
        y: keyCode === 40 // Arrow down
            ? 0.01
            : keyCode === 38 // Arrow Up
                ? -0.01
                : 0
    }, true));
};
export class Slider {
    constructor(root, part, aria, xy) {
        const template = tpl(`<div role="slider" tabindex="0" part="${part}" ${aria}><div part="${part}-pointer"></div></div>`);
        root.appendChild(template.content.cloneNode(true));
        const el = root.querySelector(`[part=${part}]`);
        el.addEventListener('mousedown', this);
        el.addEventListener('touchstart', this);
        el.addEventListener('keydown', this);
        this.el = el;
        this.xy = xy;
        this.nodes = [el.firstChild, el];
    }
    set dragging(state) {
        const toggleEvent = state ? document.addEventListener : document.removeEventListener;
        toggleEvent(hasTouched ? 'touchmove' : 'mousemove', this);
        toggleEvent(hasTouched ? 'touchend' : 'mouseup', this);
    }
    handleEvent(event) {
        switch (event.type) {
            case 'mousedown':
            case 'touchstart':
                event.preventDefault();
                // event.button is 0 in mousedown for left button activation
                if (!isValid(event) || (!hasTouched && event.button != 0))
                    return;
                this.el.focus();
                pointerMove(this, event);
                this.dragging = true;
                break;
            case 'mousemove':
            case 'touchmove':
                event.preventDefault();
                pointerMove(this, event);
                break;
            case 'mouseup':
            case 'touchend':
                this.dragging = false;
                break;
            case 'keydown':
                keyMove(this, event);
                break;
        }
    }
    style(styles) {
        styles.forEach((style, i) => {
            for (const p in style) {
                this.nodes[i].style.setProperty(p, style[p]);
            }
        });
    }
}
//# sourceMappingURL=slider.js.map