<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationApproved extends Notification
{
    use Queueable;

    protected $registration;

    /**
     * Create a new notification instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $activity = $this->registration->activity;

        return (new MailMessage)
            ->subject('您的活動報名已獲核准')
            ->greeting('您好，' . $notifiable->name)
            ->line('您對以下活動的報名已獲核准：')
            ->line($activity->title)
            ->line('活動時間: ' . $activity->start_date->format('Y年m月d日 H:i') . ' ~ ' . $activity->end_date->format('Y年m月d日 H:i'))
            ->line('活動地點: ' . $activity->location)
            ->action('查看活動詳情', url('/activities/' . $activity->id))
            ->line('感謝您的參與！');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $activity = $this->registration->activity;

        return [
            'activity_id' => $activity->id,
            'activity_title' => $activity->title,
            'type' => 'registration_approved',
            'message' => '您報名的活動「' . $activity->title . '」已獲核准。'
        ];
    }
}
