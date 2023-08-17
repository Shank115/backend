<?php

declare(strict_types=1);

namespace Drupal\plug_bloc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block task block.
 *
 * @Block(
 *   id = "Plug_block",
 *   admin_label = @Translation(" plug block "),
 *   category = @Translation("Plug"),
 * )
 */
final class ExampleBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity storage manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity display repository service.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * Constructs a new BlockTaskBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin_id for the plugin.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity storage manager. Corrected parameter name.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository
   *   The entity display repository service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $entityTypeManager, EntityDisplayRepositoryInterface $entityDisplayRepository) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->entityDisplayRepository = $entityDisplayRepository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_display.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'content_type' => '',
      'display_mode' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form['content_type'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Content type'),
      '#target_type' => 'node',
    ];
    $display_modes = $this->getDisplayModes();
    $form['display_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Display Mode'),
      '#options' => $display_modes,
      '#default_value' => $this->configuration['display_mode'],
    ];
    if (!empty($this->configuration['content_type'])) {
      $form['content_type']['#default_value'] = Node::load($this->configuration['content_type']);
    }

    return $form;
  }

  /**
   * Helper function to get available view modes for an entity type.
   *
   * @return array
   *   An array of available view modes.
   */
  protected function getDisplayModes() {
    $display_modes = [];
    $view_modes = $this->entityDisplayRepository->getViewModes('node');
    foreach ($view_modes as $view_mode => $info) {
      $display_modes[$view_mode] = $info['label'];
    }

    return $display_modes;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $content_type = $form_state->getValue('content_type');
    $this->configuration['content_type'] = $content_type;
    $this->configuration['display_mode'] = $form_state->getValue('display_mode');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $node_id = $this->configuration['content_type'];
    $view_mode = $this->configuration['display_mode'];
    $node = $this->entityTypeManager->getStorage('node')->load($node_id);
    $build = [];
    if ($node) {
      $view_builder = $this->entityTypeManager->getViewBuilder('node');
      $build = $view_builder->view($node, $view_mode);
    }
    return $build;
  }

}
