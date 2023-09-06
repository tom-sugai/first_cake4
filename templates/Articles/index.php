<h1>Article list</h1>
<table>
    <tr>
        <th>title</th>
        <th>created</th>
    </tr>
    <?php foreach ($articles as $article ): ?>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
    <?php endforeach; ?>
</table>