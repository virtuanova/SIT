<?php
// configvars.inc.php - List of SiT configuration variables
//                      and functions to manage them
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Author: Ivan Lucas, <ivanlucas[at]users.sourceforge.net

// Prevent script from being run directly (ie. it must always be included
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    exit;
}

// 'soap' commented out by ivan 3 june 2009
$CFGTAB['application'] = array('appmain', 'theming', 'ldap', 'other');
$CFGTAB['email'] = array('inboundemail','outboundemail');
$CFGTAB['features'] = array('incidents', 'portal', 'ftp', 'kb', 'sla', 'holidays', 'feedback', 'inventory');
$CFGTAB['system'] = array('paths', 'locale', 'journal');

$TABI18n['application'] = $strApplication;
$TABI18n['email'] = $strEmail;
$TABI18n['features'] = $strFeatures;
$TABI18n['system'] = $strSystem;
$TABI18n['plugins'] = $strPlugins;

$CFGCAT['paths'] = array('application_webpath',
                         'access_logfile',
                         'attachment_fspath',
                         'attachment_webpath');


$CFGCAT['appmain'] = array('application_name',
                               'application_shortname',
                               'application_uriprefix',
                               'logout_url',
                               'plugins'
                               );

$CFGCAT['locale'] = array('home_country',
                          'timezone',
                          'dst_adjust',
                          'dateformat_datetime',
                          'dateformat_date',
                          'dateformat_filedatetime',
                          'dateformat_longdate',
                          'dateformat_shortdate',
                          'dateformat_shorttime',
                          'dateformat_time',
                          'currency_symbol',
                          'default_i18n',
                          'available_i18n');

$CFGCAT['sla'] = array('default_service_level',
                       'start_working_day',
                       'end_working_day',
                       'critical_threshold',
                       'urgent_threshold',
                       'notice_threshold',
                       'regular_contact_days',
                       'working_days');


$CFGCAT['theming'] = array('default_interface_style', 'default_css_url', 'default_gravatar','font_file','tag_icons');

$CFGCAT['ftp'] = array('ftp_hostname', 'ftp_username', 'ftp_password', 'ftp_pasv', 'ftp_path');

$CFGCAT['portal'] = array('portal',
                          'portal_kb_enabled',
                          'portal_site_incidents',
                          'portal_usernames_can_be_changed',
                          'portal_creates_incidents',
                          'portal_interface_style');

$CFGCAT['holidays'] = array('holidays_enabled',
                            'default_entitlement');

$CFGCAT['incidents'] = array('auto_assign_incidents',
                             'free_support_limit',
                             'hide_closed_incidents_older_than',
                             'incident_pools',
                             'preferred_maintenance',
                             'record_lock_delay');


$CFGCAT['inboundemail'] = array('enable_inbound_mail',
                         'email_server',
                         'email_servertype',
                         'email_port',
                         'email_options',
                         'email_username',
                         'email_password',
                         'email_address',
                         'email_incoming_folder',
                         'email_archive_folder',
                         'max_incoming_email_perday',
                         'spam_email_subject'
                         );

$CFGCAT['outboundemail'] = array('support_email');


$CFGCAT['feedback'] = array('feedback_enabled',
                            'feedback_form',
                            'feedback_max_score',
                            'no_feedback_contracts');

$CFGCAT['ldap'] = array('use_ldap',
                        'ldap_type',
                        'ldap_host',
                        'ldap_port',
                        'ldap_protocol',
                        'ldap_security',
                        'ldap_bind_user',
                        'ldap_bind_pass',
                        'ldap_user_base',
                        'ldap_default_user_status',
                        'ldap_admin_group',
                        'ldap_manager_group',
                        'ldap_user_group',
                        'ldap_customer_group',
                        'ldap_default_customer_siteid',
                        'ldap_autocreate_customer',
                        'ldap_cache_passwords',
                        'ldap_allow_cached_password');

$CFGCAT['soap'] = array('soap_enabled',
                         'soap_portal_enabled');

$CFGCAT['kb'] = array('kb_enabled',
                      'kb_disclaimer_html',
                      'kb_id_prefix');

// $CFGCAT['outboundemail'] = array();
$CFGCAT['journal'] = array('journal_loglevel', 'journal_purge_after');

$CFGCAT['inventory'] = array('inventory_enabled', 'inventory_types');

$CFGCAT['other'] = array('debug', 'error_logfile',
                        'support_manager', 'tasks_enabled', 'timesheets_enabled',
                          'bugtracker_url',
                          'changelogfile','creditsfile',
                          'licensefile',
                          'session_name',
                          'upload_max_filesize','default_roleid','trusted_server');



