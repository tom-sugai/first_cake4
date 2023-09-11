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
            'order' => ['Articles.id' => 'desc']
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
            
            $article->user_id = 5;
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

    public function edit($slug = null)
    {
        // load article
        $article = $this->Articles->findBySlug($slug)->firstOrFail(); 
        //$article = $this->Articles->findBySlug($slug)->contain('Tags')->firstOrFail();
        // save process
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData(),[
                // 追加: user_id の更新を無効化
                'accessibleFields' => ['user_id' => false]   
            ]);
            
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('edit : The article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }
        //$users = $this->Articles->Users->find('list', ['limit' => 200]);
        //$tags = $this->Articles->Tags->find('list', ['limit' => 200]);
        //debug($tags);

        $this->set(compact('article'));
        //$this->set(compact('article', 'users', 'tags'));
    }

    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)){
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }
}