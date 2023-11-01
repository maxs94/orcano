import Plugin from 'src/plugin-system/plugin.class';
import Swal from 'sweetalert2';
import Axios from 'axios';

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

    getAllFormInputValues() {
        let elements = this.el.querySelectorAll('input, select, textarea');
        let result = {};
        elements.forEach((element) => {
            let name = element.getAttribute('name');
            let value = element.value;
            result[name] = value;
        });
        return result;
    }

    validateForm() {
        let formValidator = this.el.getAttribute('data-form-validator');
        let formInputs = this.getAllFormInputs();

        if (typeof window[formValidator] === "function") {
            let res = this.executeFormValidator(formValidator, window, formInputs);
            if (res == true) {
                let formEntity = this.el.getAttribute('data-form-entity');
                let formInputValues = this.getAllFormInputValues();
                var refreshAfterSave = this.el.getAttribute('data-refresh-after-save');
 
                Axios.post('/api/upsert/' + formEntity, formInputValues)
                .then((response) => {
                        if (response.data.success == true) {
                            Swal.fire({
                                text: response.data.message,
                                icon: 'success',
                            }).then(function() {
                                if (refreshAfterSave == 'true' || refreshAfterSave == true) {
                                    location.reload();
                                } 
                            });
                        } else {
                            Swal.fire({
                                text: response.data.message,
                                icon: 'error',
                            });
                        }
                })
                .catch((error) => {
                    Swal.fire({
                        text: error,
                        icon: 'error',
                    });
                });

            } else {
                Swal.fire({
                    text: res,
                    icon: 'error',
                });
            }
        } else {
            Swal.fire({
                text: "Form validator " + formValidator + " not found",
                icon: 'warning',
            });
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
