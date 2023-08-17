<?php

namespace Drupal\config_task\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure config_task settings for this site.
 */
class SettingsForm extends ConfigFormBase {
  /**
   * The configuration factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ConfiForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_task_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['config_task.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('config_task.settings');
    $tax = $config->get('tax');

    // Title field.
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config->get('title'),
    ];

    // Advanced checkbox.
    $form['advanced'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Advanced'),
      '#default_value' => $config->get('advanced'),
    ];

    $tax_entity = NULL;
    if (is_numeric($tax)) {
      // Load the taxonomy term entity based on the stored tax ID.
      $tax_entity = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tax);
    }

    // Taxonomy term autocomplete field.
    $form['tax'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Tax'),
      '#target_type' => 'taxonomy_term',
      '#default_value' => $tax_entity,
    ];

    // Call the parent buildForm method.
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  // Public function validateForm(array &$form, FormStateInterface $form_state){
  // Validate form data here, if needed.
  // Example validation:
  // if ($form_state->getValue('example') != 'example') {
  // }
  // Call the parent validateForm method.
  // parent::validateForm($form, $form_state);
  // }.

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('config_task.settings');

    // Update configuration values.
    $config->set('title', $form_state->getValue('title'));
    $config->set('advanced', $form_state->getValue('advanced'));
    $config->set('tax', $form_state->getValue('tax'));
    $config->save();

    // Call the parent submitForm method.
    parent::submitForm($form, $form_state);
  }

}
