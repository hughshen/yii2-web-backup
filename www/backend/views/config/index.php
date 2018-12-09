<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

$this->title = 'Config';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="config-form">

        <?php $form = ActiveForm::begin([
            'action' => Url::to(['/config/update'])
        ]); ?>

        <?php
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'Base',
                    'content' => $this->render('_base', [
                        'config' => $config,
                    ]),
                    'active' => true,
                ],
                [
                    'label' => 'Static',
                    'content' => $this->render('_static', [
                        'config' => $config,
                    ]),
                ],
                [
                    'label' => 'Mail',
                    'content' => $this->render('_mail', [
                        'config' => $config,
                    ]),
                ],
            ],
        ]);
        ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
