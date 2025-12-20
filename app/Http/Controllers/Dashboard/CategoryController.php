<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryService->getPaginated(
            10,
            $request->input('search')
        );

        return inertia('Dashboard/Categories/Index', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:news_categories',
            'is_main' => 'sometimes|boolean',
        ]);

        try {
            $this->categoryService->create($data);
            return redirect()->route('manage.categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:news_categories,name,' . $id,
            'slug' => 'sometimes|required|string|max:255|unique:news_categories,slug,' . $id,
            'is_main' => 'sometimes|boolean'
        ]);

        try {
            $this->categoryService->update((int) $id, $data);
            return redirect()->route('manage.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->categoryService->delete((int) $id);
            return redirect()->route('manage.categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
