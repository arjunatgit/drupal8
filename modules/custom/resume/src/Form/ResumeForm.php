<?php

/* 
 * @file
 * Contains \Drupal\resume\Form\ResumeForm.php
 * and open the template in the editor.
 */
namespace Drupal\resume\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $conn = Database::getConnection();
        $record = array();
        
        if (isset($_GET['num'])) {
            $query = $conn->select('resume','r')
                    ->condition('id',$_GET['num'])
                    ->fields('r');
            $record = $query->execute()->fetchAssoc();
        }
        
        $form['candidate_name'] = array(
            "#title" => t("Candidate Name"),
            "#type" => "textfield",
            "#required" => TRUE,
            '#default_value' => (isset($record['candidate_name']) && $_GET['num']) ? $record['candidate_name']:'',
        );
        $form['candidate_email'] = array(
            "#title" => t('Candiate Email'),
            "#type" => "email",
            "#required" => TRUE,
            '#default_value' => (isset($record['candidate_email']) && $_GET['num']) ? $record['candidate_email']:'',
        );
        $form['candidate_number'] = array (
            '#type' => 'tel',
            '#title' => t('Mobile no'),
            '#default_value' => (isset($record['candidate_number']) && $_GET['num']) ? $record['candidate_number']:'',
          );
        $form['candidate_dob'] = array (
          '#type' => 'date',
          '#title' => t('DOB'),
          '#required' => TRUE,
          '#default_value' => (isset($record['candidate_dob']) && $_GET['num']) ? $record['candidate_dob']:'',
        );
        $form['candidate_gender'] = array (
          '#type' => 'select',
          '#title' => ('Gender'),
          '#options' => array(
            'Female' => t('Female'),
            'male' => t('Male'),
          ),
          '#default_value' => (isset($record['candidate_gender']) && $_GET['num']) ? $record['candidate_gender']:'',
        );
        $form['candidate_confirmation'] = array (
          '#type' => 'radios',
          '#title' => ('Are you above 18 years old?'),
          '#options' => array(
            'Yes' =>t('Yes'),
            'No' =>t('No')
          ),
          '#default_value' => (isset($record['candidate_confirmation']) && $_GET['num']) ? $record['candidate_confirmation']:'',
        );
         $form['website'] = array(
            "#title"=>t("Website"),
            "#type"=>"textfield",
            "#required"=>TRUE,
            '#default_value' => (isset($record['website']) && $_GET['num']) ? $record['website']:'',
        );
         $form['resume_file'] = array(
            "#title"=>t("Resume File"),
            "#type"=>"managed_file",
            "#name"=>'resume_file',
            '#upload_location' => 'public://resume/',
            "#required"=>FALSE,
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
      if (strlen($form_state->getValue('candidate_number')) > 10) {
        $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too long.'));
      }
      $name = $form_state->getValue('candidate_name');
      if(preg_match('/[^A-Za-z]/', $name)) {
         $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
      }
      //parent::validateForm($form, $form_state);
       
    }
    
     /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   $field = $form_state->getValues();
   $file = \Drupal\file\Entity\File::load($form_state->getValue('resume_file')[0]);
   $name=$field['candidate_name'];
    //echo "$name";
    $number=$field['candidate_number'];
    $email=$field['candidate_email'];
    $age=$field['candidate_dob'];
    $gender=$field['candidate_gender'];
    $website=$field['website'];
    $confirmation = $field['candidate_confirmation'];
    $fiel_name = $file->getFilename();
    if (isset($_GET['num'])) {
          $field  = array(
              'candidate_name'   => $name,
              'candidate_number' =>  $number,
              'candidate_email' =>  $email,
              'candidate_dob' => $age,
              'candidate_gender' => $gender,
              'candidate_confirmation' => $confirmation,
              'website' => $website,
              'candidate_resume_title' => $fiel_name,
          );
          $query = \Drupal::database();
          $query->update('resume')
              ->fields($field)
              ->condition('id', $_GET['num'])
              ->execute();
        drupal_set_message("succesfully updated");
          $form_state->setRedirect('resume.resume_controller_display');
      }
       else
       {
           $field  = array(
              'candidate_name'   => $name,
              'candidate_number' =>  $number,
              'candidate_email' =>  $email,
              'candidate_dob' => $age,
              'candidate_gender' => $gender,
              'candidate_confirmation' => $confirmation,
              'website' => $website,
              'candidate_resume_title' => $fiel_name,
          );
           $query = \Drupal::database();
           $query ->insert('resume')
               ->fields($field)
               ->execute();
         
           drupal_set_message("succesfully saved");
           $response = new RedirectResponse("/resume/list");
           $response->send();
       }
     }
}

