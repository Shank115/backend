<?php

namespace Drupal\sixteen_cache\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Cache\Cache;

/**
 * Controller for handling tasks related to ControllerTask module.
 */
class Controller extends ControllerBase {

  /**
   * Builds the response.
   */
  public function task() {
    $nid = 132;
    $cid = 'markten:' . $nid;

    // Look for item in cache so we don't have to do work if we don't need to.
    if ($item = \Drupal::cache()->get($cid)) {
      return $item->data;
    }

    // Build up the markdown array we're going to use later.
    $node = Node::load($nid);
    $title = $node->getTitle();
    $markten = [
      // '#title' => $node->get('title')->value,
      '#markup' => $title,
      // ...
    ];

    // Set the cache so we don't need to do this work again until $node changes.
    \Drupal::cache()->set($cid, $markten, Cache::PERMANENT, $node->getCacheTags());

    return $markten;
  }

}
