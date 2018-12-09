<?php

namespace backend\modules\cms\models;

use Yii;
use yii\helpers\ArrayHelper;

class Term extends \yii\db\ActiveRecord
{
    use \common\traits\SlugTrait;
    use \common\traits\CRUDModelTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->taxonomy = static::taxonomyName();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_term}}';
    }

    /**
     * Return taxonomy name
     */
    public static function taxonomyName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taxonomy', 'name'], 'required'],
            [['parent', 'sorting', 'created_at', 'updated_at'], 'integer'],
            [['taxonomy'], 'string', 'max' => 32],
            [['name', 'slug'], 'string', 'max' => 255],
            [['description', 'extra_data'], 'string'],
            ['taxonomy', 'default', 'value' => static::taxonomyName()],
            [['parent', 'sorting'], 'default', 'value' => 0],
            ['status', 'default', 'value' => 1],
            [['created_at', 'updated_at'], 'default', 'value' => time()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => 'Parent',
            'taxonomy' => 'Taxonomy',
            'name' => 'Name',
            'description' => 'Description',
            'slug' => 'Slug',
            'sorting' => 'Sorting',
            'extra_data' => 'Extra Data',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Save model
     */
    public function saveModel()
    {
        if (!$this->isNewRecord) {
            $this->updated_at = time();
        }

        $this->setSlug($this->name);

        if (!$this->validate()) {
            Yii::$app->session->setFlash('error', implode('<br>', (array)$this->getFirstErrors()));
            return false;
        };

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->save();

            $transaction->commit();

            self::flushCache();
            Yii::$app->session->setFlash('success', 'Updated successfully.');
            return $this;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return false;
    }

    /**
     * Delete model
     */
    public function deleteModel()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->unlinkAll('posts', true);
            $this->delete();

            $transaction->commit();

            self::flushCache();
            Yii::$app->session->setFlash('success', 'Deleted successfully.');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationships()
    {
        return $this->hasMany(Relationship::className(), ['term_id' => 'id'])->select(Relationship::selectAttributes());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->viaTable(Relationship::tableName(), ['term_id' => 'id'], function($query) {
            $query->select(Relationship::selectAttributes());
        })->select(Post::listAttributes())->andOnCondition(['status' => Post::STATUS_PUBLISH])->orderBy('sorting ASC, created_at DESC');
    }

    /**
     * Get parent term
     */
    public function getParentTerm()
    {
        return $this->hasOne(static::className(), ['id' => 'parent'])->select(self::selectAttributes());
    }

    /**
     * Update children when delete record
     */
    public function updateChildren()
    {
        Yii::$app->db->createCommand()
            ->update(self::tableName(), ['parent' => $this->parent], ['parent' => $this->id])
            ->execute();
    }

    /**
     * Get parent dropdown
     * Deprecated
     */
    public function loopDropdown()
    {
        $excludeIds = [];
        if  (!$this->isNewRecord) array_push($excludeIds, $this->id);
        
        return self::loopTerms($excludeIds);
    }

    public static function selectAttributes()
    {
        return ['id', 'parent', 'taxonomy', 'name', 'description', 'slug', 'sorting', 'extra_data', 'status', 'created_at', 'updated_at'];
    }

    public static function listAttributes()
    {
        return ['id', 'taxonomy', 'name', 'slug', 'extra_data', 'created_at', 'updated_at'];
    }

    /**
     * Return status list
     */
    public static function statusList()
    {
        return [
            '1' => 'Show',
            '0' => 'Hide',
        ];
    }

    /**
     * Get term list
     */
    public static function termList($taxonomy = null)
    {
        if ($taxonomy === null) $taxonomy = static::taxonomyName();
        
        $data = self::find()
            ->select(self::selectAttributes())
            ->where(['taxonomy' => $taxonomy])
            ->orderBy('sorting ASC, id DESC')
            ->asArray()
            ->all();

        if (!empty($data)) return $data;

        return [];
    }

    /**
     * Deprecated
     */
    public static function loopTerms($excludeIds = [], $returnData = true)
    {
        $allTerms = self::find()
            ->select(self::selectAttributes())
            ->where(['taxonomy' => static::taxonomyName()])
            ->asArray()
            ->all();

        $terms = [];

        $loopTerms = $allTerms;
        do {
            $childTerms = [];
            $tempTerms = $loopTerms;
            foreach ($loopTerms as $key => $term) {
                if (in_array($term['parent'], $excludeIds)) {
                    array_push($childTerms, $term);
                    array_push($excludeIds, $term['id']);
                    unset($tempTerms[$key]);
                }
            }
            $loopTerms = array_values($tempTerms);
        } while (!empty($childTerms));

        foreach ($allTerms as $term) {
            if (!in_array($term['id'], $excludeIds)) {
                // array_push($terms, $term);
                $terms[$term['parent']][] = $term;
            }
        }

        if ($returnData) {
            return self::loopToData($terms);
        } else {
            return $terms;
        }
    }

    /**
     * Deprecated
     */
    public static function loopToData($terms, $level = 0, $index = 0, $sep = '   ')
    {
        $data = [];
        if ($level == 0) $data['0'] = 'None';

        if (isset($terms[$index])) {

            foreach ($terms[$index] as $term) {
                $data[$term['id']] = str_repeat($sep, $level) . $term['name'];
                if (isset($terms[$term['id']])) {
                    $data = ArrayHelper::merge($data, self::loopToData($terms, $level + 1, $term['id']));
                }
            }
        }

        return $data;
    }

    public static function flushCache()
    {
        Yii::$app->cache->flush();
        if (Yii::$app->cache instanceof \yii\caching\FileCache) {
            Yii::$app->fileCacheFrontend->flush();
        }
    }

    public static function frontendQuery($condition = [], $all = true)
    {
        $query = self::find()
            ->select(self::selectAttributes())
            ->with(['posts'])
            ->where(['status' => 1, 'taxonomy' => static::taxonomyName()])
            ->andWhere($condition)
            ->orderBy('sorting ASC, created_at DESC')
            ->asArray();

        if ($all) {
            return $query->all();
        } else {
            return $query->one();
        }
    }

    public static function byTaxonomy()
    {
        $cacheKey = 'TAXONOMY_' . static::taxonomyName();

        return Yii::$app->cache->getOrSet($cacheKey, function() {
            return self::frontendQuery();
        });
    }

    public static function byId($id)
    {
        $cacheKey = 'ID_' . $id;
        $condition = ['id' => $id];

        return Yii::$app->cache->getOrSet($cacheKey, function() use ($condition) {
            return self::frontendQuery($condition, false);
        });
    }

    public static function bySlug($slug)
    {
        $cacheKey = 'SLUG_' . $slug;
        $condition = ['slug' => $slug];

        return Yii::$app->cache->getOrSet($cacheKey, function() use ($condition) {
            return self::frontendQuery($condition, false);
        });
    }
}
