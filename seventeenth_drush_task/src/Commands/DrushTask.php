<?php

namespace Drupal\seventeenth_drush_task\Commands;

use Drush\Commands\DrushCommands;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\State\StateInterface;

/**
 * Drush command.
 */
class DrushTask extends DrushCommands {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructor for the DrushHelpersCommands class.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(MessengerInterface $messenger, StateInterface $state) {
    $this->messenger = $messenger;
    $this->state = $state;
    parent::__construct();
  }

  /**
   * Put the site into maintenance mode.
   *
   * @command site-maintenance:enable
   * @aliases sme
   * @usage drush site-maintenance:enable
   */
  public function enableMaintenance() {
    $this->state->set('system.maintenance_mode', TRUE);
    $this->messenger->addStatus('Site is now in maintenance mode.');
  }

}
