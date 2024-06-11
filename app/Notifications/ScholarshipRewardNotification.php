<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ScholarshipCategory;
use App\Models\User;

class ScholarshipRewardNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $category;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, ScholarshipCategory $category)
    {
        $this->user = $user;
        $this->category = $category;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Scholarship Reward Received')
                    ->greeting('Congratulations ' . $this->user->profile->full_name . '!')
                    ->line('You have received your scholarship reward for the ' . $this->category->name . ' category.')
                    ->line('We are proud of your achievements and wish you all the best in your future endeavors.')
                    ->action('View Scholarship', url('/scholarships'))
                    ->line('Thank you for being part of our community!');
    }
}