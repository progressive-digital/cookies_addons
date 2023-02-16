<?php

namespace Drupal\cookies_addons_blocks\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\block\Entity\Block;
use Drupal\Core\Entity\EntityInterface;

/**
 * Returns responses for Cookies Addons blocks routes.
 */
class CookiesAddonsBlocksController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function getBlock($block_id, $service) {
    $response = new AjaxResponse();

    $selector = '.cookies-addons-blocks-placeholder';
    $selector .= "[data-cookies-service=\"$service\"]";
    $selector .= "[data-block-id=\"$block_id\"]";
    $block = Block::load($block_id);
    if ($block instanceof EntityInterface) {
      $content = \Drupal::entityTypeManager()->getViewBuilder('block')->view($block);
      if (is_array($content)) {
        $command = new ReplaceCommand($selector, $content);
        $response->addCommand($command);
      }
      else {
        throw new \Exception("Block '$block_id' render array couldn't be build.");
      }
    }
    else {
      throw new \Exception("Block '$block_id' couldn't be loaded.");
    }

    return $response;
  }

}
