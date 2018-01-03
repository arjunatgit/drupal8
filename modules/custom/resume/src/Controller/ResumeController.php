<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Drupal\resume\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
/**
 * Table displays table controller
 * @package Drupal\resume\Controller
 */
class ResumeController extends ControllerBase {
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
        'candidate_dob' => t('DOB'),
        'candidate_gender' => t('Gender'),
        'website' => t('Web site'),
        'candidate_resume_title' => t('Resume File Name'),
        'opt' => t('operations'),
        'opt1' => t('operations'),
        );
        
       $query = \Drupal::database()->select('resume','r');
       $query->fields('r',['id','candidate_name','candidate_number','candidate_email','candidate_dob','candidate_gender','website','candidate_resume_title']);
       $results = $query->execute()->fetchAll();
       $rows = array();
       $add = Url::fromUserInput('/resume/form/myform');
       
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
            'candidate_resume_title' => $result->candidate_resume_title,
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

