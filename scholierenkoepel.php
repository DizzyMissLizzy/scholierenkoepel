<?php

require_once 'scholierenkoepel.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function scholierenkoepel_civicrm_config(&$config) {
  _scholierenkoepel_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function scholierenkoepel_civicrm_xmlMenu(&$files) {
  _scholierenkoepel_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function scholierenkoepel_civicrm_install() {
  $dirRoot =dirname( __FILE__ );
  $dirSQL = $dirRoot . DIRECTORY_SEPARATOR .'sql/scholierenkoepel.install.sql';
  CRM_Utils_File::sourceSQLFile( CIVICRM_DSN, $dirSQL );
  CRM_Core_Invoke::rebuildMenuAndCaches( );
  return _scholierenkoepel_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function scholierenkoepel_civicrm_uninstall() {
  $dirRoot =dirname( __FILE__ );
  $dirSQL = $dirRoot . DIRECTORY_SEPARATOR .'sql/scholierenkoepel.uninstall.sql';
  CRM_Utils_File::sourceSQLFile( CIVICRM_DSN, $dirSQL );
  return _scholierenkoepel_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function scholierenkoepel_civicrm_enable() {
  return _scholierenkoepel_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function scholierenkoepel_civicrm_disable() {
  return _scholierenkoepel_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function scholierenkoepel_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _scholierenkoepel_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function scholierenkoepel_civicrm_managed(&$entities) {
  return _scholierenkoepel_civix_civicrm_managed($entities);
}

function scholierenkoepel_civicrm_buildForm($formName, &$form) {
require_once 'api/api.php';

  if ($formName == 'CRM_Event_Form_Registration_Confirm' || $formName == 'CRM_Event_Form_Registration_ThankYou') {
  }
  if ($formName == 'CRM_Event_Form_Registration_Register') {


  }
  if( $formName == 'CRM_Event_Form_ManageEvent_Registration' ) {
      $form->addElement( 'checkbox', 'is_sk', ts( 'Gebruik registratie SK' ) );
      $eventID = $form->_id;
      $is_sk = null;
      $is_multiple = null;
      $is_enhanced = CRM_Core_DAO::singleValueQuery( "SELECT is_sk FROM civicrm_event_sk WHERE event_id = $eventID" );
      $is_multiple = CRM_Core_DAO::singleValueQuery( "SELECT is_multiple_registrations FROM civicrm_event WHERE id = $eventID" );
      $defaults['is_sk'] = $is_sk;
      $defaults['is_multiple_registrations'] = $is_multiple;

      $form->setDefaults( $defaults );

      //Profile Chooser
    require_once 'CRM/Core/BAO/UFGroup.php';
    require_once 'CRM/Contact/BAO/ContactType.php';
    $types = array_merge(array('Contact', 'Individual', 'Participant', 'Organization'),
                         CRM_Contact_BAO_ContactType::subTypes('Individual')
                         );
    $profiles = CRM_Core_BAO_UFGroup::getProfiles($types);
    $mainProfiles = array(
                          '' => ts('- select -')) + $profiles;
     CRM_Core_error::debug('description', $mainProfiles);
  }
}

function scholierenkoepel_civicrm_validate( $formName, &$fields, &$files, &$form )  {

  if( $formName == 'CRM_Event_Form_ManageEvent_Registration' ) {
    $eventId = $form->_id;
    $isenhanced = $form->_submitValues['is_sk'];
    if ($isenhanced) {
      //check that the selected profiles have either firstname+lastname or email required
      $profileIds = array(
        CRM_Utils_Array::value('custom_pre_id', $form->_submitValues),
        CRM_Utils_Array::value('custom_post_id', $form->_submitValues),
      );
      $additionalProfileIds = array(
        CRM_Utils_Array::value('additional_custom_pre_id', $form->_submitValues),
        CRM_Utils_Array::value('additional_custom_post_id', $form->_submitValues),
      );

      //additional profile fields default to main if not set
      if (!is_numeric($additionalProfileIds[0])) {
        $additionalProfileIds[0] = $profileIds[0];
      }
      if (!is_numeric($additionalProfileIds[1])) {
        $additionalProfileIds[1] = $profileIds[1];
      }

      //add multiple profiles if set
      CRM_Event_Form_ManageEvent_Registration::addMultipleProfiles($profileIds, $form->_submitValues, 'custom_post_id_multiple');
      CRM_Event_Form_ManageEvent_Registration::addMultipleProfiles($additionalProfileIds, $form->_submitValues, 'additional_custom_post_id_multiple');

      $isProfileComplete = isProfileComplete($profileIds);
      $isAdditionalProfileComplete = isProfileComplete($additionalProfileIds);
      if (!$isProfileComplete) {
        $errors['custom_pre_id'] = ts("Please include a Profile for online registration that contains a First Name + Last Name fields.");
      }
      if (!$isAdditionalProfileComplete) {
        $errors['additional_custom_pre_id'] = ts("Please include a Profile for online registration of additional participants that contains a First Name + Last Name fields.");
      }
    }
  }
}

function scholierenkoepel_civicrm_postProcess( $formName, &$form  ) {

  if( $formName == 'CRM_Event_Form_ManageEvent_Registration' ) {
    $eventId = $form->_id;
    $issk = $form->_submitValues['is_sk'];
    if( !$issk ) {
      $issk = 0;
    }
    if( $issk ) {
      $isSK = CRM_Core_DAO::singleValueQuery( "SELECT id FROM civicrm_event_sk WHERE event_id = $eventId" );
      if (!empty($isEnhanced) ) {

      } else {
        CRM_Core_DAO::executeQuery( "INSERT INTO civicrm_event_sk( id, event_id, is_sk ) values( null,'$eventId','$issk' )" );
      }
    }
  }
}

