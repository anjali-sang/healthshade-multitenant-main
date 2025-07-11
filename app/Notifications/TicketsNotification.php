<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketsNotification extends Notification
{
    use Queueable;

    public $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
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
            ->subject('🎫 New Support Ticket Created - #' . $this->ticket->id)
            ->cc(config('app.email'))
            ->view('emails.ticket-created', [
                'ticket' => $this->ticket,
                'user' => $notifiable
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_type' => $this->ticket->type,
            'ticket_priority' => $this->ticket->priority,
            'created_at' => $this->ticket->created_at,
            'user_name' => $notifiable->creatorUser->name ?? 'N/A',
            'organization' => $notifiable->organization->name ?? 'N/A',
        ];
    }
}