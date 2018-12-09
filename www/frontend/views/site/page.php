<?php

use yii\helpers\Url;
use yii\helpers\Html;

$extra = json_decode($post['extra_data'], true);

$styles = isset($extra['_post_styles']) ? trim($extra['_post_styles']) : null;
$scripts = isset($extra['_post_scripts']) ? trim($extra['_post_scripts']) : null;

if ($styles) {
    $this->registerCss($styles);
}
if ($scripts) {
    $this->registerJs($scripts, \yii\web\View::POS_END);
}

?>

<div id="page">
    <?= $post['content'] ?>
</div>
