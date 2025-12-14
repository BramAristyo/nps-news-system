<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\NewsCategory::all();

        // Create 30 public news articles with categories
        \App\Models\NewsArticle::factory()
            ->count(30)
            ->create()
            ->each(function ($article) use ($categories) {
                $article->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );
            });

        // Create 5 internal news articles without categories
        \App\Models\NewsArticle::factory()
            ->internal()
            ->count(5)
            ->create();
    }
}
