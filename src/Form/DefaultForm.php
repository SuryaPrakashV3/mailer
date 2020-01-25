<?php

namespace Drupal\mailer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultForm.
 */
class DefaultForm extends FormBase {

  public $receiverMail = ['surya99var@gmail.com' => 'surya', 'sathyavpm06@gmail.com' => 'sathya', 'mailaddress@mail.com' => 'username'];
  public $senderAddress = "surya99var@gmail.com";
  public $subject = "";
  public $body = "default body";

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_form';
  }

  /**
   * {@inheritdoc}
   * 
   
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['to'] = [
      '#type' => 'textfield',
      '#attributes' => array('class' => array('hidden')),
      '#id' => 'toFinal',
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];

    $form['selection'] = [
      '#type' => 'select',
      // '#title' => $this->t('Select from available emails:\n'),
      '#options' => $this->receiverMail,
      '#size' => 5,
      '#weight' => '0',
      '#attributes' => ['multiple' => 'true'],
    ];
    $form['body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Comments'),
      '#placeholder' => $this->t('Enter comments here'),
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Notify & Save'),
      '#attributes' =>  array('class' => array('btn btn-warning bg-warning')),
    ];
    
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.

    $user = \Drupal::currentUser();
    
    // Set current user's address as sender
    $this->senderAddress = $user->getEmail();

    // Set subject
    $this->subject = "New article created by ".$user->getUsername();
    
    // Getting Form data
    foreach ($form_state->getValues() as $key => $value) {
      // \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format'?$value['value']:$value));
      if($key === 'to') {
        $this->receiverMail = $value;
      } elseif($key === 'body') {
        $this->body = $value;
      }
    }
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'mailer';
    $key = 'create_article';
    $to = $this->receiverMail;
    $params['from'] = $this->senderAddress;
    $params['subject'] = $this->subject;
    $params['message'] = $this->body;
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