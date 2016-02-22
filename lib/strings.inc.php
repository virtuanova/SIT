<?php
// strings.inc.php - Set up strings
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Prevent script from being run directly (ie. it must always be included
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    exit;
}


//create array of strings in the system's language for updates etc
$SYSLANG = $_SESSION['syslang'];

// Hierarchical Menus
/* Arrays containing menu options for the top menu, each menu has an associated permission number and this is used */
/* to decide which menu to display.  In addition each menu item has an associated permission   */
/* This is so we can decide whether a user should see the menu option or not.                                     */
/* perm = permission number */
/*
$hmenu[1031] = array (10=> array ( 'perm'=> 0, 'name'=> "Option1", 'url'=>""),
                      20=> array ( 'perm'=> 0, 'name'=> "Option2", 'url'=>""),
                      30=> array ( 'perm'=> 0, 'name'=> "Option3", 'url'=>"")
);
*/

//
// Main Menu
//
if (!is_array($hmenu[0])) $hmenu[0] = array();
$hmenu[0] = $hmenu[0] +
                array (10=> array ( 'perm'=> 0, 'name'=> $CONFIG['application_shortname'], 'url'=>"{$CONFIG['application_webpath']}main.php", 'submenu'=>"10"),
                       20=> array ( 'perm'=> 11, 'name'=> $strCustomers, 'url'=>"{$CONFIG['application_webpath']}sites.php", 'submenu'=>"20"),
                       30=> array ( 'perm'=> 6, 'name'=> $strSupport, 'url'=>"{$CONFIG['application_webpath']}incidents.php?user=current&amp;queue=1&amp;type=support", 'submenu'=>"30"),
                       40=> array ( 'perm'=> 0, 'name'=> $strTasks, 'url'=>"{$CONFIG['application_webpath']}tasks.php", 'submenu'=>"40", 'enablevar' => 'tasks_enabled'),
                       50=> array ( 'perm'=> 54, 'name'=> $strKnowledgeBase, 'url'=>"{$CONFIG['application_webpath']}kb.php", 'submenu'=>"50", 'enablevar' => 'kb_enabled'),
                       60=> array ( 'perm'=> 37, 'name'=> $strReports, 'url'=>"", 'submenu'=>"60"),
                       70=> array ( 'perm'=> 0, 'name'=> $strHelp, 'url'=>"{$CONFIG['application_webpath']}help.php", 'submenu'=>"70")
);
// SiT submenu
if (!is_array($hmenu[10])) $hmenu[10] = array();
$hmenu[10] = $hmenu[10] +
                array (1=> array ( 'perm'=> 0, 'name'=> $strDashboard, 'url'=>"{$CONFIG['application_webpath']}main.php"),
                       10=> array ( 'perm'=> 60, 'name'=> $strSearch, 'url'=>"{$CONFIG['application_webpath']}search.php"),
                       20=> array ( 'perm'=> 4, 'name'=> $strMyDetails, 'url'=>"{$CONFIG['application_webpath']}user_profile_edit.php", 'submenu'=>"1020"),
                       30=> array ( 'perm'=> 4, 'name'=> $strControlPanel, 'url'=>"{$CONFIG['application_webpath']}control_panel.php", 'submenu'=>"1030"),
                       40=> array ( 'perm'=> 14, 'name'=> $strUsers, 'url'=>"{$CONFIG['application_webpath']}users.php", 'submenu'=>"1040"),
                       50=> array ( 'perm'=> 0, 'name'=> $strLogout, 'url'=>"{$CONFIG['application_webpath']}logout.php")
);
// My Details submenu
if (!is_array($hmenu[1020])) $hmenu[1020] = array();
$hmenu[1020] = $hmenu[1020] +
                array (10=> array ( 'perm'=> 4, 'name'=> $strMyProfile, 'url'=>"{$CONFIG['application_webpath']}user_profile_edit.php"),
                       20=> array ( 'perm'=> 58, 'name'=> $strMySkills, 'url'=>"{$CONFIG['application_webpath']}edit_user_skills.php"),
                       30=> array ( 'perm'=> 58, 'name'=> $strMySubstitutes, 'url'=>"{$CONFIG['application_webpath']}edit_backup_users.php"),
                       40=> array ( 'perm'=> 27, 'name'=> $strMyHolidays, 'url'=>"{$CONFIG['application_webpath']}holidays.php", 'enablevar' => 'holidays_enabled'),
                       50=> array ( 'perm'=> 4, 'name'=> $strMyDashboard, 'url'=>"{$CONFIG['application_webpath']}manage_user_dashboard.php"),
                       60=> array ( 'perm'=> 0, 'name'=> $strMyNotifications, 'url'=>"{$CONFIG['application_webpath']}triggers.php")
);
// Control Panel submenu
// TODO v3.40 set a permission for triggers
if (!is_array($hmenu[1030])) $hmenu[1030] = array();
$hmenu[1030] = $hmenu[1030] +
                array (05 => array ( 'perm'=> 22, 'name'=> $strConfigure, 'url'=>"{$CONFIG['application_webpath']}config.php"),
                       10=> array ( 'perm'=> 22, 'name'=> $strUsers, 'url'=>"{$CONFIG['application_webpath']}manage_users.php", 'submenu'=>"103010"),
                       20=> array ( 'perm'=> 43, 'name'=> $strGlobalSignature, 'url'=>"{$CONFIG['application_webpath']}edit_global_signature.php"),
                       30=> array ( 'perm'=> 22, 'name'=> $strTemplates, 'url'=>"{$CONFIG['application_webpath']}templates.php"),
                       40=> array ( 'perm'=> 22, 'name'=> $strSetPublicHolidays, 'url'=>"{$CONFIG['application_webpath']}calendar.php?type=10&amp;display=year", 'enablevar' => 'holidays_enabled'),
                       50=> array ( 'perm'=> 22, 'name'=> $strFTPFilesDB, 'url'=>"{$CONFIG['application_webpath']}ftp_list_files.php"),
                       60=> array ( 'perm'=> 22, 'name'=> $strServiceLevels, 'url'=>"{$CONFIG['application_webpath']}service_levels.php"),
                       70=> array ( 'perm'=> 7, 'name'=> $strBulkModify, 'url'=>"{$CONFIG['application_webpath']}bulk_modify.php?action=external_esc"),
                       80=> array ( 'perm'=> 64, 'name'=> $strEscalationPaths, 'url'=>"{$CONFIG['application_webpath']}escalation_paths.php"),
                       90=> array ( 'perm'=> 66, 'name'=> $strManageDashboardComponents, 'url'=>"{$CONFIG['application_webpath']}manage_dashboard.php"),
                       100=> array ( 'perm'=> 78, 'name'=> $strNotices, 'url'=>"{$CONFIG['application_webpath']}notices.php"),
                       120=> array ( 'perm'=> 22, 'name'=> $strSystemActions, 'url'=>"{$CONFIG['application_webpath']}triggers.php?user=0"),
                       130=> array ( 'perm'=> 22, 'name'=> $strScheduler, 'url'=>"{$CONFIG['application_webpath']}scheduler.php"),
                       140=> array ( 'perm'=> 49, 'name'=> $strFeedbackForms, 'url'=>"", 'submenu'=>"103090", 'enablevar' => 'feedback_enabled'),
                       150=> array ( 'perm'=> 22, 'name'=> $strJournal, 'url'=>"{$CONFIG['application_webpath']}journal.php")
);
// Control Panel: Manage Users submenu
if (!is_array($hmenu[103010])) $hmenu[103010] = array();
$hmenu[103010] = $hmenu[103010] +
                array (10=> array ( 'perm'=> 22, 'name'=> $strManageUsers, 'url'=>"{$CONFIG['application_webpath']}manage_users.php"),
                       20=> array ( 'perm'=> 20, 'name'=> $strAddUser, 'url'=>"{$CONFIG['application_webpath']}user_add.php?action=showform"),
                       30=> array ( 'perm'=> 9, 'name'=> $strRolePermissions, 'url'=>"{$CONFIG['application_webpath']}edit_user_permissions.php"),
                       40=> array ( 'perm'=> 23, 'name'=> $strUserGroups, 'url'=>"{$CONFIG['application_webpath']}usergroups.php"),
                       50=> array ( 'perm'=> 22, 'name'=> $strEditHolidayEntitlement, 'url'=>"{$CONFIG['application_webpath']}edit_holidays.php", 'enablevar' => 'holidays_enabled')
);
// Control Panel: Feedback forms submenu
if (!is_array($hmenu[103090])) $hmenu[103090] = array();
$hmenu[103090] = $hmenu[103090] +
                array (10=> array ( 'perm'=> 49, 'name'=> $strAddFeedbackForm, 'url'=>"{$CONFIG['application_webpath']}feedback_form_edit.php?action=new", 'enablevar' => 'feedback_enabled'),
                       20=> array ( 'perm'=> 49, 'name'=> $strBrowseFeedbackForms, 'url'=>"{$CONFIG['application_webpath']}feedback_form_list.php", 'enablevar' => 'feedback_enabled')
);
// SiT: Users Submenu
if (!is_array($hmenu[1040])) $hmenu[1040] = array();
$hmenu[1040] = $hmenu[1040] +
                array (10=> array ( 'perm'=> 0, 'name'=> $strViewUsers, 'url'=>"{$CONFIG['application_webpath']}users.php"),
                       20=> array ( 'perm'=> 0, 'name'=> $strListSkills, 'url'=>"{$CONFIG['application_webpath']}user_skills.php"),
                       21=> array ( 'perm'=> 0, 'name'=> $strSkillsMatrix, 'url'=>"{$CONFIG['application_webpath']}skills_matrix.php"),
                       30=> array ( 'perm'=> 27, 'name'=> $strHolidayPlanner, 'url'=>"{$CONFIG['application_webpath']}calendar.php?display=month", 'enablevar' => 'holidays_enabled'),
                       40=> array ( 'perm'=> 50, 'name'=> $strApproveHolidays, 'url'=>"{$CONFIG['application_webpath']}holiday_request.php?user=all&amp;mode=approval", 'enablevar' => 'holidays_enabled')
);
// Customers menu
if (!is_array($hmenu[20])) $hmenu[20] = array();
$hmenu[20] = $hmenu[20] +
                array (10=> array ( 'perm'=> 0, 'name'=> $strSites, 'url'=>"{$CONFIG['application_webpath']}sites.php", 'submenu'=>"2010"),
                       20=> array ( 'perm'=> 0, 'name'=> $strContacts, 'url'=>"{$CONFIG['application_webpath']}contacts.php?search_string=A", 'submenu'=>"2020"),
                       35=> array ( 'perm'=> 0, 'name'=> $strMaintenance, 'url'=>"{$CONFIG['application_webpath']}contracts.php?search_string=A", 'submenu'=>"2030"),
                       30=> array ( 'perm'=> 0, 'name'=> $strInventory, 'url'=>"{$CONFIG['application_webpath']}inventory.php", 'enablevar' => 'inventory_enabled'),
                       40=> array ( 'perm'=> 0, 'name'=> $strBrowseFeedback, 'url'=>"{$CONFIG['application_webpath']}feedback_browse.php", 'enablevar' => 'feedback_enabled')
);
// Customers: Sites submenu
if (!is_array($hmenu[2010])) $hmenu[2010] = array();
$hmenu[2010] = $hmenu[2010] +
                array (10=> array ( 'perm'=> 11, 'name'=> $strBrowse, 'url'=>"{$CONFIG['application_webpath']}sites.php"),
                       20=> array ( 'perm'=> 2, 'name'=> $strNewSite, 'url'=>"{$CONFIG['application_webpath']}site_add.php?action=showform")
);
// Customers: Contacts submenu
if (!is_array($hmenu[2020])) $hmenu[2020] = array();
$hmenu[2020] = $hmenu[2020] +
                array (10=> array ( 'perm'=> 11, 'name'=> $strBrowse, 'url'=>"{$CONFIG['application_webpath']}contacts.php?search_string=A"),
                       20=> array ( 'perm'=> 1, 'name'=> $strNewContact, 'url'=>"{$CONFIG['application_webpath']}contact_add.php?action=showform")
);
// Customers: Maintenance submenu
if (!is_array($hmenu[2030])) $hmenu[2030] = array();
$hmenu[2030] = $hmenu[2030] +
                array (10=> array ( 'perm'=> 19, 'name'=> $strBrowse, 'url'=>"{$CONFIG['application_webpath']}contracts.php?search_string=A"),
                       20=> array ( 'perm'=> 39, 'name'=> $strNewContract, 'url'=>"{$CONFIG['application_webpath']}contract_add.php?action=showform"),
                       30=> array ( 'perm'=> 21, 'name'=> $strEditContract, 'url'=>"{$CONFIG['application_webpath']}contract_edit.php?action=showform"),
                       40=> array ( 'perm'=> 2, 'name'=> $strNewReseller, 'url'=>"{$CONFIG['application_webpath']}reseller_add.php"),
                       41=> array ( 'perm'=> 56, 'name'=> $strSiteTypes, 'url'=>"{$CONFIG['application_webpath']}site_types.php"),
                       50=> array ( 'perm'=> 19, 'name'=> $strShowRenewals, 'url'=>"{$CONFIG['application_webpath']}search_renewals.php?action=showform"),
                       60=> array ( 'perm'=> 19, 'name'=> $strShowExpiredContracts, 'url'=>"{$CONFIG['application_webpath']}search_expired.php?action=showform"),
                       70=> array ( 'perm'=> 0, 'name'=> "{$strProducts} &amp; {$strSkills}", 'url'=>"{$CONFIG['application_webpath']}products.php", 'submenu'=>"203010"),
                       80=> array ( 'perm'=> 37, 'name'=> $strBilling, 'url'=>"{$CONFIG['application_webpath']}billable_incidents.php")
);
// Customers: Maintenance: Products & Skills submenu
if (!is_array($hmenu[203010])) $hmenu[203010] = array();
$hmenu[203010] = $hmenu[203010] +
                array (10=> array ( 'perm'=> 56, 'name'=> $strAddVendor, 'url'=>"{$CONFIG['application_webpath']}vendor_add.php"),
                       20=> array ( 'perm'=> 24, 'name'=> $strAddProduct, 'url'=>"{$CONFIG['application_webpath']}product_add.php"),
                       30=> array ( 'perm'=> 28, 'name'=> $strListProducts, 'url'=>"{$CONFIG['application_webpath']}products.php"),
                       35=> array ( 'perm'=> 28, 'name'=> $strListSkills, 'url'=>"{$CONFIG['application_webpath']}products.php?display=skills"),
                       40=> array ( 'perm'=> 56, 'name'=> $strAddSkill, 'url'=>"{$CONFIG['application_webpath']}software_add.php"),
                       50=> array ( 'perm'=> 24, 'name'=> $strLinkProducts, 'url'=>"{$CONFIG['application_webpath']}product_software_add.php"),
                       60=> array ( 'perm'=> 25, 'name'=> $strAddProductQuestion, 'url'=>"{$CONFIG['application_webpath']}product_info_add.php"),
                       70=> array ('perm'=> 56, 'name'=> $strEditVendor, 'url'=>"{$CONFIG['application_webpath']}vendor_edit.php")
);

