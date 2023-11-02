import Plugin from 'src/plugin-system/plugin.class';
import Axios from 'axios';

export default class ThemeTogglerPlugin extends Plugin {
    init() {
        this._html = document.querySelector('html');
        this._themeToggler = document.getElementById('theme-toggler');
        this.registerEvents();
    }

    registerEvents() {
        this._themeToggler.addEventListener('click', () => {
            this.toggleTheme();
        });
    }

    toggleTheme() {
        let currentTheme = this._html.getAttribute('data-bs-theme');

        if (currentTheme === 'dark') {
            this._html.setAttribute('data-bs-theme', 'light');
        } else {
            this._html.setAttribute('data-bs-theme', 'dark');
        }

        this.updateUserSetting();
    }

    updateUserSetting() {
        window.spinner = true;
        Axios.post('/api/upsert/user', {
            'id': this._html.getAttribute('data-user-id'),
            'theme': this._html.getAttribute('data-bs-theme')
        })
        .then((response) => {
            console.log(response);
            window.spinner = false;
        })
        .catch((error) => {
            Swal.fire({
                text: error,
                icon: error
            });
            window.spinner = false;
        });
    }
}
