<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFeedbackNotification extends Notification
{
    use Queueable;

    public $feedback;
    /**
     * Create a new notification instance.
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // public function via(object $notifiable): array
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Feedback Submitted')
                    ->line('New feedback has been submitted by {$this->feedback->name}.')
                    ->action('Notification Action', url('/feedbacks'))
                    ->line('Thank you for keeping track of the feedback!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    public function toArray($notifiable): array
    {
        return [
            'feedback_id' => $this->feedback->id,
            'feedback' => $this->feedback->feedback,
            'name' => $this->feedback->name,
        ];
    }
}
