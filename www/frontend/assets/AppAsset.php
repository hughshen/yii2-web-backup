<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [];
    public $depends = [
        // 'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        // Append timestamp
        foreach ($this->css as $key => $val) {
            if (($timestamp = @filemtime("{$this->basePath}/{$val}")) > 0) {
                $this->css[$key] = "{$val}?v={$timestamp}";
            }
        }

        // Add preload
        $preloadList = [];
        foreach ($this->css as $val) {
            $preloadList[] = "</{$val}>; as=style; rel=preload";
        }
        Yii::$app->response->headers->add('Link', implode(', ', $preloadList));
    }
}