// i18n keys for categories
$CATI18N['appmain'] = $strGeneral;
$CATI18N['theming'] = $strTheme;
$CATI18N['ldap'] = $strLDAP;
$CATI18N['soap'] = $strSOAP;
$CATI18N['other'] = $strOther;
$CATI18N['inboundemail'] = $strInbound;
$CATI18N['outboundemail'] = $strOutbound;
$CATI18N['incidents'] = $strIncidents;
$CATI18N['portal'] = $strPortal;
$CATI18N['ftp'] = $strFTP;
$CATI18N['kb'] = $strKnowledgeBase;
$CATI18N['sla'] = $strServiceLevels;
$CATI18N['holidays'] = $strHolidays;
$CATI18N['feedback'] = $strFeedback;
$CATI18N['paths'] = $strPaths;
$CATI18N['locale'] = $strLocale;
$CATI18N['journal'] = $strJournal;
$CATI18N['inventory'] = $strInventory;

// Text to introduce a configuration category, may contain HTML
$CATINTRO['sla'] = "This section allows you to configure how service levels are used, configure the <abbr title='Service Level Agreements'>SLA</abbr>'s themselves on the <a href='service_levels.php'>Service Levels</a> page.";
$CATINTRO['inboundemail'] = "Before enabling inbound email with POP/IMAP you must also configure the Scheduler to run, see the <a href='http://sitracker.org/wiki/Scheduler'>documentation</a> for more details.";


// Descriptions of all the config variables
// each config var may have these elements:
//      title   - A title/short description of the configuration variable
//      help    - A line of instructions/help to assist the user configuring
//      helplink - A help context label for help/en-GB/help.txt type help
//      type - A datatype, see cfgVarInput() for list
//      unit - A unit string to print after the input
//      options - A pipe seperated list of optios for a 'select' type

$CFGVAR['access_logfile']['title'] = 'Path to an access log file';
$CFGVAR['access_logfile']['help'] = "The filesystem path and filename of a file that already exist and is writable to log authentication messages into.";

// DEPRECATED leaving here just in case, can be removed >= 3.50 - INL
$CFGVAR['application_fspath']['help']="The full absolute filesystem path to the SiT! directory with trailing slash. e.g. '/var/www/sit/'";
$CFGVAR['application_fspath']['title'] = 'Filesystem Path';

$CFGVAR['application_name']['title'] = 'Application Name';
$CFGVAR['application_name']['help'] = 'The full name of this application. This is displayed at the top of each page and various other places throughout the web interface.';

$CFGVAR['application_shortname']['title'] = 'Short Application Name';
$CFGVAR['application_shortname']['help'] = 'A short (abbreviated) version of the application name. This is used to refer to this application where space is at a premium.';

$CFGVAR['application_uriprefix']['title'] = 'URI Prefix';
$CFGVAR['application_uriprefix']['help'] = "The <abbr title='Uniform Resource Identifier'>URI</abbr> prefix to use when referring to this application (in emails etc.) e.g. https://www.example.com";

$CFGVAR['application_webpath']['title'] = 'Web Path';
$CFGVAR['application_webpath']['help'] = 'The path to SiT! from the browsers perspective with a trailing slash. e.g. /sit/';

$CFGVAR['attachment_fspath']['title'] = "Attachment Filesystem Path";
$CFGVAR['attachment_fspath']['help'] = "The full absolute file system path to a directory to store attachments in (with a trailing slash). This directory should be writable";

$CFGVAR['attachment_webpath']['title'] = "Attachment Web Path";
$CFGVAR['attachment_webpath']['help'] = 'The path to the attachments directory from the browsers perspective with a trailing slash. e.g. /sit/';

$CFGVAR['auto_assign_incidents']['help'] = "incidents are automatically assigned based on a lottery weighted towards who are less busy, assumes everyone set to accepting is an engineer and willing to take incidents";
$CFGVAR['auto_assign_incidents']['options'] = 'TRUE|FALSE';
$CFGVAR['auto_assign_incidents']['title'] = "Auto-assign incidents";
$CFGVAR['auto_assign_incidents']['type'] = 'select';

$CFGVAR['available_i18n']['title'] = "Languages Available";
$CFGVAR['available_i18n']['help'] = "The languages available for users to select at login or in their profile.";
$CFGVAR['available_i18n']['type'] = 'languagemultiselect';

$CFGVAR['bugtracker_url']['title'] = 'Bug tracker URL';
$CFGVAR['bugtracker_url']['help'] = "The <abbr title='Uniform Resource Locator'>URL</abbr> of a web page to report bugs with SiT!  We recommend you don't alter this setting unless you really need to.";

$CFGVAR['calendar_enabled']['help'] = "TRUE for enabled, FALSE for disabled";
$CFGVAR['calendar_enabled']['options'] = 'TRUE|FALSE';
$CFGVAR['calendar_enabled']['title'] = "Calendar Enabled/Disabled";
$CFGVAR['calendar_enabled']['type'] = 'select';

