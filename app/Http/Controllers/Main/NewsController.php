<?php

namespace App\Http\Controllers\Main;

use App\Services\NewsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $categoryId = $request->input('category', '');
        $news = $this->newsService->getNewsByVisibility($search, $categoryId, 'public');

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
        $news = $this->newsService->getNewsByVisibility($search, null, 'internal');

        return inertia('News/Internal', [
            'news' => $news,
        ]);
    }
}
