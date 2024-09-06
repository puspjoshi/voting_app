<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Http\Response;

class MarkCommentAsNotSpam extends Component
{
    public Comment $comment;
    public $body;

    #[On('set-mark-as-not-spam-comment')]
    public function setMarkAsNotSpamComment($commentId)
    {
        $this->comment = Comment::findOrFail($commentId);

        $this->dispatch('mark-as-not-spam-comment-modal');
    }

    public function markAsNotSpamComment()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->comment->spam_reports = 0;
        $this->comment->save();

        $this->dispatch('comment-was-marked-as-not-spam', message: 'Comment was marked as spam successfully!');
    }
    public function render()
    {
        return view('livewire.mark-comment-as-not-spam');
    }
}
