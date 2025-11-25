<?php

namespace Tests\Unit;

use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_can_be_created(): void
    {
        $user = User::factory()->create();
        $article = NewsArticle::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Content here',
            'image' => 'http://example.com/image.jpg',
            'is_internal' => true,
        ]);

        $this->assertDatabaseHas('news_articles', [
            'title' => 'Test Article',
            'slug' => 'test-article',
            'image' => 'http://example.com/image.jpg',
            'is_internal' => 1,
        ]);
    }

    public function test_article_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $article = NewsArticle::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $article->user);
        $this->assertEquals($user->id, $article->user->id);
    }

    public function test_article_belongs_to_many_categories(): void
    {
        $article = NewsArticle::factory()->create();
        $category1 = NewsCategory::factory()->create();
        $category2 = NewsCategory::factory()->create();

        $article->categories()->attach([$category1->id, $category2->id]);

        $this->assertEquals(2, $article->categories->count());
        $this->assertTrue($article->categories->contains($category1));
        $this->assertTrue($article->categories->contains($category2));
    }
}
