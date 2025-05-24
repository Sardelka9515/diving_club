<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationRejected extends Notification
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
            ->subject('您的活動報名未獲通過')
            ->greeting('您好，' . $notifiable->name)
            ->line('很遺憾地通知您，您對以下活動的報名未獲通過：')
            ->line($activity->title)
            ->line('若有任何疑問，請聯繫我們的客服人員。')
            ->action('查看其他活動', url('/activities'))
            ->line('感謝您的支持！');
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
            'type' => 'registration_rejected',
            'message' => '您報名的活動「' . $activity->title . '」未獲通過。'
        ];
    }
}
