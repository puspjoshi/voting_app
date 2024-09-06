<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_count_of_each_status()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create(['name'=>'Category 1']);
        
        $statusOpen = Status::factory()->create(['name' => "Open"]);
        $statusImplemeted = Status::factory()->create(['name' => "Implemented"]);
        $statusInProgress = Status::factory()->create(['name' => "In Progress"]);
        $statusConsidering = Status::factory()->create(['name' => "Considering"]);
        
        $statusClosed = Status::factory()->create(['name' => "Closed"]);

        Idea::factory()->create([
            'status_id' => $statusOpen->id
        ]);
        

        Idea::factory(2)->create([
            'status_id' => $statusConsidering->id
        ]);

        Idea::factory(4)->create([
            'status_id' => $statusImplemeted->id
        ]);
        
        Idea::factory(3)->create([
            'status_id' => $statusInProgress->id
        ]);

        Idea::factory(5)->create([
            'status_id' => $statusClosed->id
        ]);
        
        $this->assertEquals(15,Status::getCount()['all_statuses']);
        
        $this->assertEquals(1,Status::getCount()['open']);
        $this->assertEquals(2,Status::getCount()['considering']);
        $this->assertEquals(3,Status::getCount()['in_progress']);
        $this->assertEquals(4,Status::getCount()['implemented']);
        $this->assertEquals(5,Status::getCount()['closed']);
    }
}
