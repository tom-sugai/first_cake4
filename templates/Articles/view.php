<p><?= "id : " . $article->id ?>
<h1><?= h($article->title) ?></h1>
<p><?= h($article->body) ?></p>
<!-- // display tag_string -->
<p><b>Tags:</b><?= h($article->tag_string) ?></p>
<p><small>Created : <?= $article->created->format(DATE_RFC850) ?></small></p>
<p><?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?></P>