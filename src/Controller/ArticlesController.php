<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use App\Event\NotificationListener;
use Cake\Mailer\Mailer;

class ArticlesController extends AppController
{
    private $mailer;
    private $userid = null;
    private $useremail = null;

    public function initialize(): void
    {
        parent ::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');
        $this->loadComponent('SendMail');

        $this->mailer = new Mailer('default');


    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        // get login user info
        if($this->request->getAttribute('identity') !== null){
            $this->userid = $this->request->getAttribute('identity')->getIdentifier();
            //debug($this->userid);
            $this->loadModel('Users');
            $user = $this->Users->get($this->userid);
            $this->useremail = $user->email;

        }

        // アプリケーション内のすべてのコントローラーの index と view アクションをパブリックにし、認証チェックをスキップします
        $this->Authentication->addUnauthenticatedActions(['index','view','tags', 'top']);
    }

    public function smail()
    {
        $this->Authorization->skipAuthorization();

        $this->autoLayout = true;
        $this->autoRender = true;
        //$this->viewBuilder()->setLayout('otsukai_layout');
        
        /** 
        // put here Event dispatch program here
        $message = "New Post by " . $this->useremail;
        //debug($message);
        //$event = new Event('Notification.E-Mail',$this,['message' => $message, 'article' => $article]);
        $event = new Event('Notification.E-Mail',$this,['message' => $message]);
        //debug($event);
        $this->getEventManager()->dispatch($event);
        $this->Flash->success(__('event dispatched.' . "form $this->useremail"));

        //return $this->redirect(['action' => 'index']);
        */
        
        $message = "New Post by " . $this->useremail;
        $this->mailer
            ->setEmailFormat('html')
            ->setTo('fumiko@svr.home.com')
            ->setSubject('New Post')
            ->setViewVars(['message' => $message, 'article' => $article])
            ->viewBuilder()
                ->setTemplate('newpost')
                ->setLayout('default');
        $this->mailer->deliver();
        

    }

    public function top()
    {
        $this->Authorization->skipAuthorization();

        $this ->autoLayout = true;
        $this->autoRender = true;
        $this->viewBuilder()->setLayout('articleLayout-1');

        //$this->Flash->set('---- Flash test from /fumiko4() ----');
        //$this->set('msg',"fumichan !!");

        $headertext = "headertext : Articles Application";
        $this->set('headertext',$headertext);
        $footertext = "footertext : end Articles Application";
        $this->set('footertext', $footertext);
        
        $this->paginate = [
            'contain' => ['Users','Tags', 'Comments'],
            'limit' => 20,
            'order' => ['Articles.id' => 'desc']
        ];

        $articles = $this->paginate($this->Articles);
        $this->set(compact('articles'));
        //$this->set('loginname', $this->Auth->user('email'));

    }


    public function index()
    {
        // allow all user to index articlesTable unless autenticate & authorize 
        $this->Authorization->skipAuthorization();
        $this->paginate = [
            'contain' => ['Users', 'Comments'],
            'limit' => 20,
            'order' => ['Articles.id' => 'desc']
        ];

        $articles = $this->paginate($this->Articles->find());
        $this->set(compact('articles'));        
    }

    public function view($slug = null)
    {
        // allow all user to view article 
        $this->Authorization->skipAuthorization();
        $article = $this->Articles->findBySlug($slug)->contain(['Users','Tags','Comments' => ['sort' => ['Comments.id' => 'DESC']]])->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        // check Authoraization Policy
        $this->Authorization->authorize($article);
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            // set $userid in beforfilter
            $article->user_id = $this->userid;
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));
                //debug($article);
                $message = "New Post by " . $this->useremail;
                $this->mailer
                    ->setEmailFormat('html')
                    ->setTo('fumiko@svr.home.com')
                    ->setSubject('New Post')
                    ->setViewVars(['message' => $message, 'article' => $article])
                    ->viewBuilder()
                        ->setTemplate('newpost')
                        ->setLayout('default');
                $this->mailer->deliver();
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }
        // get Tag list
        $tags = $this->Articles->Tags->find('list')->all();
        // set view context
        $this->set('tags', $tags);
        $this->set('article', $article);

        //$users = $this->Articles->Users->find('list', ['limit' => 200]);
        //$this->set(compact('article', 'users', 'tags'));
    }

    public function edit($slug = null)
    {
        // load article
        $article = $this->Articles->findBySlug($slug)->contain('Tags')->firstOrFail(); 
        // check Authoraization Policy
        $this->Authorization->authorize($article);
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
        $tags = $this->Articles->Tags->find('list')->all();
        //set view context
        $this->set('tags', $tags);
        $this->set(compact('article'));
        //$this->set(compact('article', 'users', 'tags'));
    }

    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        // check Authoraization Policy
        $this->Authorization->authorize($article);
        if ($this->Articles->delete($article)){
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function tags()
    {
        // allow all user to use tags method unless autenticate & authorize 
        $this->Authorization->skipAuthorization();
        $tags = $this->request->getParam('pass');
        //debug($tags);
        $articles =$this->Articles->find('tagged', ['tags' => $tags])->all();

        $this->set([
            'articles' => $articles,
            'tags' => $tags
        ]);
    }
}