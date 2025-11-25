<?php

namespace Tests\Unit\Services;

use App\Models\NewsCategory;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CategoryService $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = new CategoryService();
    }

    public function test_create_category()
    {
        $data = ['name' => 'Tech'];

        $category = $this->categoryService->create($data);

        $this->assertDatabaseHas('news_categories', ['slug' => 'tech']);
    }

    public function test_update_category()
    {
        $category = NewsCategory::factory()->create(['name' => 'Old']);
        
        $this->categoryService->update($category->id, ['name' => 'New']);

        $this->assertDatabaseHas('news_categories', ['slug' => 'new']);
    }
}
