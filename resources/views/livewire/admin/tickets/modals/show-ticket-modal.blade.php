<!-- View Ticket Modal -->
<x-modal name="show-ticket-modal" width="w-full" height="h-auto" maxWidth="4xl" class="p-0">
    <div class="relative">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">
                            {{ __('Ticket Details') }}
                        </h2>
                        <p class="text-blue-100 text-sm">
                            {{ __('View complete ticket information') }}
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="$dispatch('close-modal', 'show-ticket-modal')"
                    class="text-blue-100 hover:text-white transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 max-h-[80vh] overflow-y-auto">
            <!-- Status and Priority Badges -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <!-- Status Badge -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if(isset($status))
                                @if($status === 'Open') bg-green-100 text-green-800
                                @elseif($status === 'In Progress') bg-yellow-100 text-yellow-800
                                @elseif($status === 'Closed') bg-gray-100 text-gray-800
                                    @else bg-blue-100 text-blue-800
                                @endif
                            @else bg-blue-100 text-blue-800
                        @endif">
                        <svg class="w-2 h-2 mr-1 fill-current" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        {{ $status ?? 'Open' }}
                    </span>

                    <!-- Priority Badge -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($priority === 'Critical') bg-red-100 text-red-800
                        @elseif($priority === 'High') bg-orange-100 text-orange-800
                        @elseif($priority === 'Medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                        @endif">
                        @if($priority === 'Critical')
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        @elseif($priority === 'High')
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="w-2 h-2 mr-1 fill-current" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                        @endif
                        {{ $priority }}
                    </span>

                    <!-- Type Badge -->
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 hidden">
                        @if($type === 'Bug')
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v2a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zM4 4a4 4 0 014-4h4a4 4 0 014 4v2a4 4 0 01-4 4H8a4 4 0 01-4-4V4z"
                                    clip-rule="evenodd" />
                            </svg>
                        @elseif($type === 'Question')
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                        {{ $type }}
                    </span>
                </div>

                <!-- Created Date (if available) -->
                @if(isset($created_at))
                    <div class="text-sm text-gray-500">
                        {{ __('Created') }}: {{ \Carbon\Carbon::parse($created_at)->format('M d, Y \a\t g:i A') }}
                    </div>
                @endif
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Ticket Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Module and Tags -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <x-input-label for="module_id" :value="__('Module')"
                                class="mb-2 text-gray-700 font-semibold" />
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span class="text-gray-800 font-medium">{{ $module_id ?: __('Not specified') }}</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <x-input-label for="tags" :value="__('Tags')" class="mb-2 text-gray-700 font-semibold" />
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                                @if($tags)
                                    @foreach(explode(',', $tags) as $tag)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500 italic">{{ __('No tags') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                     <!-- Message -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <x-input-label for="message" :value="__('Description')"
                            class="mb-3 text-gray-700 font-semibold" />
                        <div class="prose max-w-none">
                            <div class="text-blue-600 leading-relaxed">
                                {{ $message ?: __('No description provided') }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <x-input-label for="message" :value="__('Resolution')"
                            class="mb-3 text-gray-700 font-semibold" />
                        <div class="prose max-w-none">
                            <div class="text-green-600">
                                {{ $note ?: __('No resolution provided yet.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Additional Info -->
                <div class="space-y-4">
                    <!-- Ticket Metadata -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('Ticket Information') }}
                        </h3>
                        <div class="space-y-3 text-sm">
                            @if(isset($creator))
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Created by') }}:</span>
                                    <span class="font-medium text-gray-800">{{ $creator }}</span>
                                </div>
                            @endif
                            @if(isset($ticket_id))
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Ticket ID') }}:</span>
                                    <span class="font-mono text-gray-800">#{{ $ticket_id }}</span>
                                </div>
                            @endif
                            @if(isset($updated_at))
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Last Updated') }}:</span>
                                    <span
                                        class="text-gray-800">{{ \Carbon\Carbon::parse($updated_at)->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions (if needed) -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-blue-800 mb-3">{{ __('Quick Actions') }}</h3>
                        <div class="space-y-2">
                            @if ($status != 'Closed')
                                <button type="button" wire:click="$dispatch('open-modal', 'close-ticket-modal')"
                                    class="w-full text-left px-3 py-2 text-sm text-red-700 hover:bg-red-50 rounded-md transition-colors duration-200 flex items-center">
                                   {{ __('Close Ticket')}}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attached Images Section -->
            @php
                $ticketImages = [];
                if (isset($images) && is_string($images)) {
                    $ticketImages = json_decode($images, true) ?: [];
                } elseif (isset($images) && is_array($images)) {
                    $ticketImages = $images;
                }
            @endphp

            @if(!empty($ticketImages))
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('Attached Images') }}</h3>
                        <span class="ml-2 text-sm text-gray-500">({{ count($ticketImages) }}
                            {{ count($ticketImages) === 1 ? __('image') : __('images') }})</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($ticketImages as $index => $image)
                            <div
                                class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="aspect-w-16 aspect-h-12">
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ __('Attachment') }} {{ $index + 1 }}"
                                        class="w-full h-32 object-cover cursor-pointer hover:opacity-90 transition-opacity duration-200"
                                        onclick="openImageModal('{{ asset('storage/' . $image) }}')" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    {{ __('All information is read-only in this view') }}
                </div>
                <div class="flex space-x-3">
                    <x-secondary-button type="button" wire:click="$dispatch('close-modal', 'show-ticket-modal')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ __('Close') }}
                    </x-secondary-button>
                    @if(isset($ticket_id))
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-modal>

<!-- Close Ticket with Note Modal -->
<x-modal name="close-ticket-modal" width="w-full" height="h-auto" maxWidth="2xl" class="p-0">
    <div class="relative">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">
                            {{ __('Close Ticket') }}
                        </h2>
                        <p class="text-red-100 text-sm">
                            {{ __('Add a closing note for ticket #') }}{{ $ticket_id ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeCloseTicketModal()"
                    class="text-red-100 hover:text-white transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Warning Message -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.314 16.5C3.544 18.333 4.506 20 6.046 20z">
                        </path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-amber-800 mb-1">{{ __('Confirm Ticket Closure') }}</h3>
                        <p class="text-sm text-amber-700">
                            {{ __('This action will permanently close the ticket. Please add a closing note to document the resolution or reason for closure.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Closing Note Form -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="closing_note" :value="__('Closing Note')"
                        class="mb-2 text-gray-700 font-semibold flex items-center" />
                    <textarea id="closing_note" wire:model="closing_note" rows="5"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                        placeholder="{{ __('Please provide details about the ticket resolution, actions taken, or reason for closure...') }}"></textarea>
                    @error('closing_note')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Character Counter -->
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>{{ __('Add detailed information to help with future reference') }}</span>
                    <span id="char-counter">0 / 500</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    {{ __('This action cannot be undone') }}
                </div>
                <div class="flex space-x-3">
                    <x-secondary-button type="button" onclick="closeCloseTicketModal()">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-danger-button type="button" wire:click="closeTicketWithNote({{ $ticket_id ?? 0 }})">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Close Ticket') }}
                    </x-danger-button>
                </div>
            </div>
        </div>
    </div>
</x-modal>

<!-- Image Modal (unchanged) -->
<div id="imageModal"
    class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-75 transition-opacity duration-200">
    <div
        class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-3xl w-full mx-4 overflow-hidden transform transition-transform duration-200 scale-95 opacity-0">
        <div class="absolute top-3 right-3">
            <button onclick="closeImageModal()"
                class="p-1.5 bg-white dark:bg-gray-700 rounded-full shadow hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
            <img id="modalImage" class="max-w-full max-h-[75vh] object-contain" src="" alt="Product Image">
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Close ticket modal functions
        window.openCloseTicketModal = function () {
            //console.log('clicked');
            Livewire.dispatch('open-modal', 'close-ticket-modal');
        }

        window.closeCloseTicketModal = function () {
            Livewire.dispatch('close-modal', 'close-ticket-modal');
        }

        // Rest of your existing script code...
    });

    // Character counter for closing note
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('closing_note');
        const counter = document.getElementById('char-counter');

        if (textarea && counter) {
            textarea.addEventListener('input', function () {
                const length = this.value.length;
                counter.textContent = `${length} / 500`;

                if (length > 500) {
                    counter.classList.add('text-red-500');
                    counter.classList.remove('text-gray-500');
                } else {
                    counter.classList.add('text-gray-500');
                    counter.classList.remove('text-red-500');
                }
            });
        }
    });

    // Enhanced image modal logic with smooth animations
    function openImageModal(imageUrl) {
        //console.log(imageUrl);
        const modal = document.getElementById('imageModal');
        const modalContent = modal.querySelector('div');

        document.getElementById('modalImage').src = imageUrl;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Trigger animation
        setTimeout(() => {
            modal.classList.add('bg-opacity-75');
            modal.classList.remove('bg-opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        const modalContent = modal.querySelector('div');

        // Trigger animation
        modal.classList.remove('bg-opacity-75');
        modal.classList.add('bg-opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        // Hide after animation completes
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 1000);
    }

    document.getElementById('imageModal').addEventListener('click', function (event) {
        if (event.target === this) closeImageModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape')
    });
</script>

<style>
    /* Tab button styles */
    .tab-btn {
        transition-property: all;
        transition-duration: 200ms;
    }

    /* Added transition for tab content */
    .tab-content {
        transition-property: opacity;
        transition-duration: 200ms;
    }

    /* Improved modal animations */
    #imageModal {
        --tw-bg-opacity: 0;
        transition-property: all;
        transition-duration: 200ms;
    }

    /* Focus styles for better accessibility */
    button:focus {
        outline: none;
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        --tw-ring-color: rgba(var(--color-primary-md), 0.5);
    }
</style>