<?php

namespace App\Services;
use App\Models\Article;
use Validator;
class ArticleService{

    public function get_artilcles(){
        return Article::get();
    }
    public function add_article($request) {
    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'description'=>'required'
    ]);
    if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
    }
    $article=Article::create($validator->validated());
    return $article;
}


}