$CFGVAR['changelogfile']['title'] = 'Path to the Changelog file';
$CFGVAR['changelogfile']['help'] = 'The filesystem path and filename of the SiT! Changelog file, this can be specified relative to the SiT directory.';

$CFGVAR['creditsfile']['title'] = 'Path to the Credits file';
$CFGVAR['creditsfile']['help'] = 'The filesystem path and filename of the SiT! CREDITS file, this can be specified relative to the SiT directory.';

$CFGVAR['critical_threshold']['title'] = 'Critical Threshold';
$CFGVAR['critical_threshold']['help'] = 'Flag items as critical when they are this percentage complete.';
$CFGVAR['critical_threshold']['type'] = 'percent';

$CFGVAR['currency_symbol']['title'] = 'Currency Symbol';
$CFGVAR['currency_symbol']['help'] = 'Currency symbol to use when displaying monetary amounts.';

$CFGVAR['dateformat_datetime']['help'] = "See <a href='http://www.php.net/manual/en/function.date.php'>http://www.php.net/manual/en/function.date.php</a> for help with date formats";
$CFGVAR['dateformat_datetime']['title'] = 'Date and Time format';

$CFGVAR['dateformat_date']['title'] = 'Normal date format';

$CFGVAR['dateformat_filedatetime']['title'] = 'Date and Time format to use for files';

$CFGVAR['dateformat_longdate']['help'] = 'Including the day of the week';
$CFGVAR['dateformat_longdate']['title'] = 'Long date format';

$CFGVAR['dateformat_shortdate']['title'] = 'Short date format';

$CFGVAR['dateformat_shorttime']['title'] = 'Short time format';

$CFGVAR['dateformat_time']['title'] = 'Normal time format';

$CFGVAR['db_database']['title'] = 'MySQL Database Name';

$CFGVAR['db_hostname']['help']="The Hostname or IP address of the MySQL Database Server, usually 'localhost'";
$CFGVAR['db_hostname']['title'] = 'MySQL Database Hostname';

$CFGVAR['db_password']['title'] = 'MySQL Database Password';
$CFGVAR['db_password']['type'] = 'password';

$CFGVAR['db_tableprefix']['help']="Optionally prefix database table names with the a string (e.g. 'sit_', use this if the database you are using is shared with other applications";
$CFGVAR['db_tableprefix']['title'] = 'MySQL Database Table Prefix';

$CFGVAR['db_username']['title'] = 'MySQL Database Username';

$CFGVAR['debug']['help'] = 'Set to TRUE to output extra debug information, some as HTML comments and some in the page footer, FALSE to disable';
$CFGVAR['debug']['options'] = 'TRUE|FALSE';
$CFGVAR['debug']['title'] = 'Debug Mode';
$CFGVAR['debug']['type'] = 'select';

$CFGVAR['default_css_url']['title'] = 'Default CSS file';
$CFGVAR['default_css_url']['help'] = "A <abbr title='Uniform Resource Locator'>URL</abbr> pointing to the <abbr title='Cascading Style Sheet'>CSS</abbr> file to use when no other is configured. You should not normally need to change this.";

$CFGVAR['default_entitlement']['title'] = 'Default Holiday Entitlement';
$CFGVAR['default_entitlement']['help'] = 'The default holiday entitlement for new users and new holiday periods (in days)';
$CFGVAR['default_entitlement']['type'] = 'number';
$CFGVAR['default_entitlement']['unit'] = $strDays;

$CFGVAR['default_gravatar']['help'] = "One of 'wavatar', 'identicon', 'monsterid', a URL to an image, or blank (for a blue G icon).  See <a href='http://www.gravatar.com/'>www.gravatar.com</a> to learn about gravatars";
$CFGVAR['default_gravatar']['title'] = "Default Gravatar";

$CFGVAR['default_i18n']['help'] = "The system language, or the language that will be used when no other language is selected by the user, see <a href='http://sitracker.org/wiki/Translation'>http://sitracker.org/wiki/Translation</a> for an up to date list of supported languages.";
$CFGVAR['default_i18n']['title'] = "Default Language";
$CFGVAR['default_i18n']['type'] = 'languageselect';

$CFGVAR['default_interface_style']['title'] = 'Default Theme';
$CFGVAR['default_interface_style']['help'] = 'The theme/interface style that be given to new user accounts';
$CFGVAR['default_interface_style']['type'] = 'interfacestyleselect';

$CFGVAR['default_roleid']['help'] = "Role given to new users by default";
$CFGVAR['default_roleid']['title'] = "Default role id";
$CFGVAR['default_roleid']['type'] = 'roleselect';

$CFGVAR['default_service_level']['title'] = 'Default Service Level';
$CFGVAR['default_service_level']['help'] = 'The service level to use in case the contact does not specify';
$CFGVAR['default_service_level']['type'] = 'slaselect';

