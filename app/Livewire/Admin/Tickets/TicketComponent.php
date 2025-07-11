<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Ticket;
use App\Notifications\CloseTicketNotification;
use Livewire\Attributes\On;
use Livewire\Component;

class TicketComponent extends Component
{

    public $ticket_id;
    public $module_id;
    public $type;
    public $selectedTicket;

    public $priority;
    public $message;
    public $tags;

    public $note;

    // For editing mode
    public $isEditing = false;

    // Available options
    public $moduleList = [];
    public $typeOptions = ['Question', 'Bug', 'Suggestion'];
    public $priorityOptions = ['Low', 'Medium', 'High', 'Critical'];
    protected $listeners = ['close-ticket' => 'closeTicket'];
    public $status;
    public $creator;
    public $created_at;
    public $updated_at;
    public $images;
    public $selectedImage;

    public $selectedImageCaption;

    // Simple approach - multiple individual file inputs
    public $image1, $image2, $image3, $image4, $image5;


    protected $rules = [
        'module_id' => 'required|string',
        'type' => 'required',
        'priority' => 'required',
        'message' => 'required|min:10',
        'tags' => 'nullable',
        'image1' => 'nullable|image|max:1024',
        'image2' => 'nullable|image|max:1024',
        'image3' => 'nullable|image|max:1024',
        'image4' => 'nullable|image|max:1024',
        'image5' => 'nullable|image|max:1024',
    ];

    public function getImagesProperty()
    {
        return array_filter([
            $this->image1,
            $this->image2,
            $this->image3,
            $this->image4,
            $this->image5,
        ]);
    }

    public function loadTicketDetails($data)
    {
        $this->selectedTicket = Ticket::find($data['rowId']);
        $this->dispatch('show-modal', 'ticketDetailsModal'); // Open modal
    }


    public function resetForm()
    {
        $this->reset(['ticket_id', 'module_id', 'type', 'priority', 'message', 'tags', 'isEditing']);
    }

    #[On('ticket-admin-modal')]
    public function viewTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        // Set all ticket properties
        $this->ticket_id = $ticket->id;
        $this->module_id = $ticket->module;
        $this->tags = $ticket->tags;
        $this->type = $ticket->type;
        $this->priority = $ticket->priority;
        $this->message = $ticket->message ?? $ticket->description;

        // Handle status and other fields if they exist
        $this->status = $ticket->status ?? 'Open';
        $this->creator = $ticket->creatorUser->name ?? 'Unknown';
        $this->created_at = $ticket->created_at;
        $this->updated_at = $ticket->updated_at;

        // Handle images - they're stored as JSON in the database
        $this->images = $ticket->images;

        $this->note = $ticket->note;

        $this->dispatch('open-modal', 'show-ticket-modal');
    }

    public function submitForm()
    {
        if ($this->isEditing) {
            $this->updateTicket();
        } else {
            $this->createTicket();
        }
    }

    public function closeTicketWithNote($ticketId)
    {
        $user = auth()->user();
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->status = 'Closed';
        $ticket->note = $this->note;
        $ticket->save();
        $this->dispatch('close-modal', 'show-ticket-modal');
        $this->dispatch('close-modal', 'close-ticket-modal');
        $this->dispatch('pg:eventRefresh-ticket-list-9cy8yv-table');
        $ticket->creatorUser->notify(new CloseTicketNotification($ticket));
    }

    public function render()
    {
        return view('livewire.admin.tickets.ticket-component');
    }
}
