import Plugin from 'src/plugin-system/plugin.class';
import * as ace from 'ace-builds/src-noconflict/ace';
import 'ace-builds/src-noconflict/mode-sh';
import 'ace-builds/src-noconflict/theme-tomorrow_night';
import 'ace-builds/src-noconflict/theme-tomorrow';
import 'ace-builds/src-noconflict/keybinding-vim';
import 'ace-builds/src-noconflict/keybinding-vscode';

export default class CodePlugin extends Plugin {

    init() {
        this.initElement();
    }

    initElement() {
        let keybinding = 'ace/keyboard/' + this.el.getAttribute('data-keybinding');
 
        var editor = ace.edit(
            this.el, {
                mode: "ace/mode/sh",
                selectionStyle: "text",
                showLineNumbers: true,
                tabSize: 4,
                theme: this.getTheme(),
                fontSize: 14,
                keyboardHandler: keybinding
            }
        );

        let textarea = document.getElementById('code-editor-textarea');

        editor.getSession().on('change', function() {
            textarea.value = editor.getSession().getValue();
        });
    }

    getTheme() {
        let htmlElement = document.querySelector('html');
        let siteTheme = htmlElement.getAttribute('data-bs-theme');

        if (siteTheme == 'dark') {
            return 'ace/theme/tomorrow_night';
        } 
    
        return 'ace/theme/tomorrow';
    }
}
