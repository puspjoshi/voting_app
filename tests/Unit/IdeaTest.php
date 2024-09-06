<?php

namespace Tests\Unit;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IdeaTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function can_check_if_idea_is_voted_for_by_user()
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
            'idea_id' => $idea->id,
            'user_id' => $user->id
        ]);
        
        $this->assertTrue($idea->isVotedByUser($user));
        $this->assertFalse($idea->isVotedByUser($userB));
        $this->assertFalse($idea->isVotedByUser(null));
    }

    /** @test  */
    public function user_can_vote_for_idea()
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
        
        $this->assertFalse($idea->isVotedByUser($user));
        $idea->vote($user);
        $this->assertTrue($idea->isVotedByUser($user));
    }

    /** @test  */
    public function voted_for_an_idea_thats_already_voted_for_thorows_exception()
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

        $this->expectException(DuplicateVoteException::class);
        $idea->vote($user);
        
    }

    /** @test  */
    public function user_can_remove_vote_for_idea()
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
        
        $this->assertTrue($idea->isVotedByUser($user));
        $idea->removeVote($user);
        $this->assertFalse($idea->isVotedByUser($user));
    }

    /** @test  */
    public function removing_a_vote_that_doesnt_exist_throws_exception()
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

        $this->expectException(VoteNotFoundException::class);
        $idea->removeVote($user);
        
    }
}
