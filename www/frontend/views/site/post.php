<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<article>
    <a href="<?= Url::home(true) ?>" class="back"><svg xmlns="http://www.w3.org/2000/svg" class="icon" version="1" viewBox="0 0 1024 1024"><path d="M774 467H415l110-108c18-18 18-48 0-66a48 48 0 0 0-67 0L273 475a47 47 0 0 0-11 11 45 45 0 0 0 13 66l183 180c19 18 48 18 67 0 18-18 18-47 0-65L415 559h359c26 0 48-21 48-46s-22-46-48-46zm0 0"></path><path d="M512 93a419 419 0 1 1 0 838 419 419 0 0 1 0-838m0-93A509 509 0 0 0 0 512a509 509 0 0 0 512 512 509 509 0 0 0 512-512A509 509 0 0 0 512 0z"></path></svg></a>
    <div class="head">
        <h1><?= $post['title'] ?></h1>
        <p class="meta">
            <?= Html::tag('time', date('M d, Y', $post['created_at']), [
                'datetime' => date('c', $post['created_at']),
                'itemprop' => 'datePublished',
                'class' => 'icon-calendar',
            ]) ?>
            <?php
            foreach ($post['categories'] as $category) {
                echo Html::a($category['name'], ['/site/category', 'slug' => $category['slug']], ['class' => 'icon-quill']);
            }
            foreach ($post['tags'] as $tag) {
                echo Html::a($tag['name'], ['/site/tag', 'slug' => $tag['slug']], ['class' => 'icon-tag']);
            }
            ?>
        </p>
    </div>
    <div class="body">
        <?= $post['content'] ?>
    </div>
</article>
