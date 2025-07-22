<div class="max-w-4xl mx-auto px-1 lg:px-4 lg:px-8 pt-4 lg:pt-12 pb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Organization Details</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update your organization information. Fields marked with <span class="text-red-500">*</span> are required.</p>
        </div>
        
        <form wire:submit.prevent="updateOrganization">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Organization Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Organization Name <span class="text-red-500">*</span>
                        </label>
                        <input id="name" wire:model="name" type="text" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1" />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input id="email" wire:model="email" type="email" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1" required />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                        <input id="phone" wire:model="phone" type="tel" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1" />
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input id="city" wire:model="city" type="text" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1" required />
                    </div>

                    <!-- Address (Full Width) -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <input id="address" wire:model="address" type="text" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1" />
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="selectedCountry"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1" required>
                            <option value="">Select a Country</option>
                            @foreach (array_keys($countries) as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- State/Province -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            State/Province <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="state"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1" required>
                            <option value="">Select a State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state }}">{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Zip/Postal Code -->
                    <div>
                        <label for="pin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Zip/Postal Code <span class="text-red-500">*</span>
                        </label>
                        <input id="pin" wire:model="pin" type="text" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1" required />
                    </div>

                    <!-- Logo Upload (Full Width) -->
                    <div class="md:col-span-2">
                        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Organization Logo <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="flex items-start gap-4">
                            <!-- File Input -->
                            <div class="flex-1">
                                <input type="file" id="logo" wire:model="logo"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-lt file:text-primary-dk hover:file:bg-primary-md hover:file:text-white file:transition-colors">
                                @error('logo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Logo Preview -->
                            @if ($logo || !empty($organization?->image))
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-lg border-2 border-gray-200 dark:border-gray-600 overflow-hidden bg-gray-50 dark:bg-gray-700">
                                        @if ($logo)
                                            <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-cover" alt="Logo preview">
                                        @elseif (!empty($organization?->image))
                                            <img src="{{ asset('storage/' . $organization->image) }}" class="w-full h-full object-cover" alt="Current logo">
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">
                                        {{ $logo ? 'New' : 'Current' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Update Button -->
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" 
                        class="px-6 py-2 bg-primary-md hover:bg-primary-dk text-white rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2">
                        Update Organization
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>