$CFGVAR['demo']['help'] = 'Set to TRUE to run in demo mode, some features are disabled or replaced with mock-ups';
$CFGVAR['demo']['options'] = 'TRUE|FALSE';
$CFGVAR['demo']['title'] = 'Demo Mode';
$CFGVAR['demo']['type'] = 'select';

$CFGVAR['email_address']['title'] = "Incoming email account address";

$CFGVAR['email_incoming_folder']['title'] = "IMAP incoming folder";
$CFGVAR['email_incoming_folder']['help'] = "INBOX by default, only needed if you want to retrieve email from a different folder";

$CFGVAR['email_archive_folder']['title'] = "IMAP Archive folder";
$CFGVAR['email_archive_folder']['help'] = "When using IMAP, move email to this folder after retreiving instead of deleting it. Leave blank to delete email from the server after it has been downloaded.";

$CFGVAR['email_options']['title'] = "Incoming email connection options";
$CFGVAR['email_options']['help'] = "Extra options to pass to the mailbox e.g. Gmail needs '/ssl', secure Groupwise needs /ssl/novalidate-cert etc. See <a href='http://www.php.net/imap_open'>http://www.php.net/imap_open</a> for examples";

$CFGVAR['email_password']['title'] = "Incoming email account password";
$CFGVAR['email_password']['help'] = "The password for the incoming email account connection";
$CFGVAR['email_password']['type'] = 'password';

$CFGVAR['email_port']['title'] = "Incoming email account port";
$CFGVAR['email_port']['help'] = "Usually 110 for POP, 143 for IMAP or 995 for secure POP, 993 for secure IMAP.";
$CFGVAR['email_port']['type'] = 'number';

$CFGVAR['email_server']['title'] = "Incoming email server";
$CFGVAR['email_server']['help'] = "The hostname or IP address of your incoming email server";

$CFGVAR['email_servertype']['options'] = 'imap|pop';
$CFGVAR['email_servertype']['title'] = "Incoming email account server type";
$CFGVAR['email_servertype']['type'] = 'select';

$CFGVAR['email_username']['help'] = "Only fill in this and the following options if you have selected 'POP/IMAP email retrieval";
$CFGVAR['email_username']['title'] = "Incoming email account username";

$CFGVAR['enable_inbound_mail']['help'] = "Normal users should choose 'POP/IMAP' and fill in the details below', advanced users can choose to pipe straight to SiT from their MTA, please read the docs for help on this.";
$CFGVAR['enable_inbound_mail']['options'] = "disabled|POP/IMAP|MTA";
$CFGVAR['enable_inbound_mail']['title'] = "Enable incoming mail to SiT";
$CFGVAR['enable_inbound_mail']['type'] = 'select';

$CFGVAR['end_working_day']['title'] = 'End of the working day';
$CFGVAR['end_working_day']['help'] = 'The number of seconds since midnight that indicate the end of the working day. (e.g. 61200 = 5pm)';

$CFGVAR['end_working_day']['type'] = 'number';
$CFGVAR['end_working_day']['unit'] = $strSeconds;

$CFGVAR['error_logfile']['title'] = "Path to an error log file";
$CFGVAR['error_logfile']['help'] = "The filesystem path and filename of a file that already exist and is writable to log error messages into. Enable Debug Mode to see more verbose messages in this file.";

$CFGVAR['feedback_enabled']['title'] = "Feedback Enabled";
$CFGVAR['feedback_enabled']['help'] = "Set to TRUE to enable Feedback, or FALSE to disable it";
$CFGVAR['feedback_enabled']['options'] = 'TRUE|FALSE';
$CFGVAR['feedback_enabled']['type'] = 'select';

$CFGVAR['feedback_form']['title'] = 'Feedback Form';
$CFGVAR['feedback_form']['help'] = 'The id number of the feedback form to use.  Leave blank disable sending feedback forms';
$CFGVAR['feedback_form']['type'] = 'number';
// TODO Feedback form lookup

$CFGVAR['feedback_max_score']['title'] = 'Max Score';
$CFGVAR['feedback_max_score']['help'] = 'The maximum score to use in rating fields for feedback forms';
$CFGVAR['feedback_max_score']['options'] = '1|2|3|4|5|6|7|8|9';
$CFGVAR['feedback_max_score']['type'] = 'select';

$CFGVAR['free_support_limit']['title'] = 'Number of free (site) support incidents that can be logged to a site';
$CFGVAR['free_support_limit']['type'] = 'number';
$CFGVAR['free_support_limit']['unit'] = $strSiteIncidents;

$CFGVAR['ftp_hostname']['title'] = 'FTP hostname';
$CFGVAR['ftp_hostname']['help'] = 'The hostname or IP address of the FTP server to connect to';

