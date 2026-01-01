import './bootstrap';

// Import DataTables (vanilla JS version - no jQuery dependency)
import DataTable from 'datatables.net';
window.DataTable = DataTable;

// Import SweetAlert2 and expose globally
import Swal from 'sweetalert2';
window.Swal = Swal;

// Global notification function
window.showNotification = function (type, message) {
    window.dispatchEvent(new CustomEvent('show-notification', {
        detail: { type, message }
    }));
};

// Import Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.start();
