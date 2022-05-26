<?php

namespace Drupal\rules\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\rules\Context\ExecutionState;
use Drupal\rules\Core\RulesConfigurableEventHandlerInterface;
use Drupal\rules\Core\RulesEventManager;
use Drupal\rules\Engine\RulesComponentRepositoryInterface;
use Drupal\rules\Event\DrushInitEvent;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\Event as SymfonyComponentEvent;
use Symfony\Contracts\EventDispatcher\Event as SymfonyContractsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Subscribes to Symfony events and maps them to Rules events.
 */
class GenericEventSubscriber implements EventSubscriberInterface {

  /**
   * The entity type manager used for loading reaction rule config entities.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Rules event manager.
   *
   * @var \Drupal\rules\Core\RulesEventManager
   */
  protected $eventManager;

  /**
   * The component repository.
   *
   * @var \Drupal\rules\Engine\RulesComponentRepositoryInterface
   */
  protected $componentRepository;

  /**
   * The eventDispatcher service.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * A ModuleHandler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The rules debug logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $rulesDebugLogger;

  /**
   * Events to subscribe if container is not available. See #2816033.
   *
   * @var array
   */
  private static $staticEvents = [
    KernelEvents::CONTROLLER => ['registerDynamicEvents', 100],
    KernelEvents::REQUEST => ['registerDynamicEvents', 100],
    KernelEvents::TERMINATE => ['registerDynamicEvents', 100],
    KernelEvents::VIEW => ['registerDynamicEvents', 100],
    DrushInitEvent::EVENT_NAME => ['registerDynamicEvents', 100],
  ];

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\rules\Core\RulesEventManager $event_manager
   *   The Rules event manager.
   * @param \Drupal\rules\Engine\RulesComponentRepositoryInterface $component_repository
   *   The component repository.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Logger\LoggerChannelInterface $logger
   *   The Rules debug logger channel.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager,
                              RulesEventManager $event_manager,
                              RulesComponentRepositoryInterface $component_repository,
                              EventDispatcherInterface $event_dispatcher,
                              ModuleHandlerInterface $module_handler,
                              LoggerChannelInterface $logger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->eventManager = $event_manager;
    $this->componentRepository = $component_repository;
    $this->eventDispatcher = $event_dispatcher;
    $this->moduleHandler = $module_handler;
    $this->rulesDebugLogger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Register this listener for every event that is used by a reaction rule.
    $events = [];
    $callback = ['onRulesEvent', 100];

    // If there is no state service there is nothing we can do here. This static
    // method could be called early when the container is built, so the state
    // service might not always be available.
    if (!\Drupal::hasService('state')) {
      return self::$staticEvents;
    }

    // Since we cannot access the reaction rule config storage here we have to
    // use the state system to provide registered Rules events. The Reaction
    // Rule storage is responsible for keeping the registered events up to date
    // in the state system.
    // @see \Drupal\rules\Entity\ReactionRuleStorage
    $state = \Drupal::state();
    $registered_event_names = $state->get('rules.registered_events');
    if (!empty($registered_event_names)) {
      foreach ($registered_event_names as $event_name) {
        $events[$event_name][] = $callback;
      }
    }
    return $events;
  }

  /**
   * Rebuilds container when dynamic rule eventsubscribers are not registered.
   *
   * @param object $event
   *   The event object containing context for the event.
   *   In Drupal 9 this will be a \Symfony\Component\EventDispatcher\Event,
   *   In Drupal 10 this will be a \Symfony\Contracts\EventDispatcher\Event.
   * @param string $event_name
   *   The event name.
   */
  public function registerDynamicEvents(object $event, $event_name) {
    // @todo The 'object' type hint should be replaced with the appropriate
    // class once Symfony 4 is no longer supported, and the assert() should be
    // removed.
    assert(
      $event instanceof SymfonyComponentEvent ||
      $event instanceof SymfonyContractsEvent
    );

    foreach (self::$staticEvents as $old_event_name => $method) {
      $this->eventDispatcher
        ->removeListener($old_event_name, [$this, $method[0]]);
    }
    $this->eventDispatcher->addSubscriber($this);
    $this->moduleHandler->reload();
  }

  /**
   * Reacts on the given event and invokes configured reaction rules.
   *
   * @param object $event
   *   The event object containing context for the event.
   *   In Drupal 9 this will be a \Symfony\Component\EventDispatcher\Event,
   *   In Drupal 10 this will be a \Symfony\Contracts\EventDispatcher\Event.
   * @param string $event_name
   *   The event name.
   */
  public function onRulesEvent(object $event, $event_name) {
    // @todo The 'object' type hint should be replaced with the appropriate
    // class once Symfony 4 is no longer supported, and the assert() should be
    // removed.
    assert(
      $event instanceof SymfonyComponentEvent ||
      $event instanceof SymfonyContractsEvent
    );

    // Get event metadata and the to-be-triggered events.
    $event_definition = $this->eventManager->getDefinition($event_name);
    $handler_class = $event_definition['class'];
    $triggered_events = [$event_name];
    if (is_subclass_of($handler_class, RulesConfigurableEventHandlerInterface::class)) {
      $qualified_event_suffixes = $handler_class::determineQualifiedEvents($event, $event_name, $event_definition);
      foreach ($qualified_event_suffixes as $qualified_event_suffix) {
        // This is where we add the bundle-specific event suffix, e.g.
        // rules_entity_insert:node--page if the content entity was type 'page'.
        $triggered_events[] = "$event_name--$qualified_event_suffix";
      }
    }

    // Setup the execution state.
    $state = ExecutionState::create();
    foreach ($event_definition['context_definitions'] as $context_name => $context_definition) {
      // If there is a getter method set in the event definition, use that.
      // @see https://www.drupal.org/project/rules/issues/2762517
      if ($context_definition->hasGetter()) {
        $value = $event->{$context_definition->getGetter()}();
      }
      // If this is a GenericEvent, get the value of the context variable from
      // the event arguments.
      elseif ($event instanceof GenericEvent) {
        $value = $event->getArgument($context_name);
      }
      // Else we cheat and use a closure to get the property value.
      // This works for public, protected, and private Event properties.
      else {
        $getter = function ($property) {
          return $this->{$property};
        };
        $value = $getter->call($event, $context_name);
      }
      $state->setVariable($context_name, $context_definition, $value);
    }

    $components = $this->componentRepository->getMultiple($triggered_events, 'rules_event');
    foreach ($components as $component) {
      $this->rulesDebugLogger->info('Reacting on event %label.', [
        '%label' => $event_definition['label'],
        'element' => NULL,
        'scope' => TRUE,
      ]);
      $component->getExpression()->executeWithState($state);
      $this->rulesDebugLogger->info('Finished reacting on event %label.', [
        '%label' => $event_definition['label'],
        'element' => NULL,
        'scope' => FALSE,
      ]);
    }
    $state->autoSave();
  }

}
