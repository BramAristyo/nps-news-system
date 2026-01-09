<?php

namespace App\Services;

use App\Models\NewsArticle;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public function getAllNews(string $search, ?int $categoryId = null, int $perPage = 16)
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

    public function getNewsByVisibility(?string $search = '', ?string $slug = '', string $visibility = 'all', int $perPage = 15)
    {
        $query = NewsArticle::with(['user', 'categories']);

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($slug) {
            $query->whereHas('categories', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }


        match ($visibility) {
            'public' => $query->where('is_internal', false),
            'internal' => $query->where('is_internal', true),
            default => null,
        };

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

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        $data['slug'] = \Str::slug($data['title']) . '-' . uniqid();

        $article = NewsArticle::create($data);

        if (!empty($categoryIds)) {
            $article->categories()->sync($categoryIds);
        }

        return $article;
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
        } else {
            // Don't update image if not provided
            unset($data['image']);
        }

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        $article->update($data);

        if (isset($categoryIds)) {
            $article->categories()->sync($categoryIds);
        }

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
