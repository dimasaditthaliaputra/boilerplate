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
                <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nama <span class="text-error-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    placeholder="Contoh: Admin, User, Manager">
            </div>

            <!-- Permissions -->
            <div>
                <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Permissions <span class="text-error-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto no-scrollbar p-1">
                    @foreach ($permissions as $permission)
                        <div x-data="{ checked: false }" class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <label class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                <div class="relative">
                                    <input type="checkbox" name="permission_name[]" value="{{ $permission->name }}" class="sr-only" @change="checked = !checked">
                                    <div :class="checked ? 'border-brand-500 bg-brand-500' : 'border-gray-300 dark:border-gray-700'" class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px]">
                                        <span :class="checked ? '' : 'opacity-0'">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                {{ $permission->name }}
                            </label>
                        </div>
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
        // Reset form completely
        function resetRoleForm() {
            const form = document.getElementById('roleForm');
            if (!form) return;
            
            // Reset form values
            form.reset();
            document.getElementById('primary_id').value = '';
            document.getElementById('roleModalLabel').textContent = 'Tambah Role';
            
            // Clear validation errors
            clearFormErrors('roleForm');
            
            // Reset Alpine.js checkbox states
            document.querySelectorAll('#roleForm [x-data]').forEach(el => {
                if (el.__x) {
                    el.__x.$data.checked = false;
                } else if (el._x_dataStack) {
                    el._x_dataStack[0].checked = false;
                }
            });
            
            // Uncheck all checkboxes
            form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });
        }

        // Listen for modal close event
        window.addEventListener('modal-closed-rolemodal', resetRoleForm);

        // Form submission
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
