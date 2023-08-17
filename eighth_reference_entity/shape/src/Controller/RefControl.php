<?php

namespace Drupal\shape\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * To include Reference Entity.
 */
class RefControl extends ControllerBase {

  /**
   * Controller function.
   *
   * @return string
   *   The concatenated details.
   */
  public function loadingValue() {
    $node_id = 2;
    $node = Node::load($node_id);
    // $details = '';
    if ($node) {
      $nodeTitle = $node->getTitle();

      $taxonomy = $node->get('field_color')->referencedEntities();
      $tax = reset($taxonomy);
      $term = $tax->getName();

      $userValue = $tax->get('field_user_ref')->referencedEntities();
      $user = reset($userValue);
      $name = $user->getDisplayName();

      $details = "$nodeTitle . $term . $name ";

      $build = [
        '#type' => 'markup',
        '#markup' => "$details",
      ];
      return $build;
    }
  }

}
