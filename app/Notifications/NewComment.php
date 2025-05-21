<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;
    
    protected $comment;
    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;//
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
        $url = route('admin.comments.index', ['comment_id' => $this->comment->id]);
        
        return (new MailMessage)
                    ->subject('潛水社網站有新評論')
                    ->line('用戶 ' . $this->comment->user->name . ' 發表了新評論：')
                    ->line('"' . \Str::limit($this->comment->content, 100) . '"')
                    ->line('在活動：' . $this->comment->activity->title)
                    ->action('查看評論', $url)
                    ->line('您可以審核、編輯或刪除此評論。');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'user_name' => $this->comment->user->name,
            'activity_title' => $this->comment->activity->title,
            'excerpt' => \Str::limit($this->comment->content, 50),
        ];
    }
}
