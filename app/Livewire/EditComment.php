<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Http\Response;

class EditComment extends Component
{
    public Comment $comment;
    public $body;

    protected $rules = [
        'body' => 'required|min:4',
    ];

    #[On('set-edit-comment')]
    public function setEditComment($commentId)
    {
        $this->comment = Comment::findOrFail($commentId);
        $this->body = $this->comment->body;

        $this->dispatch('edit-comment-form');

    }

    public function updateComment()
    {
        if (auth()->guest() || auth()->user()->cannot('update', $this->comment)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $this->validate();

        $this->comment->body = $this->body;
        $this->comment->save();

        $this->dispatch('comment-was-updated', message: 'comment was updated successfully!');
    }
    
    public function render()
    {
        return view('livewire.edit-comment');
    }
}
