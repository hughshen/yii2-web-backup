<?php

use yii\helpers\Html;
use common\models\Config;
use common\widgets\ExtraFieldInput;

?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_smtp_from',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_smtp_host',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_smtp_user',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_smtp_pass',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_smtp_auth',
        'valueData' => $config,
        'inputType' => 'dropdown',
        'valueList' => [
            '1' => 'Yes',
            '0' => 'No',
        ],
        'defaultValue' => '1',
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_smtp_secure',
        'valueData' => $config,
        'inputType' => 'dropdown',
        'valueList' => [
            '' => 'None',
            'ssl' => 'SSL',
            'tls' => 'TLS',
        ],
        'defaultValue' => 'ssl',
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_smtp_port',
        'valueData' => $config,
        'defaultValue' => 25,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>
