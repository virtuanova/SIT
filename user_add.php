<?php
// user_add.php - Form for adding users
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//


$permission = 20; // Add Users

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

$title = $strAddUser;

// External variables
$submit = cleanvar($_REQUEST['submit']);

include (APPLICATION_INCPATH . 'htmlheader.inc.php');

if (empty($submit))
{
    // Show add user form
    $gsql = "SELECT * FROM `{$dbGroups}` ORDER BY name";
    $gresult = mysql_query($gsql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
    while ($group = mysql_fetch_object($gresult))
    {
        $grouparr[$group->id] = $group->name;
    }

    $numgroups = count($grouparr);

    echo show_form_errors('add_user');
    clear_form_errors('add_user');

    echo "<h2>".icon('user', 32)." ";
    echo "{$strNewUser}</h2>";
    echo "<h5>".sprintf($strMandatoryMarked,"<sup class='red'>*</sup>")."</h5>";
    echo "<form id='adduser' action='{$_SERVER['PHP_SELF']}' method='post' ";
    echo "onsubmit='return confirm_action(\"{$strAreYouSureAdd}\");'>";
    echo "<table align='center' class='vertical'>\n";
    echo "<tr><th>{$strRealName} <sup class='red'>*</sup></th>";
    echo "<td><input maxlength='50' name='realname' size='30'";
    if ($_SESSION['formdata']['add_user']['realname'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_user']['realname']}'";
    }
    echo "/></td></tr>\n";

    echo "<tr><th>{$strUsername} <sup class='red'>*</sup></th>";
    echo "<td><input maxlength='50' name='username' size='30'";
    if ($_SESSION['formdata']['add_user']['username'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_user']['username']}'";
    }
    echo "/></td></tr>\n";

    echo "<tr id='password'><th>{$strPassword} <sup class='red'>*</sup></th>";
    echo "<td><input maxlength='50' name='password' size='30' type='password' ";
    if ($_SESSION['formdata']['add_user']['password'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_user']['password']}'";
    }
    echo "/></td></tr>\n";

    echo "<tr><th>{$strGroup}</th>";
    if ($_SESSION['formdata']['add_user']['groupid'] != '')
    {
        echo "<td>".group_drop_down('groupid', $_SESSION['formdata']['add_user']['groupid'])."</td>";
    }
    else
    {
        echo "<td>".group_drop_down('groupid', 0)."</td>";
    }
    echo "</tr>";

    echo "<tr><th>{$strRole}</th>";
    if ($_SESSION['formdata']['add_user']['roleid'] != '')
    {
        echo "<td>".role_drop_down('roleid', $_SESSION['formdata']['add_user']['roleid'])."</td>";
    }
    else
    {
        echo "<td>".role_drop_down('roleid', $CONFIG['default_roleid'])."</td>";
    }
    echo "</tr>";

    echo "<tr><th>{$strJobTitle} <sup class='red'>*</sup></th><td><input maxlength='50' name='jobtitle' size='30'";
    if ($_SESSION['formdata']['add_user']['jobtitle'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_user']['jobtitle']}'";
    }
    echo "/></td></tr>\n";

    echo "<tr id='email'><th>{$strEmail} <sup class='red'>*</sup></th><td><input maxlength='50' name='email' size='30'";
    if ($_SESSION['formdata']['add_user']['email'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_user']['email']}'";
    }
    echo "/></td></tr>\n";

    echo "<tr><th>{$strTelephone}</th><td><input maxlength='50' name='phone' size='30'";
    if ($_SESSION['formdata']['add_user']['phone'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_user']['phone']}'";
    }
    echo "/></td></tr>\n";

    echo "<tr><th>{$strMobile}</th><td><input maxlength='50' name='mobile' size='30'";
    if ($_SESSION['formdata']['add_user']['mobile'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_user']['mobile']}'";
    }
    echo "/></td></tr>\n";

    echo "<tr><th>{$strFax}</th><td><input maxlength='50' name='fax' size='30'";
    if ($_SESSION['formdata']['add_user']['fax'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_user']['fax']}'";
    }
    echo "/></td></tr>\n";

    if ($CONFIG['holidays_enabled'])
    {
        echo "<tr><th>{$strHolidayEntitlement}</th><td><input maxlength='3' name='holiday_entitlement' size='3' ";
        if ($_SESSION['formdata']['add_user']['holiday_entitlement'] != '')
        {
            echo "value='{$_SESSION['formdata']['add_user']['holiday_entitlement']}'";
        }
        else
        {
            echo "value='{$CONFIG['default_entitlement']}'";
        }
        echo " /> {$strDays}</td></tr>\n";

        echo "<tr><th>{$strStartDate} ".help_link('UserStartdate')."</th>";
        echo "<td><input type='text' name='startdate' id='startdate' size='10'";
        if ($_SESSION['formdata']['add_user']['startdate'] != '')
        echo "value='{$_SESSION['formdata']['add_user']['startdate']}'";
        echo "/> ";
        echo date_picker('adduser.startdate');
        echo "</td></tr>\n";
    }
    plugin_do('add_user_form');
    echo "</table>\n";
    echo "<input type='hidden' name='formtoken' value='" . gen_form_token() . "' />";
    echo "<p><input name='submit' type='submit' value='{$strAddUser}' /></p>";
    echo "</form>\n";
    include (APPLICATION_INCPATH . 'htmlfooter.inc.php');

    clear_form_data('add_user');
}
else
{
    // External variables
    $username = clean_dbstring(strtolower(trim(strip_tags($_REQUEST['username']))));
    $realname = cleanvar($_REQUEST['realname']);
    $password = clean_dbstring($_REQUEST['password']);
    $groupid = clean_int($_REQUEST['groupid']);
    $roleid = clean_int($_REQUEST['roleid']);
    $jobtitle = cleanvar($_REQUEST['jobtitle']);
    $email = cleanvar($_REQUEST['email']);
    $phone = cleanvar($_REQUEST['phone']);
    $mobile = cleanvar($_REQUEST['mobile']);
    $fax = cleanvar($_REQUEST['fax']);
    $holiday_entitlement = clean_int($_REQUEST['holiday_entitlement']);
    if (!empty($_POST['startdate']))
    {
        $startdate = date('Y-m-d',strtotime($_POST['startdate']));
    }
    else $startdate = '';
    $formtoken = cleanvar($_POST['formtoken']);

    $_SESSION['formdata']['add_user'] = cleanvar($_REQUEST, TRUE, FALSE, FALSE);
    // Add user
    $errors = 0;
    if (!check_form_token($formtoken))
    {
        $_SESSION['formerrors']['add_user']['formtoken'] = user_alert($strFormInvalidExpired, E_USER_ERROR);
        $errors++;
    }
    // check for blank real name
    if ($realname == '')
    {
        $errors++;
        $_SESSION['formerrors']['add_user']['realname']= sprintf($strFieldMustNotBeBlank, $strRealName)."</p>\n";
    }
    // check for blank username
    if ($username == '')
    {
        $errors++;
        $_SESSION['formerrors']['add_user']['username']= sprintf($strFieldMustNotBeBlank, $strUsername)."</p>\n";
    }
    // check for blank password
    if ($password == '')
    {
        $errors++;
        $_SESSION['formerrors']['add_user']['password']= sprintf($strFieldMustNotBeBlank, $strPassword)."</p>\n";
    }
    // check for blank job title
    if ($jobtitle == '')
    {
        $errors++;
        $_SESSION['formerrors']['add_user']['jobtitle']= sprintf($strFieldMustNotBeBlank, $strJobTitle)."</p>\n";
    }
    // check for blank email
    if ($email == '')
    {
        $errors++;
        $_SESSION['formerrors']['add_user']['email']= sprintf($strFieldMustNotBeBlank, $strEmail)."</p>\n";
    }
    // Check username is unique
    $sql = "SELECT COUNT(id) FROM `{$dbUsers}` WHERE username='$username'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);
    list($countexisting) = mysql_fetch_row($result);
    if ($countexisting >= 1)
    {
        $errors++;
        $_SESSION['formerrors']['add_user']['']= "{$strUsernameNotUnique}</p>\n";
    }
    // Check email address is unique (discount disabled accounts)
    $sql = "SELECT COUNT(id) FROM `{$dbUsers}` WHERE status > 0 AND email='$email'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
    list($countexisting) = mysql_fetch_row($result);
    if ($countexisting >= 1)
    {
        $errors++;
        $_SESSION['formerrors']['add_user']['duplicate_email'] = "{$strEmailMustBeUnique}</p>\n";
    }

    // add information if no errors
    if ($errors == 0)
    {
        $password = md5($password);
        $sql = "INSERT INTO `{$dbUsers}` (username, password, realname, roleid,
                groupid, title, email, phone, mobile, fax, status, var_style,
                holiday_entitlement, user_startdate, lastseen) ";
        $sql .= "VALUES ('$username', '$password', '$realname', '$roleid',
                '$groupid', '$jobtitle', '$email', '$phone', '$mobile', '$fax',
                1, '{$CONFIG['default_interface_style']}',
                '$holiday_entitlement', '$startdate', NOW())";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
        $newuserid = mysql_insert_id();

        // Create permissions (set to none)
        $sql = "SELECT * FROM `{$dbPermissions}`";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
        while ($perm = mysql_fetch_object($result))
        {
            $psql = "INSERT INTO `{$dbUserPermissions}` (userid, permissionid, granted) ";
            $psql .= "VALUES ('$newuserid', '{$perm->id}', 'false')";
            mysql_query($psql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
        }
        
        if (!$result) echo "<p class='error'>{$strAdditionFail}</p>\n";
        else
        {
        	plugin_do('user_created');
            setup_user_triggers($newuserid);
            trigger('TRIGGER_NEW_USER', array('userid' => $newuserid));
            html_redirect("manage_users.php#userid{$newuserid}");
        }
        clear_form_data('add_user');
        clear_form_errors('add_user');
    }
    else
    {
        html_redirect($_SERVER['PHP_SELF'], FALSE);
    }
}
?>