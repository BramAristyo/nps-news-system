<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_users()
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('dashboard.users.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard/Users/Index')
                ->has('users', 4) // 3 created + 1 admin
            );
    }

    public function test_admin_can_create_user()
    {
        $response = $this->actingAs($this->admin)->post(route('dashboard.users.store'), [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'role' => 'user',
            'is_internal' => false,
        ]);

        $response->assertRedirect(route('dashboard.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    public function test_admin_can_update_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('dashboard.users.update', $user->id), [
            'name' => 'Updated User',
            'email' => $user->email,
            'role' => 'editor',
            'is_internal' => true,
        ]);

        $response->assertRedirect(route('dashboard.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'editor', 'is_internal' => true]);
    }

    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('dashboard.users.destroy', $user->id));

        $response->assertRedirect(route('dashboard.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
