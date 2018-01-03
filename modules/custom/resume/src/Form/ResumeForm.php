<?php

/* 
 * @file
 * Contains \Drupal\resume\Form\ResumeForm.php
 * and open the template in the editor.
 */
namespace Drupal\resume\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class ResumeForm extends FormBase {
    /**
     * Form Id
     */
   public function getFormId(){
        return 'resume_form';
    }
    /**
     * 
     * @param array $form
     * @param FormStateInterface $form_state
     * @return string
     */
    public function buildForm(array $form, FormStateInterface $form_state){
        $form['candidate_name'] = array(
            "#title"=>t("Candidate Name"),
            "#type"=>"textfield",
            "#required"=>TRUE,
        );
        $form['candidate_email'] = array(
            "#title"=>t('Candiate Email'),
            "#type"=>"email",
            "#required"=>TRUE,
        );
        $form['candidate_number'] = array (
            '#type' => 'tel',
            '#title' => t('Mobile no'),
          );
        $form['candidate_dob'] = array (
          '#type' => 'date',
          '#title' => t('DOB'),
          '#required' => TRUE,
        );
        $form['candidate_gender'] = array (
          '#type' => 'select',
          '#title' => ('Gender'),
          '#options' => array(
            'Female' => t('Female'),
            'male' => t('Male'),
          ),
        );
        $form['candidate_confirmation'] = array (
          '#type' => 'radios',
          '#title' => ('Are you above 18 years old?'),
          '#options' => array(
            'Yes' =>t('Yes'),
            'No' =>t('No')
          ),
        );
        $form['candidate_copy'] = array(
          '#type' => 'checkbox',
          '#title' => t('Send me a copy of the application.'),
        );
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
          '#type' => 'submit',
          '#value' => $this->t('Save'),
          '#button_type' => 'primary',
        );
        return $form;
        
    }
  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {
      if (strlen($form_state->getValue('candidate_number')) < 10) {
        $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
      }
    }
    
     /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   // drupal_set_message($this->t('@can_name ,Your application is being submitted!', array('@can_name' => $form_state->getValue('candidate_name'))));
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }
   }
}

