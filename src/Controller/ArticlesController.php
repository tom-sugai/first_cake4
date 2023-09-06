<?php
namespace App\Controller;

//use App\Controller\AppController;

class ArticlesController extends AppController
{
    public function index()
    {
        $this->loadComponente('Paginator'); 
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));        
    }
}