<?php

namespace Drupal\cookies_addons_embed_iframe\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\Plugin\FilterBase;
use Drupal\filter\FilterProcessResult;

/**
 * Cookie restrict embedded iframes.
 *
 * @Filter(
 * id = "cookies_addons_embed_iframe_filter",
 * title = @Translation("Cookie restrict embedded iframes."),
 * description = @Translation("Cookie restrict embedded iframes."),
 * type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE,
 * )
 */
class CookiesAddonsEmbedIframeFilter extends FilterBase {

  /**
   * Iframe class to catch iframes.
   */
  public const EXTRA_CLASS = 'cookies-addons-embed-iframe';

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);
    $html_dom = Html::load($text);
    $iframes = $html_dom->getElementsByTagName('iframe');

    // Get all iframe iframes, add extra class, replace src with data-src.
    for ($i = $iframes->length; --$i >= 0;) {
      $iframe = $iframes->item($i);
      if (!$iframe instanceof \DOMElement) {
        continue;
      }

      $src = $iframe->getAttribute('src');
      if (!empty($src) && _cookies_addons_embed_iframe_is_iframe($src)) {
        $contains_iframe = TRUE;

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
    if (!empty($contains_iframe)) {
      $result->setAttachments([
        'library' => 'cookies_addons_embed_iframe/cookies-addons-embed-iframe',
      ]);
    }

    $result->setProcessedText(Html::serialize($html_dom));
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    return $this->t('Converts iframe embed to be cookies restricted.');
  }

}
