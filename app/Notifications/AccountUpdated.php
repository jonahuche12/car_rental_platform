<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Account Details Updated')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your account details have been successfully updated.')
                    ->line('If you did not make this change, please contact our support team immediately.')
                    ->line('Thank you for using our application!');
    }
}
