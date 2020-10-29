<?php

namespace Drupal\paragraphs_view;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides permissions of the paragraphs_view module.
 */
class ParagraphsViewPermissions implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The settings of this module.
   *
   * @var \Drupal\Core\Site\Settings
   */
  protected $settings;

  /**
   * ParagraphsViewPermissions constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->settings = $this->configFactory->get('paragraphs_view.settings')->get('paragraphs');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory')
    );
  }

  /**
   * Get list of permissions.
   *
   * @return array|array[]
   *   Collection of permissions.
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function permissions() {
    $perms = [];
    if ($this->settings) {
      $paragraphs_type = $this->entityTypeManager->getStorage('paragraphs_type')
        ->loadMultiple();
      foreach ($this->settings as $paragraph => $value) {
        if ($value) {
          $perms += [
            "access paragraph view for $paragraph" => [
              'title' => $this->t('Access view page for %label paragraph', ['%label' => $paragraphs_type[$paragraph]->label()]),
            ],
          ];
        }
      }
    }
    return $perms;
  }

}
