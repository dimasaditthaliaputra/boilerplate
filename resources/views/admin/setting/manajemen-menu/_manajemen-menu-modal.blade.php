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
                <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nama <span class="text-error-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    placeholder="Contoh: Manajemen Menu">
            </div>

            <!-- Icon -->
            <div>
                <label for="icon" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Icon <span class="text-error-500">*</span>
                </label>
                <input type="text" id="icon" name="icon"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    placeholder="Contoh: bi bi-list">
            </div>

            <!-- Route -->
            <div>
                <label for="route" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Route
                </label>
                <input type="text" id="route" name="route"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    placeholder="Contoh: menu.index">
            </div>

            <!-- URL -->
            <div>
                <label for="url" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    URL
                </label>
                <input type="text" id="url" name="url"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    placeholder="Contoh: /menu">
            </div>

            <!-- Parent Menu -->
            <div>
                <label for="parent_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Parent Menu
                </label>
                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                    <select name="parent_id" id="parent_id"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                        @change="isOptionSelected = true">
                        <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Pilih Parent Menu</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Order -->
            <div>
                <label for="order" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Urutan <span class="text-error-500">*</span>
                </label>
                <input type="number" id="order" name="order"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    placeholder="Contoh: 1">
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Status <span class="text-error-500">*</span>
                </label>
                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                    <select name="is_active" id="is_active"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                        @change="isOptionSelected = true">
                        <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Pilih Status</option>
                        <option value="true" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Aktif</option>
                        <option value="false" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Non Aktif</option>
                    </select>
                    <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
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
        // Reset form completely
        function resetMenuForm() {
            const form = document.getElementById('menuForm');
            if (!form) return;
            
            // Reset form values
            form.reset();
            document.getElementById('primary_id').value = '';
            document.getElementById('menuModalLabel').textContent = 'Tambah Menu';
            
            // Clear validation errors
            clearFormErrors('menuForm');
            
            // Reset Alpine.js select states
            document.querySelectorAll('#menuForm [x-data]').forEach(el => {
                if (el.__x) {
                    el.__x.$data.isOptionSelected = false;
                } else if (el._x_dataStack) {
                    el._x_dataStack[0].isOptionSelected = false;
                }
            });
        }

        // Listen for modal close event
        window.addEventListener('modal-closed-menumodal', resetMenuForm);

        // Form submission
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
                            setInputError(input, error.errors[key][0]);
                        }
                    });
                } else {
                    showNotification('error', 'An error occurred');
                }
            });
        });
    </script>
@endpush
