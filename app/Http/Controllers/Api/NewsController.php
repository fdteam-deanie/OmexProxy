<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\News\ArticleDetailResource;
use App\Http\Resources\News\ArticleResource;
use App\Models\News\Article;
use App\Models\News\Category;
use Illuminate\Http\Request;

class NewsController
{
    public function categories()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => $categories
        ]);
    }
    public function index(Category $category, Request $request)
    {
        $articles = $category->articles()->paginate(12);
        return ArticleResource::collection($articles);
    }

    public function show(Article $article)
    {
        return new ArticleDetailResource($article);
    }
}
