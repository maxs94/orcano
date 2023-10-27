import Plugin from 'src/plugin-system/plugin.class';
import PerfectScrollbar from 'perfect-scrollbar';

export default class SidebarPlugin extends Plugin {
    static options = {
        sidebarSelector: '#sidebar',
        sidebarWrapperSelector: '.sidebar-wrapper',
        burgerButtonSelector: '.burger-btn',
        sidebarHideButtonSelector: '.sidebar-hide',
        sidebarItemHasSubSelector: '.sidebar-item.has-sub',
        sidebarLinkSelector: '.sidebar-link',
        submenuSelector: '.submenu',
    };

    init() {
        this._burgerButton = this.el.querySelector(this.options.burgerButtonSelector);
        this._sidebarHideButton = this.el.querySelector(this.options.sidebarHideButtonSelector);
        this._sidebar = this.el.querySelector(this.options.sidebarSelector);
        this._sidebarItems = this.el.querySelectorAll(this.options.sidebarItemHasSubSelector);
        this._sidebarWrapper = this.el.querySelector(this.options.sidebarWrapperSelector);
        
        this._scrollbar = new PerfectScrollbar(this._sidebarWrapper);

        this.registerEvents();
        this.refresh();

    }

    registerEvents() {
        this._burgerButton.addEventListener('click', () => {
            this.toggleSidebar();
        });

        this._sidebarHideButton.addEventListener('click', () => {
            this.toggleSidebar();
        });

        window.addEventListener('resize', () => {
            var w = window.innerWidth;
            if (w < 1200) {
                this.hideSidebar();
            } else {
                this.showSidebar();
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            var w = window.innerWidth;
            if (w < 1200) {
                this.hideSidebar();
            } 
        });
    }

    refresh() {
        for (var i = 0; i < this._sidebarItems.length; i++) {
            let sidebarItem = this._sidebarItems[i];
            sidebarItem.querySelector(this.options.sidebarLinkSelector).addEventListener('click', (e) => {
                e.preventDefault();

                let submenu = sidebarItem.querySelector(this.options.submenuSelector);
                if (submenu.classList.contains('active')) submenu.style.display = "block"

                if (submenu.style.display == "none") submenu.classList.add('active')
                else submenu.classList.remove('active')
                this.slideToggle(submenu, 300)
            })
        }
    }

    showSidebar() {
        this._sidebar.classList.add('active');
    }

    hideSidebar() {
        this._sidebar.classList.remove('active');
    }

    toggleSidebar() {
        this._sidebar.classList.toggle('active');
    }

    slideToggle(t, e, o) { 0 === t.clientHeight ? this.animate(t, e, o, !0) : this.animate(t, e, o) } 
    slideUp(t, e, o) { this.animate(t, e, o) } 
    sideDown(t, e, o) { this.animate(t, e, o, !0) } 
    animate(t, e, o, i) { void 0 === e && (e = 400), void 0 === i && (i = !1), t.style.overflow = "hidden", i && (t.style.display = "block"); var p, l = window.getComputedStyle(t), n = parseFloat(l.getPropertyValue("height")), a = parseFloat(l.getPropertyValue("padding-top")), s = parseFloat(l.getPropertyValue("padding-bottom")), r = parseFloat(l.getPropertyValue("margin-top")), d = parseFloat(l.getPropertyValue("margin-bottom")), g = n / e, y = a / e, m = s / e, u = r / e, h = d / e; window.requestAnimationFrame(function l(x) { void 0 === p && (p = x); var f = x - p; i ? (t.style.height = g * f + "px", t.style.paddingTop = y * f + "px", t.style.paddingBottom = m * f + "px", t.style.marginTop = u * f + "px", t.style.marginBottom = h * f + "px") : (t.style.height = n - g * f + "px", t.style.paddingTop = a - y * f + "px", t.style.paddingBottom = s - m * f + "px", t.style.marginTop = r - u * f + "px", t.style.marginBottom = d - h * f + "px"), f >= e ? (t.style.height = "", t.style.paddingTop = "", t.style.paddingBottom = "", t.style.marginTop = "", t.style.marginBottom = "", t.style.overflow = "", i || (t.style.display = "none"), "function" == typeof o && o()) : window.requestAnimationFrame(l) }) }

}