// FIXME Have temporarily disabled the inbox feature by removing it from the menu for v3.50 release
// //need to call directly as we don't have functions yet
// $sql = "SELECT COUNT(*) AS count FROM `{$dbTempIncoming}`";
// $result = mysql_query($sql);
// list($inbox_count) = mysql_fetch_row($result);
// if ($inbox_count > 0)
// {
//     $inbox_count = " <strong>(".$inbox_count.")</strong>";
// }
// else $inbox_count = '';

// 40=> array ( 'perm'=> 42, 'name'=> $strInbox.$inbox_count, 'url'=>"{$CONFIG['application_webpath']}inbox.php", 'enablevar' => 'enable_inbound_mail'),

// Support menu
if (!is_array($hmenu[30])) $hmenu[30] = array();
$hmenu[30] = $hmenu[30] +
            array (10=> array ( 'perm'=> 5, 'name'=> $strAddIncident, 'url'=>"{$CONFIG['application_webpath']}incident_add.php"),
                   20=> array ( 'perm'=> 0, 'name'=> $strMyIncidents, 'url'=>"{$CONFIG['application_webpath']}incidents.php"),
                   30=> array ( 'perm'=> 0, 'name'=> $strAllIncidents, 'url'=>"{$CONFIG['application_webpath']}incidents.php?user=all&amp;queue=1&amp;type=support"),
                   50=> array ( 'perm'=> 42, 'name'=> $strHoldingQueue, 'url'=>"{$CONFIG['application_webpath']}holding_queue.php")
);

