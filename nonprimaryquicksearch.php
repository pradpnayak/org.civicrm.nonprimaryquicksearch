<?php

require_once 'nonprimaryquicksearch.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function nonprimaryquicksearch_civicrm_config(&$config) {
  _nonprimaryquicksearch_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function nonprimaryquicksearch_civicrm_xmlMenu(&$files) {
  _nonprimaryquicksearch_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function nonprimaryquicksearch_civicrm_install() {
  _nonprimaryquicksearch_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function nonprimaryquicksearch_civicrm_postInstall() {
  _nonprimaryquicksearch_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function nonprimaryquicksearch_civicrm_uninstall() {
  _nonprimaryquicksearch_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function nonprimaryquicksearch_civicrm_enable() {
  _nonprimaryquicksearch_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function nonprimaryquicksearch_civicrm_disable() {
  _nonprimaryquicksearch_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function nonprimaryquicksearch_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _nonprimaryquicksearch_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function nonprimaryquicksearch_civicrm_managed(&$entities) {
  _nonprimaryquicksearch_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function nonprimaryquicksearch_civicrm_caseTypes(&$caseTypes) {
  _nonprimaryquicksearch_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function nonprimaryquicksearch_civicrm_angularModules(&$angularModules) {
  _nonprimaryquicksearch_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function nonprimaryquicksearch_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _nonprimaryquicksearch_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function nonprimaryquicksearch_civicrm_entityTypes(&$entityTypes) {
  _nonprimaryquicksearch_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function nonprimaryquicksearch_civicrm_themes(&$themes) {
  _nonprimaryquicksearch_civix_civicrm_themes($themes);
}

/**
 * Implements hook_civicrm_contactListQuery().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_contactListQuery
 */
function nonprimaryquicksearch_civicrm_contactListQuery(&$query, $queryText, $context, $id) {
  $jSon = CRM_Utils_Request::retrieve('json', 'String');
  if (!empty($jSon)) {
    $searchOnNonPrimary = civicrm_api3('Setting', 'getvalue', [
      'name' => 'searchPrimaryDetailsOnly',
    ]);
    if (!empty($searchOnNonPrimary)) {
      return;
    }
    $jSon = json_decode($jSon, TRUE);
    if (CRM_Utils_Array::value('field_name', $jSon)) {
      $replaceString = NULL;
      switch ($jSon['field_name']) {
        case 'city':
        case 'street_address':
        case 'postal_code':
          $replaceString = 'AND sts.is_primary = 1';
          break;

        case 'email':
          $replaceString = 'AND eml.is_primary = 1';
          break;

        case 'phone_numeric':
          $replaceString = 'AND phe.is_primary = 1';
          break;
      }
      if ($replaceString) {
        $query = str_replace($replaceString, '', $query);
      }
    }
  }
}
