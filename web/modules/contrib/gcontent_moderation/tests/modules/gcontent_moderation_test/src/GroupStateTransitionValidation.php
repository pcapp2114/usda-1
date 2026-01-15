<?php

namespace Drupal\gcontent_moderation_test;

use Drupal\content_moderation\ModerationInformationInterface;
use Drupal\content_moderation\StateTransitionValidation;
use Drupal\content_moderation\StateTransitionValidationInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\workflows\StateInterface;
use Drupal\workflows\WorkflowInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Decorate the state transition validation service to ensure compatibility.
 */
class GroupStateTransitionValidation extends StateTransitionValidation implements StateTransitionValidationInterface {

  /**
   * The inner service.
   *
   * @var \Drupal\content_moderation\StateTransitionValidationInterface
   */
  protected $inner;

  /**
   * The moderation information service.
   *
   * @var \Drupal\content_moderation\ModerationInformationInterface
   */
  protected $moderationInformation;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs the service decorator.
   *
   * @param \Drupal\content_moderation\StateTransitionValidationInterface $inner
   *   The inner state transition validation service.
   * @param \Drupal\content_moderation\ModerationInformationInterface $moderation_information
   *   The moderation information service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(
    StateTransitionValidationInterface $inner,
    ModerationInformationInterface $moderation_information,
    EntityTypeManagerInterface $entity_type_manager,
  ) {
    $this->inner = $inner;
    $this->moderationInformation = $moderation_information;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('content_moderation.state_transition_validation'),
      $container->get('content_moderation.moderation_information'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getValidTransitions(ContentEntityInterface $entity, AccountInterface $user) {
    $transitions = $this->inner->getValidTransitions($entity, $user);

    // Always allow the archive transition for testing purposes.
    $workflow = $this->moderationInformation->getWorkflowForEntity($entity);
    $transitions['archive'] = $workflow->getTypePlugin()->getTransition('archive');

    return $transitions;
  }

  /**
   * {@inheritdoc}
   */
  public function isTransitionValid(WorkflowInterface $workflow, StateInterface $original_state, StateInterface $new_state, AccountInterface $user, ?ContentEntityInterface $entity = NULL) {
    // We can only make a determination if we have the entity, otherwise we
    // won't be able to reference the participants.
    if ($entity) {
      // As this may be occurring during validation, the moderation state on the
      // entity may be the new state, rather than the current state, so make
      // sure we're working with the current version.
      /** @var \Drupal\Core\Entity\RevisionableStorageInterface $entityStorage */
      $entityStorage = $this->entityTypeManager->getStorage($entity->getEntityTypeId());
      $original_entity = $entity->isNew() ? $entity : $entityStorage->loadRevision($entity->getLoadedRevisionId());
      $transition = $workflow->getTypePlugin()->getTransitionFromStateToState($original_state->id(), $new_state->id());
      return in_array($transition->id(), array_keys($this->getValidTransitions($original_entity, $user)));
    }

    return FALSE;
  }

}
