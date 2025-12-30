import './bootstrap';

// Import jQuery and expose globally FIRST (required for DataTables and inline scripts)
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// Import DataTables and expose globally (depends on jQuery being on window first)
import DataTable from 'datatables.net-dt';
window.DataTable = DataTable;

// Import SweetAlert2 and expose globally
import Swal from 'sweetalert2';
window.Swal = Swal;

// Import Toastr and expose globally
import toastr from 'toastr';
window.toastr = toastr;

// Configure default Toastr options
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-bottom-right',
    timeOut: 5000,
};

// Import Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.start();
