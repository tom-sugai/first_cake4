<?php
namespace App\Controller;

use App\Controller\AppController;

class ArticlesController extends AppController
{
    public function index()
    {
        $this->loadComponent('Paginator'); 
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));        
    }

    public function view()
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }
}