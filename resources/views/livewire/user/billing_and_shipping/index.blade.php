<x-app-layout>
    <div class="py-12">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Billing Information') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Enter billing values for each supplier and but only for required location.") }}
                    </p>
                </header>
                <form method="post" action="{{ route('billing.update', ['organization_id' => $organization_id]) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('post')
                    {{-- Table Section --}}
                    <div>
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr class="text-gray-700 dark:text-gray-200">
                                    <th class="p-3 text-sm font-semibold border-b dark:border-gray-600">Default</th>
                                        <th class="p-3 text-sm font-semibold border-b dark:border-gray-600">Location</th>
                                            @foreach ($suppliers as $supplier)
                                                <th class="p-3 text-sm font-semibold border-b dark:border-gray-600">
                                                    {{ $supplier->supplier_name }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($locations as $location)
                                            @php
                                                // Check if this location is the default one
                                                $isDefault = $location->is_default ?? false;
                                                @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        {{-- Default Location Radio Button --}}
                        <td class="p-3 text-center">
                            <input type="radio" name="default_location" value="{{ $location->id }}"
                                {{ $isDefault ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 focus:ring focus:ring-blue-200">
                        </td>

                        {{-- Location Name --}}
                        <td class="p-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $location->name }}
                        </td>

                        @foreach ($suppliers as $supplier)
                            @php
                                $key = $location->id . '-' . $supplier->id;
                                $existingValue = $billingData[$key][0]->bill_to ?? '';
                            @endphp
                            <td class="p-3 text-sm">
                                {{-- Make input non-editable if user is not admin --}}
                                @if ($user->role_id == 1)
                                    <input type="text" name="billingData[{{ $location->id }}][{{ $supplier->id }}]"
                                        value="{{ old("billingData.$location->id.$supplier->id", $existingValue) }}"
                                        class="w-full px-2 py-1 border rounded-md focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200"
                                        placeholder="#bill to">
                                @else
                                    <input type="text" value="{{ $existingValue }}" 
                                        class="w-full px-2 py-1 border rounded-md bg-gray-200 dark:bg-gray-600 text-gray-500 cursor-not-allowed" 
                                        disabled>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Show Message if No Data Exists --}}
        @if ($locations->isEmpty() || $suppliers->isEmpty())
            <div class="p-4 text-center text-gray-600 dark:text-gray-300">
                No billing details available.
            </div>
        @endif
        </div>
        @if ($user->role_id == 1)
            <div class="flex flex-end items-center justify-end gap-4">
                <x-primary-button>{{ __('Update') }}</x-primary-button>
            </div>
        @endif
</form>

            </div>
        </div>
    </div>
    <div class="py-6">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Shipping Information') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Enter shipping values for required supplier and but only for required location.") }}
                    </p>
                </header>
                <form method="post" action="{{ route('shipping.update', ['organization_id' => $organization_id]) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('post')
                    {{-- Table Section --}}
                    <div>
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr class="text-gray-700 dark:text-gray-200">
                                    <th class="p-3 text-sm font-semibold border-b dark:border-gray-600">Location</th>
                                    @foreach ($suppliers as $supplier)
                                        <th class="p-3 text-sm font-semibold border-b dark:border-gray-600">
                                            {{ $supplier->supplier_name }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($locations as $location)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="p-3 text-sm font-medium text-gray-900 dark:text-gray-100">
            {{ $location->name }}
        </td>
        @foreach ($suppliers as $supplier)
            @php
                $key = $location->id . '-' . $supplier->id;
                $existingValue = $shippingData[$key][0]->ship_to ?? '';
            @endphp
            <td class="p-3 text-sm">
                {{-- If user is admin, allow editing, else disable input --}}
                @if ($user->role_id == 1)
                    <input type="text" name="shippingData[{{ $location->id }}][{{ $supplier->id }}]"
                        value="{{ old("shippingData.$location->id.$supplier->id", $existingValue) }}"
                        class="w-full px-2 py-1 border rounded-md focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-200"
                        placeholder="#ship to">
                @else
                    <input type="text" value="{{ $existingValue }}" 
                        class="w-full px-2 py-1 border rounded-md bg-gray-200 dark:bg-gray-600 text-gray-500 cursor-not-allowed" 
                        disabled>
                @endif
            </td>
        @endforeach
    </tr>
@endforeach

                            </tbody>
                        </table>
                        {{-- Show Message if No Data Exists --}}
                        @if ($locations->isEmpty() || $suppliers->isEmpty())
                            <div class="p-4 text-center text-gray-600 dark:text-gray-300">
                                No Shipping details available.
                            </div>
                        @endif
                    </div>
                    @if ($user->role_id == 1)
                    <div class="flex flex-end items-center justify-end gap-4">
                        <x-primary-button>{{ __('Update') }}</x-primary-button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>