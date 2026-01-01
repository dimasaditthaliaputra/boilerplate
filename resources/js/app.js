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

// TailAdmin Input Error State Classes
const INPUT_NORMAL_CLASSES = [
    'border-gray-300', 'focus:border-brand-300', 'focus:ring-brand-500/10',
    'dark:border-gray-700', 'dark:focus:border-brand-800'
];
const INPUT_ERROR_CLASSES = [
    'border-error-300', 'focus:border-error-300', 'focus:ring-error-500/10',
    'dark:border-error-700', 'dark:focus:border-error-800'
];

// Add error state to input
window.setInputError = function (input, errorMessage) {
    if (!input) return;

    // Remove normal state classes
    INPUT_NORMAL_CLASSES.forEach(cls => input.classList.remove(cls));
    // Add error state classes
    INPUT_ERROR_CLASSES.forEach(cls => input.classList.add(cls));

    // Remove existing error message
    const container = input.closest('div');
    const existingError = container?.querySelector('.input-error-message');
    if (existingError) existingError.remove();

    // Add error message if provided
    if (errorMessage && container) {
        const errorSpan = document.createElement('p');
        errorSpan.className = 'input-error-message mt-1.5 text-theme-xs text-error-500';
        errorSpan.textContent = errorMessage;
        container.appendChild(errorSpan);
    }
};

// Clear error state from input
window.clearInputError = function (input) {
    if (!input) return;

    // Remove error state classes
    INPUT_ERROR_CLASSES.forEach(cls => input.classList.remove(cls));
    // Add normal state classes
    INPUT_NORMAL_CLASSES.forEach(cls => input.classList.add(cls));

    // Remove error message
    const container = input.closest('div');
    const existingError = container?.querySelector('.input-error-message');
    if (existingError) existingError.remove();
};

// Clear all input errors in form
window.clearFormErrors = function (formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.querySelectorAll('input, select, textarea').forEach(input => {
        window.clearInputError(input);
    });
};

// Sync Alpine.js checkbox state with DOM checked state
window.syncAlpineCheckbox = function (checkbox, isChecked) {
    if (!checkbox) return;

    // Set DOM checked state
    checkbox.checked = isChecked;

    // Find parent with x-data and sync Alpine state
    const alpineEl = checkbox.closest('[x-data]');
    if (alpineEl) {
        if (alpineEl.__x) {
            alpineEl.__x.$data.checked = isChecked;
        } else if (alpineEl._x_dataStack) {
            alpineEl._x_dataStack[0].checked = isChecked;
        }
    }
};

// Sync Alpine.js select state
window.syncAlpineSelect = function (select, hasValue) {
    if (!select) return;

    // Find parent with x-data and sync Alpine state
    const alpineEl = select.closest('[x-data]');
    if (alpineEl) {
        if (alpineEl.__x) {
            alpineEl.__x.$data.isOptionSelected = hasValue;
        } else if (alpineEl._x_dataStack) {
            alpineEl._x_dataStack[0].isOptionSelected = hasValue;
        }
    }
};

// Import Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.start();
