<?php

namespace Tests\Feature\Comments;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\Comment;
use App\Models\User;
use App\Models\Vote;
use Livewire\Livewire;
use App\Models\Category;
use App\Models\Comments;
use App\Livewire\IdeaShow;
use App\Livewire\IdeaIndex;
use App\Livewire\MarkCommentAsNotSpam;
use App\Livewire\IdeaComment;
use Illuminate\Http\Response;
use App\Livewire\MarkCommentAsSpam;
use App\Livewire\MarkIdeaAsNotSpam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentsSpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_comment_as_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('mark-comment-as-spam');
    }


    /** @test */
    public function does_not_show_mark_comment_as_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('mark-comment-as-spam');
    }


    /** @test */
    public function marking_an_comment_as_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsSpam::class, [
                'idea' => $idea
            ])
            ->call('setMarkAsSpamComment', $comment->id)
            ->call('markAsSpamComment')
            ->assertDispatched('comment-was-marked-as-spam');

        $this->assertEquals(1, Comment::first()->spam_reports);
    }


    /** @test */
    public function marking_an_comment_as_spam_does_not_work_when_user_does_not_have_authorization()
    {

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
        ]);

        Livewire::test(MarkCommentAsSpam::class)
            ->call('setMarkAsSpamComment', $comment->id)
            ->call('markAsSpamComment')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_an_comment_as_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class, [
                'comment' => $comment,
                'ideaUserId' => $idea->user_id
            ])
            ->assertSee('Mark as Spam');
    }

    /** @test */
    public function marking_an_comment_as_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {

        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::test(IdeaComment::class, [
            'comment' => $comment,
            'ideaUserId' => $idea->user_id
        ])
            ->assertDontSee('Mark as Spam');
    }

    /** @test */
    public function shows_mark_comment_as_not_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('mark-comment-as-not-spam');
    }


    /** @test */
    public function does_not_show_mark_comment_as_not_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('mark-idea-as-not-spam');
    }

    /** @test */
    public function marking_an_comment_as_not_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsNotSpam::class)
            ->call('setMarkAsNotSpamComment', $comment->id)
            ->call('markAsNotSpamComment')
            ->assertDispatched('comment-was-marked-as-not-spam');

        $this->assertEquals(0, Comment::first()->spam_reports);
    }

    /** @test */
    public function marking_an_comment_as_not_spam_does_not_work_when_user_does_not_have_authorization()
    {

        $idea = Idea::factory()->create();
        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::test(MarkCommentAsNotSpam::class)
            ->call('setMarkAsNotSpamComment', $comment->id)
            ->call('markAsNotSpamComment')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_an_comment_as_not_spam_work_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create([
            'spam_reports' => 1,
        ]);

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is my first comment',
            'spam_reports' => 3
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class,[
                'comment' =>$comment,
                'ideaUserId' => $idea->user_id
            ])
            ->assertSee('Not Spam');
    }

    /** @test */
    public function marking_an_comment_as_not_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is my first comment',
            'spam_reports' => 3
        ]);

        Livewire::test(IdeaComment::class, [
                'ideaUserId' => $idea,
                'comment' => $comment
            ])
            ->assertDontSee('Not Spam');
    }

    /** @test */
    public function spam_reports_count_shows_on_comments_index_page_if_logged_in_as_admin()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create([
            'spam_reports' => 3
        ]);

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is my first comment',
            'spam_reports' => 3
        ]);

        Livewire::actingAs($user)
            ->test(IdeaComment::class, [
                'ideaUserId' => $idea->user_id,
                'comment' => $comment
            ])
            ->assertSee('Spam Reports: 3');
    }
    
}
