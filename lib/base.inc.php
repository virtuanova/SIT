<?php
// base.inc.php - core constants, core utility functions and files
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.

// Prevent script from being run directly (ie. it must always be included
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    exit;
}

/**
  * Begin constant definitions
**/
// Journal Logging
define ('CFG_LOGGING_OFF',0); // 0 = No logging
define ('CFG_LOGGING_MIN',1); // 1 = Minimal Logging
define ('CFG_LOGGING_NORMAL',2); // 2 = Normal Logging
define ('CFG_LOGGING_FULL',3); // 3 = Full Logging
define ('CFG_LOGGING_MAX',4); // 4 = Maximum/Debug Logging

define ('CFG_JOURNAL_DEBUG', 0);     // 0 = for internal debugging use
define ('CFG_JOURNAL_LOGIN', 1);     // 1 = Logon/Logoff
define ('CFG_JOURNAL_SUPPORT', 2);   // 2 = Support Incidents
define ('CFG_JOURNAL_SALES', 3);     // 3 = Sales Incidents (Legacy, unused)
define ('CFG_JOURNAL_SITES', 4);     // 4 = Sites
define ('CFG_JOURNAL_CONTACTS', 5);  // 5 = Contacts
define ('CFG_JOURNAL_ADMIN', 6);     // 6 = Admin
define ('CFG_JOURNAL_USER', 7);       // 7 = User Management
define ('CFG_JOURNAL_MAINTENANCE', 8);  // 8 = Maintenance Contracts
define ('CFG_JOURNAL_PRODUCTS', 9);
define ('CFG_JOURNAL_OTHER', 10);
define ('CFG_JOURNAL_TRIGGERS', 11);
define ('CFG_JOURNAL_KB', 12);    // Knowledge Base
define ('CFG_JOURNAL_TASKS', 13);

define ('TAG_CONTACT', 1);
define ('TAG_INCIDENT', 2);
define ('TAG_SITE', 3);
define ('TAG_TASK', 4);
define ('TAG_PRODUCT', 5);
define ('TAG_SKILL', 6);
define ('TAG_KB_ARTICLE', 7);
define ('TAG_REPORT', 8);

define ('NOTE_TASK', 10);

define ('HOL_HOLIDAY', 1); // Holiday/Leave
define ('HOL_SICKNESS', 2);
define ('HOL_WORKING_AWAY', 3);
define ('HOL_TRAINING', 4);
define ('HOL_FREE', 5); // Compassionate/Maternity/Paterity/etc/free
// The holiday archiving assumes standard holidays are < 10
define ('HOL_PUBLIC', 10);  // Public Holiday (eg. Bank Holiday)

define ('HOL_APPROVAL_NONE', 0); // Not granted or denied
define ('HOL_APPROVAL_GRANTED', 1);
define ('HOL_APPROVAL_DENIED', 2);
// TODO define the other approval (archive) states here, 10, 11 etc.
define ('HOL_APPROVAL_NONE_ARCHIVED', 10);
define ('HOL_APPROVAL_GRANTED_ARCHIVED', 11);
define ('HOL_APPROVAL_DENIED_ARCHIVED', 12);

//default notice types
define ('NORMAL_NOTICE_TYPE', 0);
define ('WARNING_NOTICE_TYPE', 1);
define ('CRITICAL_NOTICE_TYPE', 2);
define ('TRIGGER_NOTICE_TYPE', 3);

// Incident statuses
define ("STATUS_ACTIVE",1);
define ("STATUS_CLOSED",2);
define ("STATUS_RESEARCH",3);
define ("STATUS_LEFTMESSAGE",4);
define ("STATUS_COLLEAGUE",5);
define ("STATUS_SUPPORT",6);
define ("STATUS_CLOSING",7);
define ("STATUS_CUSTOMER",8);
define ("STATUS_UNSUPPORTED",9);
define ("STATUS_UNASSIGNED",10);

// User statuses
define ('USERSTATUS_ACCOUNT_DISABLED', 0);
define ('USERSTATUS_IN_OFFICE', 1);
define ('USERSTATUS_NOT_IN_OFFICE', 2);
define ('USERSTATUS_IN_MEETING', 3);
define ('USERSTATUS_AT_LUNCH', 4);
define ('USERSTATUS_ON_HOLIDAY', 5);
define ('USERSTATUS_WORKING_FROM_HOME', 6);
define ('USERSTATUS_ON_TRAINING_COURSE', 7);
define ('USERSTATUS_ABSENT_SICK', 8);
define ('USERSTATUS_WORKING_AWAY', 9);

// BILLING
define ('NO_BILLABLE_CONTRACT', 0);
define ('CONTACT_HAS_BILLABLE_CONTRACT', 1);
define ('SITE_HAS_BILLABLE_CONTRACT', 2);

// For tempincoming
define ("REASON_POSSIBLE_NEW_INCIDENT", 1);
define ("REASON_INCIDENT_CLOSED", 2);

// Licences
define ("LICENCE_PER_USER", 1);
define ("LICENCE_PER_WORKSTATION", 2);
define ("LICENCE_PER_SERVER", 3);
define ("LICENCE_SITE", 4);
define ("LICENCE_EVALUATION", 5);

