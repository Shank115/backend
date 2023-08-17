<?php

namespace Drupal\custom_template\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller class for displaying the custom template.
 */
class CustomTemplateController extends ControllerBase {

  /**
   * Displays the custom template.
   */
  public function displayTemplate() {
    $config = $this->config('custom_template.settings');

    return [
      '#theme' => 'custom_template',
      '#title' => $config->get('title'),
      '#paragraph' => $config->get('paragraph.value'),
      '#color_code' => $config->get('color_code'),
    ];
  }

}
