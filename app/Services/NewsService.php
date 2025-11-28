<?php

namespace App\Services;

use App\Models\NewsArticle;
use Illuminate\Support\Facades\Storage;

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

    public function getNewsByVisibility(string $visibility = 'all')
    {
        $query = NewsArticle::with(['user', 'categories']);

        match ($visibility) {
            'public' => $query->where('is_internal', false),
            'internal' => $query->where('is_internal', true),
            default => null,
        };

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
        if (isset($data['image']) && $data['image']) {
            $image = $data['image'];

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $path = $image->storeAs('news-images', $filename, 'public');

            $data['image'] = $path;
        }

        return NewsArticle::create($data);
    }

    public function updateNews(int $id, array $data): NewsArticle
    {
        $article = NewsArticle::findOrFail($id);

        if (isset($data['image']) && $data['image']) {
            $image = $data['image'];

            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $path = $image->storeAs('news-images', $filename, 'public');
            $data['image'] = $path;
        }

        $article->update($data);
        return $article;
    }

    public function deleteNews(int $id): bool
    {
        $article = NewsArticle::findOrFail($id);

        if ($article->image && Storage::disk('public')->exists($article->image)) {
            Storage::disk('public')->delete($article->image);
        }

        return $article->delete();
    }

    public function attachCategories(int $newsId, array $categoryIds): void
    {
        $article = NewsArticle::findOrFail($newsId);
        $article->categories()->sync($categoryIds);
    }
}
