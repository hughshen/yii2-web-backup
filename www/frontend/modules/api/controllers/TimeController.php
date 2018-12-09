<?php

namespace frontend\modules\api\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;

/**
 * Time controller for the `ip` module
 */
class TimeController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_RAW;

        $readable = Yii::$app->request->get('readable');
        $from = Yii::$app->request->get('from');

        if (is_numeric($from)) {
            $timestamp = $from;
        } elseif ($from) {
            $timestamp = strtotime($from);
        } else {
            $timestamp = time();
        }

        if ($readable == 1) {
            $response = date('Y-m-d H:i:s', $timestamp);
        } else {
            $response = $timestamp;
        }

        return $response;
    }
}
