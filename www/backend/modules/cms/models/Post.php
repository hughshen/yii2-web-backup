<?php

namespace backend\modules\cms\models;

use Yii;
use backend\modules\cms\models\es\PostEs;

class Post extends \yii\db\ActiveRecord
{
    use \common\traits\SlugTrait;
    use \common\traits\ExtraDataTrait;
    use \common\traits\CRUDModelTrait;

    const STATUS_PUBLISH = 'publish';
    const STATUS_DRAFT = 'draft';
    const STATUS_TRASH = 'trash';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->type = static::typeName();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_post}}';
    }

    /**
     * Return type name
     */
    public static function typeName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['author', 'sorting', 'created_at', 'updated_at'], 'integer'],
            [['title', 'slug', 'guid'], 'string', 'max' => 255],
            [['content', 'excerpt', 'extra_data'], 'string'],
            ['type', 'string', 'max' => 20],
            ['type', 'default', 'value' => static::typeName()],
            ['mime_type', 'string', 'max' => 100],
            [['author', 'parent', 'sorting'], 'default', 'value' => 0],
            ['status', 'default', 'value' => 'publish'],
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
            'author' => 'Author',
            'title' => 'Title',
            'content' => 'Content',
            'excerpt' => 'Excerpt',
            'slug' => 'Slug',
            'guid' => 'Guid',
            'type' => 'Type',
            'mime' => 'MIME Type',
            'sorting' => 'Sorting',
            'extra_data' => 'Extra Data',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * If has trash
     */
    public function hasTrash()
    {
        return false;
    }

    /**
     * Slug prefix
     */
    public function slugPrefix()
    {
        return '';
    }

    public function extraFields()
    {
        $this->extractExtraData();
        return [
            [
                'fieldName' => '_seo_title',
                'inputLabel' => 'SEO Title',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_seo_title'),
            ],
            [
                'fieldName' => '_seo_keywords',
                'inputLabel' => 'SEO Keywords',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_seo_keywords'),
            ],
            [
                'fieldName' => '_seo_description',
                'inputLabel' => 'SEO Description',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_seo_description'),
            ],
            [
                'fieldName' => '_view_count',
                'inputLabel' => 'View Count',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_view_count'),
            ],
            [
                'fieldName' => '_post_styles',
                'inputLabel' => 'Post Styles',
                'inputType' => 'textarea',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_post_styles'),
            ],
            [
                'fieldName' => '_post_scripts',
                'inputLabel' => 'Post Scripts',
                'inputType' => 'textarea',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_post_scripts'),
            ],
        ];
    }

    /**
     * Save model
     */
    public function saveModel()
    {
        if ($this->isNewRecord) {
            $this->author = Yii::$app->user->id;
        } else {
            $this->updated_at = time();
        }

        $this->setSlug($this->slugPrefix() . $this->title);

        if (!$this->validate()) {
            Yii::$app->session->setFlash('error', implode('<br>', (array)$this->getFirstErrors()));
            return false;
        };

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->setExtraFieldArray(Yii::$app->request->post('ExtraFields', []));
            $this->saveExtraData();
            $this->save();

            $this->setTerm(Yii::$app->request->post('Term', []));

            $transaction->commit();

            // ES
            if ($this->type == Post::typeName()) {
                PostEs::indexDoc($this);
                if ($this->status != Post::STATUS_PUBLISH) {
                    PostEs::deleteDoc($this);
                }
            }

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
            if ($this->hasTrash()) {
                $this->moveToTrash();
                $this->save();
            } else {
                $this->unlinkAll('terms', true);
                $this->delete();
            }

            $transaction->commit();

            // ES
            if ($this->type == Post::typeName()) {
                PostEs::indexDoc($this);
                PostEs::deleteDoc($this);
            }

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
        return $this->hasMany(Relationship::className(), ['post_id' => 'id'])->select(Relationship::selectAttributes());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerms()
    {
        return $this->hasMany(Term::className(), ['id' => 'term_id'])->viaTable(Relationship::tableName(), ['post_id' => 'id'], function($query) {
            $query->select(Relationship::selectAttributes());
        })->select(Term::listAttributes())->andOnCondition(['status' => 1])->orderBy('sorting ASC, created_at DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Term::className(), ['id' => 'term_id'])->viaTable(Relationship::tableName(), ['post_id' => 'id'], function($query) {
            $query->select(Relationship::selectAttributes());
        })->select(Term::listAttributes())->andOnCondition(['status' => 1, 'taxonomy' => 'category'])->orderBy('sorting ASC, created_at DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Term::className(), ['id' => 'term_id'])->viaTable(Relationship::tableName(), ['post_id' => 'id'], function($query) {
            $query->select(Relationship::selectAttributes());
        })->select(Term::listAttributes())->andOnCondition(['status' => 1, 'taxonomy' => 'tag'])->orderBy('sorting ASC, created_at DESC');
    }

    /**
     * Set term data
     */
    public function setTerm($data)
    {
        if (!$this->isNewRecord) $this->unlinkAll('terms', true);
        foreach ($data as $key => $tid) {
            $term = Term::findOne($tid);
            $this->link('terms', $term);
        }
    }

    /**
     * Fake delete
     */
    public function moveToTrash()
    {
        $this->deleted_at = time();
        $this->status = self::STATUS_TRASH;
    }

    /**
     * Return status list
     */
    public static function statusList()
    {
        return [
            self::STATUS_PUBLISH => 'Publish',
            self::STATUS_DRAFT => 'Draft',
            // self::STATUS_TRASH => 'Trash',
        ];
    }

    public static function selectAttributes()
    {
        return ['id', 'parent', 'author', 'title', 'content', 'excerpt', 'slug', 'guid', 'type', 'mime_type', 'sorting', 'extra_data', 'status', 'created_at', 'updated_at', 'deleted_at'];
    }

    public static function listAttributes()
    {
        return ['id', 'title', 'excerpt', 'slug', 'extra_data', 'created_at', 'updated_at'];
    }

    public static function flushCache()
    {
        Yii::$app->cache->flush();
        if (Yii::$app->cache instanceof \yii\caching\FileCache) {
            Yii::$app->fileCacheFrontend->flush();
        }
    }

    public static function allListData($condition = [])
    {
        $cacheKey = 'ALL_LIST_POSTS';

        return Yii::$app->cache->getOrSet($cacheKey, function() use ($condition) {
            $data = self::find()
                ->select(self::listAttributes())
                ->where(['status' => self::STATUS_PUBLISH, 'type' => static::typeName()])
                ->andWhere($condition)
                ->orderBy('sorting ASC, created_at DESC')
                ->asArray()
                ->all();
            return $data;
        });
    }

    public static function singleQuery($condition = [])
    {
        $data = self::find()
            ->select(self::selectAttributes())
            ->with(['categories', 'tags'])
            ->where(['status' => self::STATUS_PUBLISH, 'type' => static::typeName()])
            ->andWhere($condition)
            ->orderBy('sorting ASC, created_at DESC')
            ->asArray()
            ->one();

        if ($data && $data['type'] == Post::typeName()) {
            $data['content'] = \common\components\markdown\Markdown::process($data['content']);
        }

        return $data;
    }

    public static function byId($id)
    {
        $cacheKey = 'ID_' . $id;
        $condition = ['id' => $id];

        return Yii::$app->cache->getOrSet($cacheKey, function() use ($condition) {
            return self::singleQuery($condition);
        });
    }

    public static function bySlug($slug)
    {
        $cacheKey = 'SLUG_' . $slug;
        $condition = ['slug' => $slug];

        return Yii::$app->cache->getOrSet($cacheKey, function() use ($condition) {
            return self::singleQuery($condition);
        });
    }
}
