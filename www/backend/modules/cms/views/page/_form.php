<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Base',
                'content' => $this->render('form/_base', [
                    'form' => $form,
                    'model' => $model,
                ]),
                'active' => true,
            ],
            [
                'label' => 'Meta',
                'content' => $model->renderExtraTabContent(),
            ],
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