$CFGVAR['ftp_password']['title'] = 'FTP password';
$CFGVAR['ftp_password']['type'] = 'password';

$CFGVAR['ftp_pasv']['title'] = 'FTP passive mode';
$CFGVAR['ftp_pasv']['options'] = 'TRUE|FALSE';
$CFGVAR['ftp_pasv']['help'] = 'Set to TRUE to enable FTP Passive (PASV) mode or FALSE to disable';
$CFGVAR['ftp_pasv']['type'] = 'select';

$CFGVAR['ftp_path']['title'] = 'FTP Path';
$CFGVAR['ftp_path']['help'] = 'The path to the directory where we store files on the ftp server (e.g. /pub/support/) the trailing slash is important';

$CFGVAR['ftp_username']['title'] = 'FTP username';

$CFGVAR['font_file']['title'] = 'Font File location';
$CFGVAR['font_file']['help'] = 'Location of the font file to use on graphs, leaving blank will default to the internal GD font';

$CFGVAR['hide_closed_incidents_older_than']['help'] = "Incidents closed more than this number of days ago aren't show in the incident queue, -1 means disabled";
$CFGVAR['hide_closed_incidents_older_than']['title'] = 'Hide closed incidents older than';
$CFGVAR['hide_closed_incidents_older_than']['type'] = 'number';
$CFGVAR['hide_closed_incidents_older_than']['unit'] = $strDays;

$CFGVAR['holidays_enabled']['title'] = "Holidays Enabled";
$CFGVAR['holidays_enabled']['help'] = "Set to TRUE to enable Holidays or set to FALSE to disable";
$CFGVAR['holidays_enabled']['options'] = 'TRUE|FALSE';

$CFGVAR['holidays_enabled']['type'] = 'select';

$CFGVAR['home_country']['title'] = "The default country in capitals. e.g. 'UNITED KINGDOM'";

$CFGVAR['incident_pools']['title'] = 'Incident Pool options';
$CFGVAR['incident_pools']['help'] = 'Comma seperated list specifying the numbers of incidents to assign to contracts';
// Note: incident_pools is not a 1darray, it's actually a comma separated list

$CFGVAR['inventory_enabled']['title'] = 'Inventory Enabled';
$CFGVAR['inventory_enabled']['help'] = 'Set to TRUE to enable Inventory, set to FALSE to disable';
$CFGVAR['inventory_enabled']['type'] = 'select';

$CFGVAR['inventory_types']['title'] = 'Inventory Types';
$CFGVAR['inventory_types']['help'] = 'The types of Inventory items allowed';
$CFGVAR['inventory_types']['type'] = '2darray';

$CFGVAR['journal_loglevel']['help'] = '0 = none, 1 = minimal, 2 = normal, 3 = full, 4 = maximum/debug';
$CFGVAR['journal_loglevel']['title'] = 'Journal Logging Level';
$CFGVAR['journal_loglevel']['options'] = '0|1|2|3|4';
$CFGVAR['journal_loglevel']['type'] = 'select';

$CFGVAR['journal_purge_after']['title'] = 'Journal Purge Delay';
$CFGVAR['journal_purge_after']['help'] = 'How long should we keep journal entries (in seconds), entries older than this will be purged (deleted)';
$CFGVAR['journal_purge_after']['type'] = 'number';
$CFGVAR['journal_purge_after']['unit'] = $strSeconds;

$CFGVAR['kb_disclaimer_html']['title'] = 'Knowledgebase disclaimer';
$CFGVAR['kb_disclaimer_html']['help']  = 'A disclaimer message to be displayed at the bottom of every knowledge base article. Simple HTML is allowed';

$CFGVAR['kb_enabled']['title'] = "Knowledge base Enabled";
$CFGVAR['kb_enabled']['help'] = "Set to TRUE to enable the Knowledge Base or FALSE to disable it";
$CFGVAR['kb_enabled']['options'] = 'TRUE|FALSE';
$CFGVAR['kb_enabled']['type'] = 'select';

$CFGVAR['kb_id_prefix']['help'] = 'inserted before the ID to give it uniqueness';
$CFGVAR['kb_id_prefix']['title'] = 'Knowledgebase ID prefix';

$CFGVAR['ldap_autocreate_customer']['title'] = 'Auto create customer';
$CFGVAR['ldap_autocreate_customer']['help'] = 'This attempts to create the customer record automatically using LDAP when creating an incident from an email in the holding queue.';
$CFGVAR['ldap_autocreate_customer']['options'] = 'TRUE|FALSE';
$CFGVAR['ldap_autocreate_customer']['type'] = 'select';

