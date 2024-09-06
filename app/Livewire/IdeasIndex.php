<?php

namespace App\Livewire;

use App\Livewire\Traits\WithAuthRedirects;
use App\Models\Idea;
use App\Models\Vote;
use App\Models\Status;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class IdeasIndex extends Component
{
    use WithPagination, WithAuthRedirects;

    public $status = "All";
    public $category;
    public $filter;
    public $search;

    protected $queryString = [
        'status',
        'category',
        'filter',
        'search',
    ];

    protected $listeners = ['queryStringUpdateStatus'];

    public function mount(){
        $this->status = request()->status ?? 'All';
        $this->category = request()->category ?? 'All Categories';
    }

    public function updatingCategory(){
        $this->resetPage();
    }
    public function updatingFilter(){
        $this->resetPage();
    }

    public function updatedFilter(){
        if($this->filter === 'My Ideas'){   
            if(auth()->guest()){
                return $this->redirectToLogin();
            }
        }
    }


    public function queryStringUpdateStatus($newStatus){
        $this->resetPage();
        $this->status = $newStatus;
    }

    public function render()
    {
        $statuses = Status::all()->pluck('id','name');
        $categories = Category::all();

        return view('livewire.ideas-index',[
            'ideas' => Idea::with('user','category','status')
                ->when($this->status && $this->status !== 'All', function($query) use ($statuses){
                        return $query->where('status_id', $statuses->get($this->status));            
                })
                ->when($this->category && $this->category !== 'All Categories', function($query) use ($categories){
                    return $query->where('category_id', $categories->pluck('id','name')->get($this->category));            
                })
                ->when($this->filter && $this->filter === 'Most Voted', function($query) {
                    return $query->orderBy('votes_count','desc');            
                })
                ->when($this->filter && $this->filter === 'Spam Ideas', function($query) {
                    return $query->where('spam_reports','>',0)->orderByDesc('spam_reports');            
                })
                ->when($this->filter && $this->filter === 'Spam Comments', function ($query) {
                    return $query->whereHas('comments', function ($query) {
                        $query->where('spam_reports', '>', '0');
                    });
                })
                ->when($this->filter && $this->filter === 'My Ideas', function($query) {
                    return $query->where('user_id',auth()->id());            
                })
                ->when(strlen($this->search)>=3, function($query) {
                    return $query->where('title','like','%'.$this->search.'%');            
                })
                ->addSelect(['voted_by_user' => Vote::select('id')
                    ->where('user_id', auth()->id())
                    ->whereColumn('idea_id','ideas.id')
                ])
                ->withCount('comments')
                ->withCount('votes')
                ->orderBy('id','desc')
                ->simplePaginate()
                ->withQueryString(),
            'categories' => $categories,
        ]);
    }
    

}
