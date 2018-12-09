<?php

namespace frontend\modules\api\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;

/**
 * Ip controller for the `ip` module
 */
class IpController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_RAW;
        $response->data = Yii::$app->request->getUserIp();
        return $response;
    }
}
