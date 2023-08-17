<?php

namespace Drupal\fifth_task\Plugin\Field\FieldFormatter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "", // The ID for the formatter. Add a unique ID here.
 *   label = @Translation("Field Formatter"), // The human-readable label for the formatter.
 *   field_types = {
 *     "integer" // The field types this formatter can be applied to.
 *   }
 * )
 */
class FieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
    // Default settings for the formatter.
      'concat' => 'after dividing by 100',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    // Form for configuring the formatter's settings.
    $form['concat'] = [
      '#type' => 'textfield',
      '#title' => 'Concatenate with',
      '#default_value' => $this->getSetting('concat'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    // Summary of the formatter's settings.
    $summary = [];
    $summary[] = $this->t("Concatenate with: @concat", ["@concat" => $this->getSetting('concat')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    // Loop through the field items and create the rendered output.
    foreach ($items as $delta => $item) {
      $value = $item->value / 100;
      $concatenate = $this->getSetting('concat');
      $elements[$delta] = [
        '#markup' => '<p>' . $concatenate . ' ' . $value . '</p>',
      ];
    }

    return $elements;
  }

}
