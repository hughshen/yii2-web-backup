<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\LinkPager;

$this->title = Yii::t('app', 'Media');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="media-search">

        <div class="row">
            <div class="col-md-6">
                <?= Html::beginForm(['index'], 'get') ?>
                <?= Html::hiddenInput('folder', Yii::$app->request->get('folder')) ?>
                <div class="row">
                    <div class="col-md-4">
                        <?= Html::textInput('search', Yii::$app->request->get('search'), ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-md-8">
                        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                        <?= Html::a(Yii::t('app', 'Reset'), ['index'], ['class' => 'btn btn-default']) ?>
                    </div>
                </div>                
                <?= Html::endForm() ?>
            </div>
            <div class="col-md-6">
                <?= Html::beginForm(['create-folder'], 'get') ?>
                <?= Html::hiddenInput('folder', Yii::$app->request->get('folder')) ?>
                <div class="row">
                    <div class="col-md-4">
                        <?= Html::textInput('create', null, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-md-8">
                        <?= Html::submitButton(Yii::t('app', 'Create Folder'), ['class' => 'btn btn-primary']) ?>
                        <?= Html::button(Yii::t('app', 'Upload'), ['class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#upload-modal']) ?>
                    </div>
                </div>                
                <?= Html::endForm() ?>
            </div>
            <div class="col-md-12">
                <?php
                Modal::begin([
                    'id' => 'upload-modal',
                    'header' => '<h2>Upload</h2>',
                ]);
                echo $this->render('_upload', [
                    'model' => $model,
                ]);
                Modal::end();
                ?>
            </div>
        </div>

    </div>

    <hr>

    <div class="media-list">

        <div class="row">
            
            <?php
            // List
            foreach ($list as $key => $val) {
                echo $this->render('_item', [
                    'item' => $val,
                ]);
            }
            ?>

        </div>

        <div class="row">
            <div class="col-md-12">
                <?php
                echo LinkPager::widget([
                    'pagination' => $pages,
                    'prevPageLabel' => '&lsaquo;',
                    'nextPageLabel' => '&rsaquo;',
                    'firstPageLabel' => '&laquo;',
                    'lastPageLabel' => '&raquo;',
                    'maxButtonCount' => 4,
                ]);
                ?>
            </div>
        </div>
        
    </div>

</div>
