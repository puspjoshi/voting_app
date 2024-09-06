<?php

namespace App\Livewire;
use App\Models\Comment;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Http\Response;

class DeleteComment extends Component
{
    public ?Comment $comment;
    public $body;

    #[On('set-delete-comment')]
    public function setDeleteComment($commentId)
    {
        $this->comment = Comment::findOrFail($commentId);

        $this->dispatch('delete-comment-modal');
    }

    public function deleteComment()
    {
        if (auth()->guest() || auth()->user()->cannot('delete', $this->comment)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        Comment::destroy($this->comment->id);

        $this->comment = null;

        $this->dispatch('comment-was-deleted', message: 'Comment was deleted successfully!');

        
    }

    public function render()
    {
        return view('livewire.delete-comment');
    }
}
