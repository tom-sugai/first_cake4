<p><?= "id : " . $article->id ?>
<h3><?= h($article->title) ?></h3>
<p><?= h($article->body) ?></p>
<!-- // display tag_string -->
<p><b>Tags:</b><?= h($article->tag_string) ?></p>
<p><small>Created : <?= $article->created->format(DATE_RFC850) ?></small></p>
<p><?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?></P>
<p><?= $this->Html->link('Post Comment', ['controller' => 'Comments','action' => 'add', $article->id]) ?></P>
---------------------------------------------------------------------------
<h5><?= __('Related Comments : ') ?><?= count($article->comments)?></h5>
    <?php if (!empty($article->comments)): ?>
            <?php foreach ($article->comments as $comment): ?>
                <div class="article-view-comments">
                    <div class="article-view-comment-info">
                        <?= h($comment->id) ?>
                        <?= h(strtok(strtok($comment->contributor,'@'),'.'))?>  
                        <?= h("created : " . $this->Time->format($comment->created, 'yyyy-MM-dd')) ?>
                        <?= h("modified : " . $this->Time->format($comment->modified, 'yyyy-MM-dd')) ?>
                        <?= h("published : " . $comment->published) ?>
                    </div> 
                    <div class="article-view-comment-body"><?= h($comment->body) ?></diV>
                    <div class="article-view-comment-action">
                        <div><?= $this->Html->link(__('Edit'), ['controller' => 'Comments', 'action' => 'edit', $comment->id]) ?></div>
                        <div><?= $this->Form->postLink(__('Delete'), ['controller' => 'Comments', 'action' => 'delete', $comment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comment->id)]) ?></div>
                    </div>
                </div>                 
            <?php endforeach; ?>    
    <?php endif; ?>   