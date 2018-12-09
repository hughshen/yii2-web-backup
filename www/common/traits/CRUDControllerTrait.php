<?php

namespace common\traits;

use Yii;
use yii\web\NotFoundHttpException;

trait CRUDControllerTrait
{
    abstract public function render($view, $params = []);
    abstract public function redirect($url, $statusCode = 302);

    abstract protected function getModelClassName();
    abstract protected function getSearchClassName();

    protected function newClassName($class)
    {
        return '\\' . ltrim($class, '\\');
    }

    protected function findModel($id = 0)
    {
        $modelClassName = $this->newClassName($this->getModelClassName());
        if ($id === 0) {
            return (new $modelClassName);
        } elseif (($model = $modelClassName::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex()
    {
        $searchClassName = $this->newClassName($this->getSearchClassName());
        $searchModel = new $searchClassName;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = $this->findModel();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->saveModel()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->saveModel()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->deleteModel();

        return $this->redirect(['index']);
    }
}