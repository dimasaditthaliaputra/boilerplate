<x-ui.modal id="rolemodal" maxWidth="lg">
    <x-slot name="header">
        <h3 id="roleModalLabel" class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Role</h3>
    </x-slot>

    <form id="roleForm">
        @csrf
        <input type="hidden" id="primary_id" name="primary_id" />

        <div class="space-y-5">
            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama <span class="text-error-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                    placeholder="Contoh: Admin, User, Manager">
            </div>

            <!-- Permissions -->
            <div>
                <label class="block mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Permissions <span class="text-error-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-1">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                            <input type="checkbox" name="permission_name[]" value="{{ $permission->name }}"
                                class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <div class="flex items-center justify-end gap-3">
            <x-ui.button type="button" variant="outline" onclick="closeRoleModal()">
                Tutup
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" id="submitRoleBtn" form="roleForm">
                Simpan Role
            </x-ui.button>
        </div>
    </x-slot>
</x-ui.modal>

@push('scripts')
    <script type="module">
        $('#roleForm').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitRoleBtn');
            submitBtn.prop('disabled', true).html('<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Saving...');

            let id = $('#primary_id').val();
            let url = id ? '{{ route("manajemen-role.update", ["manajemen_role" => ":id"]) }}'.replace(':id', id) :
                '{{ route("manajemen-role.store") }}';
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
                    closeRoleModal();
                    toastr.success(response.message, "SUCCESS", { positionClass: "toast-bottom-right" });
                    $('#table').DataTable().ajax.reload();
                    submitBtn.prop('disabled', false).html('Simpan Role');
                },
                error: function(response) {
                    submitBtn.prop('disabled', false).html('Simpan Role');
                    if (response.status === 422) {
                        toastr.error("There are errors in your input!", "ERROR", { positionClass: "toast-bottom-right" });
                        let errors = response.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            let input = $('#' + key);
                            input.addClass('border-error-500');
                            input.parent().find('.text-error-500').remove();
                            input.parent().append('<span class="text-error-500 text-sm mt-1">' + val[0] + '</span>');
                        });
                    } else {
                        toastr.error("An error occurred", "ERROR", { positionClass: "toast-bottom-right" });
                    }
                }
            });
        });
    </script>
@endpush
