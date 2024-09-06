<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowIdeasTest extends TestCase
{
   use RefreshDatabase;

   /** @test */
   public function list_of_ideas_shown_on_main_page()
   {
      
      $categoryOne = Category::factory()->create(['name' => 'Category 1']);
      $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

      $statusOpen = Status::factory()->create(['name' => "OpenUnique"]);
      $statusImplemented = Status::factory()->create(['name' => "ConsideringUnique"]);


      $ideaOne = Idea::factory()->create([
         'title' => 'My First Idea',
         'category_id' => $categoryOne->id,
         'status_id' => $statusOpen->id,
      ]);
      $ideaTwo = Idea::factory()->create([
        'title' => 'My Second Idea',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusImplemented->id,
     ]);
      $response = $this->get(route('idea.index'));

      $response->assertSuccessful();
      $response->assertSee($ideaOne->title);
      $response->assertSee($ideaOne->desription);
      $response->assertSee($categoryOne->name);
      $response->assertSee('OpenUnique');

      $response->assertSee($ideaTwo->title);
      $response->assertSee($ideaTwo->desription);
      $response->assertSee($categoryTwo->name);
      $response->assertSee('ConsideringUnique');
      
   }   

   /** @test */
   public function single_idea_shows_correctly_on_the_show_page()
   {
      $category = Category::factory()->create(['name'=>'Category 1']);
      $statusOpen = Status::factory()->create(['name' => "OpenUnique"]);

      $idea = Idea::factory()->create([
         'title' => 'My First Idea',
         'category_id' => $category->id,
         'status_id' => $statusOpen->id,
      ]);
      
      $response = $this->get(route('idea.show',$idea));

      $response->assertSuccessful();
      $response->assertSee($idea->title);
      $response->assertSee($idea->desription);
      
      $response->assertSee($category->name);
      $response->assertSee('OpenUnique');
      
   }
   /** @test  */
   public function ideas_pagination_works(){
      $ideaOne = Idea::factory()->create();
      Idea::factory($ideaOne->getPerPage())->create();
      
      $response = $this->get('/');

      $response->assertSee(Idea::find(Idea::count() -1)->title);
      $response->assertDontSee($ideaOne->title);
      
      $response = $this->get('/?page=2');
      
      $response->assertDontSee(Idea::find(Idea::count() - 1)->title);
      $response->assertSee($ideaOne->title);
      
   }
   
   /** @test */

   public function same_idea_title_different_slugs(){
      $user = User::factory()->create();

      $category = Category::factory()->create(['name'=>'Category 1']);
      $statusOpen = Status::factory()->create(['name' => "Open"]);

      $ideaOne = Idea::factory()->create([
         'user_id' => $user->id,
         'title' => 'My First Idea',
         'category_id' => $category->id,
         'status_id' => $statusOpen->id,
         'description' => "Description of first idea"
      ]); 
      $ideaTwo = Idea::factory()->create([
         'user_id' => $user->id,
         'category_id' => $category->id,
         'title' => 'My First Idea',
         'status_id' => $statusOpen->id,
         'description' => "Description of first idea"
      ]);

      $response = $this->get(route('idea.show',$ideaOne));
      
      $response->assertSuccessful();
      $this->assertTrue(request()->path() === 'ideas/my-first-idea');

      $response = $this->get(route('idea.show',$ideaTwo));  
      
      $response->assertSuccessful();
      $this->assertTrue(request()->path() === 'ideas/my-first-idea-2');


   }
   /** @test */
   public function in_app_back_button_works_when_index_page_visited_first()
   {
      $user = User::factory()->create();
      
      $categoryOne = Category::factory()->create(['name' => 'Category 1']);

      $statusOpen = Status::factory()->create(['name' => "Open"]);
      $statusConsidering = Status::factory()->create(['name' => "Considering"]);

      $ideaOne = Idea::factory()->create([
         'user_id' => $user->id,
         'title' => 'My First Idea',
         'category_id' => $categoryOne->id,
         'status_id' => $statusOpen->id,
         'description' => "Description of first idea"
      ]);
      
      $response = $this->get('?category=Category+1&status=Considering');

      $response = $this->get(route('idea.show',$ideaOne));

      $this->assertStringContainsString('?category=Category%201&status=Considering',$response['backUrl']);
      
   }  

   /** @test */
   public function in_app_back_button_works_when_show_page_only_page_visited()
   {
      $ideaOne = Idea::factory()->create();

      $response = $this->get(route('idea.show',$ideaOne));

      $this->assertEquals(route('idea.index'),$response['backUrl']);
      
   }  
}
