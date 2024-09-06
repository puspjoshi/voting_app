<?php

namespace App\Livewire;

use App\Models\Idea;
use Livewire\Component;
use App\Jobs\NotifyAllVoters;
use App\Models\Comment;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Response;


class SetStatus extends Component
{
    public $idea;
    public $status;
    public $comment;
    public $notifyAllVoters;

    public function mount(Idea $idea){
        $this->idea = $idea;
        $this->status = $this->idea->status_id;
    }

    public function setStatus(){
        if( ! auth()->check() || ! auth()->user()->isAdmin()){
            abort(Response::HTTP_FORBIDDEN);
        }

        if($this->idea->status_id === (int) $this->status){
            $this->dispatch('status-was-updated-error', message: 'Status is same !');
            return;
        }

        $this->idea->status_id = $this->status;
        $this->idea->save();

        if($this->notifyAllVoters){
            NotifyAllVoters::dispatch($this->idea);
        }

        Comment::create([
            'user_id' => auth()->id(),
            'idea_id' => $this->idea->id,
            'status_id' => $this->status,
            'body' => $this->comment ?? 'No comment was added.',
            'is_status_update' => true
        ]);
        
        $this->dispatch('status-was-updated', message:'Status was added successfully'); 
    }

    

    public function render()
    {
        return view('livewire.set-status');
    }
}
