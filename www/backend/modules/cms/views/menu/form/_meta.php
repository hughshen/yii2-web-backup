<?php

use yii\helpers\Url;
use yii\helpers\Html;

echo $model->renderExtraTabContent();

$this->registerJs("
;(function($) {
$('#ExtraFields_menu_type').on('change', function() {
    $.ajax({
        url: '" . Url::to(['type-list']) . "',
        type: 'post',
        data: {t: $(this).val()},
        dataType: 'json',
        success: function(data) {
            var options = '';
            $(data).each(function(key, val) {
                options += '<option value=\"' + val.id + '\">' + val.value + '</option>';
            });
            $('#ExtraFields_menu_type_id').html(options);
        }
    });
});
})(jQuery);
", \yii\web\View::POS_END);

?>
