<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationComponent extends Component
{
    public $notifications = [];

    public function addNotification($message, $type = 'success')
    {
        // Prepend new notifications to the top of the array
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type,
        ]);
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    #[On('show-notification')]
    public function handleNotification($message, $type = 'success')
    {
        $this->addNotification($message, $type);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(
            array_filter($this->notifications, function ($notification) use ($id) {
                return $notification['id'] !== $id;
            }),
        );
    }

    public function render()
    {
        return view('livewire.notification.notification-component');
    }
}