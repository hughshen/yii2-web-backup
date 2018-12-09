<?php
namespace common\components\markdown;

use Yii;
use yii\helpers\BaseMarkdown;

class Markdown extends BaseMarkdown
{
    public static function process($markdown, $flavor = null)
    {
        $parser = static::getParser($flavor);
        return $parser->parse($markdown);
    }

    public static function processParagraph($markdown, $flavor = null)
    {
        $parser = static::getParser($flavor);
        return $parser->parseParagraph($markdown);
    }

    protected static function getParser($flavor)
    {
        return (new Highlighter());
    }
}