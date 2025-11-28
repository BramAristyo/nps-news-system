<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_categories()
    {
        NewsCategory::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('dashboard.categories.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard/Categories/Index')
                ->has('categories', 3)
            );
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)->post(route('dashboard.categories.store'), [
            'name' => 'New Category',
        ]);

        $response->assertRedirect(route('dashboard.categories.index'));
        $this->assertDatabaseHas('news_categories', ['name' => 'New Category']);
    }

    public function test_admin_can_update_category()
    {
        $category = NewsCategory::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('dashboard.categories.update', $category->id), [
            'name' => 'Updated Category',
        ]);

        $response->assertRedirect(route('dashboard.categories.index'));
        $this->assertDatabaseHas('news_categories', ['id' => $category->id, 'name' => 'Updated Category']);
    }

    public function test_admin_can_delete_category()
    {
        $category = NewsCategory::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('dashboard.categories.destroy', $category->id));

        $response->assertRedirect(route('dashboard.categories.index'));
        $this->assertDatabaseMissing('news_categories', ['id' => $category->id]);
    }
}
