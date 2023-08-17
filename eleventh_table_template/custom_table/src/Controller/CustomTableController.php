<?php

namespace Drupal\custom_table\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for custom task routes.
 */
class CustomTableController extends ControllerBase {

  /**
   * Function.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Function.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Builds the response.
   */
  public function build() {

    $result = \Drupal::database()->select('user_details', 'table')
      ->fields('table')
      ->execute();
    $rows = [];
    foreach ($result as $row) {
      $rows[] = [
        'id' => $row->id,
        'firstname' => $row->firstname,
        'lastname' => $row->lastname,
        'email' => $row->email,
        'phonenumber' => $row->phonenumber,
        'gender' => $row->gender,
      ];
    }
    $build = [
      '#theme' => 'table_theme',
      '#rows' => $rows,
    ];

    return $build;
  }

}
