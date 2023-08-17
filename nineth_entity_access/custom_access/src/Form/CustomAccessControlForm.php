<?php

namespace Drupal\custom_access\Form;

use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Implements the custom access control configuration form.
 */
class CustomAccessControlForm extends FormBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Constructs an AutoParagraphForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_access_control_form';
  }

  /**
   * Comment.
   */
  protected function getEditableConfigNames() {
    return ['custom_access.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $roles = $this->getRoles();
    $content_types = $this->getContentTypes();

    $form['role'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Role'),
      '#options' => $roles,
    ];

    $form['content_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content Types'),
      '#options' => $content_types,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Helper function to get all available roles.
   */
  private function getRoles() {
    $roles = [];
    foreach (user_roles(TRUE) as $role) {
      $roles[$role->id()] = $role->label();
    }
    return $roles;
  }

  /**
   * Helper function to get all available content types.
   */
  private function getContentTypes() {
    $content_types = [];
    $node_types = NodeType::loadMultiple();
    foreach ($node_types as $node_type) {
      $content_types[$node_type->id()] = $node_type->label();
    }
    return $content_types;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $role = $form_state->getValue('role');
    $content_types = $form_state->getValue('content_types');

    // Save the selected role and content types to the configuration.
    \Drupal::configFactory()->getEditable('custom_access.settings')
      ->set('role', $role)
      ->set('content_types', $content_types)
      ->save();
  }

}


