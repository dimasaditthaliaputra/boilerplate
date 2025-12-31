<x-ui.modal id="hakaksesmodal" maxWidth="lg">
    <x-slot name="header">
        <h3 id="hakAksesModalLabel" class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Permission</h3>
    </x-slot>

    <form id="formData">
        @csrf
        <input type="hidden" id="primary_id" name="primary_id" />

        <div class="space-y-5">
            <!-- Menu Select -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Menu <span class="text-error-500">*</span>
                </label>
                <select name="menu" id="menu"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    <option value="">Pilih Menu</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->name }}">{{ $menu->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Module Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama Module <span class="text-error-500">*</span>
                </label>
                <input type="text"
                    class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                    placeholder="Contoh: tagihan, user, product"
                    id="name" name="name" required>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Nama module akan digunakan sebagai prefix permission (contoh: tagihan.show)
                </p>
            </div>

            <!-- CRUD Access -->
            <div>
                <label class="block mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Akses CRUD <span class="text-error-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="checkbox" name="access[]" value="show" id="checkShow"
                            class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Show / View
                        </span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="checkbox" name="access[]" value="create" id="checkCreate"
                            class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create
                        </span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="checkbox" name="access[]" value="update" id="checkUpdate"
                            class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Update
                        </span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="checkbox" name="access[]" value="delete" id="checkDelete"
                            class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </span>
                    </label>
                </div>
            </div>

            <!-- Additional Access -->
            <div>
                <label class="block mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Akses Tambahan
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="checkbox" name="access[]" value="export" id="checkExport"
                            class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export
                        </span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="checkbox" name="access[]" value="import" id="checkImport"
                            class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Import
                        </span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="checkbox" name="access[]" value="approve" id="checkApprove"
                            class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approve
                        </span>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                        <input type="checkbox" name="access[]" value="report" id="checkReport"
                            class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Report
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <x-ui.button type="button" variant="outline" onclick="closeModal()">
                Tutup
            </x-ui.button>
            <x-ui.button type="submit" variant="primary" id="submitBtn" form="formData">
                Simpan Permission
            </x-ui.button>
        </div>
    </x-slot>
</x-ui.modal>