// Tasks menu
if (!is_array($hmenu[40])) $hmenu[40] = array();
$hmenu[40] = $hmenu[40] +
            array (10=> array ( 'perm'=> 70, 'name'=> $strAddTask, 'url'=>"{$CONFIG['application_webpath']}task_add.php"),
                   20=> array ( 'perm'=> 69, 'name'=> $strViewTasks, 'url'=>"{$CONFIG['application_webpath']}tasks.php")
);

// Knowledge Base menu
if (!is_array($hmenu[50])) $hmenu[50] = array();
$hmenu[50] = $hmenu[50] +
            array (10=> array ( 'perm'=> 54, 'name'=> $strNewKBArticle, 'url'=>"{$CONFIG['application_webpath']}kb_article.php"),
                   20=> array ( 'perm'=> 54, 'name'=> $strBrowse, 'url'=>"{$CONFIG['application_webpath']}kb.php")
);

// Reports menu
if (!is_array($hmenu[60])) $hmenu[60] = array();
$hmenu[60] = $hmenu[60] +
            array (10=> array ( 'perm'=> 37, 'name'=>"{$strMarketingMailshot}", 'url'=>"{$CONFIG['application_webpath']}report_marketing.php"),
                   20=> array ( 'perm'=> 37, 'name'=> "{$strCustomerExport}", 'url'=>"{$CONFIG['application_webpath']}report_customers.php"),
                   30=> array ( 'perm'=> 37, 'name'=> "{$strQueryByExample}", 'url'=>"{$CONFIG['application_webpath']}report_qbe.php"),
                   35=> array ( 'perm'=> 37, 'name'=> "{$strIncidents}", 'url'=>"", 'submenu' => '6050'),
                   60=> array ( 'perm'=> 37, 'name'=> "{$strSiteProducts}", 'url'=>"{$CONFIG['application_webpath']}report_customer_products.php"),
                   61=> array ( 'perm'=> 37, 'name'=> "{$strSiteProductsMatrix}", 'url'=>"{$CONFIG['application_webpath']}report_customer_products_matrix.php"),
                   65=> array ( 'perm'=> 37,  'name'=> "{$strCountContractsByProduct}", 'url'=>"{$CONFIG['application_webpath']}report_contracts_by_product.php"),
                   70=> array ( 'perm'=> 37, 'name'=> "{$strSiteContracts}", 'url'=>"{$CONFIG['application_webpath']}report_customer_contracts.php"),
                   80=> array ( 'perm'=> 37, 'name'=> "{$strCustomerFeedback}", 'url'=>"{$CONFIG['application_webpath']}report_feedback.php", 'enablevar' => 'feedback_enabled'),
                   180=> array ( 'perm'=> 37, 'name'=> "{$strEngineerMonthlyActivityTotals}",'url'=>"{$CONFIG['application_webpath']}report_billable_engineer_utilisation.php",
));
// Reports: Incidents submenu
if (!is_array($hmenu[6050])) $hmenu[6050] = array();
$hmenu[6050] = $hmenu[6050] +
            array (10=> array ( 'perm'=> 37, 'name'=> "{$strIncidentsBySite}", 'url'=>"{$CONFIG['application_webpath']}report_incidents_by_site.php"),
                   20=> array ( 'perm'=> 37, 'name'=> "{$strIncidentsByEngineer}", 'url'=>"{$CONFIG['application_webpath']}report_incidents_by_engineer.php"),
                   30=> array ( 'perm'=> 37, 'name'=> "{$strSiteIncidents}", 'url'=>"{$CONFIG['application_webpath']}report_incidents_by_customer.php"),
                   40=> array ( 'perm'=> 37, 'name'=> "{$strRecentIncidents}", 'url'=>"{$CONFIG['application_webpath']}report_incidents_recent.php"),
                   50=> array ( 'perm'=> 37, 'name'=> "{$strIncidentsLoggedOpenClosed}", 'url'=>"{$CONFIG['application_webpath']}report_incidents_graph.php"),
                   60=> array ( 'perm'=> 37, 'name'=> "{$strAverageIncidentDuration}", 'url'=>"{$CONFIG['application_webpath']}report_incidents_average_duration.php"),
                   70=> array ( 'perm'=> 37, 'name'=> "{$strIncidentsBySkill}", 'url'=>"{$CONFIG['application_webpath']}report_incidents_by_skill.php"),
                   80=> array ( 'perm'=> 37, 'name'=> "{$strIncidentsByVendor}", 'url'=>"{$CONFIG['application_webpath']}report_incidents_by_vendor.php"),
                   90=> array ( 'perm'=> 37, 'name'=> "{$strEscalatedIncidents}",'url'=>"{$CONFIG['application_webpath']}report_incidents_escalated.php"),
                   100=> array ( 'perm'=> 37, 'name'=> "{$strBillableIncidents}",'url'=>"{$CONFIG['application_webpath']}report_incidents_billable.php"),
                   110=> array ( 'perm'=> 37, 'name'=> "{$strIncidentsDailySummary}",'url'=>"{$CONFIG['application_webpath']}report_incidents_daily_summary.php")
);
// Help menu
if (!is_array($hmenu[70])) $hmenu[70] = array();
$hmenu[70] + $hmenu[70] =
            array (10=> array ( 'perm'=> 0, 'name'=> "{$strHelpContents}...", 'url'=>"{$CONFIG['application_webpath']}help.php"),
                   15=> array ( 'perm'=> 0, 'name'=> "{$strGetHelpOnline}", 'url'=>"http://sitracker.org/wiki/Documentation".strtoupper(substr($_SESSION['lang'],0,2))),
                   20=> array ( 'perm'=> 0, 'name'=> "{$strTranslate}", 'url'=>"{$CONFIG['application_webpath']}translate.php"),
                   30=> array ( 'perm'=> 0, 'name'=> "{$strReportBug}", 'url'=>$CONFIG['bugtracker_url']),
                   40=> array ( 'perm'=> 0, 'name'=> "{$strReleaseNotes}", 'url'=>"{$CONFIG['application_webpath']}releasenotes.php"),
                   50=> array ( 'perm'=> 41, 'name'=> $strHelpAbout, 'url'=>"{$CONFIG['application_webpath']}about.php")
);

