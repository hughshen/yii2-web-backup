<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%site_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_name'], 'required'],
            [['config_name'], 'string', 'max' => 255],
            [['config_value'], 'string'],
            ['autoload', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_name' => 'Option',
            'config_value' => 'Value',
        ];
    }

    protected static function _query($condition = [])
    {
        return self::find()->select(['config_name', 'config_value'])->where($condition);
    }

    protected static function _combine($data)
    {
        return ArrayHelper::map((array)$data, 'config_name', 'config_value');
    }

    public static function flushCache()
    {
        Yii::$app->cache->delete('ALL_CONFIG');
        if (Yii::$app->cache instanceof \yii\caching\FileCache) {
            Yii::$app->fileCacheFrontend->delete('ALL_CONFIG');
        }
    }

    public static function allData($condition = [])
    {
        $cacheKey = 'ALL_CONFIG';

        return Yii::$app->cache->getOrSet($cacheKey, function() use ($condition) {
            $data = self::_query()->asArray()->all();
            return self::_combine($data);
        });
    }

    public static function byName($name)
    {
        $data = self::allData();
        return isset($data[$name]) ? $data[$name] : null;
    }
}
