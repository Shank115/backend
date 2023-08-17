<?php

namespace Drupal\dependent\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class for dependent dropdown form.
 */
class DependentDropdownForm extends FormBase {
  /**
   * The Messenger service.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs DropdownForm.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'item_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get the selected item and model IDs from the form state.
    $selected_item_id = $form_state->getValue("item");
    $selected_model_id = $form_state->getValue("model");

    // Item dropdown.
    $form['item'] = [
      '#type' => 'select',
      '#title' => $this->t('Item'),
      '#options' => $this->getItemOptions(),
      '#empty_option' => $this->t('- Select -'),
      '#ajax' => [
        'callback' => [$this, 'ajaxModelDropdownCallback'],
        'wrapper' => 'model-dropdown-wrapper',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Loading...'),
        ],
      ],
    ];

    // Model dropdown.
    $form['model'] = [
      '#type' => 'select',
      '#title' => $this->t('Model'),
      '#options' => $this->getModelOptions($selected_item_id),
      '#empty_option' => $this->t('- Select -'),
      '#prefix' => '<div id="model-dropdown-wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => [$this, 'ajaxColorDropdownCallback'],
        'wrapper' => 'color-dropdown-wrapper',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Loading...'),
        ],
      ],
    ];

    // Color dropdown.
    $form['color'] = [
      '#type' => 'select',
      '#title' => $this->t('Color'),
      '#options' => $this->getColorByModel($selected_model_id),
      '#prefix' => '<div id="color-dropdown-wrapper">',
      '#suffix' => '</div>',
      '#empty_option' => $this->t('- Select -'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Handle form submission if needed.
  }

  /**
   * AJAX callback for updating the model dropdown.
   */
  public function ajaxModelDropdownCallback(array &$form, FormStateInterface $form_state) {
    return $form['model'];
  }

  /**
   * AJAX callback for updating the color dropdown.
   */
  public function ajaxColorDropdownCallback(array &$form, FormStateInterface $form_state) {
    return $form['color'];
  }

  /**
   * Get options for the item dropdown.
   */
  private function getItemOptions() {
    $query = Database::getConnection()->select('item', 'i');
    $query->fields('i', ['id', 'name']);
    $result = $query->execute();
    $item = [];

    foreach ($result as $row) {
      $item[$row->id] = $row->name;
    }

    return $item;
  }

  /**
   * Get options for the model dropdown based on the selected item.
   */
  private function getModelOptions($selected_item_id) {
    $query = Database::getConnection()->select('model', 's');
    $query->fields('s', ['id', 'name']);
    $query->condition('s.item_id', $selected_item_id);
    $result = $query->execute();

    $model = [];
    foreach ($result as $row) {
      $model[$row->id] = $row->name;
    }
    return $model;
  }

  /**
   * Get options for the color dropdown based on the selected model.
   */
  private function getColorByModel($selected_model_id) {
    $query = Database::getConnection()->select('color', 'c');
    $query->fields('c', ['id', 'name']);
    $query->condition('c.model_id', $selected_model_id);
    $result = $query->execute();

    $color = [];
    foreach ($result as $row) {
      $color[$row->id] = $row->name;
    }

    return $color;
  }

}
