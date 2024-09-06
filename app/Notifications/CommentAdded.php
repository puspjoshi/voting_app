<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAdded extends Notification
{
    use Queueable;

    public $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votting app: A comment was posted on your idea.')
            ->markdown('emails.comment-added',[
                'comment' => $this->comment,
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
            'comment_id' => $this->comment->id,
            'comment_body' => $this->comment->body,
            'user_avatar' => $this->comment->user->getAvatar(),
            'user_name' => $this->comment->user->name,
            'idea_id' => $this->comment->idea->id,
            'idea_slug' => $this->comment->idea->slug,
            'idea_title' => $this->comment->idea->title,
        ];
    }
}
