<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\NewsArticle;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    protected $newsService;
    protected $categoryService;

    public function __construct(NewsService $newsService, CategoryService $categoryService)
    {
        $this->newsService = $newsService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $categoryId = $request->input('category', '');
        $visibility = $request->input('visibility', 'all');

        $news = $this->newsService->getNewsByVisibility($search, $categoryId, $visibility);
        $categories = $this->categoryService->getAll();

        return inertia('Dashboard/News/Index', [
            'news' => $news,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'visibility']),
        ]);
    }

    public function create()
    {
        $categories = $this->categoryService->getAll();
        return Inertia::render('Dashboard/News/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_internal' => 'boolean',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:news_categories,id',
        ]);

        try {
            $data['user_id'] = Auth::id();
            $this->newsService->createNews($data);
            return redirect()->route('manage.news.index')->with('success', 'News article created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create news article: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $newsArticle = NewsArticle::with('categories')->findOrFail($id);
        $categories = $this->categoryService->getAll();

        return inertia('Dashboard/News/Edit', [
            'news' => $newsArticle,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_internal' => 'sometimes|boolean',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:news_categories,id',
        ]);

        try {
            $this->newsService->updateNews((int) $id, $data);
            return redirect()->route('manage.news.index')->with('success', 'News article updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update news article: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->newsService->deleteNews((int) $id);
            return redirect()->route('manage.news.index')->with('success', 'News article deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete news article: ' . $e->getMessage());
        }
    }
}
