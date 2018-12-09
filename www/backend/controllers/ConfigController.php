<?php

namespace backend\controllers;

use Yii;
use common\models\Config;
use yii\web\NotFoundHttpException;

class ConfigController extends BackendController
{
    public $arrayName;

    public function init()
    {
        parent::init();
        $this->arrayName = 'Config';
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'config' => Config::allData()
        ]);
    }

    public function actionFlushCache()
    {
        Yii::$app->cache->flush();
        if (Yii::$app->cache instanceof \yii\caching\FileCache) {
            Yii::$app->fileCacheFrontend->flush();
        }

        Yii::$app->session->setFlash('success', 'Deletes all values from cache.');

        return $this->redirect(['index']);
    }

    public function actionUpdate()
    {
        if (Yii::$app->request->isPost) {

            $exists = Config::allData();
            $updated = false;

            foreach (Yii::$app->request->post($this->arrayName, []) as $name => $value) {
                if (isset($exists[$name])) {
                    if ((string)$exists[$name] !== (string)$value) {
                        $model = $this->findModel($name);
                        $model->config_value = $value;
                        $model->save();
                        $updated = true;
                    }
                } else {
                    $model = new Config();
                    $model->config_name = $name;
                    $model->config_value = $value;
                    $model->autoload = 1;
                    $model->save();
                    $updated = true;
                }
            }
            
            if ($updated) {
                Config::flushCache();
                Yii::$app->session->setFlash('success', 'Site config update success.');
            } else {
                Yii::$app->session->setFlash('info', 'Nothing changed.');
            }
        }

        return $this->redirect(['index']);      
    }

    protected function findModel($config_name)
    {
        if (($model = Config::findOne(['config_name' => $config_name])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
