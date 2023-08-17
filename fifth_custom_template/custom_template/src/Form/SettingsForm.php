<?php

namespace Drupal\custom_template\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 *
 * Defines the configuration form for the custom_template module.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['custom_template.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_template';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load the configuration.
    $config = $this->config('custom_template.settings');

    // Title field.
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config->get('title'),
    ];

    // Color Code field.
    $form['color_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Color Code'),
      '#default_value' => $config->get('color_code'),
      '#required' => TRUE,
      '#description' => $this->t('Color code:'),
    ];

    // Paragraph field.
    $form['paragraph'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Paragraph'),
      '#default_value' => $config->get('paragraph.value'),
      '#format' => $config->get('paragraph.format') ?: 'basic_html',
      '#description' => $this->t('Paragraph content:'),
    ];

    // Save button.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the configuration values.
    $this->config('custom_template.settings')
      ->set('title', $form_state->getValue('title'))
      ->set('color_code', $form_state->getValue('color_code'))
      ->set('paragraph', $form_state->getValue('paragraph'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
