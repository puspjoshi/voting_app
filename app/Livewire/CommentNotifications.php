<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;
use Livewire\Attributes\On;

class CommentNotifications extends Component
{
    const NOTIFICATION_THRESHOLD = 20;
    public $notifications;
    public $notificationCount;
    public $isLoading;

    public function mount()
    {
        $this->notifications = collect([]);
        $this->isLoading = true;
        $this->getNotificationCount();
    }

    public function getNotificationCount()
    {
        $this->notificationCount = auth()->user()->unreadNotifications()->count();

        if ($this->notificationCount > self::NOTIFICATION_THRESHOLD) {
            $this->notificationCount = self::NOTIFICATION_THRESHOLD . '+';
        }
    }

    #[On('get-notifications')]
    public function getNotifications()
    {
        $this->notifications = auth()->user()->unreadNotifications()
            ->latest()
            ->take(self::NOTIFICATION_THRESHOLD)
            ->get();

        $this->isLoading = false;
    }

    public function markAllAsRead()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        auth()->user()->unreadNotifications->markAsRead();

        $this->getNotificationCount();
        $this->getNotifications();
    }

    public function markAsRead($notificationId)
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }
        
        $notification = DatabaseNotification::findOrFail($notificationId);
        $notification->markAsRead();

        $this->commentScrollTo($notification);
    }

    public function commentScrollTo($notification)
    {
        $idea = Idea::find($notification->data['idea_id']);
        if (! $idea) {
            session()->flash('error_message', 'This idea is no longer exits!');
            return redirect()->route('idea.index');
        }

        $comment = Comment::find($notification->data['comment_id']);
        if (! $comment) {
            session()->flash('error_message', 'This comment is no longer exits!');
            return redirect()->route('idea.index');
        }

        $comments = $idea->comments->pluck('id');
        $indexOfComment = $comments->search($comment->id);

        $page = (int) ($indexOfComment / $comment->getPerPage()) + 1;
        session()->flash('scrollToCommentTo', $comment->id);

        return redirect(route('idea.show', [
            'idea' => $notification->data['idea_slug'],
            'page' => $page
        ]));
    }

    public function render()
    {
        return view('livewire.comment-notifications');
    }
}
