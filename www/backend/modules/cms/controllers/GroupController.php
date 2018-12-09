<?php

namespace backend\modules\cms\controllers;

use Yii;

use backend\modules\cms\models\Group;
use backend\modules\cms\models\search\GroupSearch;

/**
 * GroupController implements the CRUD actions for Group model.
 */
class GroupController extends \backend\controllers\BackendController
{
    use \common\traits\CRUDControllerTrait;

    protected function getSearchClassName()
    {
        return GroupSearch::className();
    }

    protected function getModelClassName()
    {
        return Group::className();
    }
}
