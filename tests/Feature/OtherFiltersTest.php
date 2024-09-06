<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Livewire\IdeaIndex;
use App\Livewire\IdeasIndex;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtherFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function top_voted_filter_works(){
        $user = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();



        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        Vote::factory()->create([
            'idea_id' => $ideaOne->id,
            'user_id' => $user->id   
        ]);
        Vote::factory()->create([
            'idea_id' => $ideaTwo->id,
            'user_id' => $userB->id   
        ]);
        Vote::factory()->create([
            'idea_id' => $ideaTwo->id,
            'user_id' => $userC->id   
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('filter', 'Top Voted')
            ->assertViewHas('ideas', function($ideas){
                return $ideas->count() === 2
                    && $ideas->first()->votes()->count() === 2
                    && $ideas->get(1)->votes()->count() === 1;
            });
    }

    /** @test */
    public function my_ideas_filter_works_correctly_when_user_logged_in(){
        $user = User::factory()->create();
        $userB = User::factory()->create();
        


        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My First Idea',
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My Second Idea',
        ]);

        $ideaThree = Idea::factory()->create([
            'user_id' => $userB->id,
            'title' => 'My Third Idea',
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('filter', 'My Ideas')
            ->assertViewHas('ideas', function($ideas){
                return $ideas->count() === 2
                    && $ideas->first()->title === 'My Second Idea'
                    && $ideas->get(1)->title === 'My First Idea';
            });
    }

    /** @test */
    public function my_ideas_filter_works_correctly_when_user_is_not_logged_in(){
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $ideaThree = Idea::factory()->create([
            'user_id' => $userB->id,
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('filter', 'My Ideas')
            ->assertRedirect(route('login'));
            
    }

    /** @test */
    public function my_ideas_filter_works_correctly_with_categories_filter(){
        $user = User::factory()->create();
        $userB = User::factory()->create();
        
        $categoryOne = Category::factory()->create(['name'=>'Category 1']);
        $categoryTwo = Category::factory()->create(['name'=>'Category 2']);

        $statusOpen = Status::factory()->create(['name' => "Open"]);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => "Description of first idea"
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My Second Idea',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => "Description of first idea"
        ]);

        $ideaThree = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My Third Idea',
            'category_id' => $categoryTwo->id,
            'status_id' => $statusOpen->id,
            'description' => "Description of first idea"
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('category', 'Category 1')
            ->set('filter', 'My Ideas')
            ->assertViewHas('ideas', function($ideas){
                return $ideas->count() === 2
                    && $ideas->first()->title === 'My Second Idea'
                    && $ideas->get(1)->title === 'My First Idea';
            });
            
    }
    /** @test */
    public function no_filter_works_correctly(){
        $user = User::factory()->create();
        
        $categoryOne = Category::factory()->create(['name'=>'Category 1']);

        $statusOpen = Status::factory()->create(['name' => "Open"]);

        $ideaOne = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My First Idea',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => "Description of first idea"
        ]);
        $ideaTwo = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My Second Idea',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => "Description of first idea"
        ]);

        $ideaThree = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'My Third Idea',
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => "Description of first idea"
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('filter', 'No Filter')
            ->assertViewHas('ideas', function($ideas){
                return $ideas->count() === 3
                    && $ideas->first()->title === 'My Third Idea'
                    && $ideas->get(1)->title === 'My Second Idea';
            });
    }

    /** @test */
    public function spam_ideas_filter_works()
    {
        $user = User::factory()->admin()->create();
        
        $ideaOne = Idea::factory()->create([
            'title' => 'Idea One',
            'spam_reports'=> 1
        ]);
        $ideaTwo = Idea::factory()->create([
            'title' => 'Idea Two',
            'spam_reports' => 2,
        ]);

        $ideaThree = Idea::factory()->create([
            'title' => 'Idea Three',
            'spam_reports' => 3,
        ]);

        $ideaFour = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('filter','Spam Ideas')
            ->assertViewHas('ideas', function ($ideas){
                return $ideas->count() === 3
                    && $ideas->first()->title === "Idea Three"
                    && $ideas->get(1)->title === "Idea Two"
                    && $ideas->get(2)->title === "Idea One";

            });
    }

    /** @test */
    public function spam_comments_filter_works()
    {
        $user = User::factory()->admin()->create();

        $ideaOne = Idea::factory()->create([
            'title' => 'Idea One',
        ]);
        $ideaTwo = Idea::factory()->create([
            'title' => 'Idea Two',
        ]);

        $ideaThree = Idea::factory()->create([
            'title' => 'Idea Three',
        ]);

        $ideaFour = Idea::factory()->create();

        $commentOne = Comment::factory()->create([
            'idea_id' => $ideaOne->id,
            'body' => 'this is my first comment',
            'spam_reports' => 3 
        ]);
        $commentTwo = Comment::factory()->create([
            'idea_id' => $ideaTwo->id,
            'body' => 'this is my first comment',
            'spam_reports' => 2
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('filter', 'Spam Comments')
            ->assertViewHas('ideas', function($ideas){
                return $ideas->count() === 2;
            });

    }
}
