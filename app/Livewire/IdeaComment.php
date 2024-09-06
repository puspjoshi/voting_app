<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\On;

class IdeaComment extends Component
{
    public $comment;
    public $ideaUserId;

    public function mount(Comment $comment, $ideaUserId)
    {
        $this->comment = $comment;
        $this->ideaUserId = $ideaUserId;
    }

    #[On('comment-was-updated')]
    public function commentWasUpdated()
    {
        $this->comment->refresh();
    }

    #[On('comment-was-marked-as-spam')]
    public function commentWasMarkedAsSpam()
    {
        $this->comment->refresh();
    }
    #[On('comment-was-marked-as-not-spam')]
    public function commentWasMarkedAsNotSpam()
    {
        $this->comment->refresh();
    }
    

    public function render()
    {
        return view('livewire.idea-comment');
    }
}
