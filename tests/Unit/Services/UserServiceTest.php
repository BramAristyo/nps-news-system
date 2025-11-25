<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function test_create_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'user',
        ];

        $user = $this->userService->createUser($data);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertEquals('user', $user->role);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        $data = ['name' => 'Updated Name'];

        $updatedUser = $this->userService->updateUser($user->id, $data);

        $this->assertEquals('Updated Name', $updatedUser->name);
    }

    public function test_assign_role()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $updatedUser = $this->userService->assignRole($user->id, 'editor');

        $this->assertEquals('editor', $updatedUser->role);
    }

    public function test_verify_internal_user()
    {
        $user = User::factory()->create(['is_internal' => false]);

        $updatedUser = $this->userService->verifyInternalUser($user->id);

        $this->assertTrue((bool)$updatedUser->is_internal);
    }
}
