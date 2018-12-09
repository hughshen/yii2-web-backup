<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\modules\cms\models\Menu;

$termIds = ArrayHelper::getColumn((array)$model->getTerms()->asArray()->all(), 'id');

?>

<div class="form-group field-menu-group">
    <label class="control-label" for="menu-group">Group</label>
    <?= Html::dropDownList('Term[]', $termIds, Menu::menuGroupList(), ['class' => 'form-control', 'id' => "menu-group"]) ?>
</div>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'sorting')->textInput() ?>
