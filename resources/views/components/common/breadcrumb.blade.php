@props(['pageTitle' => 'Dashboard'])

<div class="mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            {{ $pageTitle }}
        </h2>

        <nav>
            <ol class="flex items-center gap-1.5 text-sm">
                {{ $slot }}
            </ol>
        </nav>
    </div>
</div>
