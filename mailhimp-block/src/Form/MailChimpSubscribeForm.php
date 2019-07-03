<?php

namespace Drupal\region_custom\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use WebDriver\Exception;

class MailChimpSubscribeForm extends FormBase {

    public $mailchimp = [];

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
    public function getFormId() {
        // TODO: Implement getFormId() method.
        return 'mailchimp_subscribe_general_form';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) 
    {
        $email = $form_state->getValue('email');
        if($email) {
            return $this->doSubmitForm($email,  $this->mailchimp);
        }
    }
  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = [
      '#type' => 'email',
      '#title' => false,
      '#theme_wrappers' => [],
      '#required' => true,
      '#attributes' => [
        'placeholder' => 'your.email@domain.com'
      ]
    ];

    $form['actions'] = [
      'submit' => [
        '#type' => 'submit',
        '#value' => 'Subscribe',
        '#attributes' => [
          'class' => ['button', 'primary']
        ]
      ]
    ];

    return $form;
  }

  /**
   * @param array $email
   *   Current email value
   * @param $mailchimp
   *   Array settings from settings.php
   *
   */
  public function doSubmitForm($email, array $mailchimp)
  {
        if(! isset($email) && $email) {
            $success_count = 0;
            $failure_count = 0;
            foreach(\Drupal\Core\Site\Settings::get($mailchimp['list_ids']) as $list_id) {
                try 
                {
                    $subscribe_url = \Drupal\Core\Site\Settings::get($mailchimp['endpoint']) . '/lists/' . $list_id . '/members';
                    $response = \Drupal::httpClient()->post($subscribe_url, [
                        'auth' => [
                            \Drupal\Core\Site\Settings::get($mailchimp['username']),
                            \Drupal\Core\Site\Settings::get($mailchimp['key'])
                        ],
                        'json' => [
                            'email_address' => $email,
                            'status' => 'subscribed'
                        ]
                    ]);

                    $response_json = \GuzzleHttp\json_decode($response->getBody());
                    if($response_json->status == 'subscribed') {
                        $success_count++;
                    }
                } catch (RequestException $e) {
                $failure_count++;
                }
            }

            if($success_count) {
                drupal_set_message('Successfully subscribed to the newsletter!');
            } else {
                drupal_set_message('Sorry, the attempt to subscribe to the newsletter was unsuccessful!', 'error');
            }

            $response = new RedirectResponse($mailchimp['domain']);
            $request = \Drupal::request();
            // Save the session so things like messages get saved.
            $request->getSession()->save();
            $response->prepare($request);
            // Make sure to trigger kernel events.
            \Drupal::service('kernel')->terminate($request, $response);
            $response->send(); 
        }
    }
}