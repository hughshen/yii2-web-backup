<?php

use yii\helpers\Html;
use common\models\Config;
use common\widgets\ExtraFieldInput;

?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_google_analytics_code',
        'inputType' => 'textarea',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_google_verification',
        'inputType' => 'textarea',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_bing_auth',
        'inputType' => 'textarea',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>
