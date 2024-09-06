<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Http\Response;

class MarkCommentAsSpam extends Component
{
    public Comment $comment;
    public $body;

    #[On('set-mark-as-a-spam-comment')]
    public function setMarkAsSpamComment($commentId)
    {
        $this->comment = Comment::findOrFail($commentId);

        $this->dispatch('mark-as-spam-comment-modal');
    }

    public function markAsSpamComment()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->comment->spam_reports++;
        $this->comment->save();

        $this->dispatch('comment-was-marked-as-spam', message: 'Comment was marked as spam successfully!');
    }

    public function render()
    {
        return view('livewire.mark-comment-as-spam');
    }
}
