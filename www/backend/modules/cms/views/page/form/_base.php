<?php

use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\cms\models\Page;

?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'content')->textarea(['rows' => 18]) ?>

<?= $form->field($model, 'excerpt')->textarea() ?>

<?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'disabled' => ($model->status == Page::STATUS_PUBLISH) ? true : false]) ?>

<?= $form->field($model, 'sorting')->textInput() ?>

<?= $form->field($model, 'status')->dropDownList(Page::statusList()) ?>
