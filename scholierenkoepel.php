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
  }


}

function scholierenkoepel_civicrm_postProcess( $formName, &$form  ) {

  if( $formName == 'CRM_Event_Form_ManageEvent_Registration' ) {
    $eventId = $form->_id;
    $issk = $form->_submitValues['is_enhanced'];
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

