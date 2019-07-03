<?php

namespace Drupal\mailchimp_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use WebDriver\Exception;
use Drupal\region_custom\Form\MailChimpSubscribeForm;

class MailChimpSubscribeFormTest extends MailChimpSubscribeForm {

    public $mailchimp = [
        'key'       => 'mailchimp_key', 
        'endpoint'  => 'mailchimp_endpoint',
        'list_ids'  => 'mailchimp_subscribe_list_ids',
        'username'  => 'mailchimp_username',
        'domain'    => '[your-url]'
    ];
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
    public function getFormId() {
        // TODO: Implement getFormId() method.
        return 'mailchimp_subscribe_form';
    }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
    $email = $form_state->getValue('email');
    $this->doSubmitForm($email, $this->$mailchimp);
  }
}