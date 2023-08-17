<?php

namespace Drupal\shape\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\Entity\Node;

/**
 * Class taskcontroller.
 */
class TaskController extends ControllerBase {

  /**
   * Callback function to demonstrate loading field values from a node.
   *
   * @return array|Response
   *   A render array or a response containing the loaded values.
   */
  public function loadingValue() {
    $node_id = 38;
    $node = Node::load($node_id);
    $result = '';

    if ($node) {
      // Get the title of the loaded node.
      $nodeTitle = $node->getTitle();

      // Load the taxonomy term referenced by the 'field_color' field.
      $tax = $node->get('field_color')->entity->getName();

      // Load the user referenced by the 'field_user_ref' field.
      $userValue = $node->get('field_color')->entity;
      $user = $userValue->get('field_user_ref')->entity->getDisplayName();

      // Build a result string containing the extracted values.
      $result = "$nodeTitle $tax $user";

      // Return a render array with the result as markup.
      return [
        '#markup' => $result,
      ];
    }

    // If the node is not found, return an empty response.
    return new Response($result);
  }

}
