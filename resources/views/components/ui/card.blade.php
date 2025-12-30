@props([
    'title' => null,
    'headerAction' => null,
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]']) }}>
    @if($title || $headerAction)
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800 sm:px-6">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ $title }}
                </h3>
            @endif
            @if($headerAction)
                <div>
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $noPadding ? '' : 'p-5 sm:p-6' }}">
        {{ $slot }}
    </div>
</div>
