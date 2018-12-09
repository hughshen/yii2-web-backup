<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

AppAsset::register($this);

$this->title = 'Login';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="single-form-page">
    <div class="single-form-wrap">
        <h1 class="single-form-title">Login to your account</h1>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?php if ($model->getScenario() == 'stepTwo') { ?>

        <?= $form->field($model, 'code')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('code')]) ?>

        <?php } else { ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'options' => [
                'class' => 'form-control',
                'placeholder' => $model->getAttributeLabel('verifyCode'),
            ],
        ]) ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <?php } ?>

        <div class="form-group form-group-footer">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-submit', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
