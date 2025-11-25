<?php

namespace App\Services;

use App\Models\NewsArticle;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NewsService
{
    public function getAllNews(bool $includeInternal = false)
    {
        $query = NewsArticle::with(['user', 'categories']);

        if (!$includeInternal) {
            $query->where('is_internal', false);
        }

        return $query->latest()->get();
    }

    public function getPublicNews()
    {
        return NewsArticle::with(['user', 'categories'])
            ->where('is_internal', false)
            ->latest()
            ->get();
    }

    public function getInternalNews()
    {
        return NewsArticle::with(['user', 'categories'])
            ->where('is_internal', true)
            ->latest()
            ->get();
    }

    public function findBySlug(string $slug): ?NewsArticle
    {
        return NewsArticle::with(['user', 'categories'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function createNews(array $data): NewsArticle
    {
        return NewsArticle::create($data);
    }

    public function updateNews(int $id, array $data): NewsArticle
    {
        $article = NewsArticle::findOrFail($id);
        $article->update($data);
        return $article;
    }

    public function deleteNews(int $id): bool
    {
        $article = NewsArticle::findOrFail($id);
        return $article->delete();
    }

    public function attachCategories(int $newsId, array $categoryIds): void
    {
        $article = NewsArticle::findOrFail($newsId);
        $article->categories()->sync($categoryIds);
    }
}