$CFGVAR['ldap_cache_passwords']['title'] = 'Allow SiT! to cache users passwords';
$CFGVAR['ldap_cache_passwords']['help'] = 'This allows SiT! to cache an MD5 of users passwords which can be used for authentication if the LDAP server is down - see . $CONFIG[\'ldap_allow_cached_password\']';
$CFGVAR['ldap_cache_passwords']['options'] = 'TRUE|FALSE';
$CFGVAR['ldap_cache_passwords']['type'] = 'select';

$CFGVAR['ldap_allow_cached_password']['title'] = 'Allow use of cached passwords';
$CFGVAR['ldap_allow_cached_password']['help'] = 'This allows use of cached passwords in SiT for authentication if communication with the LDAP server fails.';
$CFGVAR['ldap_allow_cached_password']['options'] = 'TRUE|FALSE';
$CFGVAR['ldap_allow_cached_password']['type'] = 'select';

$CFGVAR['ldap_bind_user']['title'] = 'LDAP Bind user';
$CFGVAR['ldap_bind_user']['help'] = 'The user for binding to the LDAP host, this should be the full DN of the user e.g. <code>cn=sitadmin,ou=sitracker,o=org</code>';

$CFGVAR['ldap_bind_pass']['title'] = 'LDAP Bind password';
$CFGVAR['ldap_bind_pass']['help'] = 'The password for binding to the LDAP host';
$CFGVAR['ldap_bind_pass']['type'] = 'ldappassword';
$CFGVAR['ldap_bind_pass']['statusfield'] = 'TRUE';

$CFGVAR['ldap_admin_group']['title'] = 'LDAP group for SIT admins';
$CFGVAR['ldap_admin_group']['help'] = 'The full DN of the group the users are a member of which assigns the SiT! admin role e.g. <code>cn=sitadmins,ou=sitracker,o=org</code>';

$CFGVAR['ldap_manager_group']['title'] = 'LDAP group for SIT managers';
$CFGVAR['ldap_manager_group']['help'] = 'The full DN of the group the users are a member of which assigns the SiT! manager role e.g. <code>cn=sitmanagers,ou=sitracker,o=org</code>';

$CFGVAR['ldap_user_group']['title'] = 'LDAP group for SIT users';
$CFGVAR['ldap_user_group']['help'] = 'The full DN of the group the users are a member of which assigns the SiT! user role e.g. <code>cn=situsers,ou=sitracker,o=org</code>';

$CFGVAR['ldap_customer_group']['title'] = 'LDAP Customer Group';
$CFGVAR['ldap_customer_group']['help'] = 'The full DN of the group the identifies the person as a valid contact/customer e.g. <code>cn=sitcustomers,ou=sitracker,o=org</code>';

$CFGVAR['ldap_default_customer_siteid']['title'] = 'LDAP Customer default site';
$CFGVAR['ldap_default_customer_siteid']['help'] = 'Place LDAP customers as contacts under this site';
$CFGVAR['ldap_default_customer_siteid']['type'] = 'siteselect';

$CFGVAR['ldap_default_user_status']['title'] = 'LDAP User status';
$CFGVAR['ldap_default_user_status']['help'] = 'The initial status that will be given to LDAP users';
$CFGVAR['ldap_default_user_status']['type'] = 'userstatusselect';

$CFGVAR['ldap_user_base']['title'] = 'LDAP Base DN';
$CFGVAR['ldap_user_base']['help'] = 'The LDAP Base DN for user lookups e.g. <code>ou=people,ou=sitracker,o=org</code>';

$CFGVAR['ldap_host']['title'] = 'LDAP Host Name';
$CFGVAR['ldap_host']['help'] = "This should be your <abbr title='Lightweight Directory Access Protocol'>LDAP</abbr> IP address or hostname, e.g.: ldap.example.com";

$CFGVAR['ldap_type']['title'] = 'LDAP Type';
$CFGVAR['ldap_type']['help'] = "The type of LDAP server you are using";
$CFGVAR['ldap_type']['type'] = 'select';
$CFGVAR['ldap_type']['options'] = 'EDIR|AD|OPENLDAP|CUSTOM';

$CFGVAR['ldap_port']['title'] = 'LDAP Port';
$CFGVAR['ldap_port']['help'] = 'The TCP port to use to connect to the LDAP server. Usually 389 or 636 for LDAPs. Leave blank for default.';
$CFGVAR['ldap_port']['type'] = 'number';

$CFGVAR['ldap_protocol']['title'] = 'LDAP Protocol version';
$CFGVAR['ldap_protocol']['type'] = 'select';
$CFGVAR['ldap_protocol']['options'] = '1|2|3';

$CFGVAR['ldap_security']['title'] = 'LDAP Security';
$CFGVAR['ldap_security']['help'] = 'LDAP security method (Requires LDAP protocol v3)';
$CFGVAR['ldap_security']['options'] = 'SSL|TLS|NONE';
$CFGVAR['ldap_security']['type'] = 'select';

