<?php

namespace App\Services;

use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\User;

class DashboardService
{
    public function summary(): array
    {
        return [
            'total_news' => NewsArticle::count(),
            'total_categories' => NewsCategory::count(),
            'total_editors' => User::where('role', 'editor')->count(),
            'total_internal_news' => NewsArticle::where('is_internal', true)->count(),
        ];
    }
}
