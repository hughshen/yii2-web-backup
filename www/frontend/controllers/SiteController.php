<?php
namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\Html;

use backend\modules\cms\models\Post;
use backend\modules\cms\models\Page;
use backend\modules\cms\models\Tool;
use backend\modules\cms\models\Tag;
use backend\modules\cms\models\Category;
use backend\modules\cms\models\es\PostEs;

/**
 * Site controller
 */
class SiteController extends FrontendController
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $listPosts = Post::allListData();

        if (Yii::$app->request->get('page') === null) {
            $this->seo['robots'] = null;
        }

        return $this->renderListPosts($listPosts);
    }

    public function actionSearch()
    {
        $s = Yii::$app->request->get('s');
        if (empty($s)) {
            return $this->redirect(['index']);
        }

        $this->siteHeading = 'Search: ' . $s;
        $this->seo['title'] = $this->siteHeading . ' | ' . $this->configByName('_site_name');;

        if (PostEs::enabled()) {
            $list = PostEs::search($s);
            return $this->renderListPosts($list);
        }

        $query = Post::find()
            ->select(Post::listAttributes())
            ->where(['status' => Post::STATUS_PUBLISH, 'type' => Post::typeName()])
            ->orderBy('sorting ASC, created_at DESC')
            ->andFilterWhere(['or', ['like', 'title', $s], ['like', 'content', $s]]);

        $countQuery = clone $query;
        $totalCount = $countQuery->count('id');
        $pages = $this->getPages($totalCount);

        $posts = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages,
        ]);
    }

    public function actionPost()
    {
        $this->seo['robots'] = null;

        return $this->renderBySlug(Post::className(), 'post');
    }

    public function actionPage()
    {
        return $this->renderBySlug(Page::className(), 'page');
    }

    public function actionCategories()
    {
        $this->siteHeading = 'Categories';
        $this->seo['title'] = $this->siteHeading . ' | ' . $this->configByName('_site_name');;

        $listCategories = Category::byTaxonomy();

        return $this->render('categories', [
            'categories' => $listCategories,
        ]);
    }

    public function actionCategory()
    {
        $term = Category::bySlug(Yii::$app->request->get('slug'));

        if ($term) {
            $this->siteHeading = 'Category: ' . $term['name'];
            $this->seo['title'] = $this->siteHeading . ' | ' . $this->configByName('_site_name');

            return $this->renderListPosts($term['posts']);
        }

        return $this->redirect(['error']);
    }

    public function actionTags()
    {
        $this->siteHeading = 'Tags';
        $this->seo['title'] = $this->siteHeading . ' | ' . $this->configByName('_site_name');;

        $listTags = Tag::byTaxonomy();

        return $this->render('tags', [
            'tags' => $listTags,
        ]);
    }

    public function actionTag()
    {
        $term = Tag::bySlug(Yii::$app->request->get('slug'));

        if ($term) {
            $this->siteHeading = 'Tag: ' . $term['name'];
            $this->seo['title'] = $this->siteHeading . ' | ' . $this->configByName('_site_name');

            return $this->renderListPosts($term['posts']);
        }

        return $this->redirect(['error']);
    }

    protected function renderBySlug($class, $template)
    {
        $class = "\\" . $class;
        $post = $class::bySlug(Yii::$app->request->get('slug'));

        if ($post) {
            $this->initPostSeo($post);

            return $this->render($template, [
                'post' => $post,
            ]);
        }

        return $this->redirect(['error']);
    }

    protected function renderListPosts($listPosts)
    {
        $pages = $this->getPages(count($listPosts));
        $posts = array_slice($listPosts, $pages->offset, $pages->limit);

        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages,
        ]);
    }

    protected function getPages($totalCount)
    {
        $pages = new Pagination([
            'totalCount' => $totalCount,
            'defaultPageSize' => 6,
            'forcePageParam' => false,
        ]);

        return $pages;
    }

    public function actionSitemap()
    {
        $listPosts = Post::allListData();

        $output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $output .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($listPosts as $post) {
            $output .= "\t<url>\n\t\t<loc>" . Url::to(['/site/post', 'slug' => $post['slug']], true) . "</loc>\n\t\t<lastmod>" . date('Y-m-d', $post['created_at']) . "</lastmod>\n\t</url>\n";
        }

        $output .= "</urlset>";

        header('Content-type: text/xml');
        echo $output;
        exit;
    }

    public function actionGoogleVerification()
    {
        $id = $this->configByName('_site_google_verification');
        echo "google-site-verification: {$id}.html";
        Yii::$app->end();
    }

    public function actionBingAuth()
    {
        $id = $this->configByName('_site_bing_auth');

        $output = "<?xml version=\"1.0\"?>\n";
        $output .= "<users>\n";
        $output .= "\t<user>{$id}</user>\n";
        $output .= "</users>";
        
        header('Content-type: text/xml');
        echo $output;
        exit;
    }

    protected function initPostSeo($post)
    {
        $extra = json_decode($post['extra_data'], true);

        $title = isset($extra['_seo_title']) ? $extra['_seo_title'] : null;
        $keywords = isset($extra['_seo_keywords']) ? $extra['_seo_keywords'] : null;
        $description = isset($extra['_seo_description']) ? $extra['_seo_description'] : null;

        if (!$title) {
            $title = $post['title'] . ' | ' . $this->configByName('_site_name');
        }
        $this->seo['title'] = $title;

        if ($keywords) {
            $this->seo['keywords'] = $keywords;
        }
        if ($description) {
            $this->seo['description'] = $description;
        }
    }
}
