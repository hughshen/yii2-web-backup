<?php

namespace backend\modules\cms\models;

use Yii;

class Category extends Term
{
    /**
     * Return taxonomy name
     */
    public static function taxonomyName()
    {
        return 'category';
    }
}
