<?php

namespace Drupal\mailchimp_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'MailChimp Subscription' Block.
 *
 * @Block(
 *   id = "mailchimp_subscribe_block_sample",
 *   admin_label = @Translation("MailChimp Subscribe sample Block"),
 * )
 */
class MailChimpSubscribeBlock extends BlockBase {

  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * If a block should not be rendered because it has no content, then this
   * method must also ensure to return no content: it must then only return an
   * empty array, or an empty array with #cache set (with cacheability metadata
   * indicating the circumstances for it being empty).
   *
   * @return array
   *   A renderable array representing the content of the block.
   *
   * @see \Drupal\block\BlockViewBuilder
   */
  public function build() {
    // TODO: Implement build() method.

    $active_domain = \Drupal::service('domain.negotiator')->getActiveDomain();
    // For Drupal multisite
    $domain_suffix = $active_domain->getThirdPartySetting('country_path', 'domain_path');
    $main_domain = '/your-url'; 
    if(preg_match($main_domain, $domain_suffix)) {
      return \Drupal::formBuilder()
        ->getForm('\Drupal\mailchimp_block\Form\MailChimpSubscribeFormTest');
    } 
  }
}