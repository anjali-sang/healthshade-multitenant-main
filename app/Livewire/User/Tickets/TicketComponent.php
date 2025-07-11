<?php

namespace App\Livewire\User\Tickets;

use App\Notifications\CloseTicketNotification;
use App\Notifications\TicketsNotification;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class TicketComponent extends Component
{
    use WithFileUploads;

    public $ticket_id;
    public $module_id;
    public $type;
    public $module;
    public $description;
    public $priority;
    public $message;
    public $tags;
    public $organization_id;
    public $isEditing = false;
    public $status;
    public $creator;
    public $created_at;
    public $updated_at;
    public $images = [];

    public $note;

    public $moduleList = [];
    public $typeOptions = ['Question', 'Bug', 'Suggestion'];
    public $priorityOptions = ['Low', 'Medium', 'High', 'Critical'];

    protected $listeners = ['edit' => 'editTicket'];
    public $selectedImage;
    public $selectedImageCaption;
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

    public function createTicket()
    {
        $this->validate();

        $user = auth()->user();
        $uploadedImages = $this->handleImageUploads();

        $ticket = Ticket::create([
            'images' => json_encode($uploadedImages),
            'creator' => $user->id,
            'module' => $this->module_id,
            'type' => $this->type,
            'priority' => $this->priority,
            'status' => 'Open',
            'description' => $this->message,
            'message' => $this->message,
            'tags' => $this->tags,
            'organization_id' => $user->organization_id,
        ]);

        $this->dispatch('close-modal', 'add-ticket-modal');
        $this->dispatch('pg:eventRefresh-ticket-list-9cy8yv-table');

        $user->notify(new TicketsNotification($ticket));

        $this->resetForm();
    }
    private function handleImageUploads(): array
    {
        $uploadedImages = [];
        $imageFields = ['image1', 'image2', 'image3', 'image4', 'image5'];

        foreach ($imageFields as $field) {
            if ($this->$field && $this->$field->isValid()) {
                try {
                    $path = $this->$field->store('tickets', 'public');
                    $uploadedImages[] = $path;
                } catch (\Exception $e) {
                    \Log::warning("Failed to upload $field: " . $e->getMessage());
                }
            }
        }

        return $uploadedImages;
    }
    public function updateTicket()
    {
        $this->validate();

        $ticket = Ticket::findOrFail($this->ticket_id);
        $uploadedImages = [];

        foreach ($this->images as $image) {
            if ($image && is_object($image)) {
                $uploadedImages[] = $image->store('tickets', 'public');
            }
        }

        $updateData = [
            'module_id' => $this->module_id,
            'type' => $this->type,
            'priority' => $this->priority,
            'message' => $this->message,
            'tags' => $this->tags,
        ];

        if (!empty($uploadedImages)) {
            $updateData['images'] = json_encode($uploadedImages);
        }

        $ticket->update($updateData);

        $this->resetForm();
        $this->dispatch('close-modal', 'add-ticket-modal');
        $this->dispatch('refreshTickets');

        session()->flash('success', 'Ticket updated successfully.');
    }

    public function resetForm()
    {
        $this->reset(['ticket_id', 'module_id', 'type', 'priority', 'message', 'tags', 'isEditing', 'image1', 'image2', 'image3', 'image4', 'image5']);
    }

    public function removeImage($imageProperty)
    {
        $this->$imageProperty = null;
    }

    public function submitForm()
    {
        if ($this->isEditing) {
            $this->updateTicket();
        } else {
            $this->createTicket();
        }
    }

    #[On('view-user-ticket')]
    public function viewTicket($rowId)
    {
        $ticket = Ticket::findOrFail($rowId);

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
        $this->note = $ticket->note;

        // Handle images - they're stored as JSON in the database
        $this->images = $ticket->images;

        $this->dispatch('open-modal', 'show-ticket-modal');
    }

    public function close($ticketId)
    {
        $user = auth()->user();
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->status = 'Closed';
        $ticket->save();
        $this->dispatch('pg:eventRefresh-ticket-list-9cy8yv-table');
        $user->notify(new CloseTicketNotification($ticket));
        $this->dispatch('close-modal', 'show-ticket-modal');
    }

    public function render()
    {
        return view('livewire.user.tickets.ticket-component', [
            'typeOptions' => $this->typeOptions,
        ]);
    }
}