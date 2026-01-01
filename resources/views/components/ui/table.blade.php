@props([
    'id' => 'table',
    'title' => null,
    'desc' => null,
    'headerAction' => null,
])

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    {{-- Card Header with Title --}}
    @if($title)
        <div class="px-5 py-4 sm:px-6 sm:py-5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ $title }}
                    </h3>
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
        </div>
    @endif

    {{-- Table Content Area --}}
    <div class="border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-full overflow-x-auto">
            <table id="{{ $id }}" {{ $attributes->merge(['class' => 'w-full min-w-[900px]']) }}>
                {{ $slot }}
            </table>
        </div>
    </div>
</div>
