<x-modal name="customer-modal" width="w-full" maxWidth="6xl"
    class="z-[9999] fixed inset-0 flex items-center justify-center">
        <!-- Header with gradient -->
        <div class="flex items-center justify-between px-8 py-6 bg-gradient-to-r from-primary-md to-primary-lt text-white">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold">{{ $isEditing ? 'Edit Customer' : 'Add New Customer'}}</h3>
            </div>
            <button wire:click="closeModals" 
                class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'save' }}" class="px-8 py-8">
            <div class="space-y-8">
                <!-- Personal Information Section -->
                <div class="bg-gray-100 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <div class="bg-blue-100 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        Personal Information
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Name -->
                        <div class="md:col-span-1">
                            <x-input-label for="customer_name" value="Customer Name" class="text-sm font-medium text-gray-700 mb-2" />
                            <div class="relative">
                                <x-text-input id="customer_name" 
                                    type="text" 
                                    wire:model.defer="customer_name" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                                    placeholder="Enter customer name" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            @error('customer_name') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-1">
                            <x-input-label for="customer_email" value="Email Address" class="text-sm font-medium text-gray-700 mb-2" />
                            <div class="relative">
                                <x-text-input id="customer_email" 
                                    type="email" 
                                    wire:model.defer="customer_email" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                                    placeholder="Enter email address" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                            </div>
                            @error('customer_email') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-input-label for="customer_phone" value="Phone Number" class="text-sm font-medium text-gray-700 mb-2" />
                            <div class="relative">
                                <x-text-input id="customer_phone" 
                                    type="text" 
                                    wire:model.defer="customer_phone" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                                    placeholder="Enter phone number" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                            </div>
                            @error('customer_phone') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center pt-4">
                            <div class="relative inline-flex items-center">
                                <input id="customer_is_active" 
                                    type="checkbox" 
                                    wire:model="customer_is_active"
                                    class="sr-only" />
                                <label for="customer_is_active" 
                                    class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <div class="block bg-gray-300 w-14 h-8 rounded-full transition-colors duration-200"
                                            :class="{ 'bg-blue-500': $wire.customer_is_active }"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-200"
                                            :class="{ 'transform translate-x-6': $wire.customer_is_active }"></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium text-gray-700">
                                        <span class="block">Active Customer</span>
                                        <span class="text-xs text-gray-500">Customer can place orders</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="bg-gray-100 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <div class="bg-green-100 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        Address Information
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <x-input-label for="customer_address" value="Street Address" class="text-sm font-medium text-gray-700 mb-2" />
                            <textarea id="customer_address" 
                                wire:model.defer="customer_address"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                rows="3"
                                placeholder="Enter complete address"></textarea>
                            @error('customer_address') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <x-input-label for="country" value="Country" class="text-sm font-medium text-gray-700 mb-2" />
                            <div class="relative">
                                <select wire:model.live="selectedCountry" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white">
                                    <option value="">Select a Country</option>
                                    @foreach (array_keys($countries) as $country)
                                        <option value="{{ $country }}">{{ $country }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('selectedCountry')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <x-input-label for="state" value="State/Province" class="text-sm font-medium text-gray-700 mb-2" />
                            <div class="relative">
                                <select wire:model="customer_state" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white"
                                    {{ empty($selectedCountry) ? 'disabled' : '' }}>
                                    <option value="">{{ empty($selectedCountry) ? 'Select country first' : 'Select a State' }}</option>
                                    @if(!empty($states))
                                        @foreach ($states as $state)
                                            <option value="{{ $state }}">{{ $state }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('customer_state')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <x-input-label for="customer_city" value="City" class="text-sm font-medium text-gray-700 mb-2" />
                            <div class="relative">
                                <x-text-input id="customer_city" 
                                    type="text" 
                                    wire:model.defer="customer_city" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                                    placeholder="Enter city name" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            @error('customer_city') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Pin Code -->
                        <div>
                            <x-input-label for="customer_pin_code" value="PIN/ZIP Code" class="text-sm font-medium text-gray-700 mb-2" />
                            <div class="relative">
                                <x-text-input id="customer_pin_code" 
                                    type="text" 
                                    wire:model.defer="customer_pin_code"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                                    placeholder="Enter PIN/ZIP code" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                </div>
                            </div>
                            @error('customer_pin_code') 
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p> 
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex flex-col sm:flex-row justify-end pt-8 border-t border-gray-200 mt-8 space-y-4 sm:space-y-0 sm:space-x-4">
    <button type="button" 
        wire:click="closeModals"
        class="px-6 py-3 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 font-medium">
        <span class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Cancel
        </span>
    </button>
    <button type="submit" 
        class="px-6 py-3 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 font-medium shadow-lg">
        <span class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ $isEditing ? 'Update Customer' : 'Save Customer'}}
        </span>
    </button>
</div>

        </form>
</x-modal>