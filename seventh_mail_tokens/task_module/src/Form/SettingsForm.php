<?php

namespace Drupal\task_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements the example form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The config object for this form.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * TokenForm constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   */
  public function __construct(ModuleHandlerInterface $module_handler) {
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('module_handler')
    );
  }

  /**
   * The config name for this the form.
   *
   * @var string
   */
  const CONFIGNAME = "task_module.settings";

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'task_module_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::CONFIGNAME,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->config = $this->config(static::CONFIGNAME);

    // The `subject` variable contains the subject of the email.
    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $this->config->get("subject"),
    ];
    // The `textarea` variable contains the body of the email.
    $form['textarea'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Text'),
      '#default_value' => $this->config->get("textarea")['value'],
    ];

    // `Tokens` variable contains list of tokens that are used in email body.
    if (\Drupal::moduleHandler()->moduleExists('token')) {
      $form['tokens'] = [
        '#title' => $this->t('Tokens'),
        '#type' => 'container',
      ];
      $form['tokens']['help'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => [
          'node',
        ],
        '#global_types' => FALSE,
        '#dialog' => TRUE,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config->set("subject", $form_state->getValue('subject'));
    $this->config->set("textarea", $form_state->getValue('textarea'));
    $this->config->save();
  }

}
