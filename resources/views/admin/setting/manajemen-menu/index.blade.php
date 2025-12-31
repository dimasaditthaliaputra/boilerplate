@extends('layouts.app')

@section('title', 'Menu')
@section('page-title', 'Manajemen Menu')

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
    <li class="text-brand-500">Manajemen Menu</li>
@endsection

@section('content')
    <x-ui.table id="table" title="Data Menu Aplikasi">
        <x-slot name="headerAction">
            <x-ui.button variant="primary" size="sm" onclick="openMenuModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Menu
            </x-ui.button>
        </x-slot>

        <thead class="border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">No</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Nama</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Icon</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Route</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">URL</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Parent</th>
                <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Status</th>
                <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Action</th>
            </tr>
        </thead>
    </x-ui.table>

    @include('admin.setting.manajemen-menu._manajemen-menu-modal')
@endsection

@push('scripts')
    <script type="module">
        // Expose modal functions to window for onclick handlers
        window.openMenuModal = function(id = null, url = null) {
            if (id && url) {
                $.get(url, function(response) {
                    if (response.success) {
                        $('#menuModalLabel').text('Edit Menu');
                        $('#primary_id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#icon').val(response.data.icon);
                        $('#route').val(response.data.route);
                        $('#url').val(response.data.url);
                        $('#parent_id').val(response.data.parent_id).trigger('change');
                        $('#order').val(response.data.order);
                        $('#is_active').val(response.data.is_active ? 'true' : 'false');
                        window.dispatchEvent(new CustomEvent('open-modal-menumodal'));
                    }
                });
            } else {
                $('#menuModalLabel').text('Tambah Menu');
                resetMenuForm();
                window.dispatchEvent(new CustomEvent('open-modal-menumodal'));
            }
        };

        window.closeMenuModal = function() {
            window.dispatchEvent(new CustomEvent('close-modal-menumodal'));
            resetMenuForm();
        };

        function resetMenuForm() {
            $('#primary_id').val('');
            $('#menuForm')[0].reset();
        }

        // DataTable initialization
        $(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                pageLength: 10,
                pagingType: 'simple_numbers',
                layout: {
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                },
                ajax: "{{ route('manajemen-menu.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'px-5 py-4 text-center text-sm dark:text-gray-300 sm:px-6' },
                    { data: 'name', name: 'name', className: 'px-5 py-4 text-sm font-medium text-gray-800 dark:text-white/90 sm:px-6' },
                    { data: 'icon', name: 'icon', className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6' },
                    { data: 'route', name: 'route', className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6' },
                    { data: 'url', name: 'url', className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6' },
                    { data: 'parent.name', name: 'parent.name', className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6' },
                    { data: 'is_active', name: 'is_active', className: 'px-5 py-4 text-center sm:px-6' },
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
                    searchPlaceholder: "Search menus...",
                    lengthMenu: "Show _MENU_",
                    paginate: {
                        previous: 'Previous',
                        next: 'Next'
                    }
                }
            });

            $(document).on('click', '.edit-button', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                window.openMenuModal(id, url);
            });

            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This menu will be permanently deleted!',
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
