<?php

namespace App\Services;

use App\Models\NewsArticle;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public function getAllNews(string $search, ?int $categoryId = null, int $perPage = 15)
    {
        $query = NewsArticle::with(['user', 'categories']);

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getNewsByVisibility(string $search, ?int $categoryId, string $visibility = 'all', int $perPage = 15)
    {
        $query = NewsArticle::with(['user', 'categories']);

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        match ($visibility) {
            'public' => $query->where('is_internal', false),
            'internal' => $query->where('is_internal', true),
            default => null,
        };

        return $query->latest()->paginate($perPage);
    }

    public function getPublicNews(string $search = '', ?int $categoryId = null, int $perPage = 15)
    {
        $query = NewsArticle::with(['user', 'categories'])
            ->where('is_internal', false);

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getInternalNews(string $search = '', int $perPage = 15)
    {
        $query = NewsArticle::with(['user', 'categories'])
            ->where('is_internal', true);

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query->latest()->paginate($perPage);
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
