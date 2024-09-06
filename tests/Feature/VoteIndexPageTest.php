<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Livewire\IdeaShow;
use App\Livewire\IdeaIndex;
use App\Livewire\IdeasIndex;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteIndexPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_contains_idea_index_livewire_component()
    {
        Idea::factory()->create();
        $this->get(route('idea.index'))
            ->assertSeeLivewire('idea-index');
    }
    /** @test */
    public function ideas_index_livewire_component_correctly_receives_votes_count()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        
        
        $idea = Idea::factory()->create();

        Vote::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id
        ]);
        Vote::factory()->create([
            'user_id' => $userB->id,
            'idea_id' => $idea->id
        ]);

        Livewire::test(IdeasIndex::class)
            ->assertViewHas('ideas', function($ideas){
                return $ideas->first()->votes_count ==2;
            });
           
    }

    /** @test */
    public function votes_count_shows_correctly_on_the_index_page_livewire_component()
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

        Livewire::test(IdeaIndex::class,[
            'idea' => $idea,
            'votesCount' => 5
        ])
        ->assertSet('votesCount',5);
        
    }

    /** @test */
    public function user_who_is_logged_in_shows_voted_if_idea_already_voted_for()
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

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id
        ]);

        $idea->vote_count = 1;
        $idea->voted_by_user = 1;


        Livewire::actingAs($user)
            ->test(IdeaIndex::class,[
            'idea' => $idea,
            'votesCount' => 5
        ])
        ->assertSet('hasVoted',true)
        ->assertSee('Voted');
    }

    /** @test */
    public function user_who_is_not_logged_in_is_redirected_to_login_page_when_trying_to_vote()
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

        
        Livewire::test(IdeaIndex::class,[
            'idea' => $idea,
            'votesCount' => 5
        ])
        ->call('vote')
        ->assertRedirect(route('login'));
    }
}
