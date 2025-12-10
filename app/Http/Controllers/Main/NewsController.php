<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Services\NewsService;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index()
    {
        $news = $this->newsService->getAllNews();

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

    public function internal()
    {
        $news = $this->newsService->getInternalNews();

        return inertia('News/Internal', [
            'news' => $news,
        ]);
    }
}
