<?php

namespace Drupal\cookies_addons_paragraphs\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Entity\EntityInterface;

/**
 * Returns responses for Cookies Addons paragraphs routes.
 */
class CookiesAddonsParagraphsController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function getParagraph($paragraph_id, $service) {
    $response = new AjaxResponse();

    $selector = '.cookies-addons-paragraph-placeholder';
    $selector .= "[data-cookies-service=\"$service\"]";
    $selector .= "[data-paragraph-id=\"$paragraph_id\"]";
    $paragraph = Paragraph::load($paragraph_id);
    if ($paragraph instanceof EntityInterface) {
      $content = \Drupal::entityTypeManager()->getViewBuilder('paragraph')->view($paragraph);
      if (is_array($content)) {
        $command = new ReplaceCommand($selector, $content);
        $response->addCommand($command);
      }
      else {
        throw new \Exception("Paragraph '$paragraph_id' render array couldn't be build.");
      }
    }
    else {
      throw new \Exception("Paragraph '$paragraph_id' couldn't be loaded.");
    }

    return $response;
  }

}
