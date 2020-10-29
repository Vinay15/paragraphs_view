<?php

namespace Drupal\paragraphs_view\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Class ParagraphsViewAccessController.
 *
 * @package Drupal\paragraphs_view\Controller
 */
class ParagraphsViewAccessController extends ControllerBase {

  /**
   * Check if permission to access paragraphs view page.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $paragraph
   *   The paragraph entity for which the access is being checked.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account to check if it has the required permission.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Return allowed if current user account has permission to access entity.
   */
  public function access(ParagraphInterface $paragraph, AccountInterface $account) {
    $config = $this->config('paragraphs_view.settings');
    return AccessResult::allowedIfHasPermission($account, 'access paragraph view for ' . $paragraph->bundle())
      ->addCacheableDependency($config);
  }

}
