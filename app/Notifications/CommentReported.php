<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CommentReported extends Notification
{
    use Queueable;

    protected Report $report;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Report  $report
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
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
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('admin.comments.index', ['comment_id' => $this->report->comment_id]);

        $reasonMap = [
            'spam' => '垃圾/廣告訊息',
            'offensive' => '冒犯性內容',
            'inappropriate' => '不恰當內容',
            'other' => '其他原因'
        ];

        $reasonText = $reasonMap[$this->report->reason] ?? $this->report->reason;

        return (new MailMessage)
            ->subject('潛水社網站有評論被舉報')
            ->line('用戶 ' . $this->report->user->name . ' 舉報了一則評論')
            ->line('舉報原因: ' . $reasonText)
            ->line('評論內容: "' . Str::limit($this->report->comment->content, 100) . '"')
            ->line('評論作者: ' . $this->report->comment->user->name)
            ->line('在活動: ' . $this->report->comment->activity->title)
            ->when($this->report->details, function ($message) {
                return $message->line('舉報詳情: ' . $this->report->details);
            })
            ->action('查看並處理舉報', $url)
            ->line('請盡快審核此舉報內容。');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'reporter_name' => $this->report->user->name,
            'comment_id' => $this->report->comment_id,
            'comment_content' => Str::limit($this->report->comment->content, 50),
            'comment_author' => $this->report->comment->user->name,
            'activity_title' => $this->report->comment->activity->title,
            'reason' => $this->report->reason,
        ];
    }
}
