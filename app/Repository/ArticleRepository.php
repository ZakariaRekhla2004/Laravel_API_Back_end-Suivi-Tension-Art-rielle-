<?php

namespace App\Repository;

use App\Interfaces\ArticleInterfaces;
use App\Models\Article;
class ArticleRepository implements ArticleInterfaces {

    public function get_articles(){
        return Article::get();
    }
    public function add_article($validator) {
        $article=Article::create($validator->validated());
        return $article;
    }

}