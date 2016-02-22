<?php
// add_maintenance_support_contract.php - Associates a contact with a contract
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Author: Ivan Lucas <ivanlucas[at]users.sourceforge.net>


$permission = 32;  // Edit Supported Products
require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

// External Variables
$maintid = clean_int($_REQUEST['maintid']);
$contactid = clean_int($_REQUEST['contactid']);
$context = clean_fixed_list($_REQUEST['context'], array('','contact'));
$action = clean_fixed_list($_REQUEST['action'], array('', 'showform', 'add'));

$title = ("$strContract - $strAddContact");

// Valid user, check permissions
if (empty($action) || $action == "showform")
{
    include (APPLICATION_INCPATH . 'htmlheader.inc.php');
    echo "<h2>{$strAssociateContactWithContract}</h2>";

    echo "<form action='{$_SERVER['PHP_SELF']}?action=add' method='post'>";
    echo "<input type='hidden' name='context' value='{$context}' />";
    echo "<table align='center' class='vertical'>";

    if (empty($maintid))
    {
        echo "<tr><th>{$strContract} ".icon('contract', 16)."</th>";
        echo "<td width='400'>";
        maintenance_drop_down("maintid", 0, '', '', FALSE, TRUE);
        echo "</td></tr>";
    }
    else
    {
        $sql = "SELECT s.name, p.name FROM `{$dbMaintenance}` m, `{$dbSites}` s, `{$dbProducts}` p WHERE m.site=s.id ";
        $sql .= "AND m.product=p.id AND m.id='$maintid'";
        $result=mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
        list($sitename, $product)=mysql_fetch_row($result);

        echo "<tr><th>{$strContract} ".icon('contract', 16)."</th><td>$maintid - $sitename, $product</td></tr>";
        echo "<input name=\"maintid\" type=\"hidden\" value=\"$maintid\" />";
    }

    if (empty($contactid))
    {
        echo "<tr><th>{$strContact} ".icon('contact', 16)."</th>";
        echo "<td>".contact_drop_down("contactid", 0, TRUE)."</td></tr>";
    }
    else
    {
        echo "<tr><th>{$strContact} ".icon('contact', 16)."</th><td>$contactid - ".contact_realname($contactid).", ".site_name(contact_site($contactid));
        echo "<input name=\"contactid\" type=\"hidden\" value=\"$contactid\" />";
        echo "</td></tr>";
    }
    echo "</table>";
    echo "<p align='center'><input name='submit' type='submit' value='{$strContinue}' /></p>";
    echo "</form>";

    include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
}
else if ($action == "add")
{
    // Add support contact
    $errors = 0;
    // check for blank contact
    if ($contactid == 0)
    {
        $errors = 1;
        $errors_string .= user_alert("You must select a contact", E_USER_ERROR);
    }
    // check for blank maintenance id
    if ($maintid == 0)
    {
        $errors = 1;
        trigger_error("Something weird has happened, better call technical support", E_USER_ERROR);
    }

    $sql = "SELECT * FROM `{$dbSupportContacts}` WHERE maintenanceid = '{$maintid}' AND contactid = '{$contactid}'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);

    if (mysql_num_rows($result) > 0)
    {
        $errors = 1;
        $errors_string .= user_alert("A contact can only be listed once per support contract", E_USER_ERROR);
    }

    // add maintenance support contact if no errors
    if ($errors == 0)
    {
        $sql  = "INSERT INTO `{$dbSupportContacts}` (maintenanceid, contactid) VALUES ($maintid, $contactid)";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);

        // show error message if addition failed
        if (!$result)
        {
            include (APPLICATION_INCPATH . 'htmlheader.inc.php');
            echo user_alert("Addition of support contact failed", E_USER_WARNING);
            include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
        }
        // update database and show success message
        else
        {
            if ($context == 'contact') html_redirect("contact_details.php?id=$contactid");
            else html_redirect("contract_details.php?id=$maintid");
        }
    }
    else
    {
        // show error message if errors
        include (APPLICATION_INCPATH . 'htmlheader.inc.php');
        echo $errors_string;

        echo "<p align='center'><a href='contract_details.php?id={$maintid}'>Return</a></p>";
        include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
    }
}
?>