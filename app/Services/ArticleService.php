<?php

namespace App\Services;
use App\Repository\ArticleRepository;
use Validator;
class ArticleService{
    public ArticleRepository $articleRepository;

        public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository=$articleRepository;
    }
    public function get_artilcles(){
        return $this->articleRepository->get_articles();
    }
    public function add_article($request) {
    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'description'=>'required'
    ]);
    if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
    }
    $article=$this->articleRepository->add_article($validator);
    return $article;
}


}