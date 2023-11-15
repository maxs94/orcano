import Plugin from 'src/plugin-system/plugin.class';

/**
 * You can set window.spinner = true|false to show the spinner
 * or simply use the htmx requests 
 */
export default class SpinnerPlugin extends Plugin {
    static options = {
        spinnerSelector: '#topbar-spinner #spinner',
        minRequestDuration: 100,
    };

    init() {
        this._spinner = document.querySelector(this.options.spinnerSelector);

        this.registerWindowSpinnerGlobalEvent();
        this.registerHtmxSpinnerEvent();
    }

    registerHtmxSpinnerEvent() {
        document.addEventListener('htmx:beforeRequest', () => {
            this.showSpinner();
        });

        document.addEventListener('htmx:afterRequest', () => {
            this.hideSpinnerWithTimeout();
        });
    }

    hideSpinnerWithTimeout() {
        setTimeout(() => {
            this.hideSpinner();
        }, 500);
    }

    registerWindowSpinnerGlobalEvent() {
        // this will show a spinner if a request takes longer than (minRequestDuration)ms
        setInterval(() => {
            if (window.spinner === true) {
                this.showSpinner();
            } else {
                if (!this._spinner.classList.contains('htmx-request')) {
                    return;
                }
                this.hideSpinnerWithTimeout();
            }
        }, this.options.minRequestDuration);
    }

    showSpinner() {
        this._spinner.classList.add('htmx-request');
    }

    hideSpinner() {
        this._spinner.classList.remove('htmx-request');
    }

}
