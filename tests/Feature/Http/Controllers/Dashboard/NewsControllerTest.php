<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class NewsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create admin user for tests
        $this->admin = User::factory()->create(['role' => 'admin', 'is_internal' => true]);
    }

    public function test_admin_can_view_news_index()
    {
        NewsArticle::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('dashboard.news.index'));

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard/News/Index')
                ->has('news', 3)
            );
    }

    public function test_admin_can_create_news()
    {
        $response = $this->actingAs($this->admin)->post(route('dashboard.news.store'), [
            'title' => 'Test News',
            'content' => 'Test Content',
            'is_internal' => false,
        ]);

        $response->assertRedirect(route('dashboard.news.index'));
        $this->assertDatabaseHas('news_articles', ['title' => 'Test News']);
    }

    public function test_admin_can_update_news()
    {
        $article = NewsArticle::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('dashboard.news.update', $article->id), [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'is_internal' => true,
        ]);

        $response->assertRedirect(route('dashboard.news.index'));
        $this->assertDatabaseHas('news_articles', ['id' => $article->id, 'title' => 'Updated Title']);
    }

    public function test_admin_can_delete_news()
    {
        $article = NewsArticle::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('dashboard.news.destroy', $article->id));

        $response->assertRedirect(route('dashboard.news.index'));
        $this->assertDatabaseMissing('news_articles', ['id' => $article->id]);
    }
}
