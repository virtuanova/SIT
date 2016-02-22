<?php
// close_incident.php - Display a form for closing an incident
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//


$permission = 18; //  Close Incidents

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');
require_once (APPLICATION_LIBPATH . 'billing.inc.php');
// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

// External Variables
$id = clean_int($_REQUEST['id']);
$process = clean_fixed_list($_REQUEST['process'], array('', 'closeincident'));
$incidentid = $id;

$title = $strClose;

// No submit detected show closure form
if (empty($process))
{
    $sql = "SELECT owner FROM `{$dbIncidents}` WHERE id = '{$incidentid}'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
    list($owner) = mysql_fetch_row($result);

    if ($owner == 0)
    {
        html_redirect("incident_details.php?id={$incidentid}", FALSE, $strCallMustBeAssignedBeforeClosure);
        exit;
    }

    if (count(open_activities_for_incident($incidentid)) > 0)
    {
        html_redirect("incident_details.php?id={$incidentid}", FALSE, $strMustCompleteActivitiesBeforeClosure);
        exit;
    }

    include (APPLICATION_INCPATH . 'incident_html_top.inc.php');

    ?>
    <script type="text/javascript">
    <!--
    function enablekb()
    {
        // INL 28Nov07 Yes I know a lot of this javascript is horrible
        // it's old and I'm tired and can't be bothered right now
        // the newer stuff at the bottom is pretty and uses prototype.js
        // syntax
        if (document.closeform.kbtitle.disabled==true)
        {
            // Enable KB
            document.closeform.kbtitle.disabled=false;
            //document.closeform.cust_vis1.disabled=true;
            //document.closeform.cust_vis1.checked=true;
            //document.closeform.cust_vis2.checked=true;
            //document.closeform.cust_vis2.disabled=true;
            // Enable KB includes
            //document.closeform.incsummary.disabled=false;
            document.closeform.summary.disabled=false;
            document.closeform.incsymptoms.disabled=false;
            document.closeform.symptoms.disabled=false;
            document.closeform.inccause.disabled=false;
            document.closeform.cause.disabled=false;
            document.closeform.incquestion.disabled=false;
            document.closeform.question.disabled=false;
            document.closeform.incanswer.disabled=false;
            document.closeform.answer.disabled=false;
            //document.closeform.incsolution.disabled=false;
            document.closeform.solution.disabled=false;
            document.closeform.incworkaround.disabled=false;
            document.closeform.workaround.disabled=false;
            document.closeform.incstatus.disabled=false;
            document.closeform.status.disabled=false;
            document.closeform.incadditional.disabled=false;
            document.closeform.additional.disabled=false;
            document.closeform.increferences.disabled=false;
            document.closeform.references.disabled=false;
            $('helptext').innerHTML = strSelectKBSections;
            $('helptext').innerHTML = $('helptext').innerHTML + "<br /><strong>" + strKnowledgeBaseArticle + "</strong>:";
            // Show the table rows for KB article
            $('titlerow').show();
            $('distributionrow').show();
            $('symptomsrow').show();
            $('causerow').show();
            $('questionrow').show();
            $('answerrow').show();
            $('workaroundrow').show();
            $('statusrow').show();
            $('inforow').show();
            $('referencesrow').show();
        }
        else
        {
            // Disable KB
            document.closeform.kbtitle.disabled=true;
            //document.closeform.cust_vis1.disabled=false;
            //document.closeform.cust_vis2.disabled=false;
            // Disable KB includes
            document.closeform.incsymptoms.checked=false;
            document.closeform.incsymptoms.disabled=true;
            document.closeform.symptoms.disabled=true;
            document.closeform.inccause.checked=false;
            document.closeform.inccause.disabled=true;
            document.closeform.cause.disabled=true;
            document.closeform.incquestion.checked=false;
            document.closeform.incquestion.disabled=true;
            document.closeform.question.disabled=true;
            document.closeform.incanswer.checked=false;
            document.closeform.incanswer.disabled=true;
            document.closeform.answer.disabled=true;
            // document.closeform.incsolution.checked=false;
            // document.closeform.incsolution.disabled=true;
            // document.closeform.solution.disabled=true;
            document.closeform.incworkaround.checked=false;
            document.closeform.incworkaround.disabled=true;
            document.closeform.workaround.disabled=true;
            document.closeform.incstatus.checked=false;
            document.closeform.incstatus.disabled=true;
            document.closeform.status.disabled=true;
            document.closeform.incadditional.checked=false;
            document.closeform.incadditional.disabled=true;
            document.closeform.additional.disabled=true;
            document.closeform.increferences.checked=false;
            document.closeform.increferences.disabled=true;
            document.closeform.references.disabled=true;
            document.closeform.incworkaround.checked=false;
            document.closeform.incworkaround.disabled=true;
            document.closeform.workaround.disabled=true;
            $('helptext').innerHTML = strEnterDetailsAboutIncidentToBeStoredInLog;
            $('helptext').innerHTML = $('helptext').innerHTML + ' '  + strSummaryOfProblemAndResolution;
            $('helptext').innerHTML = $('helptext').innerHTML + "<br /><strong>" + strFinalUpdate + "</strong>:";
            // Hide the table rows for KB article
            $('titlerow').hide();
            $('distributionrow').hide();
            $('symptomsrow').hide();
            $('causerow').hide();
            $('questionrow').hide();
            $('answerrow').hide();
            $('workaroundrow').hide();
            $('statusrow').hide();
            $('inforow').hide();
            $('referencesrow').hide();
        }
    }

    function editbox(object, boxname)
    {
        var boxname;
        object.boxname.disabled=true;
    }

    -->
    </script>
    <?php

    echo "<form name='closeform' action='{$_SERVER['PHP_SELF']}' method='post'>";
    echo "<table class='vertical' width='100%'>";
    echo "<tr><th width='20%'>{$strClose}</th>";
    echo "<td><label><input type='radio' name='wait' value='yes' checked='checked' />";
    echo "{$strMarkForClosure}</label><br />";
    echo "<label><input type='radio' name='wait' value='no' />{$strCloseImmediately}</label></td></tr>\n";
    echo "<tr><th>{$strKnowledgeBase}";
    echo "</th><td><label><input type='checkbox' name='kbarticle' onchange='enablekb();' value='yes' />";
    echo "{$strNewKBArticle}</label></td></tr>\n";

    echo "<tr id='titlerow' style='display:none;'><th>{$strTitle}</th>";
    echo "<td><input class='required' type='text' name='kbtitle' id='kbtitle' ";
    echo "size='30' value='{$incident_title}' disabled='disabled' /> ";
    echo "<span class='required'>{$strRequired}</span></td></tr>\n";
    echo "<tr id='distributionrow' style='display:none;'><th>{$strDistribution}</th>";
    echo "<td>";
    echo "<select name='distribution'> ";
    echo "<option value='public' selected='selected'>{$strPublic}</option>";
    echo "<option value='private' style='color: blue;'>{$strPrivate}</option>";
    echo "<option value='restricted' style='color: red;'>{$strRestricted}</option>";
    echo "</select> ";
    echo help_link('KBDistribution');
    echo "</td></tr>\n";

    echo "<tr><th>&nbsp;</th><td>";
    echo "<span id='helptext'>{$strEnterDetailsAboutIncidentToBeStoredInLog}";
    echo "{$strSummaryOfProblemAndResolution}<br /><strong>{$strFinalUpdate}</strong>:</span></td></tr>\n";

    echo "<tr><th>{$strSummary}<br /><span class='required'>{$strRequired}\n";
    echo "</span><br />";
    echo "<input type='checkbox' name='incsummary' onclick=\"if (this.checked) {document.closeform.summary.disabled = false; ";
    echo "document.closeform.summary.style.display='';} else { saveValue=document.closeform.summary.value; ";
    echo "document.closeform.summary.disabled = true; document.closeform.summary.style.display='none';}\" checked='checked' disabled='disabled' /></th>";

    echo "<td>{$strSummaryOfProblem}<br />\n";
    echo "<textarea class='required' id='summary' name='summary' cols='40' rows='8' onfocus=\"if (this.enabled) { this.value = saveValue; ";
    echo "setTimeout('document.articlform.summary.blur()',1); } else saveValue=this.value;\">";

    //  style="display: none;"
    $sql = "SELECT * FROM `{$dbUpdates}` WHERE incidentid='{$id}' AND type='probdef' ORDER BY timestamp ASC";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    while ($row = mysql_fetch_object($result))
    {
        $bodytext = str_replace("<hr>", "", $row->bodytext);
        echo $bodytext;
        echo "\n\n";
    }
    echo "</textarea>\n";
    echo "</td></tr>";

    echo "<tr id='symptomsrow' style='display:none;'><th><label>{$strSymptoms}<br /><input type='checkbox' name='incsymptoms' onclick=\"if (this.checked) {document.closeform.symptoms.disabled = false; document.closeform.symptoms.style.display=''} else { saveValue=document.closeform.symptoms.value; document.closeform.symptoms.disabled = true; document.closeform.symptoms.style.display='none'}\" disabled='disabled' /></label></th>";
    echo "<td><textarea id='symptoms' name='symptoms' cols='40' style='display: none;' rows='8' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articlform.symptoms.blur()',1); } else saveValue=this.value;\"></textarea></td></tr>";

    echo "<tr id='causerow' style='display:none;'><th><label>{$strCause}<br /><input type='checkbox' name='inccause' onclick=\"if (this.checked) {document.closeform.cause.disabled = false; document.closeform.cause.style.display=''} else { saveValue=document.closeform.cause.value; document.closeform.cause.disabled = true; document.closeform.cause.style.display='none'}\" disabled='disabled' /></label></th>";
    echo "<td><textarea id='cause' name='cause' cols='40' rows='8' style='display: none;' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articlform.cause.blur()',1); } else saveValue=this.value;\"></textarea></td></tr>";

    echo "<tr id='questionrow' style='display:none;'><th><label>{$strQuestion}<br /><input type='checkbox' name='incquestion' onclick=\"if (this.checked) {document.closeform.question.disabled = false; document.closeform.question.style.display=''} else { saveValue=document.closeform.question.value; document.closeform.question.disabled = true; document.closeform.question.style.display='none'}\" disabled='disabled' /></label></th>";
    echo "<td><textarea id='question' name='question' cols='40' rows='8' style='display: none;' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articlform.question.blur()',1); } else saveValue=this.value;\"></textarea></td></tr>";

    echo "<tr id='answerrow' style='display:none;'><th><label>{$strAnswer}<br /><input type='checkbox' name='incanswer' onclick=\"if (this.checked) {document.closeform.answer.disabled = false; document.closeform.answer.style.display=''} else { saveValue=document.closeform.answer.value; document.closeform.answer.disabled = true; document.closeform.answer.style.display='none'}\" disabled='disabled' /></label></th>";
    echo "<td><textarea id='answer' name='answer' cols='40' rows='8' style='display: none;' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articlform.answer.blur()',1); } else saveValue=this.value;\"></textarea></td></tr>";

    echo "<tr><th><label>{$strSolution}</label>";
    echo "<br /><span class='required'>{$strRequired}</span><br />";
    echo "<input type='checkbox' name='incsolution' onclick=\"if (this.checked) {document.closeform.solution.disabled = false; document.closeform.solution.style.display=''} else { saveValue=document.closeform.solution.value; document.closeform.solution.disabled = true; document.closeform.solution.style.display='none'}\" checked='checked' disabled='disabled' /></th>";

    echo "<td><textarea id='solution' name='solution' cols='40' rows='8' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articleform.solution.blur()',1); } else saveValue=this.value;\">";
    $sql = "SELECT * FROM `{$dbUpdates}` WHERE incidentid='{$id}' AND (type='solution' OR type='actionplan') ORDER BY timestamp DESC";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    while ($row = mysql_fetch_object($result))
    {
        $bodytext = str_replace("<hr>", "", $row->bodytext);
        echo trim($bodytext);
        echo "\n\n";
    }
    echo "</textarea>\n";
    echo "</td></tr>";

    echo "<tr id='workaroundrow' style='display:none;'><th><label>{$strWorkaround}<br /><input type='checkbox' name='incworkaround' onclick=\"if (this.checked) {document.closeform.workaround.disabled = false; document.closeform.workaround.style.display=''} else { saveValue=document.closeform.workaround.value; document.closeform.workaround.disabled = true; document.closeform.workaround.style.display='none'}\" disabled='disabled' /></label></th>";
    echo "<td><textarea id='workaround' name='workaround' cols='40' rows='8' style='display: none;' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articlform.workaround.blur()',1); } else saveValue=this.value;\"></textarea></td></tr>";

    echo "<tr id='statusrow' style='display:none;'><th><label>{$strStatus}<br /><input type='checkbox' name='incstatus' onclick=\"if (this.checked) {document.closeform.status.disabled = false; document.closeform.status.style.display=''} else { saveValue=document.closeform.status.value; document.closeform.status.disabled = true; document.closeform.status.style.display='none'}\" disabled='disabled' /></label></th>";
    echo "<td><textarea id='status' name='status' cols='40' rows='8' style='display: none;' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articlform.status.blur()',1); } else saveValue=this.value;\"></textarea></td></tr>";

    echo "<tr id='inforow' style='display:none;'><th><label>{$strAdditionalInfo}<br /><input type='checkbox' name='incadditional' onclick=\"if (this.checked) {document.closeform.additional.disabled = false; document.closeform.additional.style.display=''} else { saveValue=document.closeform.additional.value; document.closeform.additional.disabled = true; document.closeform.additional.style.display='none'}\" disabled='disabled' /></label></th>";
    echo "<td><textarea id='additional' name='additional' cols='40' rows='8' style='display: none;' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articlform.additional.blur()',1); } else saveValue=this.value;\"></textarea></td></tr>";

    echo "<tr id='referencesrow' style='display:none;'><th><label>{$strReferences}<br /><input type='checkbox' name='increferences' onclick=\"if (this.checked) {document.closeform.references.disabled = false; document.closeform.references.style.display=''} else { saveValue=document.closeform.references.value; document.closeform.references.disabled = true; document.closeform.references.style.display='none'}\" disabled='disabled' /></label></th>";
    echo "<td><textarea id='references' name='references' cols='40' rows='8' style='display: none;' onfocus=\"if (this.enabled) { this.value = saveValue; setTimeout('document.articlform.references.blur()',1); } else saveValue=this.value;\"></textarea></td></tr>";

    echo "<tr><th>{$strClosingStatus}</th><td>";
    echo closingstatus_drop_down("closingstatus", 0, TRUE);
    echo " <span class='required'>{$strRequired}</span></td></tr>\n";
    echo "<tr><th>".sprintf($strInformX, $strCustomer)."</th>";
    echo "<td>{$strSendEmailExplainingIncidentClosure}<br />";
    echo "<label><input name='send_email' checked='checked' type='radio' value='no' />{$strNo}</label> ";
    echo "<input name='send_email' type='radio' value='yes' />{$strYes}</td></tr>\n";
    $externalemail = incident_externalemail($id);
    if ($externalemail)
    {
        echo "<tr><th>".sprintf($strInformX, $strExternalEngineer).":<br />";
        printf($strSendEmailExternalIncidentClosure, "<em>{$externalemail}</em>");
        echo "</th>";
        echo "<td class='shade2'><label><input name='send_engineer_email' type='radio' value='no' />{$strNo}</label> ";
        echo "<label><input name='send_engineer_email' type='radio' value='yes' checked='checked' />{$strYes}</label></td></tr>\n";
    }
    plugin_do('incident_closing_form1');
    echo "</table>\n";
    echo "<p align='center'>";
    echo "<input name='type' type='hidden' value='Support' />";
    echo "<input name='id' type='hidden' value='$id' />";
    echo "<input type='hidden' name='process' value='closeincident' />";
    echo "<input name='submit' type='submit' value=\"{$strClose}\" /></p>";
    echo "</form>";
    include (APPLICATION_INCPATH . 'incident_html_bottom.inc.php');
}
else
{
    // External variables
    $closingstatus = clean_int($_POST['closingstatus']);
    $summary = cleanvar($_POST['summary']);
    $id = clean_int($_POST['id']);
    $distribution = cleanvar($_POST['distribution']);
    $solution = cleanvar($_POST['solution']);
    $kbarticle = clean_fixed_list($_POST['kbarticle'], array('no','yes'));
    $kbtitle = cleanvar($_POST['kbtitle']);
    $symptoms = cleanvar($_POST['symptoms']);
    $cause = cleanvar($_POST['cause']);
    $question = cleanvar($_POST['question']);
    $answer = cleanvar($_POST['answer']);
    $workaround = cleanvar($_POST['workaround']);
    $status = cleanvar($_POST['status']);
    $additional = cleanvar($_POST['additional']);
    $references = cleanvar($_POST['references']);
    $wait = cleanvar($_POST['wait']);
    $send_email = cleanvar($_POST['send_email']);
    $send_engineer_email = cleanvar($_POST['send_engineer_email']);

    // Close the incident
    $errors = 0;

    echo "<script src='{$CONFIG['application_webpath']}scripts/webtrack.js' type='text/javascript'></script>\n";

    // check for blank closing status field
    if ($closingstatus == 0)
    {
        $errors = 1;
        $error_string = user_alert(sprintf($strFieldMustNotBeBlank, "'{$strClosingStatus}'"), E_USER_ERROR);
    }

    if ($summary == '' && $solution == '')
    {
        $errors = 1;
        $error_string = user_alert(sprintf($strFieldMustNotBeBlank, "'{$strSummary}' / '$strSolution'"), E_USER_ERROR);
    }

    if ($kbarticle == 'yes' AND $kbtitle == '')
    {
        $errors = 1;
        $error_string = user_alert(sprintf($strFieldMustNotBeBlank, "'{$strTitle}'"), E_USER_ERROR);
    }
    
    plugin_do('pre_incident_closing');

    if ($errors == 0)
    {
        $addition_errors = 0;

        // update incident
        if ($wait == 'yes')
        {
            // mark incident as awaiting closure
            $sql = "SELECT params FROM `{$dbScheduler}` WHERE action = 'CloseIncidents' LIMIT 1";
            $result = mysql_query($sql);
            if (mysql_error())
            {
                trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
                $closure_delay = 554400;
            }
            else
            {
                list($closure_delay) = mysql_fetch_row($result);
            }
            $timeofnextaction = $now + $closure_delay;
            $sql = "UPDATE `{$dbIncidents}` SET status='7', lastupdated='{$now}', timeofnextaction='{$timeofnextaction}' WHERE id='{$id}'";
            $result = mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
        }
        else
        {
            $bill = close_billable_incident($id);
            if (!$bill)
            {
                $addition_errors = 1;
                $addition_errors_string .= "<p class='error'>{$strBilling}: {$strAdditionFail}</p>\n";
            }
            else
            {
                // mark incident as closed
                $sql = "UPDATE `{$dbIncidents}` SET status='2', closingstatus='{$closingstatus}', lastupdated='{$now}', closed='{$now}' WHERE id='{$id}'";
                $result = mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
            }
        }

        if (!$result)
        {
            $addition_errors = 1;
            $addition_errors_string .= "<p class='error'>{$strIncident}: {$strUpdateFailed}</p>\n";
        }

        // add update(s)
        if ($addition_errors == 0)
        {
            $sql = "SELECT owner, status ";
            $sql .= "FROM `{$dbIncidents}` WHERE id = {$id}";
            $result = mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);

            $currentowner = $sit[2];
            $currentstatus = 1;
            if (mysql_num_rows($result) > 0)
            {
                list($currentowner, $currentstatus) = mysql_fetch_row($result);
            }

            if (strlen($summary) > 3)
            {
                // Problem Definition
                $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, currentowner, currentstatus, bodytext, timestamp, customervisibility) ";
                $sql .= "VALUES ('$id', '$sit[2]', 'probdef', '{$currentowner}', '{$currentstatus}', '$summary', '$now', 'hide')";
                $result = mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
            }

            if (strlen($solution) > 3)
            {
                // Final Solution
                $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, currentowner, currentstatus, bodytext, timestamp, customervisibility) ";
                $sql .= "VALUES ('$id', '$sit[2]', 'solution', '{$currentowner}', '{$currentstatus}', '$solution', '$now', 'hide')";
                $result = mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
            }

            // Meet service level 'solution'
            $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, timestamp, currentowner, currentstatus, customervisibility, sla, bodytext) ";
            // $sql .= "VALUES ('$id', '{$sit[2]}', '{$now}', '{$currentstatus}', 'slamet', '$now', '{$sit[2]}', 'show', 'solution')";
            $sql .= "VALUES ('{$id}', '{$sit[2]}', 'slamet', '{$now}', '{$currentowner}', '{$currentstatus}', 'show', 'solution', '')";
            $result = mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);

            //
            if ($wait == 'yes')
            {
                // Update - mark for closure
                $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, currentowner, currentstatus, bodytext, timestamp) ";
                $sql .= "VALUES ('$id', '{$sit[2]}', 'closing', '{$currentowner}', '{$currentstatus}', '" . clean_lang_dbstring($_SESSION['syslang']['strMarkedforclosure']) ."', '$now')";
                $result = mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
            }
            else
            {
                // Update - close immediately
                $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, currentowner, currentstatus, bodytext, timestamp) ";
                $sql .= "VALUES ('$id', '{$sit[2]}', 'closing', '{$currentowner}', '{$currentstatus}', '" . clean_lang_dbstring($_SESSION['syslang']['strIncidentClosed']) . "', '$now')";
                $result = mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
            }

            if (!$result)
            {
                $addition_errors = 1;
                $addition_errors_string .= "<p class='error'>{$strUpdateIncidentFailed}</p>\n";
            }

            //notify related inicdents this has been closed
            $sql = "SELECT distinct (relatedid) AS relateid FROM `{$dbRelatedIncidents}` AS r, `{$dbIncidents}` AS i WHERE incidentid = '$id' ";
            $sql .= "AND i.id = r.relatedid AND i.status != ".STATUS_CLOSED." AND i.status != ".STATUS_CLOSING;
            $result = mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);

            $relatedincidents;

            while ($a = mysql_fetch_object($result))
            {
                $relatedincidents[] = $a->relateid;
            }

            $sql = "SELECT distinct (incidentid) AS relateid FROM `{$dbRelatedIncidents}` AS r, `{$dbIncidents}` AS i WHERE relatedid = '$id' ";
            $sql .= "AND i.id = r.incidentid AND i.status != ".STATUS_CLOSED." AND i.status != ".STATUS_CLOSING;
            $result = mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);

            while ($a = mysql_fetch_object($result))
            {
                $relatedincidents[] = $a->relateid;
            }

            if (is_array($relatedincidents))
            {
                $uniquearray = array_unique($relatedincidents);

                foreach ($uniquearray AS $relatedid)
                {
                    //dont care if I'm related to myself
                    if ($relatedid != $id)
                    {
                        $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, currentowner,currentstatus, bodytext, timestamp) ";
                        $sql .= "VALUES ('$relatedid', '{$sit[2]}', 'research', '{$currentowner}', '{$currentstatus}', 'New Status: [b]Active[/b]<hr>\nRelated incident [$id] has been closed', '$now')";
                        $result = mysql_query($sql);
                        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);

                        $sql = "UPDATE `{$dbIncidents}` SET status = ".STATUS_ACTIVE.", lastupdated = '{$now}', timeofnextaction = '0' ";
                        $sql .= "WHERE id = '{$relatedid}' ";
                        $result = mysql_query($sql);
                        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
                    }
                }
            }
            //tidy up temp reassigns
            $sql = "DELETE FROM `{$dbTempAssigns}` WHERE incidentid = '$id'";
            $result = mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
        }
        $bodytext = "{$SYSLANG['strClosingStatus']}: <b>" . closingstatus_name($closingstatus) . "</b>\n\n" . $bodytext;

        if ($addition_errors == 0)
        {   //maintenceid
            $send_feedback = send_feedback(db_read_column('maintenanceid', $dbIncidents, $id));
            if ($CONFIG['feedback_form'] != '' AND $CONFIG['feedback_form'] > 0 AND $send_feedback == TRUE)
            {
                create_incident_feedback($CONFIG['feedback_form'], $id);
            }

            $notifyexternal = $notifycontact = $awaitingclosure = 0;

            if ($send_engineer_email == 'yes')
            {
                $notifyexternal = 1;
            }

            if ($send_email == 'yes')
            {
                $notifycontact = 1;
                if ($wait=='yes')
                {
                    $awaitingclosure = 1;
                }
                else
                {
                    $awaitingclosure = 0;
                }
            }

            //FIXME move the FALSE->0 hack into triggers
            if (!$send_feedback) $send_feedback = '0';
            trigger('TRIGGER_INCIDENT_CLOSED', array('incidentid' => $incidentid,
                                                     'userid' => $sit[2],
                                                     'notifyexternal' => $notifyexternal,
                                                     'notifycontact' => $notifycontact,
                                                     'awaitingclosure' => $awaitingclosure,
                                                     'sendfeedback' => $send_feedback
                                                    ));

            // Tidy up drafts i.e. delete
            $draft_sql = "DELETE FROM `{$dbDrafts}` WHERE incidentid = {$id}";
            mysql_query($draft_sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);

            // Check for knowledge base stuff, prior to confirming:
            if ($kbarticle == 'yes')
            {
                $sql = "INSERT INTO `{$dbKBArticles}` (doctype, title, distribution, author, published, keywords) VALUES ";
                $sql .= "('1', ";
                $sql .= "'{$kbtitle}', ";
                $sql .= "'{$distribution}', ";
                $sql .= "'".mysql_real_escape_string($sit[2])."', ";
                $sql .= "'".date('Y-m-d H:i:s', mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')))."', ";
                $sql .= "'[$id]') ";
                mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
                $docid = mysql_insert_id();

                // Update the incident to say that a KB article was created, with the KB Article number
                $update = "<b>" . clean_lang_dbstring($_SESSION['syslang']['strKnowledgeBaseArticleCreated']) . ": {$CONFIG['kb_id_prefix']}".leading_zero(4,$docid) . "</b>";
                $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, bodytext, timestamp) ";
                $sql .= "VALUES ('$id', '$sit[2]', 'default', '$update', '$now')";
                $result = mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);


                // Get softwareid from Incident record
                $sql = "SELECT softwareid FROM `{$dbIncidents}` WHERE id='$id'";
                $result=mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
                list($softwareid)=mysql_fetch_row($result);

                if (!empty($_POST['summary'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Summary', '1', '{$summary}', 'public') ";
                if (!empty($_POST['symptoms'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Symptoms', '1', '{$symptoms}', 'public') ";
                if (!empty($_POST['cause'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Cause', '1', '{$cause}', 'public') ";
                if (!empty($_POST['question'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Question', '1', '{$question}', 'public') ";
                if (!empty($_POST['answer'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Answer', '1', '{$answer}', 'public') ";
                if (!empty($_POST['solution'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Solution', '1', '{$solution}', 'public') ";
                if (!empty($_POST['workaround'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Workaround', '1', '{$workaround}', 'public') ";
                if (!empty($_POST['status'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Status', '1', '{$status}', 'public') ";
                if (!empty($_POST['additional'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Additional Information', '1', '{$additional}', 'public') ";
                if (!empty($_POST['references'])) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'References', '1', '{$references}', 'public') ";

                if (count($query) < 1) $query[] = "INSERT INTO `{$dbKBContent}` (docid, ownerid, headerstyle, header, contenttype, content, distribution) VALUES ('$docid', '".mysql_real_escape_string($sit[2])."', 'h1', 'Summary', '1', 'Enter details here...', 'restricted') ";

                foreach ($query AS $sql)
                {
                    mysql_query($sql);
                    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
                }

                // Add Software Record
                if ($softwareid > 0)
                {
                    $sql = "INSERT INTO `{$dbKBSoftware}` (docid,softwareid) VALUES ('{$docid}', '{$softwareid}')";
                    mysql_query($sql);
                    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
                    journal(CFG_LOGGING_NORMAL, 'KB Article Added', "KB Article {$docid} was added", CFG_JOURNAL_KB, $docid);
                }

                //html_redirect("incident_details.php?id={$id}", TRUE, "Knowledge Base Article {$CONFIG['kb_id_prefix']}{$docid} created");
                plugin_do('incident_closing');

                echo "<html>";
                echo "<head></head>";
                echo "<body onload=\"close_page_redirect('incident_details.php?id={$id}');\">";
                echo "</body>";
                echo "</html>";
            }
            else
            {
                plugin_do('incident_closing');

                echo "<html>";
                echo "<head></head>";
                echo "<body onload=\"close_page_redirect('incident_details.php?id={$id}');\">";
                echo "</body>";
                echo "</html>";
            }
        }
        else
        {
            include (APPLICATION_INCPATH . 'incident_html_top.inc.php');
            echo $addition_errors_string;
            include (APPLICATION_INCPATH . 'incident_html_bottom.inc.php');
        }
    }
    else
    {
        include (APPLICATION_INCPATH . 'incident_html_top.inc.php');
        echo $error_string;
        include (APPLICATION_INCPATH . 'incident_html_bottom.inc.php');
    }
}
?>