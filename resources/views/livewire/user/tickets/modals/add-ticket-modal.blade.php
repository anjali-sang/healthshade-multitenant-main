<!-- Add/Edit Ticket Modal -->
<x-modal name="add-ticket-modal" width="w-100" height="h-auto" maxWidth="2xl">
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-6">
            {{ $isEditing ? __('Edit Ticket') : __('Add New Ticket') }}
        </h2>
        <form wire:submit.prevent="createTicket" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Module -->
                <div>
                    <x-input-label for="module_id" :value="__('Module')" class="mb-1" />
                    <select wire:model="module_id" class="border-gray-300 rounded-md shadow-sm w-full">
                        <option value="">{{ __('Select Module') }}</option>
                        <option value="Purchasing">Purchasing</option>
                        <option value="Receiving">Receiving</option>
                        <option value="Picking">Picking</option>
                        <option value="Setting">Settings</option>
                        <option value="Setting">Permissions</option>
                        <option value="Setting">Others</option>
                    </select>
                    @error('module_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>

                <!-- Tags -->
                <div>
                    <x-input-label for="tags" :value="__('Tags')" class="mb-1" />
                    <x-text-input wire:model="tags" type="text" class="block w-full"
                        placeholder="Enter comma separated tags" />
                    @error('tags')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>

                <!-- Type -->
                <div>
                    <x-input-label for="type" :value="__('Type')" />
                    <select wire:model="type" class="border-gray-300 rounded-md shadow-sm w-full">
                        <option value="">{{ __('Select Type') }}</option>
                        @foreach ($typeOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('type')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>

                <!-- Priority -->
                <div>
                    <div class="flex items-center mb-1">
                        <x-input-label for="priority" :value="__('Priority')" />
                        <div class="ml-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <select wire:model="priority" class="border-gray-300 rounded-md shadow-sm w-full">
                        <option value="">{{ __('Select Priority') }}</option>
                        <option value="Critical">Critical</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                    @error('priority')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Message -->
            <div class="mt-6">
                <x-input-label for="message" :value="__('Your message')" class="mb-1" />
                <textarea wire:model="message" rows="5" class="border-gray-300 rounded-md shadow-sm w-full"
                    placeholder="Mention your query here..."></textarea>
                @error('message')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- File Upload Section -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Upload Images (Optional - Up to 5 images)
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Image 1 -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <input type="file" wire:model="image1" accept="image/*" class="mb-2 text-sm">
                        @error('image1')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        @if ($image1)
                            <div class="mt-2 relative">
                                <img src="{{ $image1->temporaryUrl() }}" class="w-full h-20 object-cover rounded">
                                <button type="button" wire:click="removeImage('image1')" 
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs">×</button>
                            </div>
                        @endif
                        <div wire:loading wire:target="image1" class="text-xs text-blue-600">Uploading...</div>
                    </div>

                    <!-- Image 2 -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <input type="file" wire:model="image2" accept="image/*" class="mb-2 text-sm">
                        @error('image2')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        @if ($image2)
                            <div class="mt-2 relative">
                                <img src="{{ $image2->temporaryUrl() }}" class="w-full h-20 object-cover rounded">
                                <button type="button" wire:click="removeImage('image2')"
                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs">×</button>
                            </div>
                        @endif
                        <div wire:loading wire:target="image2" class="text-xs text-blue-600">Uploading...</div>
                    </div>

                    <!-- Image 3 -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <input type="file" wire:model="image3" accept="image/*" class="mb-2 text-sm">
                        @error('image3')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        @if ($image3)
                            <div class="mt-2 relative">
                                <img src="{{ $image3->temporaryUrl() }}" class="w-full h-20 object-cover rounded">
                                <button type="button" wire:click="removeImage('image3')"
                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs">×</button>
                            </div>
                        @endif
                        <div wire:loading wire:target="image3" class="text-xs text-blue-600">Uploading...</div>
                    </div>

                    <!-- Image 4 -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <input type="file" wire:model="image4" accept="image/*" class="mb-2 text-sm">
                        @error('image4')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        @if ($image4)
                            <div class="mt-2 relative">
                                <img src="{{ $image4->temporaryUrl() }}" class="w-full h-20 object-cover rounded">
                                <button type="button" wire:click="removeImage('image4')"
                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs">×</button>
                            </div>
                        @endif
                        <div wire:loading wire:target="image4" class="text-xs text-blue-600">Uploading...</div>
                    </div>

                    <!-- Image 5 -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <input type="file" wire:model="image5" accept="image/*" class="mb-2 text-sm">
                        @error('image5')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        @if ($image5)
                            <div class="mt-2 relative">
                                <img src="{{ $image5->temporaryUrl() }}" class="w-full h-20 object-cover rounded">
                                <button type="button" wire:click="removeImage('image5')"
                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs">×</button>
                            </div>
                        @endif
                        <div wire:loading wire:target="image5" class="text-xs text-blue-600">Uploading...</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <x-secondary-button type="button" wire:click="$dispatch('close-modal', 'add-ticket-modal')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button type="submit" wire:loading.attr="disabled" wire:target="createTicket">
                    <span wire:loading.remove wire:target="createTicket">
                        {{ $isEditing ? __('Update Ticket') : __('Create Ticket') }}
                    </span>
                    <span wire:loading wire:target="createTicket" class="flex items-center">
                        {{ __('Processing...') }}
                    </span>
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>