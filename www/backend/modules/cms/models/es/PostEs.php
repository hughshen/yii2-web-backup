<?php

namespace backend\modules\cms\models\es;

use Yii;
use yii\helpers\ArrayHelper;
use Elasticsearch\ClientBuilder;
use backend\modules\cms\models\Post;

class PostEs
{
    public static function indexName()
    {
        return 'cms_post';
    }

    public static function typeName()
    {
        return 'search';
    }

    public static function enabled()
    {
        return (isset(Yii::$app->params['ES_STATUS']) && Yii::$app->params['ES_STATUS'] === true);
    }

    public static function getClient()
    {
        return ClientBuilder::create()->setHosts(['es'])->build();
    }

    public static function indexDoc(Post $model)
    {
        if (!static::enabled()) {
            return;
        }

        $terms = $model->getTerms()->asArray()->all();
        $termText = self::makeTermText($terms);

        $params = [
            'index' => static::indexName(),
            'type' => static::typeName(),
            'id' => $model->id,
            'body' => [
                'id' => $model->id,
                'title' => $model->title,
                'slug' => $model->slug,
                'content' => $model->content,
                'cat' => $termText['category'],
                'tag' => $termText['tag'],
                'sorting' => $model->sorting,
                'created_at' => $model->created_at,
            ],
        ];

        $client = static::getClient();
        $response = $client->index($params);
    }

    public static function deleteDoc(Post $model)
    {
        if (!static::enabled()) {
            return;
        }

        $params = [
            'index' => static::indexName(),
            'type' => static::typeName(),
            'id' => $model->id,
        ];

        $client = static::getClient();
        $response = $client->delete($params);
    }

    public static function indexAll()
    {
        if (!static::enabled()) {
            return 'ES disabled.';
        }

        $client = static::getClient();

        try {
            $params = ['index' => static::indexName()];
            $response = $client->indices()->delete($params);
        } catch (\Exception $e) {}

        $params = [
            'index' => static::indexName(),
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2,
                    'analysis' => [
                        'char_filter' => [
                            'remove_numbers' => [
                                'type' => 'pattern_replace',
                                'pattern' => "(\\d+)",
                                'replacement' => '',
                            ],
                        ],
                        'analyzer' => [
                            'my_analyzer' => [
                                'tokenizer' => 'ik_max_word',
                                'char_filter' => [
                                    'remove_numbers',
                                ],
                            ],
                        ],
                    ],
                ],
                'mappings' => [
                    static::typeName() => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'title' => [
                                'type' => 'text',
                                'analyzer' => 'my_analyzer',
                            ],
                            'slug' => [
                                'type' => 'text',
                                'analyzer' => 'my_analyzer',
                            ],
                            'content' => [
                                'type' => 'text',
                                'analyzer' => 'my_analyzer',
                            ],
                            'cat' => [
                                'type' => 'text',
                                'analyzer' => 'my_analyzer',
                            ],
                            'tag' => [
                                'type' => 'text',
                                'analyzer' => 'my_analyzer',
                            ],
                            'sorting' => [
                                'type' => 'integer',
                            ],
                            'created_at' => [
                                'type' => 'integer',
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $response = $client->indices()->create($params);
        if (!isset($response['acknowledged']) || (int)$response['acknowledged'] !== 1) {
            return 'Update ES failed.';
        }

        $data = Post::find()
            ->with(['terms'])
            ->andWhere(['type' => Post::typeName()])
            ->andWhere(['status' => Post::STATUS_PUBLISH])
            ->asArray()
            ->all();

        $params = [];
        foreach ($data as $val) {
            $termText = self::makeTermText($val['terms']);

            $params['body'][] = [
                'index' => [
                    '_index' => static::indexName(),
                    '_type' => static::typeName(),
                    '_id' => $val['id'],
                ]
            ];
            $params['body'][] = [
                'id' => $val['id'],
                'title' => $val['title'],
                'slug' => $val['slug'],
                'content' => $val['content'],
                'cat' => $termText['category'],
                'tag' => $termText['tag'],
                'sorting' => $val['sorting'],
                'created_at' => $val['created_at'],
            ];
        }

        $responses = $client->bulk($params);
        if ($responses['errors']) {
            return $responses['errors'];
        }

        return true;
    }

    protected static function makeTermText($terms)
    {
        $text = [];
        $text['tag'] = [];
        $text['category'] = [];

        foreach ($terms as $val) {
            $text[$val['taxonomy']][] = $val['name'];
            $text[$val['taxonomy']][] = $val['slug'];
        }

        foreach ($text as $key => $val) {
            $text[$key] = implode(',', $val);
        }

        return $text;
    }

    public static function search($q)
    {
        if (!static::enabled()) {
            return [];
        }

        $fields = ['title', 'slug', 'content', 'cat', 'tag'];
        $arr = explode(':', $q);
        if (isset($arr['1']) && in_array($arr['0'], $fields)) {
            $queryParams = [
                'match' => [
                    $arr['0'] => $arr['1']
                ],
            ];
        } else {
            $matches = [];
            foreach ($fields as $val) {
                $matches[] = ['match' => [$val => $q]];
            }
            $queryParams = [
                'bool' => [
                    'should' => $matches
                ]
            ];
        }

        $client = static::getClient();
        $params = [
            'index' => static::indexName(),
            'type' => static::typeName(),
            'body' => [
                'from' => 0,
                'size' => 10000,
                'query' => $queryParams,
                'sort' => [
                    ['sorting' => ['order' => 'asc']],
                    ['created_at' => ['order' => 'desc']]
                ]
            ]
        ];

        $response = $client->search($params);

        if (isset($response['hits']['hits'])) {
            return ArrayHelper::getColumn($response['hits']['hits'], '_source');
        } else {
            return [];
        }
    }
}
