<?php

namespace backend\modules\cms\controllers;

use Yii;
use yii\web\NotFoundHttpException;

use common\components\markdown\Markdown;
use backend\modules\cms\models\Post;
use backend\modules\cms\models\search\PostSearch;
use backend\modules\cms\models\es\PostEs;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends \backend\controllers\BackendController
{
    use \common\traits\CRUDControllerTrait;

    protected function getSearchClassName()
    {
        return PostSearch::className();
    }

    protected function getModelClassName()
    {
        return Post::className();
    }

    public function actionToMarkdown()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $content = Yii::$app->request->post('content');
            return Markdown::process($content);
        }

        throw new NotFoundHttpException();
    }

    public function actionUpdateEs()
    {
        $res = PostEs::indexAll();

        if ($res === true) {
            Yii::$app->session->setFlash('success', 'Updated successfully.');
        } elseif (is_array($res)) {
            Yii::$app->session->setFlash('error', json_encode($res));
        } else {
            Yii::$app->session->setFlash('error', $res);
        }

        return $this->redirect(['index']);
    }
}
