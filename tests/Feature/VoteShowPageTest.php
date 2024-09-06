<?php

namespace Tests\Feature;

use App\Livewire\IdeaShow;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class VoteShowPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_page_contains_idea_show_livewire_component()
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
        $this->get(route('idea.show', $idea))
            ->assertSeeLivewire('idea-show');
    }

    /** @test */
    public function show_page_correctly_receives_votes_count()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

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
            'user_id' => $user->id,
            'idea_id' => $idea->id
        ]);
        Vote::factory()->create([
            'user_id' => $userB->id,
            'idea_id' => $idea->id
        ]);
        $this->get(route('idea.show', $idea))
            ->assertViewHas('votesCount',2);
    }

    /** @test */
    public function votes_count_shows_correctly_on_the_show_page_livewire_component()
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

        Livewire::test(IdeaShow::class,[
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

        Livewire::actingAs($user)
            ->test(IdeaShow::class,[
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

        
        Livewire::test(IdeaShow::class,[
            'idea' => $idea,
            'votesCount' => 5
        ])
        ->call('vote')
        ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_who_is_logged_in_can_vote_for_idea()
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

        $this->assertDatabaseMissing('votes',[
            'user_id' => $user->id,
            'idea_id' => $idea->id
        ]);

        Livewire::actingAs($user)
            ->test(IdeaShow::class,[
            'idea' => $idea,
            'votesCount' => 5
        ])
        ->call('vote')
        ->assertSet('votesCount',6)
        ->assertSet('hasVoted',true)
        ->assertSee('Voted');

        $this->assertDatabaseHas('votes',[
            'user_id' => $user->id,
            'idea_id' => $idea->id
        ]);
    }
}
