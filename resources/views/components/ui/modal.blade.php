@props([
    'id' => 'modal',
    'maxWidth' => 'lg',
])

@php
$maxWidthClasses = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
];
@endphp

<div
    x-data="{ 
        open: false,
        closeModal() {
            this.open = false;
            $dispatch('modal-closed-{{ $id }}');
        }
    }"
    x-on:open-modal-{{ $id }}.window="open = true"
    x-on:close-modal-{{ $id }}.window="closeModal()"
    x-on:keydown.escape.window="if (open) closeModal()"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-99999 overflow-hidden"
    aria-labelledby="{{ $id }}-title"
    role="dialog"
    aria-modal="true"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
        @click="closeModal()"
    ></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="relative w-full {{ $maxWidthClasses[$maxWidth] ?? 'sm:max-w-lg' }} bg-white dark:bg-gray-900 rounded-xl shadow-theme-xl flex flex-col"
            style="max-height: 75vh;"
        >
            {{-- Header (Fixed) --}}
            @if(isset($header))
            <div class="flex-shrink-0 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                {{ $header }}
            </div>
            @endif

            {{-- Body (Scrollable with hidden scrollbar) --}}
            <div class="flex-1 overflow-y-auto px-6 py-5 no-scrollbar">
                {{ $slot }}
            </div>

            {{-- Footer (Fixed) --}}
            @if(isset($footer))
            <div class="flex-shrink-0 px-6 pb-5">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>
