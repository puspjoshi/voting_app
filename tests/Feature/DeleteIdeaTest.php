<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Category;
use App\Livewire\IdeaShow;
use App\Livewire\DeleteIdea;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteIdeaTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function shows_delete_idea_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('delete-idea');
    }

    /** @test */
    public function does_not_show_delete_idea_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('delete-idea');
    }


    /** @test */
    public function deleting_an_idea_works_when_user_has_authorization() 
    {
        $user = User::factory()->create();
        

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(DeleteIdea::class,[
                'idea' =>$idea
            ])
            ->call('deleteIdea')
            ->assertRedirect(route('idea.index'));

            $this->assertEquals(0,Idea::count());

    }

    /** @test */
    public function deleting_an_idea_works_when_user_is_admin() 
    {
        $user = User::factory()->admin()->create();
        

        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(DeleteIdea::class,[
                'idea' =>$idea
            ])
            ->call('deleteIdea')
            ->assertRedirect(route('idea.index'));

            $this->assertEquals(0,Idea::count());

    }

    /** @test */
    public function deleting_an_idea_with_votes_works_when_user_has_authorization() 
    {
        $user = User::factory()->create();
        

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        Vote::factory()->create([
            'user_id' =>$user->id,
            'idea_id' => $idea->id
        ]);

        Livewire::actingAs($user)
            ->test(DeleteIdea::class,[
                'idea' =>$idea
            ])
            ->call('deleteIdea')
            ->assertRedirect(route('idea.index'));
            
            $this->assertEquals(0,Vote::count());
            $this->assertEquals(0,Idea::count());

    }

    /** @test */
    public function deleting_an_idea_with_comments_works_when_user_has_authorization() 
    {
        $user = User::factory()->create();
        

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        Comment::factory()->create([
            'idea_id' => $idea->id
        ]);

        Livewire::actingAs($user)
            ->test(DeleteIdea::class,[
                'idea' =>$idea
            ])
            ->call('deleteIdea')
            ->assertRedirect(route('idea.index'));
            
            $this->assertEquals(0,Vote::count());
            // $this->assertEquals(0,Idea::count());

    }


    /** @test */
    public function deleting_an_idea_shows_on_menu_when_user_has_authorization() 
    {
        $user = User::factory()->create();
        
        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaShow::class,[
                'idea' =>$idea,
                'votesCount' => 4
            ])
            ->assertSee('Delete Idea');
    }

    /** @test */
    public function deleting_an_idea_does_not_show_on_menu_when_user_does_not_have_authorization() 
    {
        $user = User::factory()->create();
        
        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(IdeaShow::class,[
                'idea' =>$idea,
                'votesCount' => 4
            ])
            ->assertDontSee('Delete Idea');
    }
}
