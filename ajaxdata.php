<?php
// ajaxdata.php - Return data for AJAX calls
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Author: Ivan Lucas <ivanlucas[at]users.sourceforge.net>


$permission = 0; // not required
require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
if ($_REQUEST['action'] == 'contexthelp' AND $_REQUEST['auth'] == 'portal')
{
    // Special exception for contexthelp, use the portal authentication for
    // portal help tips
    $accesslevel = 'any';
    require (APPLICATION_LIBPATH . 'portalauth.inc.php');
}
else
{
    require (APPLICATION_LIBPATH . 'auth.inc.php');
}
$action = cleanvar($_REQUEST['action']);
$selected = cleanvar($_REQUEST['selected']);

if ($_SESSION['auth'] == TRUE)
{
    $styleid = $_SESSION['style'];
}
else
{
    $styleid = $CONFIG['default_interface_style'];
}
$iconsql = "SELECT iconset FROM `{$GLOBALS['dbInterfaceStyles']}` WHERE id='{$styleid}'";
$iconresult = mysql_query($iconsql);
if (mysql_error())trigger_error(mysql_error(),E_USER_WARNING);

list($iconset) = mysql_fetch_row($iconresult);

switch ($action)
{
    case 'auto_save':
        $userid = clean_int($_REQUEST['userid']);
        $incidentid = clean_int($_REQUEST['incidentid']);
        $type = cleanvar($_REQUEST['type']);
        $draftid = clean_int($_REQUEST['draftid']);
        $meta = cleanvar($_REQUEST['meta']);
        $content = cleanvar($_REQUEST['content']);

        if ($userid == $_SESSION['userid'])
        {
            if ($draftid == -1)
            {
                $sql = "INSERT INTO `{$dbDrafts}` (userid,incidentid,type,meta,content,lastupdate) VALUES ('{$userid}','{$incidentid}','{$type}','{$meta}','{$content}','{$now}')";
            }
            else
            {
                $sql = "UPDATE `{$dbDrafts}` SET content = '{$content}', meta = '{$meta}', lastupdate = '{$now}' WHERE id = {$draftid}";
            }
            $result = mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
            echo mysql_insert_id();
        }
        break;
    case 'servicelevel_timed':
        $sltag = servicelevel_id2tag(cleanvar($_REQUEST['servicelevel']));
        if (servicelevel_timed($sltag))
        {
            echo "TRUE";
        }
        else
        {
            echo "FALSE";
        }
        break;
    case 'contexthelp':
        $context = cleanvar($_REQUEST['context']);
        $helpfile = clean_fspath(APPLICATION_HELPPATH . "{$_SESSION['lang']}". DIRECTORY_SEPARATOR . "{$context}.txt");
        // Default back to english if language helpfile isn't found
        if (!file_exists($helpfile)) $helpfile = APPLICATION_HELPPATH . "en-GB/{$context}.txt";
        if (file_exists($helpfile))
        {
            $fp = fopen($helpfile, 'r', TRUE);
            $helptext = fread($fp, 1024);
            fclose($fp);
            echo nl2br($helptext);
        }
        else
        {
            echo "Error: Missing helpfile '{$_SESSION['lang']}/{$context}.txt'";
        }
        break;
    case 'dismiss_notice':
        require (APPLICATION_LIBPATH . 'auth.inc.php');
        // We don't use clean_int here as it may be a int or 'all' if its a string its not used directly
        $noticeid = clean_dbstring($_REQUEST['noticeid']);
        $userid = clean_int($_REQUEST['userid']);
        if (is_numeric($noticeid))
        {
            $sql = "DELETE FROM `{$GLOBALS['dbNotices']}` WHERE id='{$noticeid}' AND userid='{$sit[2]}'";
            mysql_query($sql);
            if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
            else echo "deleted {$noticeid}";
        }
        elseif ($noticeid == 'all')
        {
            $sql = "DELETE FROM `{$GLOBALS['dbNotices']}` WHERE userid={$userid} LIMIT 20"; // only delete 20 max as we only show 20 max
            mysql_query($sql);
            if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
            else echo "deleted {$noticeid}";
        }
        break;
    case 'dashboard_display':
        require (APPLICATION_LIBPATH . 'auth.inc.php');
        $dashboard = cleanvar($_REQUEST['dashboard']);
        $dashletid = 'win'.cleanvar($_REQUEST['did']);
        // FIXME need some sanitation here
        include (APPLICATION_PLUGINPATH . "dashboard_{$dashboard}.php");
        $dashfn = "dashboard_{$dashboard}_display";
        echo $dashfn($dashletid);
        break;
    case 'dashboard_save':
    case 'dashboard_edit':
        require (APPLICATION_LIBPATH . 'auth.inc.php');

        $dashboard = cleanvar($_REQUEST['dashboard']);
        $dashletid = 'win'.cleanvar($_REQUEST['did']);
        // FIXME need some sanitation here
        include (APPLICATION_PLUGINPATH . "dashboard_{$dashboard}.php");
        $dashfn = "dashboard_{$dashboard}_edit";
        echo $dashfn($dashletid);
        break;
    case 'autocomplete_sitecontact':
        $s = clean_dbstring($_REQUEST['s']);
        // JSON encoded
        $sql = "SELECT DISTINCT forenames, surname FROM `{$dbContacts}` ";
        $sql .= "WHERE active='true' AND (forenames LIKE '{$s}%' OR surname LIKE '{$s}%')";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        if (mysql_num_rows($result) > 0)
        {
            while ($obj = mysql_fetch_object($result))
            {
                $str .= "[\"".$obj->forenames." ".$obj->surname."\"],";
            }
        }
        $sql = "SELECT DISTINCT name FROM `{$dbSites}` ";
        $sql .= "WHERE active='true' AND name LIKE '{$s}%'";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        if (mysql_num_rows($result) > 0)
        {
            while ($obj = mysql_fetch_object($result))
            {
                $str .= "[\"".$obj->name."\"],";
            }
        }
        echo "[".substr($str,0,-1)."]";
        break;
    case 'tags':
        $sql = "SELECT DISTINCT t.name FROM `{$dbSetTags}` AS st, `{$dbTags}` AS t WHERE st.tagid = t.tagid GROUP BY t.name";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        if (mysql_num_rows($result) > 0)
        {
            while ($obj = mysql_fetch_object($result))
            {
                $str .= "[".$obj->name."],";
            }
        }
        echo "[".substr($str,0,-1)."]";
        break;
    case 'contact' :
        $s = clean_dbstring($_REQUEST['s']);
        $sql = "SELECT DISTINCT forenames, surname FROM `{$dbContacts}` ";
        $sql .= "WHERE active='true' AND (forenames LIKE '{$s}%' OR surname LIKE '{$s}%')";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        if (mysql_num_rows($result) > 0)
        {
            while ($obj = mysql_fetch_object($result))
            {
                $str .= "[\"".$obj->forenames." ".$obj->surname."\"],";
            }
        }
        echo "[".substr($str,0,-1)."]";
        break;
    case 'sites':
        $s = clean_dbstring($_REQUEST['s']);
        $sql = "SELECT DISTINCT name FROM `{$dbSites}` ";
        $sql .= "WHERE active='true' AND name LIKE '{$s}%'";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        if (mysql_num_rows($result) > 0)
        {
            while ($obj = mysql_fetch_object($result))
            {
                $str .= "[\"".$obj->name."\"],";
            }
        }
        echo "[".substr($str,0,-1)."]";
        break;
    case 'slas':
        $sql = "SELECT DISTINCT tag FROM `{$dbServiceLevels}`";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        while ($obj = mysql_fetch_object($result))
        {
            $strIsSelected = '';
            if ($obj->tag == $selected)
            {
                $strIsSelected = "selected='selected'";
            }
            echo "<option value='{$obj->tag}' $strIsSelected>{$obj->tag}</option>";
        }
        break;
    case 'products':
        $sql = "SELECT id, name FROM `{$dbProducts}` ORDER BY name ASC";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        while ($obj = mysql_fetch_object($result))
        {
            $strIsSelected = '';
            if ($obj->id == $selected)
            {
                $strIsSelected = "selected='selected'";
            }
            echo "<option value='{$obj->id}' $strIsSelected>{$obj->name}</option>";
        }
        break;
    case 'skills':
        $sql = "SELECT id, name FROM `{$dbSoftware}` ORDER BY name ASC";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        while ($obj = mysql_fetch_object($result))
        {
            $strIsSelected = '';
            if ($obj->id == $selected)
            {
                $strIsSelected = "selected='selected'";
            }
            echo "<option value='{$obj->id}' $strIsSelected>{$obj->name}</option>";
        }
        break;
    case 'storedashboard':
        $id = clean_int($_REQUEST['id']);
        $val = clean_dbstring($_REQUEST['val']);

        if ($id == $_SESSION['userid'])
        {
            //check you're changing your own
            $sql = "UPDATE `{$dbUsers}` SET dashboard = '$val' WHERE id = '$id'";
            $contactresult = mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
        }
        break;
    case 'checkldap':
        $ldap_host = cleanvar($_REQUEST['ldap_host']);
        $ldap_port = clean_int($_REQUEST['ldap_port']);
        $ldap_protocol = cleanvar($_REQUEST['ldap_protocol']);
        $ldap_security = cleanvar($_REQUEST['ldap_security']);
        $ldap_user = cleanvar($_REQUEST['ldap_bind_user']);
        $ldap_password = cleanvar($_REQUEST['ldap_bind_pass']);

        $r = ldapOpen($ldap_host, $ldap_port, $ldap_protocol, $ldap_security, $ldap_user, $ldap_password);
        if ($r == -1) echo "0"; // Failed
        else echo "1"; // Success

        break;
    default:
        plugin_do('ajaxdata_add_action', array('action' => $action));
        break;
}

?>
