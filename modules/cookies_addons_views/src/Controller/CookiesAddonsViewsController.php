<?php

namespace Drupal\cookies_addons_views\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Cookies Addons Views routes.
 */
class CookiesAddonsViewsController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function getView($view_id, $display_id, $service, $arguments) {
    $response = new AjaxResponse();
    $args = [];
    if (!empty($arguments)) {
      $args['field_value'] = $arguments;
    }
    $view = [
      '#type' => 'view',
      '#name' => $view_id,
      '#display_id' => $display_id,
      '#arguments' => array_values($args),
    ];

    $selector = '.cookies-addons-views-placeholder';
    $selector .= "[cookies-service=\"$service\"]";
    $selector .= "[view-id=\"$view_id\"]";
    $selector .= "[display-id=\"$display_id\"]";

    if ($service === 'leaflet') {
      $attachments = [
        'leaflet/leaflet-drupal',
        'leaflet/leaflet.fullscreen',
      ];
      $response->addAttachments($attachments);
    }

    $command = new ReplaceCommand($selector, $view);
    $response->addCommand($command);

    return $response;
  }

}
