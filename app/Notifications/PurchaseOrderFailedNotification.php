<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseOrderFailedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $organization;    
    public $errorMessage;

    public function __construct($organization, $errorMessage)
    {
        $this->organization = $organization;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Purchase Order Processing Failed')
            ->greeting('Hello,')
            ->line("Purchase order processing failed for organization: {$this->organization->name}")
            ->line("Message: {$this->errorMessage}")
            ->line('Please review the issue and take necessary action.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
