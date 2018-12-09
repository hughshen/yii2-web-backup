<?php
namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                // 'class' => 'yii\captcha\CaptchaAction',
                // 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'class' => 'common\components\MathCaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? '42' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->setScenario('stepOne');

        if (Yii::$app->request->isPost) {
            if (User::check2FAEnable()) {
                if (Yii::$app->session->has('user2FARequest')) {
                    $user2FARequest = Yii::$app->session->get('user2FARequest');
                    $model->setScenario('stepTwo');
                    $model->load(Yii::$app->request->post());
                    $model->load([
                        'username' => substr($user2FARequest, 0, -1),
                        'rememberMe' => substr($user2FARequest, -1, 1) === '1',
                    ], '');
                    if ($model->login()) {
                        Yii::$app->session->remove('user2FARequest');
                        return $this->redirect(['index']);
                    } else {
                        return $this->renderPartial('login', ['model' => $model]);
                    }
                } else {
                    $model->load(Yii::$app->request->post());
                    if ($model->validate()) {
                        $user2FARequest = $model->username . ($model->rememberMe ? '1' : '0');
                        Yii::$app->session->set('user2FARequest', $user2FARequest);
                        $model->setScenario('stepTwo');
                        return $this->renderPartial('login', ['model' => $model]);
                    }
                }
            } else {
                if ($model->load(Yii::$app->request->post()) && $model->login()) {
                    return $this->redirect(['index']);
                }
            }
        }

        Yii::$app->session->remove('user2FARequest');

        return $this->renderPartial('login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
