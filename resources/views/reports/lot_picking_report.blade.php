<x-app-layout>
    <div class="max-w-10xl mx-auto px-4">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-5">
            <section class="w-full border-b-2 pb-4 mb-6">
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Batch(LOT#)Picking report') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('View your batch picking list below.') }}
                        </p>
                    </div>
                </header>
            </section>
            <div class="text-xs">
                <livewire:tables.reports.batch-picking-report />
            </div>
        </div>

    </div>
</x-app-layout>