$CFGVAR['licensefile']['title'] = 'Path to the License file';

$CFGVAR['logout_url']['help'] = "The URL to redirect the user to after he/she logs out. When left blank this defaults to the SiT login page.";
$CFGVAR['logout_url']['title'] = "Logout URL";

$CFGVAR['max_incoming_email_perday']['title'] = 'Maximum number of incoming emails';
$CFGVAR['max_incoming_email_perday']['help'] = 'The maximum number of incoming emails per incident, per day to allow before a mail-loop is detected';
$CFGVAR['max_incoming_email_perday']['type'] = 'number';

$CFGVAR['no_feedback_contracts']['title'] = "No-Feedback Contracts";
$CFGVAR['no_feedback_contracts']['help'] = "A comma separated list of contract ID's where feedback is not to be requested e.g. '123,765' would withhold feedback requests for contract 123 and 765";
$CFGVAR['no_feedback_contracts']['type'] = '1darray';

$CFGVAR['notice_threshold']['title'] = 'Notice Threshold';
$CFGVAR['notice_threshold']['help'] = 'Flag items as notice when they are this percentage complete.';
$CFGVAR['notice_threshold']['type'] = 'percent';

$CFGVAR['plugins']['title'] = "Load Plugins";
$CFGVAR['plugins']['help'] = "Comma separated list of plugins to load. e.g. 'magic_plugin,lookup_plugin'";
$CFGVAR['plugins']['type'] = '1darray';

$CFGVAR['portal_creates_incidents']['help'] = "TRUE if customers can create incidents from the portal, FALSE if they can just create emails that arrive in the holding queue";
$CFGVAR['portal_creates_incidents']['title'] = "Portal users can create incidents directly?";
$CFGVAR['portal_creates_incidents']['type'] = 'select';
$CFGVAR['portal_creates_incidents']['options'] = 'TRUE|FALSE';

$CFGVAR['portal_interface_style']['title'] = "Portal interface style";
$CFGVAR['portal_interface_style']['type'] = 'interfacestyleselect';

$CFGVAR['portal_kb_enabled']['help'] = "Public puts a link on the login page, Private makes it available on login for contacts";
$CFGVAR['portal_kb_enabled']['options'] = 'Public|Private|Disabled';
$CFGVAR['portal_kb_enabled']['title'] = "Portal/Public Knowledge base";
$CFGVAR['portal_kb_enabled']['type'] = 'select';

$CFGVAR['portal_site_incidents']['help'] = "Users in the portal can view site incidents based on the contract options";
$CFGVAR['portal_site_incidents']['options'] = 'TRUE|FALSE';
$CFGVAR['portal_site_incidents']['title'] = "Show site incidents in portal";
$CFGVAR['portal_site_incidents']['type'] = 'select';

$CFGVAR['portal']['title'] = 'Enable user portal';
$CFGVAR['portal']['options'] = 'TRUE|FALSE';
$CFGVAR['portal']['type'] = 'select';

$CFGVAR['portal_usernames_can_be_changed']['options'] = 'TRUE|FALSE';
$CFGVAR['portal_usernames_can_be_changed']['title'] = "Allow portal users to change usernames";
$CFGVAR['portal_usernames_can_be_changed']['type'] = 'select';

$CFGVAR['preferred_maintenance']['title'] = "Preferred SLA for new incidents";
$CFGVAR['preferred_maintenance']['help'] = "A comma separated list of SLA's to indicate order of preference when logging incidents against them e.g. 'standard,high'";
$CFGVAR['preferred_maintenance']['type'] = '1darray';

$CFGVAR['record_lock_delay']['title'] = 'Record Lock Delay';
$CFGVAR['record_lock_delay']['help'] = 'The period to wait before automatically unlocking records that have been locked by a user (e.g. Incoming email)';
$CFGVAR['record_lock_delay']['type'] = 'number';
$CFGVAR['record_lock_delay']['unit'] = $strSeconds;

$CFGVAR['regular_contact_days']['title'] = 'Regular contact period';
$CFGVAR['regular_contact_days']['help'] = 'The number of days to elapse before users are prompted to contact the customer (can be overridden by the SLA)';
$CFGVAR['regular_contact_days']['type'] = 'number';
$CFGVAR['regular_contact_days']['unit'] = $strDays;

$CFGVAR['session_name']['title'] = 'Session Name';
$CFGVAR['session_name']['help'] = 'The session name for use in cookies and URLs, Must contain alphanumeric characters only';

$CFGVAR['soap_enabled']['title'] = 'Soap enabled';
$CFGVAR['soap_enabled']['help'] = 'Enable SOAP for SiT! users';
$CFGVAR['soap_enabled']['options'] = 'TRUE|FALSE';
$CFGVAR['soap_enabled']['type'] = 'select';

