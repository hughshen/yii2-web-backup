<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserMeta;
use backend\models\UserSearch;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BackendController
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGenerateSecret($id)
    {
        if (Yii::$app->request->isPost) {
            $model = $this->findModel($id);
            $model->extractExtraData();
            $secrct = $model->getExtraValue('_2fa_secret');
            if (empty($secrct)) {
                $secrct = \common\components\GoogleAuthenticator::createSecret();
                $model->setExtraField('_2fa_secret', $secrct);
                $model->saveExtraData(true);
                Yii::$app->session->setFlash('success', 'Generate 2FA secrct successfully.');
            } else {
                Yii::$app->session->setFlash('info', '2FA secrct already exist.');
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->redirect(['index']);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if (Yii::$app->request->isPost) {
            if ($this->saveModel($model) && $model->id) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            if ($this->saveModel($model)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->deleteModel($id);
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveModel($model)
    {
        $user = Yii::$app->request->post('User');

        if (is_array($user) && $user) {

            $model->username = $user['username'];
            $model->email = $user['email'];
            $model->role = $user['role'];

            if ($model->isNewRecord) {
                $error = true;
                if (empty($user['username'])) {
                    // Check username
                    Yii::$app->session->setFlash('error', 'Username is required.');
                } elseif (filter_var($user['email'], FILTER_VALIDATE_EMAIL) === false) {
                    // Check email if valid
                    Yii::$app->session->setFlash('error', 'Email is invalid.');
                } elseif (!$user['password']) {
                    // Check password
                    Yii::$app->session->setFlash('error', 'Password is required.');
                } else {
                    $error = false;
                }

                if (!$error) {
                    $model->setPassword($user['password']);
                    $model->generateAuthKey();
                } else {
                    return false;
                }
            } else {
                if ($user['password']) {
                    $model->setPassword($user['password']);
                }
                $model->updated_at = time();
            }
        }

        return $model->save();
    }

    protected function deleteModel($id)
    {
        try {
            $model = $this->findModel($id);
            $model->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
