<?php

use yii\helpers\Url;
use yii\helpers\Html;
use frontend\assets\AppAsset;
use backend\modules\cms\models\Menu;

AppAsset::register($this);

$isSingle = Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'post';

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="main">
    <?php if (!$isSingle) { ?>
    <div class="author">
        <p class="avatar">
            <img src="data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QNxaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6MEE4MDExNzQwNzIwNjgxMTg1M0RBOUY3NzgyOTVBNDAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QTI1MjNBN0E5MjEzMTFFNTg3NEVBMEVGRDhFNEFGMzciIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QTI1MjNBNzk5MjEzMTFFNTg3NEVBMEVGRDhFNEFGMzciIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNS4xIE1hY2ludG9zaCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjEyN0JBQ0E1OTEyMDY4MTE4NTNEQTlGNzc4Mjk1QTQwIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjBBODAxMTc0MDcyMDY4MTE4NTNEQTlGNzc4Mjk1QTQwIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4ADkFkb2JlAGTAAAAAAf/bAIQABgQEBAUEBgUFBgkGBQYJCwgGBggLDAoKCwoKDBAMDAwMDAwQDA4PEA8ODBMTFBQTExwbGxscHx8fHx8fHx8fHwEHBwcNDA0YEBAYGhURFRofHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8fHx8f/8AAEQgAyADIAwERAAIRAQMRAf/EAHcAAQADAQEBAQAAAAAAAAAAAAABBQYEAwIIAQEAAAAAAAAAAAAAAAAAAAAAEAACAQIEAQgHBwQDAAAAAAAAAQIDBBEhMQVBUWFxgRIiEwaRobHBMnIU8NFCUmKSM7LiIyQ0RBURAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AP0iAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABIEAAAAAAAAAAAAAAAAAAAAAAAAHhd31pZxxuKig38MFnN9EUBT3HmibxVtQUV+eq8X+1ZAcFTfN2n/wBhw5oJRXsA8/8A09yxx+qq/uA9Ke+btB/8hz5ppSXsA77fzRUWCuaCkuM6Twf7XkBcWl9aXkcbeoptfFB5TXTFge4AAAAAAAAAAAAAAAAAAkCj3TzB2HKhYtOayncapPkhy9IGflKU5uc5OU5ZylJ4t9LAgAAAAAJjKUJKcJOM45xlF4NdDA0G1+YO240L5pTeULjRN8k+R84F5oBAAAAAAAAAAAAAAAACi3/dnFysbeWD0uKi/oXvAoAAAAAAAAAAC/2DdnJxsbiWL0t6j/ofuAvQAAAAAAAAAAAAAAOXdL76KynWX8r7lFfqfHq1AxuLbbbxbzberb4gAAAAAAAAAABi0008Gs01qmuIGy2u++tsoVn/ACruVl+pcevUDqAAAAAAAAAAAAABmvMty6l7Ggn3KEc/nlm/UBUAAAAAAAAAAAABb+Wrl072VBvuV45fPDNeoDSgAAAAAAAAAAABKWLSAxF5Vda8r1X+OpJ+vADxAAAAAAAAAAAAD2s6ro3lCqvwVIv14MDbtYNrkAgAAAAAAAAAAASgME9W+d+0AAAAAAAAAAAAABarpXtA3rAgAAAAAAAAAAATHVAYWtB069WD1jOS9DYHwAAAAAAAAAAAAH3Rg6lelBaynFeloDdS+JgQAAAAAAAAAAAAGU3+38Hc6kvw1kqkevKXrQFcAAAAAAAAAAAAFjsFv42505Yd2inUl1ZR9bA1YAAAAAAAAAAAAAKzzDZOvZeNBY1bfGWHFwfxLq1AywAAAAAAAAAAAAajy9ZOhZeNNYVbjCWHFQXwrr1AtAAAAAAAAAAAAAAAMpvO2Oyr9umv9Wq/8b/K9XB+4CuAAAAAAAAAALHZtsd7X7dRf6tJ41H+Z8IL3gawCAAAAAAAAAAAAAAAPitRpV6UqNaKnTmsJRf21Ayu57RXsZOaxqWzfdq8nNPkYHAAAAAAAAB37ZtFe+kpvGnap96ry80OV84Gqo0aVClGjRioU4LCMV9tQPsAAAAAAAAAAAAAAABE5QhBznJQhHOUpPBLrApb7zLTSlTs4Krjk6tRdx9EePWBn28W3gli8cEsF1ICAAAAAA+oS7M4y7MZdl49mSxi+lAaSw8xWtXs0riKtp6Ra/i/tAtvY9GAAAAAAAAAAAAAAAA576/t7Kj4lZ4t/wAdNfFJ833gZW/3K6vp41XhTT7lGPwx+984HKAAAAAAAAAAd+27xcWTUHjVtuNJvNc8Hw6ANTbXNC5oqtQl26b9KfI1wYHoAAAAAAAAAAAAHLuO40bGh4k+9UllSpcZP7lxAyVzc17mtKtXl2qkuPBLkS4IDyAAAAAAAAAAAADpsL+vZV/FpZxeVSm9Jrn5+Rga61uqF1QjXovGEsmnrFrWL50B6gAAAAAAAAAHnc3NG2oTr1nhCC0Wrb0iudgY28u613cSr1X3pZRitIx4RQHiAAAAAAAAAAAAAAB2bXuM7G47ecqM8q1PlXKudAa+E4ThGcJKUJpSjJaNPiBIAAAAAAAErMDK77uP1dz4VN429BtR5JT0cvcgKwAAAAAAAAAAAAAAAAAvPLm49mf0NV92bbt2+EtXHr1QGgAAAAAAAAr98vna2TjB4V6+MKb4pfil6AMmlgsAAAAAAAAAAAAAAAAAABKcotSi+zKLxjJcGtGBs9vvI3lnTrrKTyqR5JrX7wOgAAAAAJSxeAGQ3m8+qv6kovGlT/x0uiOr62BwgAAAAAAAAAAAAAAAAAABb+W7zwruVtJ9y4Xd+eKy9KyA0oAAAAAcu6XTtdvrVU8J4din80skBjUsFgAAAAAAAAAAAAAAAAAAAAD6hOdOcakHhODUovnWYG3o1oV6NOtD4asVJdYH2AAAAKHzRcZ29snpjVmunKPvAoQAAAAAAAAAAAAAAAAAAAAANP5auPEsJUW86E2l8ss17wLUAAAAZHeq3i7pXeqg1Tj0QWHtA4QAAAAAAAAAAAAAAAAAAAAALfyzW7F/OlwrU36YPEDSgAAE9pRxk9IrF9WYGDlNznKb1nJy9LxAgAAAAAAAAAAAAAAAAAAAAADq2ur4W5W0+HiJPoll7wNm8mBAH//Z">
        </p>
        <p class="name">
            <?= Html::a($this->context->configByName('_site_name'), Url::home(true)) ?>
        </p>
        <p class="bio">
            <?= $this->context->configByName('_site_description') ?>
        </p>
        <p class="menu">
            <a class="icon-home" href="<?= Url::home(true) ?>"></a>
            <a class="icon-categories" href="<?= Url::to(['/site/categories'], true) ?>"></a>
            <a class="icon-tags" href="<?= Url::to(['/site/tags'], true) ?>"></a>
        </p>
        <div class="search">
            <form action="<?= Url::to(['/site/search']) ?>" method="get">
                <?= Html::textInput('s', Yii::$app->request->get('s')) ?>
            </form>
        </div>
    </div>
    <?php } ?>
    <div class="page">
        <?php if ($this->context->siteHeading) { ?>
            <h1 class="site-heading"><?= $this->context->siteHeading ?></h1>
        <?php } ?>
        <?= $content ?>
    </div>
    <div class="footer">
        <span class="copyright">
            <?= $this->context->configByName('_site_copyright') ?>.
        </span>
        <span class="theme-author">Theme inspired by <a target="_blank" href="https://mirror.am0200.com/">Mirror</a>.
        <span>
    </div>
</div>
<?= $this->context->configByName('_site_google_analytics_code') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
