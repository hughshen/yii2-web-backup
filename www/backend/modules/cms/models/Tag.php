<?php

namespace backend\modules\cms\models;

use Yii;

class Tag extends Term
{
    /**
     * Return taxonomy name
     */
    public static function taxonomyName()
    {
        return 'tag';
    }
}
