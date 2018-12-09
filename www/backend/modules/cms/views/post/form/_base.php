<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\components\markdown\Markdown;
use backend\modules\cms\models\Post;

?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'content')->textarea(['rows' => 15]) ?>

<div class="form-group">
    <?php
    Modal::begin([
        'header' => '<h2>Markdown Preview</h2>',
        'toggleButton' => [
            'label' => 'Markdown Preview',
            'tag' => 'a',
            'class'=>'btn btn-success',
            'id' => 'markdown-preview'
        ],
    ]);
    echo Html::tag('div', null,['id' => 'markdown-viewer', 'style' => 'max-height: 400px; overflow-y: scroll;']);
    Modal::end();
    ?>
</div>

<?= $form->field($model, 'excerpt')->textarea() ?>

<?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'disabled' => ($model->status == Post::STATUS_PUBLISH) ? true : false]) ?>

<?= $form->field($model, 'sorting')->textInput() ?>

<?= $form->field($model, 'status')->dropDownList(Post::statusList()) ?>

<?php
$this->registerJs("
;(function($) {
$('#markdown-preview').on('click', function() {
    $.ajax({
        url: '" . Url::to(['to-markdown']) . "',
        type: 'post',
        data: {content: $('#" . Html::getInputId($model, 'content') . "').val(), '" . Yii::$app->request->csrfParam . "': '" . Yii::$app->request->csrfToken . "'},
        success: function(data) {
            $('#markdown-viewer').html(data);
        }
    });
});
})(jQuery);
", \yii\web\View::POS_END);
?>
