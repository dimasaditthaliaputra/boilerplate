@props([
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $variantMap = [
        'primary' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400',
        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
        'danger' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
        'info' => 'bg-blue-light-50 text-blue-light-600 dark:bg-blue-light-500/15 dark:text-blue-light-400',
        'gray' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
    ];
    $variantClass = $variantMap[$variant] ?? $variantMap['primary'];

    $sizeMap = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];
    $sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-medium {$variantClass} {$sizeClass}"]) }}>
    {{ $slot }}
</span>
