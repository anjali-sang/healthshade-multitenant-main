<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    public $email;
    public $password;

    /**
     * Create a new notification instance.
     */
    
    public function __construct($email, $password)
     {
        $this->email = $email;
        $this->password = $password;
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
        ->subject('Your Account Has Been Created')
        ->greeting('Hello ' . $notifiable->name . ',')
        ->line('Your account has been created successfully.')
        ->line('Email: ' . $this->email)
        ->line('Password: ' . $this->password)
        ->action('Login Here', url('/login'))
        ->line('Please change your password after login for security.');
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
