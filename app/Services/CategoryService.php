<?php

namespace App\Services;

use App\Models\NewsCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    public function getAll(): Collection
    {
        return NewsCategory::all();
    }

    public function getPaginated(int $perPage = 10, ?string $search = null)
    {
        $query = NewsCategory::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data): NewsCategory
    {
        if (!isset($data['slug']) && isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        return NewsCategory::create($data);
    }

    public function update(int $id, array $data): NewsCategory
    {
        $category = NewsCategory::findOrFail($id);
        
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);
        return $category;
    }

    public function delete(int $id): bool
    {
        $category = NewsCategory::findOrFail($id);
        return $category->delete();
    }

    public function getMainCategories(): Collection
    {
        return NewsCategory::where('is_main', true)->limit(5)->get();
    }
}
