<div class="fixed top-24 right-4 z-100 space-y-2">
    @foreach ($notifications as $notification)
        <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }" x-init="setTimeout(() => {
                                show = false;
                                $wire.removeNotification('{{ $notification['id'] }}');
                            }, 3000)" x-show="show" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-full"
            class="{{ $notification['type'] === 'success' ? 'border-green-800 text-green-800  bg-green-300' : 'bg-red-300 border-red-800 text-red-800' }} border-l-4 x-6 py-6 px-4  shadow-lg bg-white dark:bg-gray-700">
            <p>{{ $notification['message'] }}</p>
        </div>
    @endforeach
</div>