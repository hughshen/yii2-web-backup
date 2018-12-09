<?php

use yii\helpers\Html;
use common\models\Config;
use common\widgets\ExtraFieldInput;

?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_name',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_keywords',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_description',
        'inputType' => 'textarea',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_logo',
        'inputType' => 'image',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_copyright',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_tagline',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_email',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_domain',
        'valueData' => $config,
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_site_noindex',
        'valueData' => $config,
        'inputType' => 'dropdown',
        'valueList' => [
            '1' => 'Yes',
            '0' => 'No',
        ],
        'defaultValue' => '0',
        'arrayName' => $this->context->arrayName,
    ],
]) ?>

<?= ExtraFieldInput::widget([
    'options' => [
        'fieldName' => '_2fa_enable',
        'valueData' => $config,
        'inputType' => 'dropdown',
        'inputLabel' => 'Two-factor authentication',
        'valueList' => [
            '1' => 'Yes',
            '0' => 'No',
        ],
        'defaultValue' => '0',
        'arrayName' => $this->context->arrayName,
    ],
]) ?>
