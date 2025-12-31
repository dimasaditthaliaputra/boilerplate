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
        // Expose modal functions to window for onclick handlers
        window.openRoleModal = function(id = null, url = null) {
            if (id && url) {
                $.get(url, function(response) {
                    if (response.success) {
                        $('#roleModalLabel').text('Edit Role');
                        $('#primary_id').val(response.data.id);
                        $('#name').val(response.data.name);
                        if (response.data.permissions) {
                            response.data.permissions.forEach(function(perm) {
                                $(`input[name="permission_name[]"][value="${perm.name}"]`).prop('checked', true);
                            });
                        }
                        window.dispatchEvent(new CustomEvent('open-modal-rolemodal'));
                    }
                });
            } else {
                $('#roleModalLabel').text('Tambah Role');
                resetRoleForm();
                window.dispatchEvent(new CustomEvent('open-modal-rolemodal'));
            }
        };

        window.closeRoleModal = function() {
            window.dispatchEvent(new CustomEvent('close-modal-rolemodal'));
            resetRoleForm();
        };

        function resetRoleForm() {
            $('#primary_id').val('');
            $('input[name="permission_name[]"]').prop('checked', false);
            $('#roleForm')[0].reset();
        }

        // DataTable initialization
        $(function() {
            var table = $('#table').DataTable({
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
                        previous: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                        next: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>'
                    }
                }
            });

            $(document).on('click', '.edit-button', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                window.openRoleModal(id, url);
            });

            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
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
                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                toastr.success(response.message, "SUCCESS", { positionClass: "toast-bottom-right" });
                                table.ajax.reload(null, false);
                            },
                            error: function() {
                                toastr.error("Failed to delete", "ERROR", { positionClass: "toast-bottom-right" });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
