@extends('layouts.app')

@section('title', 'Hak Akses')
@section('page-title', 'Manajemen Hak Akses')

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
    <li class="text-brand-500">Manajemen Hak Akses</li>
@endsection

@section('content')
    <x-ui.table id="table" title="Data Permission">
        <x-slot name="headerAction">
            <x-ui.button variant="primary" size="sm" onclick="openModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Permission
            </x-ui.button>
        </x-slot>

        <thead class="border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">No</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Nama</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Guard</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Access</th>
                <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Action</th>
            </tr>
        </thead>
    </x-ui.table>

    @include('admin.setting.manajemen-hak-akses._manajemen-hak-akses-modal')
@endsection

@push('scripts')
    <script type="module">
        let dataTable;

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
                ajax: "{{ route('manajemen-hak-akses.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'px-5 py-4 text-center text-sm dark:text-gray-300 sm:px-6' },
                    { data: 'name', name: 'name', className: 'px-5 py-4 text-sm font-medium text-gray-800 dark:text-white/90 sm:px-6' },
                    { data: 'guard_name', name: 'guard_name', className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6' },
                    { data: 'access', name: 'access', className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6' },
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
                    searchPlaceholder: "Search permissions...",
                    lengthMenu: "Show _MENU_",
                    paginate: {
                        previous: 'Previous',
                        next: 'Next'
                    }
                }
            });

            // Form submission
            document.getElementById('formData').addEventListener('submit', function(e) {
                e.preventDefault();

                const accessChecked = document.querySelectorAll('input[name="access[]"]:checked').length;
                if (accessChecked === 0) {
                    showNotification('error', 'Pilih minimal satu akses!');
                    return;
                }

                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Saving...';

                const id = document.getElementById('primary_id').value;
                const baseStoreUrl = '{{ route("manajemen-hak-akses.store") }}';
                const baseUpdateUrl = '{{ route("manajemen-hak-akses.update", ["manajemen_hak_akse" => ":id"]) }}';
                const url = id ? baseUpdateUrl.replace(':id', id) : baseStoreUrl;

                const formData = new FormData(this);
                if (id) {
                    formData.append('_method', 'PUT');
                }

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (res.status === 422) {
                        return res.json().then(data => Promise.reject({ status: 422, errors: data.errors }));
                    }
                    if (!res.ok) {
                        return Promise.reject({ status: res.status });
                    }
                    return res.json();
                })
                .then(response => {
                    window.closeModal();
                    showNotification('success', response.message);
                    dataTable.ajax.reload();
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Simpan Permission';
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Simpan Permission';

                    if (error.status === 422) {
                        showNotification('error', 'There are errors in your input!');
                        Object.keys(error.errors).forEach(key => {
                            const input = document.getElementById(key);
                            if (input) {
                                input.classList.add('border-error-500');
                                const existingError = input.parentElement.querySelector('.text-error-500:not(label span)');
                                if (existingError) existingError.remove();
                                const errorSpan = document.createElement('span');
                                errorSpan.className = 'text-error-500 text-sm mt-1';
                                errorSpan.textContent = error.errors[key][0];
                                input.parentElement.appendChild(errorSpan);
                            }
                        });
                    } else {
                        showNotification('error', 'An error occurred');
                    }
                });
            });

            // Event delegation for edit and delete buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-button')) {
                    const btn = e.target.closest('.edit-button');
                    const id = btn.dataset.id;
                    const url = btn.dataset.url;
                    window.openModal(id, url);
                }

                if (e.target.closest('.delete-button')) {
                    e.preventDefault();
                    const btn = e.target.closest('.delete-button');
                    const form = btn.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This permission will be permanently deleted!',
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
        window.openModal = function(id = null, url = null) {
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
                        document.getElementById('hakAksesModalLabel').textContent = 'Edit Permission';
                        document.getElementById('primary_id').value = response.data.id;
                        document.getElementById('name').value = response.data.name;
                        document.getElementById('menu').value = response.data.menu;

                        // Parse permissions and check boxes
                        if (response.data.permissions) {
                            const actions = ['show', 'create', 'update', 'delete', 'export', 'import', 'approve', 'report'];
                            actions.forEach(action => {
                                const checkbox = document.querySelector(`input[name="access[]"][value="${action}"]`);
                                if (checkbox) {
                                    checkbox.checked = response.data.permissions.some(p => p.includes(`.${action}`));
                                }
                            });
                        } else {
                            // Parse from single permission name
                            const permissionTypes = ['show', 'create', 'update', 'delete', 'export', 'import', 'approve', 'report'];
                            permissionTypes.forEach(type => {
                                if (response.data.name.includes(`.${type}`)) {
                                    const checkbox = document.querySelector(`input[name="access[]"][value="${type}"]`);
                                    if (checkbox) checkbox.checked = true;
                                }
                            });
                        }

                        window.dispatchEvent(new CustomEvent('open-modal-hakaksesmodal'));
                    }
                });
            } else {
                document.getElementById('hakAksesModalLabel').textContent = 'Tambah Permission';
                resetForm();
                window.dispatchEvent(new CustomEvent('open-modal-hakaksesmodal'));
            }
        };

        window.closeModal = function() {
            window.dispatchEvent(new CustomEvent('close-modal-hakaksesmodal'));
            resetForm();
        };

        function resetForm() {
            document.getElementById('primary_id').value = '';
            document.querySelectorAll('input[name="access[]"]').forEach(el => el.checked = false);
            document.getElementById('formData').reset();
            document.querySelectorAll('.border-error-500').forEach(el => el.classList.remove('border-error-500'));
            document.querySelectorAll('.text-error-500:not(label span)').forEach(el => el.remove());
        }
    </script>
@endpush
