<?php

namespace App\Http\Controllers\Main;

use App\Services\CategoryService;
use App\Services\NewsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $categorySlug = $request->input('category', '');
        
        $news = $this->newsService->getNewsByVisibility($search, $categorySlug, 'public');

        return inertia('News/Index', [
            'news' => $news,
        ]);
    }

    public function show(string $slug)
    {
        $newsArticle = $this->newsService->findBySlug($slug);

        return inertia('News/Show', [
            'newsArticle' => $newsArticle,
        ]);
    }

    public function internal(Request $request)
    {
        $search = $request->input('search', '');
        $news = $this->newsService->getNewsByVisibility($search, '', 'internal');

        return inertia('News/Internal', [
            'news' => $news,
        ]);
    }
}
