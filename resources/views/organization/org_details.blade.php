<section class="rounded-lg bg-primary-md p-6 dark:bg-gray-800 mt-3">
    <header class="flex justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-100 dark:text-gray-200">
                {{ __('Organization Overview') }}
            </h2>
            <p class="mt-1 text-sm text-gray-200 dark:text-gray-200">
                {{ __("Your organization's information and other basic details.") }}
            </p>
        </div>
        <div class="flex items-center justify-start">
            @if (!empty($org?->image))
                <img src="{{ asset('storage/' . $org->image) }}" alt="Organization Logo"
                    class="w-20 h-20 object-cover rounded shadow">
            @else

            @endif
        </div>
    </header>
    <div class="pt-2">
        <div class="mx-auto max-w-screen-2xl pt-6 grid grid-cols-1 lg:grid-cols-3 lg:gap-8">
            <!-- First Column: Image -->
            <div class="bg-primary-dk p-3 rounded-lg">
                <h1 class="text-3xl text-white dark:text-white mb-2 font-bold">{{ $org->name }}</h1>
                <div class="text-white dark:text-white">
                    <p>
                        <span>{{ $org->email }}</span>
                    </p>
                    @if ($org->phone)
                        <p>{{ __('Contact Number') }}:
                            <span class="{{ $org->phone ?? 'text-red-600' }}">{{ $org->phone ?? 'Pending' }}</span>
                        </p>
                    @endif
                    <p>
                        <span>{{ $org->city }}, {{ $org->state }}</span>
                    </p>
                    </p>
                </div>
            </div>
            <!-- Second Column: Name and Address -->
            <div class="flex flex-col items-start justify-end">
                {{-- <div class="bg-gray-800 inline-flex rounded-lg px-3 py-1 text-white">2 MEMBERS</div>
                <div class="bg-gray-800 inline-flex rounded-lg px-3 py-1 text-white">2 LOCATIONS</div> --}}
            </div>
            <div class="flex items-end justify-end">
                <p class="flex items-center justify-center text-sm text-gray-100 dark:text-gray-200">
                    {{ __('Your Plan expires on') }}
                </p>
                <h2 class="text-sm font-medium bg-primary-dk text-gray-200 dark:text-gray-200 p-1 rounded-lg">
                    {{ $org && $org->subscription_valid
    ? \Carbon\Carbon::parse($org->subscription_valid)->format(session('date_format', 'F d, Y') . ' ' . session('time_format', 'h:ia'))
    : 'N/A'
                }}
                </h2>
                </div>
                
            </div>
        </div>
    </div>
</section>