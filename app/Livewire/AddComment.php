<?php

namespace App\Livewire;

use App\Livewire\Traits\WithAuthRedirects;
use App\Models\Idea;
use App\Models\Comment;
use App\Notifications\CommentAdded;
use Livewire\Component;
use Illuminate\Http\Response;

class AddComment extends Component
{
    use WithAuthRedirects;
    public $idea;
    public $comment;

    protected $rules = [
        'comment' => 'required|min:4'
    ];

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function addComment()
    {
        if(auth()->guest()){
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();
        
        $newComment = Comment::create([
            'user_id' => auth()->id(),
            'idea_id' => $this->idea->id,
            'status_id' => 1,
            'body' => $this->comment,
        ]);

        $this->reset('comment');
        $this->idea->user->notify(new CommentAdded($newComment));
        $this->dispatch('comment-was-added', message: 'Comment was added successfully');
    }

    public function render()
    {
        return view('livewire.add-comment');
    }
}
