import Plugin from 'src/plugin-system/plugin.class';

export default class SpinnerPlugin extends Plugin {
    static options = {
        spinnerButtonSelector: '#topbar-spinner button',
        minRequestDuration: 100,
    };

    init() {
        this._spinner = document.querySelector(this.options.spinnerButtonSelector);

        this.registerEvents();
    }

    registerEvents() {
        // this will show a spinner if a request takes longer than (minRequestDuration)ms
        setInterval(() => {
            if (window.spinner === true) {
                this.showSpinner();
            } else {
                if (this._spinner.classList.contains('d-none')) {
                    return;
                }
                setTimeout(() => {
                    this.hideSpinner();
                }, 1000);
            }
        }, this.options.minRequestDuration);
    }

    showSpinner() {
        this._spinner.classList.remove('d-none');
    }

    hideSpinner() {
        this._spinner.classList.add('d-none');
    }

}
