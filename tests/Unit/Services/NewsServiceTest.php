<?php

namespace Tests\Unit\Services;

use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\User;
use App\Services\NewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected NewsService $newsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsService = new NewsService();
    }

    public function test_get_all_news_excludes_internal_by_default()
    {
        NewsArticle::factory()->create(['is_internal' => false]);
        NewsArticle::factory()->create(['is_internal' => true]);

        $news = $this->newsService->getAllNews(false);

        $this->assertCount(1, $news);
    }

    public function test_get_all_news_includes_internal_when_requested()
    {
        NewsArticle::factory()->create(['is_internal' => false]);
        NewsArticle::factory()->create(['is_internal' => true]);

        $news = $this->newsService->getAllNews(true);

        $this->assertCount(2, $news);
    }

    public function test_create_news()
    {
        $user = User::factory()->create();
        $data = [
            'title' => 'Test News',
            'slug' => 'test-news',
            'content' => 'Content',
            'user_id' => $user->id,
            'is_internal' => false,
        ];

        $article = $this->newsService->createNews($data);

        $this->assertDatabaseHas('news_articles', ['slug' => 'test-news']);
    }

    public function test_attach_categories()
    {
        $article = NewsArticle::factory()->create();
        $category = NewsCategory::factory()->create();

        $this->newsService->attachCategories($article->id, [$category->id]);

        $this->assertTrue($article->categories->contains($category));
    }
}