if ($_SESSION['auth'] == TRUE AND function_exists('plugin_do')) plugin_do('define_menu');

// Sort the top level menu, so that plugin menus appear in the right place
ksort($hmenu[0], SORT_NUMERIC);

//
// Non specific update types
//
$updatetypes['actionplan'] = array('icon' => 'actionplan', 'text' => sprintf($strActionPlanBy,'updateuser'));
$updatetypes['auto'] = array('icon' => 'auto', 'text' => sprintf($strUpdatedAutomaticallyBy, 'updateuser'));
$updatetypes['closing'] = array('icon' => 'close', 'text' => sprintf($strMarkedforclosureby,'updateuser'));
$updatetypes['editing'] = array('icon' => 'edit', 'text' => sprintf($strEditedBy,'updateuser'));
$updatetypes['email'] = array('icon' => 'emailout', 'text' => sprintf($strEmailsentby,'updateuser'));
$updatetypes['emailin'] = array('icon' => 'emailin', 'text' => sprintf($strEmailreceivedby,'updateuser'));
$updatetypes['emailout'] = array('icon' => 'emailout', 'text' => sprintf($strEmailsentby,'updateuser'));
$updatetypes['externalinfo'] = array('icon' => 'externalinfo', 'text' => sprintf($strExternalInfoAddedBy,'updateuser'));
$updatetypes['probdef'] = array('icon' => 'probdef', 'text' => sprintf($strProblemDefinitionby,'updateuser'));
$updatetypes['research'] = array('icon' => 'research', 'text' => sprintf($strResearchedby,'updateuser'));
$updatetypes['reassigning'] = array('icon' => 'reassign', 'text' => sprintf($strReassignedToBy,'currentowner','updateuser'));
$updatetypes['reviewmet'] = array('icon' => 'review', 'text' => sprintf($strReviewby, 'updatereview', 'updateuser')); // conditional
$updatetypes['tempassigning'] = array('icon' => 'tempassign', 'text' => sprintf($strTemporarilyAssignedto,'currentowner','updateuser'));
$updatetypes['opening'] = array('icon' => 'open', 'text' => sprintf($strOpenedby,'updateuser'));
$updatetypes['phonecallout'] = array('icon' => 'callout', 'text' => sprintf($strPhonecallmadeby,'updateuser'));
$updatetypes['phonecallin'] = array('icon' => 'callin', 'text' => sprintf($strPhonecalltakenby,'updateuser'));
$updatetypes['reopening'] = array('icon' => 'reopen', 'text' => sprintf($strReopenedby,'updateuser'));
$updatetypes['slamet'] = array('icon' => 'sla', 'text' => sprintf($strSLAby,'updatesla', 'updateuser'));
$updatetypes['solution'] = array('icon' => 'solution', 'text' => sprintf($strResolvedby, 'updateuser'));
$updatetypes['webupdate'] = array('icon' => 'webupdate', 'text' => sprintf($strWebupdate));
$updatetypes['auto_chase_phone'] = array('icon' => 'chase', 'text' => $strRemind);
$updatetypes['auto_chase_manager'] = array('icon' => 'chase', 'text' => $strRemind);
$updatetypes['auto_chase_email'] = array('icon' => 'chased', 'text' => $strReminded);
$updatetypes['auto_chased_phone'] = array('icon' => 'chased', 'text' => $strReminded);
$updatetypes['auto_chased_manager'] = array('icon' => 'chased', 'text' => $strReminded);
$updatetypes['auto_chased_managers_manager'] = array('icon' => 'chased', 'text' => $strChased);
$updatetypes['customerclosurerequest'] = array('icon' => 'close', 'text' => $strCustomerRequestedClosure);
$updatetypes['fromtask'] = array('icon' => 'timer', 'text' => sprintf($strUpdatedFromActivity, 'updateuser'));
$slatypes['opened'] = array('icon' => 'open', 'text' => $strOpened);
$slatypes['initialresponse'] = array('icon' => 'initialresponse', 'text' => $strInitialResponse);
$slatypes['probdef'] = array('icon' => 'probdef', 'text' => $strProblemDefinition);
$slatypes['actionplan'] = array('icon' => 'actionplan', 'text' => $strActionPlan);
$slatypes['solution'] = array('icon' => 'solution', 'text' => $strSolution);
$slatypes['closed'] = array('icon' => 'close', 'text' => $strClosed);


