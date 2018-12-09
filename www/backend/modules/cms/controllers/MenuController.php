<?php

namespace backend\modules\cms\controllers;

use Yii;

use backend\modules\cms\models\Menu;
use backend\modules\cms\models\search\MenuSearch;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends \backend\controllers\BackendController
{
    use \common\traits\CRUDControllerTrait;

    protected function getSearchClassName()
    {
        return MenuSearch::className();
    }

    protected function getModelClassName()
    {
        return Menu::className();
    }

    public function actionTypeList()
    {
        $data  =[];

        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $type = Yii::$app->request->post('t');
            $data = Menu::menuIdList($type);
        }

        return $this->asJson($data);
    }
}
