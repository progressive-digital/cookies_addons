<?php

namespace Drupal\cookies_addons_embed_video\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\FilterProcessResult;

/**
 * YouTube embed videos to be cookies restricted.
 *
 * @Filter(
 * id = "cookies_addons_embed_viedeo_filter",
 * title = @Translation("YouTube embed videos to be cookies restricted"),
 * description = @Translation("YouTube embed videos to be cookies restricted"),
 * type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE,
 * )
 */
class CookiesAddonsEmbedVideoFilter extends FilterBase {

  /**
   * Cookies video module class to catch videos.
   */
  public const EXTRA_CLASS = 'cookies-video-embed-field';

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);
    $html_dom = Html::load($text);
    $iframes = $html_dom->getElementsByTagName('iframe');

    // Get all YouTube iframes, add extra class, replace src with data-src.
    for ($i = $iframes->length; --$i >= 0;) {
      $iframe = $iframes->item($i);
      if (!$iframe) {
        continue;
      }

      $src = $iframe->getAttribute('src');
      if (!empty($src) && _cookies_addons_embed_video_is_youtube($src)) {
        $contains_youtube = TRUE;

        $classes = $iframe->getAttribute('class');
        $classes = $classes
          ? $classes . ' ' . self::EXTRA_CLASS
          : self::EXTRA_CLASS;

        $iframe->setAttribute('src', '');
        $iframe->setAttribute('data-src', $src);
        $iframe->setAttribute('class', $classes);
      }
    }

    // Library "cookies_video_embed_field" attached
    // and does the job of replacing video with the placeholder.
    if (!empty($contains_youtube)) {
      $result->setAttachments([
        'library' => 'cookies_video/cookies_video_embed_field',
      ]);
    }

    $result->setProcessedText(Html::serialize($html_dom));
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    return $this->t('Converts Youtube embed to be cookies restricted.');
  }

}
