<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\UserMeta;
use common\components\GoogleAuthenticator;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php
        $userSecrct = $model->getExtraValue('_2fa_secret');
        if (User::check2FAEnable() && empty($userSecrct)) {
            echo Html::a('Generate 2FA secret', ['generate-secret', 'id' => $model->id], ['class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Are you sure?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email',
            'role',
            'status',
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->updated_at);
                }
            ],
            [
                'label' => '2FA QR Code',
                'value' => function($model) {
                    $secret = $model->getExtraValue('_2fa_secret');
                    if (!empty($secret)) {
                        // var_dump(GoogleAuthenticator::verifyCode($secret, '789491'));
                        return Html::img(GoogleAuthenticator::getQRCodeGoogleUrl($model->username, $secret, 'Blog'));
                    } else {
                        return 'Missing secret.';
                    }
                },
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
