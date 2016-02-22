<?php
// services/add.php - Adds a new service record
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// This Page Is Valid XHTML 1.0 Transitional! 24May2009

$permission = 21; // FIXME need a permission for add service

require ('core.php');
require_once (APPLICATION_LIBPATH . 'functions.inc.php');
// This page requires authentication
require_once (APPLICATION_LIBPATH . 'auth.inc.php');

// External variables
$contractid = clean_int($_REQUEST['contractid']);
$submit = cleanvar($_REQUEST['submit']);
$title = ("$strContract - $strAddService");

// Contract ID must not be blank
if (empty($contractid))
{
    html_redirect('main.php', FALSE);
    exit;
}

// Find the latest end date so we can suggest a start date
$sql = "SELECT enddate FROM `{$dbService}` WHERE contractid = {$contractid} ORDER BY enddate DESC LIMIT 1";
$result = mysql_query($sql);
if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);

if (mysql_num_rows($result) > 0)
{
    list($prev_enddate) = mysql_fetch_row($result);
    $suggested_startdate = mysql2date($prev_enddate) + 86400; // the next day
}
else
{
    $suggested_startdate = $now; // Today
}

if (empty($submit) OR !empty($_SESSION['formerrors']['add_service']))
{
    include (APPLICATION_INCPATH . 'htmlheader.inc.php');
    echo show_form_errors('add_service');
    clear_form_errors('add_service');
    echo "<h2>{$strNewService}</h2>\n";

    $timed = is_contract_timed($contractid);

    echo "<form id='serviceform' name='serviceform' action='{$_SERVER['PHP_SELF']}' method='post' onsubmit='return confirm_submit(\"{$strAreYouSureMakeTheseChanges}\");'>";
    echo "<table align='center' class='vertical'>";
    if ($timed) echo "<thead>\n";
    echo "<tr><th>{$strStartDate}</th>";
    echo "<td><input class='required' type='text' name='startdate' id='startdate' size='10' ";
    if ($_SESSION['formdata']['add_service']['startdate'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_service']['startdate']}'";
    }
    else
    {
        echo "value='".date('Y-m-d', $suggested_startdate)."'";
    }
    echo "/> ";
    echo date_picker('serviceform.startdate');
    echo " <span class='required'>{$strRequired}</span></td></tr>";

    echo "<tr><th>{$strEndDate}</th>";
    echo "<td><input class='required' type='text' name='enddate' id='enddate' size='10'";
    if ($_SESSION['formdata']['add_service']['enddate'] != '')
    {
        echo "value='{$_SESSION['formdata']['add_service']['enddate']}'";
    }
    echo "/> ";
    echo date_picker('serviceform.enddate');
    echo " <span class='required'>{$strRequired}</span></td></tr>";

    echo "<tr><th>{$strTitle}</th><td>";
    echo "<input type='text' id='title' name='title' /></td></tr>";

    echo "<tr><th>{$strNotes}</th><td>";
    echo "<textarea rows='5' cols='20' name='notes'></textarea></td></tr>";

    echo "<tr><th>{$strBilling}</th>";
    echo "<td>";
    if ($timed)
    {
        echo "<label>";
        echo "<input type='radio' name='billtype' value='billperunit' onchange=\"addservice_showbilling('serviceform');\" checked='checked' /> ";
        echo "{$strPerUnit}</label>";
        echo "<label>";
        echo "<input type='radio' name='billtype' value='billperincident' onchange=\"addservice_showbilling('serviceform');\" /> ";
        echo "{$strPerIncident}</label>";
    }
    else
    {
        echo "<label>";
        echo "<input type='radio' name='billtype' value='' checked='checked' disabled='disabled' /> ";
        echo "{$strNone}</label>";
    }
    echo "</td></tr>\n";

    if ($timed)
    {
        echo "</thead>\n";
        echo "<tbody id='billingsection'>\n";

        echo "<tr><th>{$strCustomerReference}</th>";
        echo "<td><input type='text' id='cust_ref' name='cust_ref' /></td></tr>\n";

        echo "<tr><th>{$strCustomerReferenceDate}</th>";
        echo "<td><input type='text' name='cust_ref_date' id='cust_ref_date' size='10' ";
        echo "value='".date('Y-m-d', $now)."' />";
        echo date_picker('serviceform.cust_ref_date');
        echo " </td></tr>\n";

        echo "<tr><th>{$strCreditAmount}</th>";
        echo "<td>{$CONFIG['currency_symbol']} ";
        echo "<input class='required' type='text' name='amount' size='5' />";
        echo " <span class='required'>{$strRequired}</span></td></tr>\n";

        echo "<tr id='unitratesection'><th>{$strUnitRate}</th>";
        echo "<td>{$CONFIG['currency_symbol']} ";
        echo "<input class='required' type='text' name='unitrate' size='5' />";
        echo " <span class='required'>{$strRequired}</span></td></tr>\n";

        echo "<tr id='incidentratesection' style='display:none'><th>{$strIncidentRate}</th>";
        echo "<td>{$CONFIG['currency_symbol']} ";
        echo "<input class='required' type='text' name='incidentrate' size='5' />";
        echo " <span class='required'>{$strRequired}</span></td></tr>\n";

        echo "<tr>";
        echo "<th>{$strFreeOfCharge}</th>";
        echo "<td><input type='checkbox' id='foc' name='foc' value='yes' /> {$strAboveMustBeCompletedToAllowDeductions}</td>";
        echo "</tr>\n";

        echo "</tbody>\n";
    }

//  Not sure how applicable daily rate is, INL 4Apr08
//     echo "<tr><th>{$strDailyRate}</th>";
//     echo "<td>{$CONFIG['currency_symbol']} <input type='text' name='dailyrate' size='5' />";
//     echo "</td></tr>";

    echo "</table>\n\n";
    echo "<input type='hidden' name='contractid' value='{$contractid}' />";
    echo "<p><input name='submit' type='submit' value=\"{$strAdd}\" /></p>";
    echo "</form>\n";

    echo "<p align='center'><a href='contract_details.php?id={$contractid}'>{$strReturnWithoutSaving}</a></p>";

    //cleanup form vars
    clear_form_data('add_service');
    include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
}
else
{
    // External variables
    $contractid = clean_int($_POST['contractid']);
    $startdate = strtotime($_REQUEST['startdate']);
    if ($startdate > 0) $startdate = date('Y-m-d',$startdate);
    else $startdate = date('Y-m-d',$now);
    $enddate = strtotime($_REQUEST['enddate']);
    if ($enddate > 0) $enddate = date('Y-m-d',$enddate);
    else $enddate = date('Y-m-d',strtotime($startdate)+31556926); // No date set so we default to one year after start
    $amount =  clean_float($_POST['amount']);
    if ($amount == '') $amount = 0;
    $unitrate =  clean_float($_POST['unitrate']);
    if ($unitrate == '') $unitrate = 0;
    $incidentrate =  clean_float($_POST['incidentrate']);
    if ($incidentrate == '') $incidentrate = 0;
    $notes = cleanvar($_REQUEST['notes']);
    $title = cleanvar($_REQUEST['title']);

    $billtype = cleanvar($_REQUEST['billtype']);
    if (!empty($billtype))
    {
        $foc = cleanvar($_REQUEST['foc']);
        if (empty($foc)) $foc = 'no';

        if ($billtype == 'billperunit') $incidentrate = 0;
        elseif ($billtype == 'billperincident') $unitrate = 0;

        $cust_ref = cleanvar($_REQUEST['cust_ref']);
        $cust_ref_date = cleanvar($_REQUEST['cust_ref_date']);

        $sql = "INSERT INTO `{$dbService}` (contractid, startdate, enddate, creditamount, unitrate, incidentrate, cust_ref, cust_ref_date, title, notes, foc) ";
        $sql .= "VALUES ('{$contractid}', '{$startdate}', '{$enddate}', '{$amount}', '{$unitrate}', '{$incidentrate}', '{$cust_ref}', '{$cust_ref_date}', '{$title}', '{$notes}', '{$foc}')";
    }
    else
    {
        $sql = "INSERT INTO `{$dbService}` (contractid, startdate, enddate, title, notes) ";
        $sql .= "VALUES ('{$contractid}', '{$startdate}', '{$enddate}', '{$title}', '{$notes}')";
    }

    mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);
    if (mysql_affected_rows() < 1) trigger_error("Insert failed",E_USER_ERROR);

    $serviceid = mysql_insert_id();

    if ($amount != 0)
    {
        update_contract_balance($contractid, "New service", $amount, $serviceid);
    }

    $sql = "SELECT expirydate FROM `{$dbMaintenance}` WHERE id = {$contractid}";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);

    if (mysql_num_rows($result) > 0)
    {
        $obj = mysql_fetch_object($result);
        if ($obj->expirydate < strtotime($enddate))
        {
            $update = "UPDATE `$dbMaintenance` ";
            $update .= "SET expirydate = '".strtotime($enddate)."' ";
            $update .= "WHERE id = {$contractid}";
            mysql_query($update);
            if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);
            if (mysql_affected_rows() < 1) trigger_error("Expiry of contract update failed",E_USER_ERROR);
        }
    }

    html_redirect("contract_details.php?id={$contractid}", TRUE);
}
?>