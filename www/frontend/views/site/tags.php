<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<?php if ($tags) { ?>

<ul class="tags">
    <?php foreach ($tags as $tag) { ?>
    <li class="item">
        <?= Html::a('#' . $tag['name'], ['/site/tag', 'slug' => $tag['slug']]) ?>
    </li>
    <?php } ?>
</ul>

<?php } else { ?>

<div class="site-empty">
    <h2>Nothing found.</h2>
</div>

<?php } ?>
