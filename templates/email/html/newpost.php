<?= $message ?><br>
<?= "投稿番号 : " . $article->id ?><br>
<?= "タイトル : " . $article->title ?><br>
<?= "本　　文 : " . $this->Html->link($article->body, ['controller' => 'Articles', 'action' => 'view', $article->slug]) . "<br/>" ?><br>