/**
  * Begin global variable definitions
**/
// Version number of the application, (numbers only)
$application_version = '3.67';

// Revision string, e.g. 'beta2' or 'svn' or ''
$application_revision = 'p2';

// The number of errors that have occurred
$siterrors = 0;

$fsdelim = DIRECTORY_SEPARATOR;

// Time settings
$now = time();
// next 16 hours, based on reminders being run at midnight this is today
$today = $now + (16 * 3600);
$lastweek = $now - (7 * 86400); // the previous seven days
$todayrecent = $now -(16 * 3600);  // past 16 hours
$startofsession = $now - ini_get("session.gc_maxlifetime");

$CONFIG['upload_max_filesize'] = return_bytes($CONFIG['upload_max_filesize']);

//**** Begin internal functions ****//
// Append SVN data for svn versions
if ($application_revision == 'svn')
{
    // Add the svn revision number
    preg_match('/([0-9]+)/','$LastChangedRevision: 7530 $',$revision);
    $application_revision .= $revision[0];
}

// Set a string to be the full version number and revision of the application
$application_version_string = trim("v{$application_version} {$application_revision}");

$ldap_conn = "";

/**
  * End global variable definitions
**/

// Clean PHP_SELF server variable to avoid potential XSS security issue
$_SERVER['PHP_SELF'] = substr($_SERVER['PHP_SELF'], 0,
                            (strlen($_SERVER['PHP_SELF'])
                            - @strlen($_SERVER['PATH_INFO'])));

// Report all PHP errors
error_reporting(E_ALL);
$oldeh = set_error_handler("sit_error_handler");

// Decide which language to use and setup internationalisation
require (APPLICATION_I18NPATH . 'en-GB.inc.php');
if ($CONFIG['default_i18n'] != 'en-GB')
{
    include (APPLICATION_I18NPATH . "{$CONFIG['default_i18n']}.inc.php");
}
if (!empty($_SESSION['lang'])
    AND $_SESSION['lang'] != $CONFIG['default_i18n'])
{
    include (APPLICATION_I18NPATH . "{$_SESSION['lang']}.inc.php");
}
ini_set('default_charset', $i18ncharset);


//**** Begin functions ****//



/**
  * Make an external variable safe for database and HTML display
  * @author Ivan Lucas, Kieran Hogg
  * @param mixed $var variable to replace
  * @param bool $striphtml whether to strip html
  * @param bool $transentities whether to translate all aplicable chars (true) or just special chars (false) into html entites
  * @param bool $mysqlescape whether to mysql_escape()
  * @param array $disallowedchars array of chars to remove
  * @param array $replacechars array of chars to replace as $orig => $replace
  * @param bool $intval whether to get the integer value of the variable
  * @todo TODO this function could use a bit of tidy-up
  * @returns variable
*/
function cleanvar($vars, $striphtml = TRUE, $transentities = FALSE,
                $mysqlescape = TRUE, $disallowedchars = array(),
                $replacechars = array(), $intval = FALSE)
{
    if (is_array($vars))
    {
        foreach ($vars as $key => $singlevar)
        {
            $var[$key] = cleanvar($singlevar, $striphtml, $transentities, $mysqlescape,
                    $disallowedchars, $replacechars);
        }
    }
    else
    {
        $var = $vars;
        if ($striphtml === TRUE)
        {
            $var = strip_tags($var);
        }

        if (!empty($disallowedchars))
        {
            $var = str_replace($disallowedchars, '', $var);
        }

        if (!empty($replacechars))
        {
            foreach ($replacechars as $orig => $replace)
            {
                $var = str_replace($orig, $replace, $var);
            }
        }

        if ($transentities)
        {
            $var = htmlentities($var, ENT_COMPAT, $GLOBALS['i18ncharset']);
        }
        else
        {
            $var = htmlspecialchars($var, ENT_COMPAT, $GLOBALS['i18ncharset']);
        }

        if ($mysqlescape)
        {
            $var = mysql_real_escape_string($var);
        }

        if ($intval)
        {
            $var = intval($val);
        }

        $var = trim($var);
    }
    return $var;
}


/**
  * Make an external variable safe. Force it to be an integer.
  * @author Ivan Lucas
  * @param mixed $string variable to make safe
  * @returns int - safe variable
*/
function clean_int($vars)
{
    if (is_array($vars))
    {
        foreach ($vars as $key => $singlevar)
        {
            $var[$key] = clean_int($singlevar);
        }
    }
    elseif (!is_null($vars) AND $vars != '' AND !is_numeric($vars))
    {
        if ($vars != '')
        {
            trigger_error("Input was expected to be numeric but received string [$vars] instead", E_USER_WARNING);
        }
        $var = 0;
    }
    else
    {
        $var = intval($vars);
    }

    return $var;
}



