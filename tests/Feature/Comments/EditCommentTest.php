<?php

namespace Tests\Feature\Comments;

use App\Livewire\EditComment;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Category;
use App\Livewire\EditIdea;
use App\Livewire\IdeaComment;
use App\Livewire\IdeaShow;
use App\Models\Comment;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_edit_idea_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('edit-comment');
    }

    /** @test */
    public function doe_not_show_edit_comment_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('edit-comment');
    }

    /** @test */
    public function edit_comment_set_correctly_when_user_clicks_it_from_menu()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'body' => 'This is my first comment'
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment',$comment->id)
            ->assertSee('body',$comment->body)
            ->assertDispatched('edit-comment-form');
    }

    /** @test */
    public function edit_comment_form_validation_works()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'body' => 'This is my first comment'
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment', $comment->id)
            ->set('body','')
            ->call('updateComment')
            ->assertHasErrors(['body'])
            ->set('body', 'asd')
            ->call('updateComment')
            ->assertHasErrors(['body']);

    }

    /** @test */
    public function editing_a_comment_works_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'body' => 'This is my first comment'
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment', $comment->id)
            ->set('body', 'This is my updated comment')
            ->call('updateComment')
            ->assertDispatched('comment-was-updated');
        

        $this->assertEquals('This is my updated comment', Comment::first()->body);
    }

    /** @test */
    public function editing_a_comment_does_not_work_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is my first comment'
        ]);

        Livewire::actingAs($user)
            ->test(EditComment::class)
            ->call('setEditComment', $comment->id)
            ->set('body', 'This is my updated comment')
            ->call('updateComment')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }


    /** @test */
    public function editing_a_comment_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment'
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class, [
                'comment' => $comment,
                'ideaUserId' => $idea->user_id
            ])
            ->assertSee('Edit Comment');
    }

    /** @test */
    public function editing_a_comment_does_not_show_on_menu_when_user_does_not_have_authorization()
    {

        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
                'idea_id' => $idea->id,
                'body' => 'This is my first comment'
            ]);

        Livewire::actingAs($user)
        ->test(IdeaComment::class, [
            'comment' => $comment,
            'ideaUserId' => $idea->user_id
        ])
            ->assertDontSee('Edit Comment');
    }
}
