import Plugin from 'src/plugin-system/plugin.class';
import Swal from 'sweetalert2';

export default class SweetalertPlugin extends Plugin {
    static options = {
        
    };

    init() {
        Swag.bindClickHandler();
        swal.fire('testing');
    }


}
