<?php

namespace App\Livewire;

use App\Livewire\Traits\WithAuthRedirects;
use App\Models\Idea;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Http\Response;

class CreateIdea extends Component
{
    use WithAuthRedirects;
    public $title;
    public $category = 1;
    public $description;

    protected $rules = [
        'title' => 'required|min:4',
        'category' => 'required|integer|exists:categories,id',
        'description' => 'required|min:4',
    ];

    public function createIdea(){
        if(auth()->guest()){
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();
        
        Idea::create([
            'user_id' => auth()->id(),
            'status_id' => 1,
            'category_id' => $this->category,
            'title' => $this->title,
            'description' => $this->description
        ]);
        session()->flash('success_message','Idea was added successfully');
        $this->dispatch('idea-was-created',message:'Idea was creted');
        $this->reset();
        
        return redirect()->route('idea.index');
        
        
    }
    public function render()
    {
        return view('livewire.create-idea',[
            'categories' => Category::all()
        ]);
    }
}