$CFGVAR['soap_portal_enabled']['title'] = 'Soap portal enabled';
$CFGVAR['soap_portal_enabled']['help'] = 'Enable SOAP for portal users';
$CFGVAR['soap_portal_enabled']['options'] = 'TRUE|FALSE';
$CFGVAR['soap_portal_enabled']['type'] = 'select';

$CFGVAR['spam_email_subject']['title'] = 'Spam Subject';
$CFGVAR['spam_email_subject']['help'] = 'String to look for in email message subject to determine a message is spam';

$CFGVAR['start_working_day']['title'] = 'Start of the working day';
$CFGVAR['start_working_day']['help'] = 'The number of seconds since midnight that indicate the start of the working day. (e.g. 32400 = 9am)';
$CFGVAR['start_working_day']['type'] = 'number';
$CFGVAR['start_working_day']['unit'] = $strSeconds;

$CFGVAR['support_email']['title'] = 'From address for support emails';
$CFGVAR['support_email']['help'] = 'Email sent by SiT that uses the template variable <code>{supportemail}</code> will come from this address';

$CFGVAR['support_manager']['title'] = 'Support Manager';
$CFGVAR['support_manager']['help'] = 'The person who is in charge of your support service. Used in email templates etc.';
$CFGVAR['support_manager']['type'] = 'userselect';

$CFGVAR['tag_icons']['title'] = "Tag Icons";
$CFGVAR['tag_icons']['help'] = "You can specify icons to display next to certain tags, enter tag/icon associations one per line, format: tag=>icon";
$CFGVAR['tag_icons']['type'] = '2darray';

$CFGVAR['tasks_enabled']['title'] = "Tasks Enabled";
$CFGVAR['tasks_enabled']['help'] = "Set to TRUE to enable the tasks feature, or FALSE to disable";
$CFGVAR['tasks_enabled']['options'] = 'TRUE|FALSE';
$CFGVAR['tasks_enabled']['type'] = 'select';

$CFGVAR['timesheets_enabled']['title'] = "Timesheets Enabled";
$CFGVAR['timesheets_enabled']['help'] = "<em>Experimental Feature</em>. Set to TRUE to enable, or FALSE to disable";
$CFGVAR['timesheets_enabled']['options'] = 'TRUE|FALSE';
$CFGVAR['timesheets_enabled']['type'] = 'select';

$CFGVAR['timezone']['title'] = 'System Time Zone';
$CFGVAR['timezone']['help'] = "Set this to match the timezone that your server running SiT! is configured to use";
$CFGVAR['timezone']['type'] = 'select';
$CFGVAR['timezone']['options'] = file_get_contents('lib/timezones.txt');

$CFGVAR['dst_adjust']['title'] = 'Daylight Savings (DST) Adjust';
$CFGVAR['dst_adjust']['help'] = "The number of minutes to adjust for daylight savings time (DST), usually 60 (one hour)";
$CFGVAR['dst_adjust']['type'] = 'number';
$CFGVAR['dst_adjust']['unit'] = $strMinutes;

$CFGVAR['trusted_server']['help'] = 'If you set this to TRUE, passwords cannot be changed from the users profile, this assumes that you are using an external mechanism for managing passwords';
$CFGVAR['trusted_server']['options'] = 'TRUE|FALSE';
$CFGVAR['trusted_server']['title'] = 'Enable trusted server mode';
$CFGVAR['trusted_server']['type'] = 'select';

$CFGVAR['upload_max_filesize']['title'] = "The maximum file upload size (in bytes)";
$CFGVAR['upload_max_filesize']['type'] = 'number';
$CFGVAR['upload_max_filesize']['unit'] = $strBytes;

$CFGVAR['use_ldap']['title'] = 'Enable LDAP authentication';
$CFGVAR['use_ldap']['help'] = "Set to TRUE for LDAP authentication, or FALSE for standard database authentication";
$CFGVAR['use_ldap']['options'] = 'TRUE|FALSE';
$CFGVAR['use_ldap']['type'] = 'select';

$CFGVAR['urgent_threshold']['title'] = 'Urgent Threshold';
$CFGVAR['urgent_threshold']['help'] = 'Flag items as urgent when they are this percentage complete.';

$CFGVAR['urgent_threshold']['type'] = 'percent';

$CFGVAR['working_days']['title'] = 'Working Days';
$CFGVAR['working_days']['help'] = 'Comma separated list of working days (Where 0 = Sun, 1 = Mon... 6 = Sat)';
$CFGVAR['working_days']['type'] = '1darray';

if (function_exists('plugin_do'))
{
    // Plugin_do won't always be available in this file, because we use this
    // file for setup as well, no plugins before sit is installed.
    plugin_do('cfgvar');
}
?>