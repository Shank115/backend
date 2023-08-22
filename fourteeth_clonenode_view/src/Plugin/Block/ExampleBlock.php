<?php

namespace Drupal\fourteeth_clonenode_view\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "fourteeth_clonenode_view_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("fourteeth_clonenode_view")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
