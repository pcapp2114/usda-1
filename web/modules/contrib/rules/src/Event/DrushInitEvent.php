<?php

namespace Drupal\rules\Event;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Event that is fired when Drush tasks are performed.
 *
 * @see rules_drush_init()
 */
class DrushInitEvent extends GenericEvent {

  const EVENT_NAME = 'rules_drush_init';

}
