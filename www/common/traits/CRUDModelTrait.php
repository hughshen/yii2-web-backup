<?php

namespace common\traits;

use Yii;
use yii\web\NotFoundHttpException;

trait CRUDModelTrait
{
    abstract protected function saveModel();
    abstract protected function deleteModel();
}