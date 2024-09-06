<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use App\Models\Category;
use App\Jobs\NotifyAllVoters;
use Illuminate\Support\Facades\Mail;
use App\Mail\IdeaStatusUpdatedMailable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotifyAllVotersTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function it_sends_an_email_to_all_voters(): void
    {
        $user = User::factory()->create([
            'email' => 'erpushparaj23@gmail.com'
        ]);
        $userB = User::factory()->create([
            'email' =>'user@user.com'
        ]);

        $category = Category::factory()->create(['name'=>'Category 1']);
        
        $status = Status::factory()->create(['id' => 2, 'name' => "Considering"]);
        
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

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $userB->id
        ]);


        Mail::fake();

        NotifyAllVoters::dispatch($idea);

        Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) {
            return $mail->hasTo('erpushparaj23@gmail.com')
                && $mail->envelope()->subject === 'An idea you voted for has a new status';
        });

        Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) {
            return $mail->hasTo('user@user.com')
                && $mail->envelope()->subject === 'An idea you voted for has a new status';
        });
    }
}
