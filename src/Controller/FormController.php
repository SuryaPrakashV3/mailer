<?php

namespace Drupal\mailer\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class FormController.
 */
class FormController extends ControllerBase {

  /**
   * Showform.
   *
   * @return string
   *   Return Hello string.
   */
  public function showForm() {
    return [
      '#theme' => 'mailerform',
    ];
  }
  
  public function sendMail($to, $body) {
    $user = \Drupal::currentUser();
    
    // Set current user's address as sender
    $senderAddress = $user->getEmail();

    // Set subject
    $subject = "New article created by ".$user->getUsername();
    
    // Getting Form data
    
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'mailer';
    $key = 'create_article';
    $params['from'] = $senderAddress;
    $params['subject'] = $subject;
    $params['message'] = $body;
    $langcode = $user->getPreferredLangcode();
    $send = true;

    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      \Drupal::messenger()->addMessage(('There was a problem sending your message and it was not sent.'), 'error');
    }
    else {
      \Drupal::messenger()->addMessage(('Your message has been sent.'));
    }
}

}
