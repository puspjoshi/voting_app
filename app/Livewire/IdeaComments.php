<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class IdeaComments extends Component
{
    use WithPagination;
    public $idea;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }

    #[On('comment-was-added')]
    public function commentWasAdded(){
        $this->idea->refresh();
        $this->goToPage($this->idea->comments()->paginate()->lastPage());
    }

    #[On('comment-was-deleted')]
    public function commentWasDeleted()
    {
        $this->idea->refresh();
        $this->goToPage(1);
    }
    #[On('status-was-updated')]
    public function statusWasUpdated()
    {
        $this->idea->refresh();
        $this->goToPage($this->idea->comments()->paginate()->lastPage());
    }
    
    public function render()
    {
        return view('livewire.idea-comments',[
            // 'comments' => $this->idea->comments()->paginate()->withQueryString()
            'comments' => Comment::with(['user','status'])->where('idea_id',$this->idea->id)->paginate()->withQueryString()
        ]);
    }
}
