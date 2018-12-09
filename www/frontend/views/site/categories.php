<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<?php if ($categories) { ?>

<ul class="categories">
    <?php foreach ($categories as $category) { ?>
    <li class="item">
        <?= Html::a($category['name'], ['/site/category', 'slug' => $category['slug']]) ?>
    </li>
    <?php } ?>
</ul>

<?php } else { ?>

<div class="site-empty">
    <h2>Nothing found.</h2>
</div>

<?php } ?>
