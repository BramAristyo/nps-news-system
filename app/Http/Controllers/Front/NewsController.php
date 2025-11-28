<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index()
    {
        $news = $this->newsService->getPublicNews();
        return Inertia::render('Home', [
            'news' => $news
        ]);
    }

    public function show($slug)
    {
        $article = $this->newsService->findBySlug($slug);
        return Inertia::render('News/Show', [
            'article' => $article
        ]);
    }

    public function internal()
    {
        $news = $this->newsService->getInternalNews();
        return Inertia::render('Internal/Index', [
            'news' => $news
        ]);
    }
}
