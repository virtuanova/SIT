<?php
// login.php - processes the login
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Author: Ivan Lucas <ivanlucas[at]users.sourceforge.net>


require ('core.php');

session_name($CONFIG['session_name']);
session_start();

$_SESSION['auth'] = FALSE;

if (function_exists('session_regenerate_id'))
{
    if (!version_compare(phpversion(),"5.1.0",">=")) session_regenerate_id(TRUE);
    else session_regenerate_id();
}

setcookie(session_name(), session_id(),ini_get("session.cookie_lifetime"), "/");

$language = htmlspecialchars(substr(strip_tags($_POST['lang']), 0, 5), ENT_NOQUOTES, 'utf-8');
if ((substr($language, 2, 1) != '-' OR strpos('.', $language) !== FALSE) AND strlen($language) != 2)
{
    $language = 'xx-xx'; // default lang
}

require (APPLICATION_LIBPATH . 'functions.inc.php');
require (APPLICATION_LIBPATH . 'triggers.inc.php');

populate_syslang();
// External vars
$password = $_REQUEST['password'];
$username = cleanvar($_REQUEST['username']);
$public_browser = cleanvar($_REQUEST['public_browser']);
$page = clean_url($_REQUEST['page']);

if (empty($_REQUEST['username']) AND empty($_REQUEST['password']) AND $language != $_SESSION['lang'])
{
    if ($language != 'xx-xx')
    {
        $_SESSION['lang'] = $language;
    }
    else
    {
        $_SESSION['lang'] = '';
    }
    header ("Location: index.php");
}
elseif (authenticate($username, $_REQUEST['password']))
{
    // Valid user
    $_SESSION['auth'] = TRUE;

    $password = md5($_REQUEST['password']);

    // Retrieve users profile
    $sql = "SELECT * FROM `{$dbUsers}` WHERE username='{$username}' LIMIT 1";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(), E_USER_WARNING);
    if (mysql_num_rows($result) < 1)
    {
        $_SESSION['auth'] = FALSE;
        trigger_error("No such user", E_USER_ERROR);
    }
    $user = mysql_fetch_object($result);
    // Profile
    $_SESSION['userid'] = $user->id;
    $_SESSION['username'] = $user->username;
    $_SESSION['realname'] = $user->realname;
    $_SESSION['email'] = $user->email;
    $_SESSION['style'] = $user->var_style;
    $_SESSION['incident_refresh'] = $user->var_incident_refresh;
    $_SESSION['update_order'] = $user->var_update_order;
    $_SESSION['num_update_view'] = $user->var_num_updates_view;
    $_SESSION['groupid'] = is_null($user->groupid) ? 0 : $user->groupid;
    $_SESSION['utcoffset'] = is_null($user->var_utc_offset) ? 0 : $user->var_utc_offset;
    $_SESSION['portalauth'] = FALSE;
    $_SESSION['user_source'] = $user->user_source;
    if (!is_null($_SESSION['startdate'])) $_SESSION['startdate'] = $user->user_startdate;


    // Read user config from database
    $sql = "SELECT * FROM `{$dbUserConfig}` WHERE userid = {$user->id}";
    $result = @mysql_query($sql);
    if ($result AND mysql_num_rows($result) > 0)
    {
        while ($conf = mysql_fetch_object($result))
        {
            if ($conf->value==='TRUE') $conf->value = TRUE;
            if ($conf->value==='FALSE') $conf->value = FALSE;
            if (substr($conf->value, 0, 6)=='array(')
            {
                    eval("\$val = {$conf->value};");
                    $conf->value = $val;
            }
            $_SESSION['userconfig'][$conf->config] = $conf->value;
        }
    }

    // Delete any old session user notices
    $sql = "DELETE FROM `{$dbNotices}` WHERE durability='session' AND userid={$_SESSION['userid']}";
    mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(), E_USER_ERROR);

    //check if the session lang is different the their profiles
    if ($_SESSION['lang'] != '' AND $_SESSION['lang'] != $user->var_i18n)
    {
        $t = trigger('TRIGGER_LANGUAGE_DIFFERS', array('profilelang' => $user->var_i18n, 'currentlang' => $_SESSION['lang'], 'user' => $_SESSION['userid']));
    }

    if ($user->var_i18n != $CONFIG['default_i18n'] AND $_SESSION['lang'] == '')
    {
        $_SESSION['lang'] = is_null($user->var_i18n) ? '' : $user->var_i18n;
    }

    // Make an array full of users permissions
    // The zero permission is added to all users, zero means everybody can access
    $userpermissions[] = 0;
    // First lookup the role permissions
    $sql = "SELECT * FROM `{$dbUsers}` AS u, `{$dbRolePermissions}` AS rp WHERE u.roleid = rp.roleid ";
    $sql .= "AND u.id = '{$_SESSION['userid']}' AND granted='true'";
    $result = mysql_query($sql);
    if (mysql_error())
    {
        $_SESSION['auth'] = FALSE;
        trigger_error(mysql_error(), E_USER_ERROR);
    }
    if (mysql_num_rows($result) >= 1)
    {
        while ($perm = mysql_fetch_object($result))
        {
            $userpermissions[] = $perm->permissionid;
        }
    }

    // Next lookup the individual users permissions
    $sql = "SELECT * FROM `{$dbUserPermissions}` WHERE userid = '{$_SESSION['userid']}' AND granted='true' ";
    $result = mysql_query($sql);
    if (mysql_error())
    {
        $_SESSION['auth'] = FALSE;
        trigger_error(mysql_error(),E_USER_ERROR);
    }

    if (mysql_num_rows($result) >= 1)
    {
        while ($perm = mysql_fetch_object($result))
        {
            $userpermissions[] = $perm->permissionid;
        }
    }


    $_SESSION['permissions'] = array_unique($userpermissions);

    // redirect
    if (empty($page))
    {
        header ("Location: main.php");
        exit;
    }
    else
    {
        header("Location: {$page}");
        exit;
    }
}
elseif ($CONFIG['portal'] == TRUE)
{
    // Invalid user and portal enabled
    if ($language != 'xx-xx')
    {
        $_SESSION['lang'] = $language;
    }

    if (authenticateContact($username, $password))
    {
        debug_log("PORTAL AUTH SUCCESSFUL");
        $_SESSION['portalauth'] = TRUE;

        $sql = "SELECT * FROM `{$dbContacts}` WHERE username = '{$username}'";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(), E_USER_WARNING);
        if (mysql_num_rows($result) < 1)
        {
            $_SESSION['portalauth'] = FALSE;
            trigger_error("No such user", E_USER_ERROR);
        }
        $contact = mysql_fetch_object($result);

        // Customer session
        // Valid user
        $_SESSION['contactid'] = $contact->id;
        $_SESSION['siteid'] = $contact->siteid;
        $_SESSION['style'] = $CONFIG['portal_interface_style'];
        $_SESSION['contracts'] = array();
        $_SESSION['auth'] = FALSE;
        $_SESSION['contact_source'] = $contact->contact_source;

        //get admin contracts
        if (admin_contact_contracts($_SESSION['contactid'], $_SESSION['siteid']) != NULL)
        {
            $admincontracts = admin_contact_contracts($_SESSION['contactid'], $_SESSION['siteid']);
            $_SESSION['usertype'] = 'admin';
        }

        //get named contact contracts
        if (contact_contracts($_SESSION['contactid'], $_SESSION['siteid']) != NULL)
        {
            $contactcontracts = contact_contracts($_SESSION['contactid'], $_SESSION['siteid']);
            if (!isset($_SESSION['usertype']))
            {
               $_SESSION['usertype'] = 'contact';
            }
        }

        //get other contracts
        if (all_contact_contracts($_SESSION['contactid'], $_SESSION['siteid']) != NULL)
        {
            $allcontracts = all_contact_contracts($_SESSION['contactid'], $_SESSION['siteid']);
            if (!isset($_SESSION['usertype']))
            {
                $_SESSION['usertype'] = 'user';
            }
        }

        $_SESSION['contracts'] = array_merge((array)$admincontracts, (array)$contactcontracts, (array)$allcontracts);

        load_entitlements($_SESSION['contactid'], $_SESSION['siteid']);
        header("Location: portal/");
        exit;
    }
    else
    {
        // Login failure
        $_SESSION['auth'] = FALSE;
        $_SESSION['portalauth'] = FALSE;
        // log the failure
        if ($username != '')
        {
            $errdate = date('M j H:i');
            $errmsg = "$errdate Failed login for user '{$username}' from IP: " . substr($_SERVER['REMOTE_ADDR'],0, 15);
            $errmsg .= "\n";
            $errlog = @error_log($errmsg, 3, $CONFIG['access_logfile']);
            ## if (!$errlog) echo "Fatal error logging this problem<br />";
            unset($errdate);
            unset($errmsg);
            unset($errlog);
        }
        // redirect
        header ("Location: index.php?id=3");
        exit;
    }
}
else
{
    //invalid user and portal disabled
    header ("Location: index.php?id=3");
    exit;
}
?>