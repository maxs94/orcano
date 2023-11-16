// import base requirements
import * as bootstrap from 'bootstrap';
import 'htmx.org';

// plugin manager
import PluginManager from 'src/plugin-system/plugin.manager';
import ViewportDetection from 'src/helper/viewport-detection.helper';
import NativeEventEmitter from 'src/helper/emitter.helper';

// utils
import TimezoneUtil from 'src/utility/timezone/timezone.util';
import BootstrapUtil from 'src/utility/bootstrap/bootstrap.util';

// import plugins
import ScrollUpPlugin from 'src/plugin/scroll-up/scroll-up.plugin';
import SidebarPlugin from './plugin/sidebar/sidebar.plugin';
import ThemeTogglerPlugin from './plugin/theme-toggler/theme-toggler.plugin';
import SpinnerPlugin from './plugin/spinner/spinner.plugin';
import SweetAlertPlugin from './plugin/sweetalert/sweetalert.plugin';
import CodePlugin from './plugin/code/code.plugin';
import ChoicesPlugin from './plugin/form/choices.plugin';

// init
window.eventEmitter = new NativeEventEmitter();
window.bootstrap = bootstrap;
window.htmx = require('htmx.org');
new ViewportDetection();

// register plugins
PluginManager.register('ScrollUpPlugin', ScrollUpPlugin, '[data-scroll-up]');
PluginManager.register('SidebarPlugin', SidebarPlugin);
PluginManager.register('ThemeTogglerPlugin', ThemeTogglerPlugin, '#theme-toggler');
PluginManager.register('SpinnerPlugin', SpinnerPlugin, '#topbar-spinner');
PluginManager.register('SweetAlertPlugin', SweetAlertPlugin, '[data-sweet-alert]');
PluginManager.register('CodePlugin', CodePlugin, '[data-code]');
PluginManager.register('ChoicesPlugin', ChoicesPlugin, '[data-choices]');

// run plugins 
document.addEventListener('DOMContentLoaded', () => PluginManager.initializePlugins(), false);
document.addEventListener('htmx:afterSwap', () => PluginManager.initializePlugins(), false);

// run utils
new TimezoneUtil();

BootstrapUtil.initBootstrapPlugins();

// import styles
import './styles/global.scss';

