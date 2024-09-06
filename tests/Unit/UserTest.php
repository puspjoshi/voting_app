<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_check_if_user_is_an_admin()
    {
        $user = User::factory()->create([
            'name' => 'pusp joshi',
            'email' => 'erpushparaj23@gmail.com',
        ]);
        $userB = User::factory()->create([
            'name' => 'Deepak joshi',
            'email' => 'deepak@gmail.com',
        ]);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($userB->isAdmin());
    }
}
