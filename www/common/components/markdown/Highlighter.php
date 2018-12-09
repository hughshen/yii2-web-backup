<?php
namespace common\components\markdown;

use Yii;
use cebe\markdown\GithubMarkdown;

/**
 * @link https://github.com/yiisoft/yii2-apidoc/blob/master/helpers/MarkdownHighlightTrait.php
 */
class Highlighter extends GithubMarkdown
{
    /**
     * @var Highlighter
     */
    private static $highlighter;

    /**
     * @inheritdoc
     */
    protected function renderCode($block)
    {
        if (self::$highlighter === null) {
            self::$highlighter = new \Highlight\Highlighter();
            self::$highlighter->setAutodetectLanguages([
                'apache', 'nginx',
                'bash', 'dockerfile', 'http',
                'css', 'less', 'scss',
                'javascript', 'json', 'markdown',
                'php', 'sql', 'twig', 'xml',
            ]);
        }
        try {
            if (isset($block['language'])) {
                $result = self::$highlighter->highlight($block['language'], $block['content'] . "\n");
                return "<pre><code class=\"hljs {$result->language} language-{$block['language']}\">{$result->value}</code></pre>\n";
            } else {
                $result = self::$highlighter->highlightAuto($block['content'] . "\n");
                return "<pre><code class=\"hljs {$result->language}\">{$result->value}</code></pre>\n";
            }
        } catch (\DomainException $e) {
            return parent::renderCode($block);
        }
    }

    /**
     * Highlights code
     *
     * @param string $code code to highlight
     * @param string $language language of the code to highlight
     * @return string HTML of highlighted code
     * @deprecated since 2.0.5 this method is not used anymore, highlight.php is used for highlighting
     */
    public static function highlight($code, $language)
    {
        if ($language !== 'php') {
            return htmlspecialchars($code, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        if (strncmp($code, '<?php', 5) === 0) {
            $text = @highlight_string(trim($code), true);
        } else {
            $text = highlight_string("<?php ".trim($code), true);
            $text = str_replace('&lt;?php', '', $text);
            if (($pos = strpos($text, '&nbsp;')) !== false) {
                $text = substr($text, 0, $pos) . substr($text, $pos + 6);
            }
        }
        // remove <code><span style="color: #000000">\n and </span>tags added by php
        $text = substr(trim($text), 36, -16);

        return $text;
    }
}