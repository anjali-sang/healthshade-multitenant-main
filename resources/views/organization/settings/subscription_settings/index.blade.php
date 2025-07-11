<x-app-layout>
    @if ($currentSubscription['name'] != 'NA')
        <div class="max-w-screen-md mx-auto mb-12">
            <div
                class="relative flex flex-col p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden">
                <!-- Responsive Badge + Heading -->
                <div class="text-center flex flex-col md:block md:relative">
                    <!-- Badge (mobile view) -->
                    <div class="mb-2 md:hidden">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full">
                            Active Plan
                        </span>
                    </div>

                    <!-- Badge (desktop absolute) -->
                    <div class="hidden md:block absolute top-0 right-0 mt-4 mr-4">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full">
                            Active Plan
                        </span>
                    </div>

                    <!-- Heading -->
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        Your Current Plan:
                        <span class="text-primary-md dark:text-primary-light">
                            {{ ucfirst($currentSubscription['name']) }}
                        </span>
                    </h3>
                </div>
                <div class="text-center">
                    <div class="flex items-baseline justify-center mb-4">
                        <span
                            class="text-3xl font-extrabold text-gray-900 dark:text-white">${{ number_format($currentSubscription['price'], 2) }}</span>
                        <span class="ml-2 text-gray-500 dark:text-gray-400 text-sm">
                            /
                            {{ $currentSubscription['duration'] == 12 ? 'year' : $currentSubscription['duration'] . ' months' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-center">
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Expires On</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                <time datetime="{{ $currentSubscription['expiry_date'] }}">
                                    {{ \Carbon\Carbon::parse($currentSubscription['expiry_date'])->format('M j, Y') }}
                                </time>
                            </p>
                        </div>

                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Max Users</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $currentSubscription['max_users'] }}
                            </p>
                        </div>

                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Max Clinics</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $currentSubscription['max_locations'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Header with better spacing and typography -->
    <div class="mx-auto max-w-screen-md text-center mb-12">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-4">
            Find the right plan for you
        </h2>
        <p class="text-gray-600 dark:text-gray-400 text-lg mb-8">
            Choose a plan which fits best for your organization. All plans include core features.
        </p>

        <!-- Filter toggle -->
        <div class="inline-flex bg-gray-100 dark:bg-gray-800 p-1 rounded-xl shadow-inner mb-8">
            @foreach ([0 => 'All Plans', 3 => 'Quarterly', 6 => 'Semi-Annual', 12 => 'Annual'] as $value => $label)
                <button type="button" data-duration="{{ $value }}"
                    id="filter-{{ strtolower(str_replace(' ', '-', $label)) }}"
                    class="subscription-filter-btn px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 focus:outline-none
                                {{ $subscriptionDuration == $value ? 'bg-white dark:bg-gray-700 text-primary-md shadow-sm' : 'bg-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-750' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Subscription cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        @forelse ($subscriptions as $subscription)
            <div id="subscription-{{ $subscription->id }}" data-duration="{{ $subscription->duration }}"
                data-subscription="{{ json_encode($subscription) }}"
                class="subscription-card relative flex flex-col h-full p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">

                @if ($subscription->id == $selectedSubscription)
                    <div class="absolute top-0 right-0 mt-4 mr-4">
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full">Selected</span>
                    </div>
                @endif

                <h3 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ ucfirst($subscription->name) }}
                </h3>
                <p class="mb-6 text-gray-500 dark:text-gray-400 text-sm flex-grow">
                    {{ $subscription->description }}
                </p>

                <div class="flex items-baseline mb-6">
                    <span
                        class="text-3xl font-extrabold text-gray-900 dark:text-white">${{ $subscription->price }}</span>
                    <span class="ml-2 text-gray-500 dark:text-gray-400 text-sm">
                        {{ $subscription->duration == '12' ? '/year' : '/' . $subscription->duration . ' months' }}
                    </span>
                </div>

                <ul role="list" class="mb-8 space-y-3 text-left">
                    @php
                        $features = [
                            'Individual configuration',
                            'No setup, or hidden fees',
                            'Maximum users: <span class="font-semibold">' . $subscription->max_users . '</span>',
                            'Premium support: <span class="font-semibold">' .
                            $subscription->duration .
                            ' months</span>',
                            'Maximum clinics: <span class="font-semibold">' . $subscription->max_locations . '</span>',
                        ];
                    @endphp
                    @foreach ($features as $feature)
                        <li class="flex items-center">
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">{!! $feature !!}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-auto">
                    @if ($subscription->id == $selectedSubscription)
                        <button type="button"
                            class="w-full py-3 px-6 font-medium text-center text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-lg cursor-default">
                            Current Selection
                        </button>
                    @else
                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                            <input type="hidden" name="subscription_price" value="{{ $subscription->price }}">
                            <button type="submit"
                                class="w-full py-3 px-6 font-medium text-center text-white bg-primary-md hover:bg-primary-dk focus:ring-4 focus:ring-primary-200 rounded-lg transition-all duration-300 dark:focus:ring-primary-900">
                                Select This Plan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty

            <div class="col-span-3 text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No plans available</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Please check back later or contact support.</p>
            </div>
        @endforelse
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.subscription-filter-btn');
            const subscriptionCards = document.querySelectorAll('.subscription-card');

            // Filter logic
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const selectedDuration = this.dataset.duration;

                    // Toggle button styles
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-white', 'dark:bg-gray-700',
                            'text-primary-md', 'shadow-sm');
                        btn.classList.add('bg-transparent', 'text-gray-700',
                            'dark:text-gray-300');
                        btn.setAttribute('aria-pressed', 'false');
                    });

                    this.classList.remove('bg-transparent', 'text-gray-700', 'dark:text-gray-300');
                    this.classList.add('bg-white', 'dark:bg-gray-700', 'text-primary-md',
                        'shadow-sm');
                    this.setAttribute('aria-pressed', 'true');

                    // Filter cards
                    subscriptionCards.forEach(card => {
                        const cardDuration = card.dataset.duration;
                        if (selectedDuration === '0' || selectedDuration === cardDuration) {
                            card.style.display = 'flex';
                            card.classList.remove('opacity-0');
                            card.classList.add('opacity-100', 'transition-opacity',
                                'duration-300');
                        } else {
                            card.classList.remove('opacity-100');
                            card.classList.add('opacity-0');
                            setTimeout(() => {
                                card.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            });

            // Plan selection logic
            const selectPlanButtons = document.querySelectorAll('.select-plan-btn');
            selectPlanButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const subscriptionId = this.dataset.subscriptionId;
                    const token = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');
                    const originalText = this.textContent;

                    // Prevent multiple clicks
                    if (this.disabled) return;

                    this.textContent = 'Processing...';
                    this.disabled = true;

                    try {
                        const res = await fetch('/checkout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token || ''
                            },
                            body: JSON.stringify({
                                subscription_id: subscriptionId
                            })
                        });

                        const data = await res.json();
                        if (res.ok && data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Could not select plan.');
                        }
                    } catch (err) {
                        console.error(err);
                        alert('An error occurred. Please try again later.');
                    } finally {
                        this.textContent = originalText;
                        this.disabled = false;
                    }
                });
            });
        });
    </script>

</x-app-layout>
