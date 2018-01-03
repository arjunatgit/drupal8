<?php
namespace Drupal\resume\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class DisplayTableController extends ControllerBase {
     public function getContent(){
        $build = [
            'description'=> [
                '#theme' => 'resume_description', 
                '#description' => 'foo',
                '#attributes' => [],
            ],
        ];
        return $build;
    }
    
    public function display(){
        $header_table = array(
        'id'=>    t('SrNo'),
        'candidate_name' => t('Name'),
        'candidate_number' => t('MobileNumber'),
        'candidate_email'=>t('Email'),
        'candidate_dob' => t('Age'),
        'candidate_gender' => t('Gender'),
        'website' => t('Web site'),
        'opt' => t('operations'),
        'opt1' => t('operations'),
        );
        
       $query = \Drupal::database()->select('resume','r');
       $query->fields('r',['id','candidate_name','candidate_number','candidate_email','candidate_dob','candidate_gender','website']);
       $results = $query->execute()->fetchAll();
       $rows = array();
       foreach ($results as $result){
           $delete = Url::fromUserInput('/resume/form/delete/'.$result->id);
           $edit = Url::fromUserInput('/resume/form/myform?num='.$result->id);
           
           //print the data from table
             $rows[] = array(
            'id' =>$result->id,
            'candidate_name' => $result->candidate_name,
            'candidate_number' => $result->candidate_number,
            'candidate_email' => $result->candidate_email,
            'candidate_dob' => $result->candidate_dob,
            'candidate_gender' => $result->candidate_gender,
            'website' => $result->website,
             \Drupal::l('Delete', $delete),
             \Drupal::l('Edit', $edit),
            );
       }
       
       //display data in site
    $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No users found'),
        ];
        return $form;
    }
    
}
