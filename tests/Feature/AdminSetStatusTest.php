<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Livewire\SetStatus;
use App\Jobs\NotifyAllVoters;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSetStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_page_contains_set_status_livewire_component_when_user_is_admin(){
        $user = User::factory()->admin()->create();

        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('set-status');
    }

    /** @test */
    public function show_page_does_not_contain_set_status_livewire_component_when_user_is_not_admin(){
        $user = User::factory()->create([
            'email' => 'deepak@gmail.com'
        ]);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => "Open"]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => "Description of first idea"

        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('set-status');
    }

    /** @test */
    public function initial_status_is_set_correctly(){
        $user = User::factory()->create([
            'email' => 'erpushparaj23@gmail.com'
        ]);


        $statusConsidering = Status::factory()->create(['id'=>2, 'name' => "Considering"]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My First Idea',
            'status_id' => $statusConsidering->id,
            'description' => "Description of first idea"

        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class,[
                'idea' => $idea
            ])
            ->assertSet('status',$statusConsidering->id);
    }

    /** @test */
    public function can_set_status_correctly_no_comment(){
        $user = User::factory()->admin()->create();


        $statusConsidering = Status::factory()->create(['id'=>2, 'name' => "Considering"]);
        $statusInProgress = Status::factory()->create(['id'=>3, 'name' => "In Progress"]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'status_id' => $statusConsidering->id,

        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class,[
                'idea' => $idea
            ])
            ->set('status',$statusInProgress->id)
            ->call('setStatus')
            ->assertDispatched('status-was-updated');

        $this->assertDatabaseHas('ideas',[
            'id' => $idea->id,
            'status_id' => $statusInProgress->id
        ]);
        $this->assertDatabaseHas('comments', [
            'body' => 'No comment was added.',
            'is_status_update' => true
        ]);
    }

    /** @test */
    public function can_set_status_correctly_with_comment()
    {
        $user = User::factory()->admin()->create();


        $statusConsidering = Status::factory()->create(['id' => 2, 'name' => "Considering"]);
        $statusInProgress = Status::factory()->create(['id' => 3, 'name' => "In Progress"]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'status_id' => $statusConsidering->id,

        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class, [
                'idea' => $idea
            ])
            ->set('status', $statusInProgress->id)
            ->set('comment', 'This is a comment when setting a status')
            ->call('setStatus')
            ->assertDispatched('status-was-updated');

        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'status_id' => $statusInProgress->id
        ]);
        $this->assertDatabaseHas('comments', [
            'body' => 'This is a comment when setting a status',
            'is_status_update' => true
        ]);
    }

    /** @test */
    public function can_set_status_correctly_while_notifying_all_voters(){
        $user = User::factory()->admin()->create();


        $statusConsidering = Status::factory()->create(['id'=>2, 'name' => "Considering"]);
        $statusInProgress = Status::factory()->create(['id'=>3, 'name' => "In Progress"]);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'status_id' => $statusConsidering->id,

        ]);

        Queue::fake();

        Queue::assertNothingPushed();

        Livewire::actingAs($user)
            ->test(SetStatus::class,[
                'idea' => $idea
            ])
            ->set('status',$statusInProgress->id)
            ->set('notifyAllVoters',true)
            ->call('setStatus')
            ->assertDispatched('status-was-updated');

        Queue::assertPushed(NotifyAllVoters::class);
    }

}
