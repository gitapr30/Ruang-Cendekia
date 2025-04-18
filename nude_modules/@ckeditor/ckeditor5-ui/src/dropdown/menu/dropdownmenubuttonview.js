/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-licensing-options
 */
/**
 * @module ui/dropdown/menu/dropdownmenubuttonview
 */
import IconView from '../../icon/iconview.js';
import ListItemButtonView from '../../button/listitembuttonview.js';
import dropdownArrowIcon from '../../../theme/icons/dropdown-arrow.svg';
import '../../../theme/components/dropdown/menu/dropdownmenubutton.css';
/**
 * Represents a view for a dropdown menu button.
 */
export default class DropdownMenuButtonView extends ListItemButtonView {
    /**
     * Creates an instance of the dropdown menu button view.
     *
     * @param locale The localization services instance.
     */
    constructor(locale) {
        super(locale);
        const bind = this.bindTemplate;
        this.set({
            withText: true,
            role: 'menuitem'
        });
        this.arrowView = this._createArrowView();
        this.extendTemplate({
            attributes: {
                class: [
                    'ck-dropdown-menu-list__nested-menu__button'
                ],
                'aria-haspopup': true,
                'aria-expanded': this.bindTemplate.to('isOn', value => String(value)),
                'data-cke-tooltip-disabled': bind.to('isOn')
            },
            on: {
                'mouseenter': bind.to('mouseenter')
            }
        });
    }
    /**
     * @inheritDoc
     */
    render() {
        super.render();
        this.children.add(this.arrowView);
    }
    /**
     * Creates the arrow view instance.
     *
     * @private
     */
    _createArrowView() {
        const arrowView = new IconView();
        arrowView.content = dropdownArrowIcon;
        arrowView.extendTemplate({
            attributes: {
                class: 'ck-dropdown-menu-list__nested-menu__button__arrow'
            }
        });
        return arrowView;
    }
}
