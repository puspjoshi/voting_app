<?php

namespace Tests\Feature\Comments;

use App\Livewire\AddComment;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use App\Notifications\CommentAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_comment_livewire_component_render()
    {
        $idea = Idea::factory()->create();

        $this->get(route('idea.show',$idea))
            ->assertSeeLivewire('add-comment');
    }

    /** @test */
    public function add_comment_form_does_render_if_user_logged_in()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();


        $this->actingAs($user)->get(route('idea.show', $idea))
            ->assertSee('Share your thoughts');
    }

    /** @test */
    public function add_comment_form_does_not_render_if_user_logged_out()
    {
        $idea = Idea::factory()->create();

        $this->get(route('idea.show', $idea))
            ->assertSee('Please login or create an account to post a comment');
    }

    /** @test */
    public function add_comment_form_validation_works()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(AddComment::class,[
                'idea'=>$idea
            ])
            ->set('comment','')
            ->call('addComment')
            ->assertHasErrors(['comment'])
            ->set('comment', 'ads')
            ->call('addComment')
            ->assertHasErrors(['comment']);
    }

    /** @test */
    public function add_comment_form_works()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        Notification::fake();

        
        Notification::assertNothingSent();

        Livewire::actingAs($user)
            ->test(AddComment::class, [
                'idea' => $idea
            ])
            ->set('comment', 'THis is my first comment')
            ->call('addComment')
            ->assertDispatched('comment-was-added');
        
        Notification::assertSentTo(
            [$idea->user],CommentAdded::class
        );

        $this->assertEquals(1, Comment::count());
        $this->assertEquals('THis is my first comment', $idea->comments->first()->body);

    }
}
