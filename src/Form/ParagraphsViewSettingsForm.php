<?php

namespace Drupal\paragraphs_view\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\paragraphs_view\Form
 */
class ParagraphsViewSettingsForm extends ConfigFormBase {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a \Drupal\paragraphs_view\Form\ParagraphsViewSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'paragraphs_view.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'paragraphs_view_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('paragraphs_view.settings')->getRawData();
    $paragraphsTypes = $this->entityTypeManager->getStorage('paragraphs_type')
      ->loadMultiple();

    if (!empty($paragraphsTypes)) {
      foreach ($paragraphsTypes as $paragraphsTypeId => $paragraphsType) {
        $form[$paragraphsTypeId] = [
          '#type' => 'checkbox',
          '#title' => $paragraphsType->label(),
          '#default_value' => !empty($config['paragraphs'][$paragraphsTypeId])
        ];
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save configs.
    parent::submitForm($form, $form_state);
    $config = $this->config('paragraphs_view.settings');
    $config->set('paragraphs', $form_state->cleanValues()->getValues());
    $config->save();
  }

}
