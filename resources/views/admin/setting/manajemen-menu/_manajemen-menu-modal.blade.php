<x-ui.modal id="menumodal" maxWidth="lg">
    <x-slot name="header">
        <h3 id="menuModalLabel" class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Menu</h3>
    </x-slot>

    <form id="menuForm">
        @csrf
        <input type="hidden" id="primary_id" name="primary_id" />

        <div class="space-y-4">
            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama <span class="text-error-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                    placeholder="Contoh: Manajemen Menu">
            </div>

            <!-- Icon -->
            <div>
                <label for="icon" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Icon <span class="text-error-500">*</span>
                </label>
                <input type="text" id="icon" name="icon"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                    placeholder="Contoh: bi bi-list">
            </div>

            <!-- Route -->
            <div>
                <label for="route" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Route
                </label>
                <input type="text" id="route" name="route"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                    placeholder="Contoh: menu.index">
            </div>

            <!-- URL -->
            <div>
                <label for="url" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    URL
                </label>
                <input type="text" id="url" name="url"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                    placeholder="Contoh: /menu">
            </div>

            <!-- Parent Menu -->
            <div>
                <label for="parent_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Parent Menu
                </label>
                <select name="parent_id" id="parent_id"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    <option value="">Pilih Parent Menu</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Order -->
            <div>
                <label for="order" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Urutan <span class="text-error-500">*</span>
                </label>
                <input type="number" id="order" name="order"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                    placeholder="Contoh: 1">
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Status <span class="text-error-500">*</span>
                </label>
                <select name="is_active" id="is_active"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    <option value="">Pilih Status</option>
                    <option value="true">Aktif</option>
                    <option value="false">Non Aktif</option>
                </select>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <x-ui.button type="button" varianty="outline" onclick="closeMenuModal()">
                Tutup
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" id="submitMenuBtn" form="menuForm">
                Simpan Menu
            </x-ui.button>
        </div>
    </x-slot>
</x-ui.modal>

@push('scripts')
    <script type="module">
        $('#menuForm').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitMenuBtn');
            submitBtn.prop('disabled', true).html('<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Saving...');

            let id = $('#primary_id').val();
            let url = id ? '{{ route("manajemen-menu.update", ["manajemen_menu" => ":id"]) }}'.replace(':id', id) :
                '{{ route("manajemen-menu.store") }}';
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
                    closeMenuModal();
                    toastr.success(response.message, "SUCCESS", { positionClass: "toast-bottom-right" });
                    $('#table').DataTable().ajax.reload();
                    submitBtn.prop('disabled', false).html('Simpan Menu');
                },
                error: function(response) {
                    submitBtn.prop('disabled', false).html('Simpan Menu');
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
