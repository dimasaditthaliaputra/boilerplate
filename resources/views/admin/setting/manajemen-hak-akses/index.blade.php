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
    <x-ui.table id="table" title="Data Hak Akses">
        <x-slot name="headerAction">
            <x-ui.button variant="primary" size="sm" onclick="openModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Hak Akses
            </x-ui.button>
        </x-slot>

        <thead class="border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">No</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Nama</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Guard</th>
                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Akses</th>
                <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 sm:px-6">Action</th>
            </tr>
        </thead>
    </x-ui.table>

    @include('admin.setting.manajemen-hak-akses._manajemen-hak-akses-modal')
@endsection

@push('scripts')
    <script type="module">
        // Expose modal functions to window for onclick handlers
        window.openModal = function(id = null, url = null) {
            if (id && url) {
                // Edit mode - fetch data first
                $.get(url, function(response) {
                    if (response.success) {
                        $('#hakAksesModalLabel').text('Edit Permission');
                        $('#primary_id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#menu').val(response.data.menu).trigger('change');

                        if (response.data.actions && response.data.actions.length > 0) {
                            response.data.actions.forEach(function(action) {
                                $(`input[name="access[]"][value="${action}"]`).prop('checked', true);
                            });
                        }

                        const permissionName = response.data.name;
                        const parts = permissionName.split('.');
                        if (parts.length === 2) {
                            const permissionType = parts[1];
                            $(`input[name="access[]"][value="${permissionType}"]`).prop('checked', true);
                        }

                        window.dispatchEvent(new CustomEvent('open-modal-hakaksesmodal'));
                    }
                });
            } else {
                // Create mode
                $('#hakAksesModalLabel').text('Tambah Permission');
                resetForm();
                window.dispatchEvent(new CustomEvent('open-modal-hakaksesmodal'));
            }
        };

        window.closeModal = function() {
            window.dispatchEvent(new CustomEvent('close-modal-hakaksesmodal'));
            resetForm();
        };

        function resetForm() {
            $('#primary_id').val('');
            $('input[name="access[]"]').prop('checked', false);
            $('#formData')[0].reset();
            $('.border-error-500').removeClass('border-error-500');
            $('.text-error-500').not('label').remove();
        }

        // DataTable initialization
        $(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                layout: {
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                },
                ajax: "{{ route('manajemen-hak-akses.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'px-5 py-4 text-center text-sm text-gray-700 dark:text-gray-300 sm:px-6'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: false,
                        searchable: false,
                        className: 'px-5 py-4 text-sm font-medium text-gray-800 dark:text-white/90 sm:px-6'
                    },
                    {
                        data: 'guard_name',
                        name: 'guard_name',
                        orderable: false,
                        searchable: true,
                        className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6'
                    },
                    {
                        data: 'access',
                        name: 'access',
                        orderable: false,
                        searchable: false,
                        className: 'px-5 py-4 text-sm text-gray-700 dark:text-gray-300 sm:px-6'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'px-5 py-4 text-center sm:px-6'
                    }
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
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                        next: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>'
                    }
                }
            });

            // Form submission
            $('#formData').on('submit', function(e) {
                e.preventDefault();

                let accessChecked = $('input[name="access[]"]:checked').length;
                if (accessChecked === 0) {
                    toastr.error("Pilih minimal satu akses!", "ERROR", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                    return;
                }

                let submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true).html('<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Saving...');

                let id = $('#primary_id').val();
                let url = id ? '{{ route("manajemen-hak-akses.update", ["manajemen_hak_akse" => ":id"]) }}'.replace(':id', id) :
                    '{{ route("manajemen-hak-akses.store") }}';
                let method = id ? 'PUT' : 'POST';

                let formData = new FormData(this);
                formData.append('_method', method);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        window.closeModal();
                        toastr.success(response.message, "SUCCESS", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                        table.ajax.reload();
                    },
                    error: function(response) {
                        submitBtn.prop('disabled', false).html('Simpan Permission');
                        if (response.status === 422) {
                            toastr.error("There are errors in your input!", "ERROR", {
                                progressBar: true,
                                timeOut: 3500,
                                positionClass: "toast-bottom-right",
                            });
                            let errors = response.responseJSON.errors;
                            $.each(errors, function(key, val) {
                                let input = $('#' + key);
                                input.addClass('border-error-500');
                                input.parent().find('.text-error-500').remove();
                                input.parent().append('<span class="text-error-500 text-sm mt-1">' + val[0] + '</span>');
                            });
                        } else {
                            toastr.error("An error occurred", "ERROR", {
                                progressBar: true,
                                timeOut: 3500,
                                positionClass: "toast-bottom-right",
                            });
                        }
                    }
                });
            });

            // Delete handler
            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

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
                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                toastr.success(response.message, "SUCCESS", {
                                    progressBar: true,
                                    timeOut: 3500,
                                    positionClass: "toast-bottom-right"
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function() {
                                toastr.error("Failed to delete", "ERROR", {
                                    progressBar: true,
                                    timeOut: 3500,
                                    positionClass: "toast-bottom-right"
                                });
                            }
                        });
                    }
                });
            });

            // Edit handler
            $(document).on('click', '.edit-button', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                window.openModal(id, url);
            });
        });
    </script>
@endpush