// Language codes and language name in local language
// Aphabetical by language code
// This is the list of languages that SiT recognises, to configure which of
// these languages to use, make sure you have a file in your i18n dir and
// go to the sit configuration page
$i18n_codes = array(
                    'af' => 'Afrikaans',
                    'ar' => 'العربية',
                    'bg-BG' => 'български',
                    'bn-IN' => 'বাংলা',
                    'cs-CZ' => 'Český',
                    'en-GB' => 'English (British)',
                    'en-US' => 'English (US)',
                    'ca-ES' => 'Català',
                    'cy-GB' => 'Cymraeg',
                    'da-DK' => 'Dansk',
                    'de-DE' => 'Deutsch',
                    'el-GR' => 'Ελληνικά',
                    'es-ES' => 'Español',
                    'es-CO' => 'Español (Colombia)',
                    'es-MX' => 'Español (Mexicano)',
                    'et-EE' => 'Eesti',
                    'eu-ES' => 'Euskara',
                    'fa-IR' => 'فارسی',
                    'fi-FI' => 'Suomi',
                    'fo-FO' => 'føroyskt',
                    'fr-FR' => 'Français',
                    'he-IL' => 'עִבְרִית',
                    'hr-HR' => 'Hrvatski',
                    'hu-HU' => 'Magyar',
                    'id-ID' => 'Bahasa Indonesia',
                    'is-IS' => 'Íslenska',
                    'it-IT' => 'Italiano',
                    'ja-JP' => '日本語',
                    'ka' => 'ქართული',
                    'ko-KR' => '한국어',
                    'lt-LT' => 'Lietuvių',
                    'ms-MY' => 'بهاس ملايو',
                    'nb-NO' => 'Norsk (Bokmål)',
                    'nl-NL' => 'Nederlands',
                    'nn-NO' => 'Norsk (Nynorsk)',
                    'pl-PL' => 'Polski',
                    'pt-BR' => 'Português (Brasil)',
                    'pt-PT' => 'Português',
                    'ro-RO' => 'Română',
                    'ru-RU' => 'Русский',
                    'sl-SL' => 'Slovenščina',
                    'sk-SK' => 'Slovenčina',
                    'sr-YU' => 'Српски',
                    'sv-SE' => 'Svenska',
                    'th-TH' => 'ภาษาไทย',
                    'tr_TR' => 'Türkçe',
                    'uk-UA' => 'Украї́нська мо́ва',
                    'zh-CN' => '简体中文',
                    'zh-TW' => '繁體中文',
                    );


