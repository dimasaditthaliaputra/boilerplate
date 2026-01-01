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
            <x-ui.button type="button" variant="outline" onclick="closeMenuModal()">
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
        document.getElementById('menuForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitMenuBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Saving...';

            const id = document.getElementById('primary_id').value;
            const baseStoreUrl = '{{ route("manajemen-menu.store") }}';
            const baseUpdateUrl = '{{ route("manajemen-menu.update", ["manajemen_menu" => ":id"]) }}';
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
                closeMenuModal();
                showNotification('success', response.message);
                window.reloadMenuTable();
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan Menu';
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan Menu';

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
    </script>
@endpush
