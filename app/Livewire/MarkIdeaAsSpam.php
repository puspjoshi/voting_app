<?php

namespace App\Livewire;

use App\Models\Idea;
use Livewire\Component;
use Illuminate\Http\Response;
use Livewire\Attributes\On;

class MarkIdeaAsSpam extends Component
{
    public $idea;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function markAsSpam()
    {
        if(auth()->guest()){
            abort(Response::HTTP_FORBIDDEN);
        }
        $this->idea->spam_reports++;
        $this->idea->save();
        // $this->dispatch('ideaWasMarkedAsSpam');
        $this->dispatch('idea-was-marked-as-spam',message: 'Idea was marked as spam!');
    }
    

    public function render()
    {
        return view('livewire.mark-idea-as-spam');
    }
}
