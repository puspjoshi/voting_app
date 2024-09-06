<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Livewire\IdeaIndex;
use App\Livewire\IdeasIndex;
use App\Livewire\StatusFilters;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_contains_status_filters_livewire_component()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create(['name'=>'Category 1']);
        
        $status = Status::factory()->create(['name' => "Open"]);
        
        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => "Description of first idea",
            'title' => 'My First Idea'
        ]);

        $this->get(route('idea.index'))
            ->assertSeeLivewire('status-filters');
    }

    /** @test */
    public function show_page_contains_status_filters_livewire_component()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create(['name'=>'Category 1']);
        
        $status = Status::factory()->create(['name' => "Open"]);
        
        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $status->id,
            'description' => "Description of first idea",
            'title' => 'My First Idea'
        ]);

        $this->get(route('idea.show',$idea))
            ->assertSeeLivewire('status-filters');
    }

    /** @test */
    public function shows_correct_status_count()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create(['name'=>'Category 1']);
        
        $statusImplemented = Status::factory()->create(['id' => 2 ,'name' => "Implemented"]);
        
        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'description' => "Description of first idea",
            'title' => 'My First Idea'
        ]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'description' => "Description of first idea",
            'title' => 'My First Idea'
        ]);

        Livewire::test(StatusFilters::class)
            ->assertSee('All Ideas (2)')
            ->assertSee('Implemented (2)');
    }

    /** @test */
    public function filtering_works_when_query_string_in_place()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create(['name'=>'Category 1']);
        
        $statusImplemented = Status::factory()->create(['name' => "Implemented"]);
        $statusConsidering = Status::factory()->create(['name' => "Considering"]);

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusConsidering->id
        ]);
        

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusConsidering->id
        ]);


        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id
        ]);
        

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id
        ]);
        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id
        ]);


        Livewire::withQueryParams(['status'=>'Implemented'])
            ->test(IdeasIndex::class)
            ->assertViewHas('ideas',function($ideas){
                return $ideas->count() === 3
                    && $ideas->first()->status->name === 'Implemented';
            });

    }

    /** @test */
    public function show_page_does_not_show_selected_status()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create(['name'=>'Category 1']);
        
        $statusImplemented = Status::factory()->create(['id' => 2 ,'name' => "Implemented"]);
        
        

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'description' => "Description of first idea",
            'title' => 'My First Idea'
        ]);

        $response = $this->get(route('idea.show',$idea));

        $response->assertDontSee('border-blue text-gray-900');

        
    }

    /** @test */
    public function index_page_shows_selected_status()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create(['name'=>'Category 1']);
        
        $statusImplemented = Status::factory()->create(['id' => 2 ,'name' => "Implemented"]);
        
        

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $statusImplemented->id,
            'description' => "Description of first idea",
            'title' => 'My First Idea'
        ]);

        $response = $this->get(route('idea.index'));

        $response->assertSee('border-blue text-gray-900');

        
    }
}
