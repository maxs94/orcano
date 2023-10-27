import Plugin from 'src/plugin-system/plugin.class';

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
    }
}