/**
  * Make an external variable safe. Force it to be a float.
  * @author Ivan Lucas
  * @param mixed $string variable to make safe
  * @returns int - safe variable
*/
function clean_float($vars)
{
    if (is_array($vars))
    {
        foreach ($vars as $key => $singlevar)
        {
            $var[$key] = clean_float($singlevar);
        }
    }
    elseif (!is_null($vars) AND $vars != '' AND !is_numeric($vars))
    {
        trigger_error("Input was expected to be numeric but received string instead", E_USER_WARNING);
        $var = 0.0;
    }
    else
    {
        $var = floatval($vars);
    }

    return $var;
}


/**
  * Make an external variable safe for use in a database query
  * @author Ivan Lucas
  * @param mixed $string variable to make safe
  * @returns string - DB safe variable
  * @note Strips HTML
*/
function clean_dbstring($vars)
{
    if (is_array($vars))
    {
        foreach ($vars as $key => $singlevar)
        {
            $string[$key] = clean_dbstring($singlevar);
        }
    }
    else
    {
        $string = strip_tags($vars);

        if (get_magic_quotes_gpc() == 1)
        {
            stripslashes($string);
        }

        $string = mysql_real_escape_string($string);
    }
    return $string;
}


/**
  * Make a language string safe for use in a database query
  * @author Ivan Lucas
  * @param mixed $string variable to make safe
  * @returns string - DB safe variable
  * @note Use for db queries only, not for display
*/
function clean_lang_dbstring($string)
{
    stripslashes($string);
    $string = mysql_real_escape_string($string);

    return $string;
}


/**
  * Make an external variable safe by ensuring the value is one of a list
  * of predetermined values
  * @author Ivan Lucas
  * @param mixed $string variable to make safe
  * @param array $list list of safe values
  * @param bool $strict, also check the types of the values in the list
  * @returns mixed - DB safe variable
  * @note If the input string isn't found in the list, the first option is used
*/
function clean_fixed_list($string, $list, $strict = FALSE)
{
    if (is_array($list))
    {
        if (!in_array($string, $list, $strict))
        {
            if ($string != NULL AND $string != '')
            {
                trigger_error("Unexpected input", E_USER_WARNING);
            }
            $string = $list[0];
        }
    }
    else
    {
        trigger_error("Could not understand list of predetermined values for fixed_list()", E_USER_ERROR);
        return false;
    }
    return $string;
}


/**
 * Make an external username variable safe for use in a database
 * This is seperate from the other clean functions as both ints and strings are supported
 * @author Paul Heaney
 * @param mixed $string Text the clean
 * @return mixed - DB safe
 * @note Should be be stricter and check for current, all etc?
 */
function clean_username($string)
{
    if (is_numeric($string))
    {
        return intval($string);
    }
    else
    {
        // We also support current, all and all usernames
        return clean_dbstring($string);
    }
}


/**
 * Make a string safe for use with file related functions
 * @author Ivan Lucas
 * @param string $string Text the clean
 * @return mixed - DB safe
 * @note Does not imply any other filtering, only safe for file functions
 */
function clean_fspath($string)
{
    $string = strip_tags($string);

    $bad = array('://', '..', '.htaccess', '.htpasswd', "\n", "\r", "\x00", "?", "*", '[', ']');
    $string = str_replace($bad,'', $string);

    return $string;
}


/**
  * Make an external variable safe. Force it to be an alphanumeric string.
  * @author Ivan Lucas
  * @param mixed $string variable to make safe
  * @returns string - safe variable, only A-Z, a-z or 0 - 9 characters
  * @note whitespace not allowed
*/
function clean_alphanumeric($vars)
{
    if (is_array($vars))
    {
        foreach ($vars as $key => $singlevar)
        {
            $var[$key] = clean_alphanumeric($singlevar);
        }
    }
    else
    {
        $var = preg_replace("/[^a-zA-Z0-9]/", "", $vars);
    }

    return $var;
}


/**
 * Make a string safe for use with url related functions
 * @author Ivan Lucas/Carsten Jensen
 * @param string $string Text the clean
 * @return mixed - URL safe
 */
function clean_url($string)
{
    $string = strip_tags($string);

    $bad = array('://', '..', '.htaccess', '.htpasswd', "\n", "\r", "\x00", "*",
                 '[', ']', '<', '>', 'javascript:');
    $string = str_replace($bad,'', $string);

    return $string;
}


/**
  * Return an array of available languages codes by looking at the files
  * in the i18n directory
  * @author Ivan Lucas
  * @param bool $test - (optional) Include test language (zz) in results
  * @retval array Language codes
**/
function available_languages($test = FALSE)
{
    $i18nfiles = list_dir('.'.DIRECTORY_SEPARATOR.'i18n');
    $i18nfiles = array_filter($i18nfiles, 'filter_i18n_filenames');
    array_walk($i18nfiles, 'i18n_filename_to_code');
    asort($i18nfiles);
    foreach ($i18nfiles AS $code)
    {
        if ($code != 'zz')
        {
            $available[$code] = i18n_code_to_name($code);
        }
        elseif ($code == 'zz' AND $test === TRUE)
        {
            $available[$code] = 'Test Language (zz)';
        }
    }

    return $available;
}

?>
