<?php

namespace App\Livewire;

use App\Models\Idea;
use App\Models\Vote;
use App\Models\Comment;
use Livewire\Component;
use Illuminate\Http\Response;

class DeleteIdea extends Component
{
    public $idea;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function deleteIdea()
    {
        if(auth()->guest() || auth()->user()->cannot('delete', $this->idea)){
            abort(Response::HTTP_FORBIDDEN);
        }
        
        Idea::destroy($this->idea->id);

        $this->dispatch('idea-was-deleted',message: 'Idea was deleted successfully!');

        session()->flash('success_message','Idea was deleted successfully');

        return redirect()->route('idea.index');
    }
    public function render()
    {
        return view('livewire.delete-idea');
    }
}
