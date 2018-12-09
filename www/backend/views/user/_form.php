<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <div class="form-group field-manager-password">
        <?= Html::label('Password', 'manager-password', ['class' => 'control-label']) ?>
        <?= Html::passwordInput('User[password]', '', ['class' => 'form-control']) ?>
    </div>

    <?= $form->field($model, 'role')->dropDownList([
        'user' => 'User',
        'manager' => 'Manager',
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        '10' => 'Active',
        '0' => 'Inactive',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
