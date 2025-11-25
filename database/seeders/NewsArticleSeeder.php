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

        \App\Models\NewsArticle::factory()
            ->count(20)
            ->create()
            ->each(function ($article) use ($categories) {
                $article->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
    }
}