// List of timezones, with UTC offset in minutes
// Source: http://en.wikipedia.org/wiki/List_of_time_zones (where else?)
$availabletimezones = array('-720' => 'UTC-12',
                            '-660' => 'UTC-11',
                            '-600' => 'UTC-10',
                            '-570' => 'UTC-9:30',
                            '-540' => 'UTC-9',
                            '-480' => 'UTC-8',
                            '-420' => 'UTC-7',
                            '-360' => 'UTC-6',
                            '-300' => 'UTC-5',
                            '-270' => 'UTC-4:30',
                            '-240' => 'UTC-4',
                            '-210' => 'UTC-3:30',
                            '-180' => 'UTC-3',
                            '-120' => 'UTC-2',
                            '-60' => 'UTC-1',
                            '0' => 'UTC',
                            '60' => 'UTC+1',
                            '120' => 'UTC+2',
                            '180' => 'UTC+3',
                            '210' => 'UTC+3:30',
                            '240' => 'UTC+4',
                            '300' => 'UTC+5',
                            '330' => 'UTC+5:30',
                            '345' => 'UTC+5:45',
                            '360' => 'UTC+6',
                            '390' => 'UTC+6:30',
                            '420' => 'UTC+7',
                            '480' => 'UTC+8',
                            '525' => 'UTC+8:45',
                            '540' => 'UTC+9',
                            '570' => 'UTC+9:30',
                            '600' => 'UTC+10',
                            '630' => 'UTC+10:30',
                            '660' => 'UTC+11',
                            '690' => 'UTC+11:30',
                            '720' => 'UTC+12',
                            '765' => 'UTC+12:45',
                            '780' => 'UTC+13',
                            '840' => 'UTC+14',
                           );
?>