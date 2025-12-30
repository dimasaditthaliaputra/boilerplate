@props([
    'id' => 'table',
    'title' => null,
    'desc' => null,
    'headerAction' => null,
])

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
    {{-- Card Header --}}
    @if($title || $headerAction)
        <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                @if($title)
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ $title }}
                    </h3>
                @endif
                @if($desc)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $desc }}
                    </p>
                @endif
            </div>
            @if($headerAction)
                <div class="flex items-center gap-3">
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif

    {{-- Table Content --}}
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
        <div class="space-y-6">
            <div class="overflow-hidden rounded-xl bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <table id="{{ $id }}" {{ $attributes->merge(['class' => 'w-full']) }}>
                    {{ $slot }}
            </table>
        </div>
    </div>
</div>

