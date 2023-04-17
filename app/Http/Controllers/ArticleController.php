<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
class ArticleController extends Controller
{
    public ArticleService $articleService;
    public function __construct(ArticleService $articleService)
    {
        $this->articleService=$articleService;
        $this->middleware('jwt');
    }
    public function addArticle(Request $request) {
    
    $articles = $this->articleService->add_article($request);
 
    return response()->json([
        'message' => 'article successfully registered'
    ], 201);
}
    public function getArticle() {
            $articles = $this->articleService->get_artilcles();
            return response()->json([
            'article' => $articles
        ], 201);
    }
}
