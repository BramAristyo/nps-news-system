<?php

namespace Tests\Unit\Services;

use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardService $dashboardService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dashboardService = new DashboardService();
    }

    public function test_summary()
    {
        NewsArticle::factory()->count(3)->create();
        NewsCategory::factory()->count(2)->create();
        User::factory()->create(['role' => 'editor']);

        $summary = $this->dashboardService->summary();

        $this->assertEquals(3, $summary['total_news']);
        $this->assertEquals(2, $summary['total_categories']);
        $this->assertEquals(1, $summary['total_editors']);
    }
}
