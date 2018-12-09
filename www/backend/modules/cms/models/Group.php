<?php

namespace backend\modules\cms\models;

use Yii;
use yii\helpers\ArrayHelper;

class Group extends Term
{
    /**
     * Return taxonomy name
     */
    public static function taxonomyName()
    {
        return 'group';
    }

    public static function frontendMenus($slug)
    {
        $term = self::bySlug($slug);

        if ($term) {
            return $term['posts'];
        } else {
            return [];
        }
    }
}
