<?php

namespace Drupal\clone_control_task\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for clone_control_task routes.
 */
class CloneControlTaskController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
