<?php

namespace Tests\Unit;

use App\Models\NewsArticle;
use App\Models\NewsCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created(): void
    {
        $category = NewsCategory::factory()->create([
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        $this->assertDatabaseHas('news_categories', [
            'name' => 'Technology',
            'slug' => 'technology',
        ]);
    }

    public function test_category_has_many_articles(): void
    {
        $category = NewsCategory::factory()->create();
        $article = NewsArticle::factory()->create();

        $category->articles()->attach($article);

        $this->assertTrue($category->articles->contains($article));
        $this->assertEquals(1, $category->articles->count());
    }
}
