import Plugin from 'src/plugin-system/plugin.class';
import Choices from 'choices.js';

export default class ChoicesPlugin extends Plugin {

    init() {
        this._choicesElement = new Choices(this.el, {
            addItems: true,
            removeItems: true,
            removeItemButton: true,
            editItems: false,
            duplicateItemsAllowed: false,
            delimiter: ',',
        });
    }
}
