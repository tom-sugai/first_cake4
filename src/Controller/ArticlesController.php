<?php
namespace App\Controller;

use App\Controller\AppController;

class ArticlesController extends AppController
{
    public function initialize(): void
    {
        parent ::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');
    }

    public function index()
    {
        $this->paginate = [
            'limit' => 10,
            'order' => ['Articles.id' => 'asc']
        ];

        $articles = $this->paginate($this->Articles->find());
        $this->set(compact('articles'));        
    }

    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            
            $article->user_id = 1;
            // 変更: セッションから user_id をセット
            //$article->user_id = $this->Auth->user('id');
 
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }
        $this->set('article', $article);
        //$users = $this->Articles->Users->find('list', ['limit' => 200]);
        //$tags = $this->Articles->Tags->find('list', ['limit' => 200]);
        //$this->set(compact('article', 'users', 'tags'));
    }

}