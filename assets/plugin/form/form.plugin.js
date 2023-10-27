import Plugin from 'src/plugin-system/plugin.class';
import Swal from 'sweetalert2';

export default class FormPlugin extends Plugin {
    static options = {
    }
 
    init() {
        this.registerEvents();
    }

    registerEvents() {
        this.el.addEventListener('submit', (e) => {
            e.preventDefault();

            this.validateForm();
        });
    }

    getAllFormInputs() {
        let elements = this.el.querySelectorAll('input, select, textarea');
        let result = {}; 
        elements.forEach((element) => {
            let name = element.getAttribute('name');
            result[name] = element;
        });

        return result;
    }

    validateForm() {
        let formValidator = this.el.getAttribute('data-form-validator');
        let formInputs = this.getAllFormInputs();

        if (typeof window[formValidator] === "function") {
            let res = this.executeFormValidator(formValidator, window, formInputs);
            console.log(res);
            if (res == true) {
                console.log("success");
            } else {
                Swal.fire({
                    text: res,
                    icon: 'error',
                });
            }
        } else {
            console.error("no form validator found or provided");
        }
    }

    executeFormValidator(validatorName, context /*, args */) {
        var args = Array.prototype.slice.call(arguments, 2);
        var namespaces = validatorName.split(".");
        var func = namespaces.pop();
        for(var i = 0; i < namespaces.length; i++) {
            context = context[namespaces[i]];
        }
        return context[func].apply(context, args);
    }
}
