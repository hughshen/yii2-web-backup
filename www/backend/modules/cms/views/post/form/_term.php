<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\widgets\ExtraFieldInput;
use backend\modules\cms\models\Category;
use backend\modules\cms\models\Tag;

$termIds = ArrayHelper::getColumn((array)$model->getTerms()->asArray()->all(), 'id');

?>

<?= ExtraFieldInput::widget([
    'options' => [
        'inputId' => 'post-category',
        'inputName' => 'Term[]',
        'inputType' => 'checkboxlist',
        'defaultValue' => $termIds,
        'valueList' => ArrayHelper::map(Category::termList(), 'id', 'name'),
        'inputLabel' => 'Categories',
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'inputId' => 'post-tag',
        'inputName' => 'Term[]',
        'inputType' => 'checkboxlist',
        'defaultValue' => $termIds,
        'valueList' => ArrayHelper::map(Tag::termList(), 'id', 'name'),
        'inputLabel' => 'Tags',
    ],
]) ?>
