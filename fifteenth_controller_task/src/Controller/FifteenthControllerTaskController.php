<?php

namespace Drupal\fifteenth_controller_task\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\Entity\Node;

/**
 * Returns responses for fifteenth_controller_task routes.
 */
final class FifteenthControllerTaskController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function nodeTitle(Node $node) {
    $build['content'] = [
      '#markup' => $node->getTitle(),
    ];

    return $build;
  }
}
