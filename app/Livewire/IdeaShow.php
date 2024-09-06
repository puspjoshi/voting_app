<?php

namespace App\Livewire;

use App\Models\Idea;
use Livewire\Component;
use App\Exceptions\VoteNotFoundException;
use App\Exceptions\DuplicateVoteException;
use App\Livewire\Traits\WithAuthRedirects;
use Livewire\Attributes\On;

class IdeaShow extends Component
{
    use WithAuthRedirects;

    public $idea;
    public $votesCount;
    public $hasVoted;


    public function mount(Idea $idea, $votesCount){
        $this->idea = $idea;
        $this->votesCount = $votesCount;
        $this->hasVoted = $idea->isVotedByUser(auth()->user());
    }

    #[On('status-was-updated')]
    public function statusWasUpdated(){
        $this->idea->refresh();
    }

    #[On('status-was-updated-error')]
    public function statusWasUpdatedError()
    {
        $this->idea->refresh();
    }

    #[On('idea-was-updated')]
    public function ideaWasUpdated(){
        $this->idea->refresh();
    }

    #[On('idea-was-marked-as-spam')]
    public function ideaWaasMarkedAsSpam(){
        $this->idea->refresh();
    }
    #[On('idea-was-marked-as-not-spam')]
    public function ideaWasMarkedAsNotSpam(){
        $this->idea->refresh();
    }

    #[On('comment-was-added')]
    public function commentWasAdded(){
        $this->idea->refresh();
    }

    #[On('comment-was-deleted')]
    public function commentWasDeleted()
    {
        $this->idea->refresh();
    }

    public function vote(){
        if(auth()->guest()){
            return $this->redirectToLogin();
        }
        if($this->hasVoted){
            try{
                $this->idea->removeVote(auth()->user());
            }catch(VoteNotFoundException $e){
                //do nothing
            }
            
            $this->votesCount--;
            $this->hasVoted = false;
        }else{
            try{
                $this->idea->vote(auth()->user());
            }catch(DuplicateVoteException $e){
                //do nothing
            }
            
            $this->votesCount++;
            $this->hasVoted = true;
        }
    }
    
    public function render()
    {
        return view('livewire.idea-show');
    }
}
