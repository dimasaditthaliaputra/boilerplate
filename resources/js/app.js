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

// Global notification function (replaces Toastr)
window.showNotification = function (type, message) {
    window.dispatchEvent(new CustomEvent('show-notification', {
        detail: { type, message }
    }));
};


// Import Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.start();
