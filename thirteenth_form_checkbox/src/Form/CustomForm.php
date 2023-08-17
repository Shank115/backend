<?php

namespace Drupal\thirteenth_form_checkbox\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Provides the form for adding names.
 */
class CustomForm extends FormBase {

  /**
   * Logger channel.
   *
   * @var \Psr\Log\LoggerInterface
   */

  protected $logger;

  /**
   * Constructs a CustomLogger object.
   *
   * @param \Drupal\Core\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    $container->get('logger.factory')->get('custom_form')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'custom_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['#attached']['library'][] = "thirteenth_form_checkbox/js_lib";

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#required' => TRUE,
    ];

    $form['has_last_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('SAme name'),
      '#attributes' => ['id' => 'has-last-name'],
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#attributes' => ['id' => 'last-name'],
      // '#states' => [
      // 'visible' => [
      // ':input[name="has_last_name"]' => ['checked' => FALSE],
      // ],
      // ]
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submitted'),
    ];

    return $form;

  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // $this->logger->warning($this->t('message sent'));
    // $this->logger->error($this->t('message sent'));
    // $this->logger->notice($this->t('message sent'));
    $this->logger->info($this->t('message sent'));

    $this->messenger()->addStatus($this->t('submitted'));
  }

}
