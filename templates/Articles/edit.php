<div class="articles form large-9 medium-8 columns content">
    <?= $this->Form->create($article) ?>
    <fieldset>
        <legend><?= __('Edit Article') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['type' => 'hidden']);
            echo $this->Form->control('title');
            echo $this->Form->control('body',['rows' => '3']);
            echo $this->Form->control('published');
            // tagsテーブルからタグ一覧の選択リストを表示する
            echo $this->Form->control('tags._ids',['options' => $tags]);    
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save Article')) ?>
    <?= $this->Form->end() ?>
</div>