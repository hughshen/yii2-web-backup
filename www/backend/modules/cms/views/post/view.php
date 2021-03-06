<?php

use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

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
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'parent',
            'author',
            'title',
            // 'content:html',
            [
                'attribute' => 'content',
                'value' => function($model) {
                    return Markdown::process($model->content, 'gfm');
                },
                'format' => 'raw',
            ],
            'excerpt',
            'slug',
            'guid',
            'type',
            'mime_type',
            'sorting',
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
        ],
    ]) ?>

</div>
