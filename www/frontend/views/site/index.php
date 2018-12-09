<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;

?>

<?php if ($posts) { ?>

<ul class="posts">
    <?php foreach ($posts as $post) { ?>
    <li class="item">
        <?= Html::a($post['title'], ['/site/post', 'slug' => $post['slug']]) ?>
        <?= Html::tag('time', date('M d, Y', $post['created_at']), [
            'datetime' => date('c', $post['created_at']),
            'itemprop' => 'datePublished',
        ]) ?>
    </li>
    <?php } ?>
</ul>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
    'prevPageLabel' => 'Prev',
    'nextPageLabel' => 'Next',
    'firstPageLabel' => 'First',
    'lastPageLabel' => 'Last',
    'maxButtonCount' => 1,
]);
?>

<?php } else { ?>

<div class="site-empty">
    <h2>Nothing found.</h2>
</div>

<?php } ?>
