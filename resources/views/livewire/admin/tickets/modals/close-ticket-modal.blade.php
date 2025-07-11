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
                <button type="button" wire:click="$dispatch('close-modal', 'close-ticket-modal')"
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
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <x-input-label for="note" :value="__('Closing Note')" 
                        class="mb-2 text-gray-700 font-semibold flex items-center" />
                    <textarea 
                        id="note"
                        wire:model="note"
                        rows="5"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                        placeholder="{{ __('Please provide details about the ticket resolution, actions taken, or reason for closure...') }}"></textarea>
                    @error('note')
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
                    <x-secondary-button type="button" wire:click="$dispatch('close-modal', 'close-ticket-modal')">
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

<script>
    // Character counter for closing note
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('closing_note');
        const counter = document.getElementById('char-counter');
        
        if (textarea && counter) {
            textarea.addEventListener('input', function() {
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
</script>