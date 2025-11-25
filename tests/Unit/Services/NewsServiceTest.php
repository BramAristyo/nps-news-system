<?php

namespace Tests\Unit\Services;

use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\User;
use App\Services\NewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_create_news_with_image_upload()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test-image.jpg', 800, 600);

        $data = [
            'title' => 'News with Image',
            'slug' => 'news-with-image',
            'content' => 'Content with image',
            'user_id' => $user->id,
            'is_internal' => false,
            'image' => $file,
        ];

        $article = $this->newsService->createNews($data);

        // Assert image was stored
        $this->assertNotNull($article->image);
        $this->assertStringStartsWith('news-images/', $article->image);
        Storage::disk('public')->assertExists($article->image);
    }

    public function test_create_news_without_image()
    {
        $user = User::factory()->create();
        $data = [
            'title' => 'News without Image',
            'slug' => 'news-without-image',
            'content' => 'Content without image',
            'user_id' => $user->id,
            'is_internal' => false,
        ];

        $article = $this->newsService->createNews($data);

        $this->assertNull($article->image);
    }

    public function test_update_news_with_new_image_deletes_old_image()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        
        // Create article with initial image
        $oldFile = UploadedFile::fake()->image('old-image.jpg');
        $article = NewsArticle::factory()->create([
            'user_id' => $user->id,
            'image' => null,
        ]);
        
        // Manually store old image to simulate existing image
        $oldPath = $oldFile->storeAs('news-images', 'old_image.jpg', 'public');
        $article->update(['image' => $oldPath]);
        
        Storage::disk('public')->assertExists($oldPath);

        // Update with new image
        $newFile = UploadedFile::fake()->image('new-image.jpg');
        $updateData = [
            'title' => 'Updated News',
            'image' => $newFile,
        ];

        $updatedArticle = $this->newsService->updateNews($article->id, $updateData);

        // Assert old image was deleted
        Storage::disk('public')->assertMissing($oldPath);
        
        // Assert new image exists
        $this->assertNotNull($updatedArticle->image);
        $this->assertNotEquals($oldPath, $updatedArticle->image);
        Storage::disk('public')->assertExists($updatedArticle->image);
    }

    public function test_update_news_without_image_keeps_existing_image()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('existing-image.jpg');
        $existingPath = $file->storeAs('news-images', 'existing.jpg', 'public');
        
        $article = NewsArticle::factory()->create([
            'user_id' => $user->id,
            'image' => $existingPath,
        ]);

        $updateData = [
            'title' => 'Updated Title Only',
        ];

        $updatedArticle = $this->newsService->updateNews($article->id, $updateData);

        // Assert image was not changed
        $this->assertEquals($existingPath, $updatedArticle->image);
        Storage::disk('public')->assertExists($existingPath);
    }

    public function test_delete_news_removes_associated_image()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('to-delete.jpg');
        $imagePath = $file->storeAs('news-images', 'delete_me.jpg', 'public');
        
        $article = NewsArticle::factory()->create([
            'user_id' => $user->id,
            'image' => $imagePath,
        ]);

        Storage::disk('public')->assertExists($imagePath);

        // Delete the article
        $this->newsService->deleteNews($article->id);

        // Assert image was deleted from storage
        Storage::disk('public')->assertMissing($imagePath);
        
        // Assert article was deleted from database
        $this->assertDatabaseMissing('news_articles', ['id' => $article->id]);
    }

    public function test_delete_news_without_image()
    {
        $user = User::factory()->create();
        $article = NewsArticle::factory()->create([
            'user_id' => $user->id,
            'image' => null,
        ]);

        $result = $this->newsService->deleteNews($article->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('news_articles', ['id' => $article->id]);
    }
}
