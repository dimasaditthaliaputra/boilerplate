@extends('layouts.app')

@section('title', 'Role')
@section('page-title', 'Manajemen Role')

@section('breadcrumb')
    <li class="flex items-center text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-500">Home</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="flex items-center text-gray-500 dark:text-gray-400">
        <span>Setting</span>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-brand-500">Manajemen Role</li>
@endsection

@section('content')
    <x-ui.table id="table" title="Data Role User">
        <x-slot name="headerAction">
            <x-ui.button variant="primary" size="sm" onclick="openRoleModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Role
            </x-ui.button>
        </x-slot>

        <thead class="border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">No</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Nama</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Guard</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Permission</th>
                <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Action</th>
            </tr>
        </thead>
    </x-ui.table>

    @include('admin.setting.manajemen-role._manajemen-role-modal')
@endsection

@push('scripts')
    <script type="module">
        let dataTable;

        // Initialize DataTable on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            dataTable = new DataTable('#table', {
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                pagingType: 'simple_numbers',
                layout: {
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                },
                ajax: "{{ route('manajemen-role.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'px-5 py-4 text-center text-sm dark:text-gray-300 sm:px-6' },
                    { data: 'name', name: 'name', className: 'px-5 py-4 text-sm font-medium text-gray-800 dark:text-white/90 sm:px-6' },
                    { data: 'guard_name', name: 'guard_name', className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6' },
                    { data: 'permissions', name: 'permissions', className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'px-5 py-4 text-center sm:px-6' }
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }],
                language: {
                    search: "",
                    searchPlaceholder: "Search roles...",
                    lengthMenu: "Show _MENU_",
                    paginate: {
                        previous: 'Previous',
                        next: 'Next'
                    }
                }
            });

            // Event delegation for edit and delete buttons
            document.addEventListener('click', function(e) {
                // Edit button handler
                if (e.target.closest('.edit-button')) {
                    const btn = e.target.closest('.edit-button');
                    const id = btn.dataset.id;
                    const url = btn.dataset.url;
                    window.openRoleModal(id, url);
                }

                // Delete button handler
                if (e.target.closest('.delete-button')) {
                    e.preventDefault();
                    const btn = e.target.closest('.delete-button');
                    const form = btn.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This role will be permanently deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            confirmButton: 'bg-error-500 text-white px-4 py-2 rounded-lg mx-2 hover:bg-error-600',
                            cancelButton: 'bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300'
                        },
                        buttonsStyling: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(form.action, {
                                method: 'POST',
                                body: new FormData(form),
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(response => {
                                showNotification('success', response.message);
                                dataTable.ajax.reload(null, false);
                            })
                            .catch(() => {
                                showNotification('error', 'Failed to delete');
                            });
                        }
                    });
                }
            });
        });

        // Modal functions
        window.openRoleModal = function(id = null, url = null) {
            if (id && url) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        document.getElementById('roleModalLabel').textContent = 'Edit Role';
                        document.getElementById('primary_id').value = response.data.id;
                        document.getElementById('name').value = response.data.name;

                        // Reset all checkboxes first
                        document.querySelectorAll('input[name="permission_name[]"]').forEach(cb => {
                            syncAlpineCheckbox(cb, false);
                        });

                        // Set checked state for permissions from response
                        if (response.data.permissions) {
                            response.data.permissions.forEach(function(perm) {
                                const checkbox = document.querySelector(`input[name="permission_name[]"][value="${perm.name}"]`);
                                if (checkbox) {
                                    syncAlpineCheckbox(checkbox, true);
                                }
                            });
                        }
                        window.dispatchEvent(new CustomEvent('open-modal-rolemodal'));
                    }
                });
            } else {
                document.getElementById('roleModalLabel').textContent = 'Tambah Role';
                resetRoleForm();
                window.dispatchEvent(new CustomEvent('open-modal-rolemodal'));
            }
        };

        window.closeRoleModal = function() {
            window.dispatchEvent(new CustomEvent('close-modal-rolemodal'));
            resetRoleForm();
        };

        function resetRoleForm() {
            document.getElementById('primary_id').value = '';
            document.querySelectorAll('input[name="permission_name[]"]').forEach(el => el.checked = false);
            document.getElementById('roleForm').reset();
        }

        // Expose dataTable reload for modal
        window.reloadRoleTable = function() {
            dataTable.ajax.reload();
        };
    </script>
@endpush
