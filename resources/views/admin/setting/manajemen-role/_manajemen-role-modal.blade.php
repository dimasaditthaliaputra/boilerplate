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
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto no-scrollbar p-1">
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
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
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
        document.getElementById('roleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitRoleBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Saving...';

            const id = document.getElementById('primary_id').value;
            const baseStoreUrl = '{{ route("manajemen-role.store") }}';
            const baseUpdateUrl = '{{ route("manajemen-role.update", ["manajemen_role" => ":id"]) }}';
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
                closeRoleModal();
                showNotification('success', response.message);
                window.reloadRoleTable();
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan Role';
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan Role';

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
