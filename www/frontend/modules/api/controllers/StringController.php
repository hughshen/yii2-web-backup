<?php

namespace frontend\modules\api\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;

/**
 * String controller for the `ip` module
 */
class StringController extends Controller
{
    public function actionRandom()
    {
        $length = Yii::$app->request->get('length');
        $length = $length == 0 || $length > 256 ? 16 : (int)$length;
        $random = Yii::$app->security->generateRandomString($length);

        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_RAW;
        $response->data = $random;
        return $response;
    }
}
