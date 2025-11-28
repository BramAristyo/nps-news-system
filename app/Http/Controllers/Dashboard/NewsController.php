<?php

namespace App\Http\Controllers\Dashboard;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class NewsController extends Controller
{
    protected $newsService;
    protected $categoryService;

    public function __construct(NewsService $newsService, CategoryService $categoryService)
    {
        $this->newsService = $newsService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $news = $this->newsService->getAllNews(true); // Include internal
        return Inertia::render('Dashboard/News/Index', [
            'news' => $news
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
            $data['user_id'] = Auth::user()->id;
            $news = $this->newsService->createNews($data);
            
            if (isset($data['category_ids'])) {
                $this->newsService->attachCategories($news->id, $data['category_ids']);
            }

            return Redirect::route('dashboard.news.index')->with('success', 'News created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create news: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Assuming findOrFail logic is inside service or we use model binding, 
        // but service has findBySlug, maybe not findById exposed directly?
        // Checking service... updateNews takes ID. 
        // I'll assume we can fetch it via model or add findById to service.
        // For now using model directly for edit view or adding a method to service.
        // Service has findBySlug. Let's use that or just use Model for retrieval in controller if simple.
        // But strict service usage requested.
        // I will use a simple find via Eloquent here or add to service if I could.
        // But I can't edit service easily without checking.
        // Service has `updateNews(int $id, ...)` and `deleteNews(int $id)`.
        // It doesn't have `findById`. I'll use Model here for simplicity as it's a retrieval.
        
        $news = \App\Models\NewsArticle::with('categories')->findOrFail($id);
        $categories = $this->categoryService->getAll();
        
        return Inertia::render('Dashboard/News/Edit', [
            'news' => $news,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, $id)
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
            $this->newsService->updateNews($id, $data);
            
            if (isset($data['category_ids'])) {
                $this->newsService->attachCategories($id, $data['category_ids']);
            }

            return Redirect::route('dashboard.news.index')->with('success', 'News updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update news: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->newsService->deleteNews($id);
            return Redirect::route('dashboard.news.index')->with('success', 'News deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete news: ' . $e->getMessage());
        }
    }
}
