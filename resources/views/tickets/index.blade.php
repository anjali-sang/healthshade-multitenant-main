<x-app-layout>
    @if (auth()->user()->role_id == '1')
        <livewire:admin.tickets.ticket-component />
    @else
        <livewire:user.tickets.ticket-component />
    @endif
    <!-- Image Modal - Improved design and animations -->
</x-app-layout>