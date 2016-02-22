<?php
// edit_contract.php - Form for editing maintenance contracts
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//


$permission = 21; // Edit Contracts

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');
// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

$title = $strEditContract;

// External variables
$action = clean_fixed_list($_REQUEST['action'], array('','showform','edit','update'));
$maintid = clean_int($_REQUEST['maintid']);
$changeproduct = clean_fixed_list($_REQUEST['changeproduct'], array('','no','yes'));

if (empty($action) OR $action == "showform")
{
    include (APPLICATION_INCPATH . 'htmlheader.inc.php');
    echo "<h2>".icon('contract', 32)." ";
    echo "{$strContract}:</h2>";
    echo "<form action='{$_SERVER['PHP_SELF']}?action=edit' method='post'>";
    echo "<table align='center' class='vertical'>";
    echo "<tr><th>{$strContract}:</th><td>";
    maintenance_drop_down("maintid", 0);
    echo "</td></tr>\n";
    echo "</table>\n";
    echo "<p align='center'><input name='submit' type='submit' value=\"$strContinue\" /></p>\n";
    echo "</form>\n";
    include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
}


if ($action == "edit")
{
    // Show edit maintenance form
    include (APPLICATION_INCPATH . 'htmlheader.inc.php');
    if ($maintid == 0) echo user_alert(sprintf($strFieldMustNotBeBlank, "'{$strContract}'"), E_USER_ERROR);
    else
    {
        $sql = "SELECT * FROM `{$dbMaintenance}` WHERE id='$maintid'";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Error", E_USER_WARNING);
        $maint = mysql_fetch_array($result);
        ?>
        <script type='text/javascript'>
        //<![CDATA[

        function set_terminated()
        {
            if (document.maintform.productonly.checked==true)
            {
                document.maintform.terminated.disabled=true;
                document.maintform.terminated.checked=true;
            }
            else
            {
                document.maintform.terminated.disabled=false;
                document.maintform.terminated.checked=false;
            }
        }
        //]]>
        </script>
        <?php
        echo "<h2>".icon('contract', 32)." ";
        echo "{$strEditContract}: {$maintid}</h2>";
        echo "<h5>".sprintf($strMandatoryMarked,"<sup class='red'>*</sup>")."</h5>";
        echo "<form id='maintform' name='maintform' action='{$_SERVER['PHP_SELF']}?action=update' method='post' onsubmit='return confirm_action(\"{$strAreYouSureMakeTheseChanges}\")'>\n";
        echo "<table align='center' class='vertical'>\n";
        echo "<thead>\n";
        echo "<tr><th>{$strSite}: <sup class='red'>*</sup></th><td>";
        echo site_name($maint["site"]). "</td></tr>";
        echo "<tr><th>{$strContacts}:</th><td>";
        echo "<input value='amount' type='radio' name='contacts' checked='checked' />";
        echo "{$strLimitTo} <input size='2' value='{$maint[supportedcontacts]}' name='amount' /> {$strSupportedContacts} ({$str0MeansUnlimited})<br />";
        echo "<input type='radio' value='all' name='contacts'";
        if ($maint[allcontactssupported] == 'yes')
        echo "checked='checked'";
        echo " />{$strAllSiteContactsSupported}</td></tr>";
        echo "<tr><th>{$strProduct}: <sup class='red'>*</sup></th><td>";
        $productname=product_name($maint["product"]);
        if (user_permission($sit[2], 22))
        {
            if ($changeproduct=='yes')
            {
                echo product_drop_down("product", $maint['product']);
            }
            else
            {
                echo "{$productname} (<a href='{$_SERVER['PHP_SELF']}?action=edit&amp;maintid={$maintid}&amp;changeproduct=yes'>{$strChange}</a>)";
            }
        }
        else echo "{$productname}";
        echo "</td></tr>\n";

        echo "<tr><th>{$strExpiryDate}: <sup class='red'>*</sup></th>";
        echo "<td><input name='expirydate' size='10' value='";
        if ($maint['expirydate'] > 0) echo ldate('Y-m-d',$maint['expirydate']);
        echo "' /> ".date_picker('maintform.expirydate');
        if ($maint['expirydate'] == '-1')
        {
            echo "<input type='checkbox' checked='checked' name='noexpiry' /> {$strUnlimited}";
        }
        else
        {
            echo "<input type='checkbox' name='noexpiry' /> {$strUnlimited}";
        }
        echo "</td></tr>\n";
        echo "<tr><th>{$strServiceLevel}:</th><td>";
        echo servicelevel_drop_down('servicelevelid',$maint['servicelevelid'], TRUE);
        echo "</td></tr>\n";
        echo "<tr><th>{$strAdminContact}: <sup class='red'>*</sup></th><td>";
        echo contact_drop_down("admincontact", $maint["admincontact"], true);
        echo "</td></tr>\n";
        echo "<tr><th>{$strNotes}:</th><td><textarea cols='40' name='notes' rows='5'>";
        echo $maint["notes"];
        echo "</textarea></td></tr>\n";
        echo "<tr><th>{$strTerminated}:</th><td><input name='terminated' id='terminated' type='checkbox' value='yes'";
        if ($maint["term"] == "yes") echo " checked";
        echo " /></td></tr>\n";


        echo "<tr><th></th><td><a href=\"javascript:void(0);\" onclick=\"$('hidden').toggle();\">{$strAdvanced}</a></td></tr>";
        echo "</thead>\n";
        echo "<tbody id='hidden' style='display:none'>";

        echo "<tr><th>{$strReseller}:</th><td>";
        echo reseller_drop_down("reseller", $maint["reseller"]);
        echo "</td></tr>\n";

        echo "<tr><th>{$strLicenseQuantity}:</th>";
        echo "<td><input maxlength='7' name='licence_quantity' size='5' value='{$maint['licence_quantity']}' /></td></tr>\n";
        echo "<tr><th>{$strLicenseType}:</th><td>";
        echo licence_type_drop_down("licence_type", $maint["licence_type"]);
        echo "</td></tr>\n";

        echo "<tr><th>{$strIncidentPool}:</th>";
        $incident_pools = explode(',', "Unlimited,{$CONFIG['incident_pools']}");
        echo "<td>".array_drop_down($incident_pools, 'incident_poolid', $maint['incident_quantity'], '', TRUE, FALSE)."</td></tr>";

        echo "<tr><th>{$strProductOnly}:</th>";
        echo "<td><input name='productonly' type='checkbox' value='yes' onclick='set_terminated();' ";
        if ($maint["productonly"] == "yes") echo " checked";
        echo " /></td></tr>\n";
        plugin_do('contract_edit_form_more');
        echo "</tbody>";
        plugin_do('contract_edit_form');
        echo "</table>\n";
        echo "<input name='maintid' type='hidden' value='{$maintid}' />";
        echo "<p align='center'><input name='submit' type='submit' value='{$strSave}' /></p>";
        echo "</form>\n";

        echo "<p align='center'><a href='contract_details.php?id={$maintid}'>{$strReturnWithoutSaving}</a></p>";
        mysql_free_result($result);
    }
    include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
}
else if ($action == "update")
{
    // External variables
    $incident_pools = explode(',', "0,{$CONFIG['incident_pools']}");
    $incident_quantity = clean_int($incident_pools[$_POST['incident_poolid']]);
    $reseller = clean_int($_POST['reseller']);
    $licence_quantity = clean_int($_POST['licence_quantity']);
    $licence_type = clean_int($_POST['licence_type']);
    $notes = cleanvar($_POST['notes']);
    $admincontact = clean_int($_POST['admincontact']);
    $terminated = cleanvar($_POST['terminated']);
    $servicelevelid = clean_int($_POST['servicelevelid']);
    $incidentpoolid = clean_int($_POST['incidentpoolid']);
    $product = clean_int($_POST['product']);
    $productonly = clean_fixed_list($_POST['productonly'], array('no', 'yes'));
    $contacts = cleanvar($_REQUEST['contacts']);
    if ($_REQUEST['noexpiry'] == 'on') $expirydate = '-1';
    else $expirydate = strtotime($_REQUEST['expirydate']);

    $allcontacts = 'No';
    if ($contacts == 'amount') $amount = clean_float($_REQUEST['amount']);
    elseif ($contacts == 'all') $allcontacts = 'Yes';

    // Update maintenance
    $errors = 0;

    // check for blank reseller
    if ($reseller == 0)
    {
        $errors = 1;
        $errors_string .= user_alert(sprintf($strFieldMustNotBeBlank, "'{$strReseller}'"), E_USER_ERROR);
    }
    // check for blank admin contact
    if ($admincontact == 0)
    {
        $errors = 1;
        $errors_string .= user_alert(sprintf($strFieldMustNotBeBlank, "'{$strAdminContact}'"), E_USER_ERROR);
    }
    // check for blank expiry day
    if ($expirydate == 0)
    {
        $errors = 1;
        $errors_string .= user_alert(sprintf($strFieldMustNotBeBlank, "'{$strExpiryDate}'"), E_USER_ERROR);
    }

    // update maintenance if no errors
    if ($errors == 0)
    {
        if ($productonly=='yes') $terminated='yes';

        if (empty($reseller) OR $reseller == 0)
        {
            $reseller = "NULL";
        }
        else
        {
            $reseller = "'{$reseller}'";
        }

        if (empty($licence_type) OR $licence_type == 0)
        {
            $licence_type = "NULL";
        }
        else
        {
            $licence_type = "'{$licence_type}'";
        }
        // NOTE above is so we can insert null so browse_contacts etc can see the contract rather than inserting 0
        $sql  = "UPDATE `{$dbMaintenance}` SET reseller=$reseller, expirydate='$expirydate', licence_quantity='$licence_quantity', ";
        $sql .= "licence_type=$licence_type, notes='$notes', admincontact=$admincontact, term='$terminated', servicelevelid='$servicelevelid', ";
        $sql .= "incident_quantity='$incident_quantity', ";
        $sql .= "incidentpoolid='$incidentpoolid', productonly='$productonly', ";
        $sql .= "supportedcontacts='$amount', allcontactssupported='$allcontacts'";
        if (!empty($product) AND user_permission($sit[2], 22)) $sql .= ", product='$product'";
        $sql .= " WHERE id='$maintid'";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);

        // show error message if addition failed
        if (!$result)
        {
            include (APPLICATION_INCPATH . 'htmlheader.inc.php');
            echo user_alert("Update failed", E_USER_WARNING);
            include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
        }
        // show success message
        else
        {
            plugin_do('contract_edit_save');
            journal(CFG_LOGGING_NORMAL, 'Contract Edited', "contract $maintid modified", CFG_JOURNAL_MAINTENANCE, $maintid);
            html_redirect("contract_details.php?id={$maintid}");
        }
    }
    // show error message if errors
    else
    {
        include (APPLICATION_INCPATH . 'htmlheader.inc.php');
        echo $errors_string;
        include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
    }
}
?>