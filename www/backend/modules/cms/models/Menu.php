<?php

namespace backend\modules\cms\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class Menu extends Post
{
    /**
     * Return type name
     */
    public static function typeName()
    {
        return 'menu';
    }

    public function slugPrefix()
    {
        return 'menu-';
    }

    public function extraFields()
    {
        $this->extractExtraData();        
        $menuType = $this->getExtraValue('_menu_type');
        $promptText = '- Select -';
        return [
            [
                'fieldName' => '_menu_type',
                'inputLabel' => 'Menu Type',
                'valueData' => $this->extraData,
                'inputType' => 'dropdown',
                'valueList' => self::menuTypeList(),
                'defaultValue' => $menuType,
                'promptText' => $promptText,
            ],
            [
                'fieldName' => '_menu_type_id',
                'inputLabel' => 'Menu Type Name',
                'valueData' => $this->extraData,
                'inputType' => 'dropdown',
                'valueList' => ArrayHelper::map(self::menuIdList($menuType), 'id', 'value'),
                'defaultValue' => $this->getExtraValue('_menu_type_id'),
                'promptText' => $promptText,
            ],
            [
                'fieldName' => '_menu_route',
                'inputLabel' => 'Menu Route',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_menu_route'),
            ],
            [
                'fieldName' => '_menu_link',
                'inputLabel' => 'Menu Link',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_menu_link'),
            ],
            [
                'fieldName' => '_menu_target',
                'inputLabel' => 'Link Target',
                'valueData' => $this->extraData,
                'inputType' => 'dropdown',
                'valueList' => [
                    '' => 'Current Window',
                    '_blank' => 'New Window',
                ],
                'defaultValue' => $this->getExtraValue('_menu_target'),
            ],
            [
                'fieldName' => '_menu_attributes',
                'inputLabel' => 'Tag Attributes',
                'valueData' => $this->extraData,
                'defaultValue' => $this->getExtraValue('_menu_attributes'),
            ],
        ];
    }

    public static function menuGroupList()
    {
        $query = Term::find()->select(Term::selectAttributes())->where(['taxonomy' => 'group'])->asArray()->all();
        return ArrayHelper::map((array)$query, 'id', 'name');
    }

    public static function menuTypeList()
    {
        return [
            'post' => 'Post',
            'page' => 'Page',
            'tag' => 'Tag',
            'category' => 'Category',
        ];
    }

    public static function menuIdList($type = 'post')
    {
        if (!in_array($type, array_keys(self::menuTypeList()))) return [];

        $data = [];
        switch ($type) {
            case 'post':
            case 'page':
                $query = Post::find()->select(Post::selectAttributes())->where(['status' => Post::STATUS_PUBLISH, 'type' => $type])->asArray()->all();
                $query = (array)$query;
                foreach ($query as $val) {
                    $data[] = [
                        'id' => $val['id'],
                        'value' => $val['title'],
                    ];
                }
                break;
            case 'tag':
            case 'category':
                $query = Term::find()->select(Term::selectAttributes())->where(['taxonomy' => $type])->asArray()->all();
                $query = (array)$query;
                foreach ($query as $val) {
                    $data[] = [
                        'id' => $val['id'],
                        'value' => $val['name'],
                    ];
                }
                break;
        }

        return $data;
    }

    public static function frontendLink($menu)
    {
        if (!isset($menu['extra_data'])) return null;

        $extra = json_decode($menu['extra_data'], true);

        $url = '';

        if (isset($extra['_menu_route']) && !empty($extra['_menu_route'])) {
            $url = [$extra['_menu_route']];
        } elseif (isset($extra['_menu_link']) && !empty($extra['_menu_link'])) {
            $url = $extra['_menu_link'];
        } elseif (isset($extra['_menu_type']) && isset($extra['_menu_type_id']) && !empty($extra['_menu_type']) && !empty($extra['_menu_type_id'])) {
            switch ($extra['_menu_type']) {
                case 'post':
                    $post = Post::byId($extra['_menu_type_id']);
                    if ($post) $url = ['/site/' . $extra['_menu_type'], 'slug' => $post['slug']];
                    break;
                case 'page':
                    $page = Page::byId($extra['_menu_type_id']);
                    if ($page) $url = ['/site/' . $extra['_menu_type'], 'slug' => $page['slug']];
                    break;
                case 'tag':
                    $tag = Tag::byId($extra['_menu_type_id']);
                    if ($tag) $url = ['/site/' . $extra['_menu_type'], 'slug' => $tag['slug']];
                    break;
                case 'category':
                    $category = Category::byId($extra['_menu_type_id']);
                    if ($category) $url = ['/site/' . $extra['_menu_type'], 'slug' => $category['slug']];
                    break;
            }
        }

        if (!empty($url)) {
            $urlOptions = [];
            if (isset($extra['_menu_target']) && !empty($extra['_menu_target'])) {
                $urlOptions = ['target' => $extra['_menu_target']];
            }

            $url = Url::to($url, true);

            return Html::a($menu['title'], $url, $urlOptions);
        }

        return null;
    }
}
