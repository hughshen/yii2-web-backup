<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use common\models\Config;
use backend\modules\cms\models\Post;
use backend\modules\cms\models\Term;
use backend\modules\cms\models\Group;
use backend\modules\cms\models\Relationship;

/**
 * Frontend controller
 */
class FrontendController extends Controller
{
    public $config;
    public $menus;

    public $seo = [];
    public $siteHeading = null;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initData();
        $this->initSeo();
    }

    protected function initData()
    {
        $this->config = Config::allData();
        $this->menus = Group::frontendMenus('header-menu');
    }

    protected function initSeo()
    {
        $this->seoNoIndex();
        $this->seo['title'] = $this->configByName('_site_name');
        $this->seo['keywords'] = $this->configByName('_site_keywords');
        $this->seo['description'] = $this->configByName('_site_description');

        // if ((int)$this->configByName('_site_noindex') === 1 && stripos($this->configByName('_site_domain'), Yii::$app->request->getHostName()) === false) {
        //     $this->seoNoIndex();
        // }
    }

    protected function registerSeo()
    {
        foreach ($this->seo as $key => $val) {
            if (!$val) continue;
            if ($key === 'title') {
                $this->getView()->title = $val;
            } elseif (!is_numeric($key)) {
                $this->getView()->registerMetaTag(['name' => $key, 'content' => $val]);
            } else {
                $this->getView()->registerMetaTag($val);
            }
        }
    }

    public function seoNoIndex()
    {
        $this->seo['robots'] = 'noindex, nofollow';
    }

    public function render($view, $params = [])
    {
        $this->registerSeo();

        $content = $this->getView()->render($view, $params, $this);
        return $this->renderContent($content);
    }

    public function configByName($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return null;
        }
    }
}
