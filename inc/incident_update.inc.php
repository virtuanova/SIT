<?php
// update.inc.php - Displays a page for updating the incident log
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Included by ../incident.php

// Prevent script from being run directly (ie. it must always be included
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    exit;
}

$title = $strUpdate;

/**
    * Update page
*/
function display_update_page($draftid=-1)
{
    global $id;
    global $incidentid;
    global $action;
    global $CONFIG;
    global $iconset;
    global $now;
    global $dbDrafts;
    global $sit;

    if ($draftid != -1)
    {
        $draftsql = "SELECT * FROM `{$dbDrafts}` WHERE id = {$draftid}";
        $draftresult = mysql_query($draftsql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
        $draftobj = mysql_fetch_object($draftresult);

        $metadata = explode("|",$draftobj->meta);
    }

    // No update body text detected show update form

    ?>
    <script type="text/javascript">
    <!--
    function deleteOption(object) {
        var Current = object.updatetype.selectedIndex;
        object.updatetype.options[Current] = null;
    }

    function notarget(object)
    {
        // remove last option
        var length = object.updatetype.length;
        if (length > 6)
        {
            object.updatetype.selectedIndex=6;
            var Current = object.updatetype.selectedIndex;
            object.updatetype.options[Current] = null;
        }
        object.priority.value=object.storepriority.value;
        //object.priority.disabled=true;
        object.priority.disabled=false;
        object.updatetype.selectedIndex=0;
        object.updatetype.disabled=false;
    }


    function initialresponse(object)
    {
        // remove last option
        var length = object.updatetype.length;
        if (length > 6)
        {
            object.updatetype.selectedIndex=6;
            var Current = object.updatetype.selectedIndex;
            object.updatetype.options[Current] = null;
        }
        object.priority.value=object.storepriority.value;
        object.priority.disabled=true;
        object.updatetype.selectedIndex=0;
        object.updatetype.disabled=false;
    }


    function actionplan(object)
    {
        // remove last option
        var length = object.updatetype.length;
        if (length > 6)
        {
            object.updatetype.selectedIndex=6;
            var Current = object.updatetype.selectedIndex;
            object.updatetype.options[Current] = null;
        }

        var defaultSelected = true;
        var selected = true;
        var optionName = new Option('Action Plan', 'actionplan', defaultSelected, selected)
        var length = object.updatetype.length;
        object.updatetype.options[length] = optionName;
        object.priority.value=object.storepriority.value;
        object.priority.disabled=true;
        object.updatetype.disabled=true;
    }

    function reprioritise(object)
    {
        // remove last option
        var length = object.updatetype.length;
        if (length > 6)
        {
            object.updatetype.selectedIndex=6;
            var Current = object.updatetype.selectedIndex;
            object.updatetype.options[Current] = null;
        }
        // add new option
        var defaultSelected = true;
        var selected = true;
        var optionName = new Option('Reprioritise', 'solution', defaultSelected, selected)
        var length = object.updatetype.length;
        object.updatetype.options[length] = optionName;
        object.priority.disabled=false;
        document.updateform.priority.disabled=false;
        object.updatetype.disabled=true;
    }

    function probdef(object)
    {
        // remove last option
        var length = object.updatetype.length;
        if (length > 6)
        {
            object.updatetype.selectedIndex=6;
            var Current = object.updatetype.selectedIndex;
            object.updatetype.options[Current] = null;
        }

        var defaultSelected = true;
        var selected = true;
        var optionName = new Option('Problem Definition', 'probdef', defaultSelected, selected)
        var length = object.updatetype.length;
        object.updatetype.options[length] = optionName;
        object.priority.value=object.storepriority.value;
        object.priority.disabled=true;
        object.updatetype.disabled=true;
    }

    function replaceOption(object) {
        var Current = object.updatetype.selectedIndex;
        object.updatetype.options[Current].text = object.currentText.value;
        object.updatetype.options[Current].value = object.currentText.value;
    }

    <?php
        echo "var draftid = {$draftid}";
    ?>

    // Auto save
    function save_content(){
        var xmlhttp=false;

        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
            try {
                xmlhttp = new XMLHttpRequest();
            } catch (e) {
                xmlhttp=false;
            }
        }
        if (!xmlhttp && window.createRequest) {
            try {
                xmlhttp = window.createRequest();
            } catch (e) {
                xmlhttp=false;
            }
        }

        var toPass = $('updatelog').value;
        //alert(toPass.value);

        var meta = $('target').value+"|"+$('updatetype').value+"|"+$('cust_vis').checked+"|";
        meta += $('priority').value+"|"+$('newstatus').value+"|"+$('nextaction').value+"|";

        if (toPass != '')
        {
            xmlhttp.open("GET", "ajaxdata.php?action=auto_save&userid="+<?php echo $_SESSION['userid']; ?>+"&type=update&incidentid="+<?php echo $id; ?>+"&draftid="+draftid+"&meta="+meta+"&content="+escape(toPass), true);

            xmlhttp.onreadystatechange=function() {
                //remove this in the future after testing
                if (xmlhttp.readyState==4) {
                    if (xmlhttp.responseText != ''){
                        //alert(xmlhttp.responseText);
                        if (draftid == -1)
                        {
                            draftid = xmlhttp.responseText;
                            $('draftid').value = draftid;
                        }
                        var currentTime = new Date();
                        var hours = currentTime.getHours();
                        var minutes = currentTime.getMinutes();
                        if (minutes < 10)
                        {
                            minutes = "0"+minutes;
                        }
                        var seconds = currentTime.getSeconds();
                        if (seconds < 10)
                        {
                            seconds = "0"+seconds;
                        }
                        $('updatestr').innerHTML = '<?php echo "<a href=\"javascript:save_content();\">".icon('save', 16, $GLOBALS['strSaveDraft'])."</a> ".icon('info', 16, $GLOBALS['strDraftLastSaved'])." "; ?>' + hours + ':' + minutes + ':' + seconds;
                    }
                }
            }
            xmlhttp.send(null);
        }
    }

    setInterval("save_content()", 10000); //every 10 seconds

    //-->
    </script>
    <?php

    echo show_form_errors('update');
    clear_form_errors('update');

    //echo "<form action='".$_SERVER['PHP_SELF']."?id={$id}&amp;draftid={$draftid}' method='post' name='updateform' id='updateform' enctype='multipart/form-data'>";
    echo "<form action='".$_SERVER['PHP_SELF']."?id={$id}' method='post' name='updateform' id='updateform' enctype='multipart/form-data'>";
    echo "<table class='vertical'>";
    echo "<tr>";
    echo "<th align='right' width='20%;'>{$GLOBALS['strSLATarget']}";
    echo icon('sla', 16)."</th>";
    echo "<td class='shade2'>";
    $target = incident_get_next_target($id);

    $targetNone = '';
    $targetInitialresponse = '';
    $targetProbdef = '';
    $targetActionplan = '';
    $targetSolution = '';

    $typeResearch = '';
    $typeEmailin = '';
    $typeEmailout = '';
    $typePhonecallin = '';
    $typePhonecallout = '';
    $typeExternalinfo = '';
    $typeReviewmet = '';


    if (!empty($metadata))
    {
        switch ($metadata[0])
        {
            case 'none':
                $targetNone = " selected='selected' ";
                break;
            case 'initialresponse':
                $targetInitialresponse = " selected='selected' ";
                break;
            case 'probdef':
                $targetProbdef = " selected='selected' ";
                break;
            case 'actionplan':
                $targetActionplan = " selected='selected' ";
                break;
            case 'solution':
                $targetSolution = " selected='selected' ";
                break;
        }

        switch ($metadata[1])
        {
            case 'research':
                $typeResearch = " selected='selected' ";
                break;
            case 'emailin':
                $typeEmailin = " selected='selected' ";
                break;
            case 'emailout':
                $typeEmailout = " selected='selected' ";
                break;
            case 'phonecallin':
                $typePhonecallin = " selected='selected' ";
                break;
            case 'phonecallout':
                $typePhonecallout = " selected='selected' ";
                break;
            case 'externalinfo':
                $typeExternalinfo = " selected='selected' ";
                break;
            case 'reviewmet':
                $typeReviewmet = " selected='selected' ";
                break;
        }
    }


    echo "<select name='target' id='target' class='dropdown'>\n";
    echo "<option value='none' {$targetNone} onclick='notarget(this.form)'>{$GLOBALS['strNone']}</option>\n";
    switch ($target->type)
    {
        case 'initialresponse':
            echo "<option value='initialresponse' {$targetInitialresponse} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/initialresponse.png); background-repeat: no-repeat;' onclick='initialresponse(this.form)' >{$GLOBALS['strInitialResponse']}</option>\n";
            echo "<option value='probdef' {$targetProbdef} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/probdef.png); background-repeat: no-repeat;' onclick='probdef(this.form)'>{$GLOBALS['strProblemDefinition']}</option>\n";
            echo "<option value='actionplan' {$targetActionplan} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/actionplan.png); background-repeat: no-repeat;' onclick='actionplan(this.form)'>{$GLOBALS['strActionPlan']}</option>\n";
            echo "<option value='solution' {$targetSolution} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/solution.png); background-repeat: no-repeat;' onclick='reprioritise(this.form)'>{$GLOBALS['strResolutionReprioritisation']}</option>\n";
            break;
        case 'probdef':
            echo "<option value='probdef' {$targetProbdef} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/probdef.png); background-repeat: no-repeat;' onclick='probdef(this.form)'>{$GLOBALS['strProblemDefinition']}</option>\n";
            echo "<option value='actionplan' {$targetActionplan} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/actionplan.png); background-repeat: no-repeat;' onclick='actionplan(this.form)'>{$GLOBALS['strActionPlan']}</option>\n";
            echo "<option value='solution' {$targetSolution} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/solution.png); background-repeat: no-repeat;' onclick='reprioritise(this.form)'>{$GLOBALS['strResolutionReprioritisation']}</option>\n";
            break;
        case 'actionplan':
            echo "<option value='actionplan' {$targetActionplan} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/actionplan.png); background-repeat: no-repeat;' onclick='actionplan(this.form)'>{$GLOBALS['strActionPlan']}</option>\n";
            echo "<option value='solution' {$targetSolution} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/solution.png); background-repeat: no-repeat;' onclick='reprioritise(this.form)'>{$GLOBALS['strResolutionReprioritisation']}</option>\n";
            break;
        case 'solution':
            echo "<option value='solution' {$targetSolution} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/solution.png); background-repeat: no-repeat;' onclick='reprioritise(this.form)'>{$GLOBALS['strResolutionReprioritisation']}</option>\n";
            break;
    }
    echo "</select>\n";
    echo "</td></tr>\n";
    echo "<tr><th align='right'>{$GLOBALS['strUpdateType']}</th>";
    echo "<td class='shade1'>";
    echo "<select name='updatetype' id='updatetype' class='dropdown'>";
    /*
    if ($target->type!='actionplan' && $target->type!='solution')
        echo "<option value='probdef'>Problem Definition</option>\n";
    if ($target->type!='solution')
        echo "<option value='actionplan'>Action Plan</option>\n";
    */
    echo "<option value='research' {$typeResearch} selected='selected' style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/research.png); background-repeat: no-repeat;'>{$GLOBALS['strResearchNotes']}</option>\n";
    echo "<option value='emailin' {$typeEmailin} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/emailin.png); background-repeat: no-repeat;'>{$GLOBALS['strEmailFromCustomer']}</option>\n";
    echo "<option value='emailout' {$typeEmailout} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/emailout.png); background-repeat: no-repeat;'>{$GLOBALS['strEmailToCustomer']}</option>\n";
    echo "<option value='phonecallin' {$typePhonecallin} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/callin.png); background-repeat: no-repeat;'>{$GLOBALS['strCallFromCustomer']}</option>\n";
    echo "<option value='phonecallout' {$typePhonecallout} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/callout.png); background-repeat: no-repeat;'>{$GLOBALS['strCallToCustomer']}</option>\n";
    echo "<option value='externalinfo' {$typeExternalinfo} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/externalinfo.png); background-repeat: no-repeat;'>{$GLOBALS['strExternalInfo']}</option>\n";
    echo "<option value='reviewmet' {$typeReviewmet} style='text-indent: 15px; height: 17px; background-image: url({$CONFIG['application_webpath']}images/icons/{$iconset}/16x16/review.png); background-repeat: no-repeat;'>{$GLOBALS['strReview']}</option>\n";

    echo "</select>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th align='right'>{$GLOBALS['strUpdate']}<br />";
    echo "<span class='required'>{$GLOBALS['strRequired']}</span></th>";
    echo "<td class='shade1'>";
    $checkbox = '';
    if (!empty($metadata))
    {
        if ($metadata[2] == "true") $checkbox = "checked='checked'";
    }
    else
    {
        $checkbox = "checked='checked'";
    }
    echo "<label><input type='checkbox' name='cust_vis' id='cust_vis' ";
    echo "{$checkbox} value='yes' /> {$GLOBALS['strMakeVisibleInPortal']}<label><br />";
    echo bbcode_toolbar('updatelog');
    echo "<textarea name='bodytext' id='updatelog' rows='13' cols='50'>";
    if ($draftid != -1) echo $draftobj->content;
    echo "</textarea>";
    echo "<div id='updatestr'><a href='javascript:save_content();'>".icon('save', 16, $GLOBALS['strSaveDraft'])."</a></div>";
    echo "</td></tr>";

    if ($target->type == 'initialresponse')
    {
        $disable_priority = TRUE;
    }
    else $disable_priority = FALSE;
    echo "<tr><th align='right'>{$GLOBALS['strNewPriority']}</th>";
    echo "<td class='shade1'>";

    $maxpriority = servicelevel_maxpriority(incident_service_level($id));

    $setPriorityTo = incident_priority($id);

    if (!empty($metadata))
    {
        $setPriorityTo = $metadata[3];
    }

    echo priority_drop_down("newpriority", $setPriorityTo, $maxpriority, $disable_priority); //id='priority
    echo "</td></tr>\n";

    echo "<tr>";
    echo "<th align='right'>{$GLOBALS['strNewStatus']}</th>";

    $setStatusTo = incident_status($id);

    $disabled = FALSE;

    //we do this so if you update another user's incident, it defaults to active
    if ($sit[2] != incident_owner($incidentid))
    {
        $setStatusTo = '0';
    }
    elseif (!empty($metadata))
    {
        $setStatusTo = $metadata[4];
    }

    echo "<td class='shade1'>".incidentstatus_drop_down("newstatus", $setStatusTo)."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th align='right'>{$GLOBALS['strNextAction']}</th>";

    $nextAction = '';

    if (!empty($metadata))
    {
        $nextAction = $metadata[5];
    }

    echo "<td class='shade2'><input type='text' name='nextaction' ";
    echo "id='nextaction' maxlength='50' size='30' value='{$nextAction}' /></td></tr>";
    echo "<tr>";
    echo "<th align='right'>";
    echo "<strong>{$GLOBALS['strTimeToNextAction']}</strong></th>";
    echo "<td class='shade2'>";
   	echo show_next_action('updateform');
    echo "</td></tr>";
    echo "<tr>";
    // calculate upload filesize
    $att_file_size = readable_file_size($CONFIG['upload_max_filesize']);
    echo "<th align='right'>{$GLOBALS['strAttachFile']}";
    echo " (&lt;{$att_file_size})</th>";

    echo "<td class='shade1'><input type='hidden' name='MAX_FILE_SIZE' value='{$CONFIG['upload_max_filesize']}' />";
    echo "<input type='file' name='attachment' size='40' /></td>";
    echo "</tr>";
    echo "</table>";
    echo "<p class='center'>";
    echo "<input type='hidden' name='action' value='update' />";
    if ($draftid == -1)
    {
        $localdraft = '';
    }
    else
    {
        $localdraft = $draftid;
    }

    echo "<input type='hidden' name='draftid' id='draftid' value='{$localdraft}' />";
    echo "<input type='hidden' name='storepriority' value='".incident_priority($id)."' />";
    echo "<input type='submit' name='submit' value='{$GLOBALS['strUpdateIncident']}' /></p>";
    echo "</form>";
}


if (empty($action))
{
    $sql = "SELECT * FROM `{$dbDrafts}` WHERE type = 'update' AND userid = '{$sit[2]}' AND incidentid = '{$id}'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);

    include ('inc/incident_html_top.inc.php');

    if (mysql_num_rows($result) > 0)
    {
        echo "<h2>{$title}</h2>";

        echo display_drafts('update', $result);

        echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?action=newupdate&amp;id={$id}'>{$strUpdateNewUpdate}</a></p>";
    }
    else
    {
        //No previous updates - just display the page
        display_update_page();
    }
}
else if ($action == "editdraft")
{
    include ('inc/incident_html_top.inc.php');
    $draftid = clean_int($_REQUEST['draftid']);
    display_update_page($draftid);
}
else if ($action == "deletedraft")
{
    $draftid = clean_int($_REQUEST['draftid']);
    if ($draftid != -1)
    {
        $sql = "DELETE FROM `{$dbDrafts}` WHERE id = {$draftid}";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);
    }
    html_redirect("{$_SERVER['PHP_SELF']}.php?id={$id}");
}
else if ($action == "newupdate")
{
    include ('inc/incident_html_top.inc.php');
    display_update_page();
}
else
{
    // Update the incident

    // External variables
    $target = clean_fixed_list($_POST['target'], array('', 'none', 'initialresponse', 'actionplan', 'probdef', 'solution'));
    $updatetype = cleanvar($_POST['updatetype']);
    $newstatus = clean_int($_POST['newstatus']);
    $nextaction = cleanvar($_POST['nextaction']);
    $newpriority = clean_int($_POST['newpriority']);
    $cust_vis = clean_fixed_list($_POST['cust_vis'], array('no', 'yes'));
    $timetonextaction = cleanvar($_POST['timetonextaction']);
    $date = cleanvar($_POST['date']);
    $timeoffset = cleanvar($_POST['timeoffset']);
    $timetonextaction_days = clean_int($_POST['timetonextaction_days']);
    $timetonextaction_hours = clean_int($_POST['timetonextaction_hours']);
    $timetonextaction_minutes = clean_int($_POST['timetonextaction_minutes']);
    $draftid = clean_int($_POST['draftid']);

    // \p{L} A Unicode character
    // \p{N} A Unicode number
    // /u does a unicode search
    if (empty($bodytext) OR
        ((strlen($bodytext) < 4) OR
        !preg_match('/[\p{L}\p{N}]+/u', $bodytext)))
    {
        //FIXME 3.40 make this two errors and i18n for
        $_SESSION['formerrors']['update'][] = sprintf($strFieldMustNotBeBlank, $strUpdate);
        html_redirect($_SERVER['PHP_SELF']."?id={$id}", FALSE);
        exit;
    }

    if (empty($newpriority)) $newpriority  = incident_priority($id);
    // update incident
    switch ($timetonextaction)
    {
        case 'none':
            $timeofnextaction = 0;
            break;
        case 'time':
            if ($timetonextaction_days < 1 && $timetonextaction_hours < 1 && $timetonextaction_minutes < 1)
            {
                $timeofnextaction = 0;
            }
            else
            {
                $timeofnextaction = calculate_time_of_next_action($timetonextaction_days, $timetonextaction_hours, $timetonextaction_minutes);
            }
            break;
        case 'date':
            // kh: parse date from calendar picker, format: 200-12-31
            $date = explode("-", $date);
            $timeofnextaction = mktime(8 + $timeoffset,0,0,clean_int($date[1]),clean_int($date[2]),clean_int($date[0]));
            $now = time();
            if ($timeofnextaction < 0) $timeofnextaction = 0;
            break;
        default:
            $timeofnextaction = 0;
            break;
    }

    // Put text into body of update for field changes (reverse order)
    // delim first
    $bodytext = "<hr>" . $bodytext;
    $oldstatus = incident_status($id);
    $oldtimeofnextaction = incident_timeofnextaction($id);
    if ($newstatus != $oldstatus)
    {
        $bodytext = "Status: ".incidentstatus_name($oldstatus)." -&gt; <b>" . incidentstatus_name($newstatus) . "</b>\n\n" . $bodytext;
    }
    if ($newpriority != incident_priority($id))
    {
        $bodytext = "New Priority: <b>" . priority_name($newpriority) . "</b>\n\n" . $bodytext;
    }
    if ($timeofnextaction > ($oldtimeofnextaction+60))
    {
        $timetext = "Next Action Time: ";
        if (($oldtimeofnextaction - $now) < 1) $timetext .= "None";
        else $timetext .= date("D jS M Y @ g:i A", $oldtimeofnextaction);
        $timetext .= " -&gt; <b>";
        if ($timeofnextaction < 1) $timetext .= "None";
        else $timetext .= date("D jS M Y @ g:i A", $timeofnextaction);
        $timetext .= "</b>\n\n";
        $bodytext = $timetext.$bodytext;
    }

    // attach file - have to do it here to get fileid
    // TODO user file_upload
    $att_max_filesize = return_bytes($CONFIG['upload_max_filesize']);
    if ($_FILES['attachment']['name'] != '')
    {
        $filename = clean_fspath($_FILES['attachment']['name']);
        if ($cust_vis == 'yes')
        {
            $category = 'public';
        }
        else
        {
            $category = 'private';
        }

        $sql = "INSERT INTO `{$dbFiles}`(category, filename, size, userid, usertype, shortdescription, longdescription, filedate) ";
        $sql .= "VALUES ('{$category}', '" . clean_dbstring($filename) . "', '{$_FILES['attachment']['size']}', '{$sit[2]}', 'user', '', '', NOW())";
        mysql_query($sql);
        if (mysql_error())
        {
            trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
        }
        else
        {
            $fileid = mysql_insert_id();
        }
    }

    // was '$attachment'
    if ($_FILES['attachment']['name'] != '' && isset($_FILES['attachment']['name']) == TRUE)
    {
        $bodytext = "{$SYSLANG['strAttachment']}: [[att=$fileid]]" . cleanvar($_FILES['attachment']['name']) . "[[/att]]\n\n".$bodytext;
    }
    // Debug
    ## if ($target!='') $bodytext = "Target: $target\n".$bodytext;

    // Check the updatetype field, if it's blank look at the target
    if (empty($updatetype))
    {
        switch ($target)
        {
            case 'actionplan':
                $updatetype = 'actionplan';
                break;
            case 'probdef':
                $updatetype = 'probdef';
                break;
            case 'solution':
                $updatetype = 'solution';
                break;
            default:
                $updatetype = 'research';
                break;
        }
    }

    // Force reviewmet to be visible
    if ($updatetype == 'reviewmet') $cust_vis = 'yes';

    $owner = incident_owner($id);

    // visible update
    if ($cust_vis == "yes")
    {
        $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, bodytext, timestamp, currentowner, currentstatus, customervisibility, nextaction) ";
        $sql .= "VALUES ('{$id}', '$sit[2]', '$updatetype', '$bodytext', '{$now}', '{$owner}', '{$newstatus}', 'show' , '$nextaction')";
    }
    // invisible update
    else
    {
        $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, bodytext, timestamp, currentowner, currentstatus, nextaction) ";
        $sql .= "VALUES ($id, $sit[2], '$updatetype', '$bodytext', '{$now}', '{$owner}', '{$newstatus}', '$nextaction')";
    }
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
    $updateid = mysql_insert_id();
    trigger('TRIGGER_INCIDENT_UPDATED_INTERNAL', array('incidentid' => $id, 'userid' => $sit[2]));

    //upload file, here because we need updateid
    if ($_FILES['attachment']['name'] != '')
    {
        $delim = DIRECTORY_SEPARATOR;

        // make incident attachment dir if it doesn't exist
        $umask = umask(0000);
        if (!file_exists("{$CONFIG['attachment_fspath']}{$id}{$delim}u{$updateid}"))
        {
            $mk = @mkdir("{$CONFIG['attachment_fspath']}{$id}{$delim}u{$updateid}", 0770, TRUE);
            if (!$mk)
            {
                $sql = "DELETE FROM `{$dbUpdates}` WHERE id='{$updateid}'";
                mysql_query($sql);
                if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
                trigger_error("Failed creating incident attachment directory: {$CONFIG['attachment_fspath']}{$id}{$delim}u{$updateid}", E_USER_WARNING);
            }
        }
        umask($umask);
        $newfilename = "{$CONFIG['attachment_fspath']}{$id}{$delim}u{$updateid}{$delim}" . clean_fspath($_FILES['attachment']['name']);

        // Move the uploaded file from the temp directory into the incidents attachment dir
        $mv = @move_uploaded_file($_FILES['attachment']['tmp_name'], $newfilename);
        if (!$mv)
        {
            $sql = "DELETE FROM `{$dbUpdates}` WHERE id='{$updateid}'";
            mysql_query($sql);
            if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
            trigger_error('!Error: Problem moving attachment from temp directory', E_USER_WARNING);
        }

        // Check file size before attaching
        if ($_FILES['attachment']['size'] > $att_max_filesize)
        {
            trigger_error('User Error: Attachment too large or file upload error', E_USER_WARNING);
            // throwing an error isn't the nicest thing to do for the user but there seems to be no guaranteed
            // way of checking file sizes at the client end before the attachment is uploaded. - INL
        }
        $filename = cleanvar($_FILES['attachment']['name']);
        if ($cust_vis == 'yes')
        {
            $category = 'public';
        }
        else
        {
            $category = 'private';
        }
    }

    //create link
    $sql = "INSERT INTO `{$dbLinks}`(linktype, origcolref, linkcolref, direction, userid) ";
    $sql .= "VALUES(5, '{$updateid}', '{$fileid}', 'left', '{$sit[2]}')";
    mysql_query($sql);
    if (mysql_error())
    {
        trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
    }

    $sql = "UPDATE `{$dbIncidents}` SET status='{$newstatus}', priority='$newpriority', lastupdated='{$now}', timeofnextaction='$timeofnextaction' WHERE id='{$id}'";
    mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);

    // Handle meeting of service level targets
    switch ($target)
    {
        case 'none':
            // do nothing
            $sql = '';
            break;
        case 'initialresponse':
            $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, timestamp, currentowner, currentstatus, customervisibility, sla, bodytext) ";
            $sql .= "VALUES ('{$id}', '{$sit[2]}', 'slamet', '{$now}', '{$owner}', '{$newstatus}', 'show', 'initialresponse','The Initial Response has been made.')";
            break;
        case 'probdef':
            $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, timestamp, currentowner, currentstatus, customervisibility, sla, bodytext) ";
            $sql .= "VALUES ('{$id}', '{$sit[2]}', 'slamet', '{$now}', '{$owner}', '{$newstatus}', 'show', 'probdef','The problem has been defined.')";
            break;
        case 'actionplan':
            $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, timestamp, currentowner, currentstatus, customervisibility, sla, bodytext) ";
            $sql .= "VALUES ('{$id}', '{$sit[2]}', 'slamet', '{$now}', '{$owner}', '{$newstatus}', 'show', 'actionplan','An action plan has been made.')";
           break;
        case 'solution':
            $sql  = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, timestamp, currentowner, currentstatus, customervisibility, sla, bodytext) ";
            $sql .= "VALUES ('{$id}', '{$sit[2]}', 'slamet', '{$now}', '{$owner}', '{$newstatus}', 'show', 'solution','The incident has been resolved or reprioritised.\nThe issue should now be brought to a close or a new problem definition created within the service level.')";
            break;
    }

    if (!empty($sql))
    {
        mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
    }

    if ($target!='none')
    {
        // Reset the slaemail sent column, so that email reminders can be sent if the new sla target goes out
        $sql = "UPDATE `{$dbIncidents}` SET slaemail='0', slanotice='0' WHERE id='{$id}' LIMIT 1";
        mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
    }


    if (!$result)
    {
        include ('inc/incident_html_top.inc.php');
        echo user_alert($strUpdateIncidentFailed, E_USER_WARNING);
        include ('inc/incident_html_bottom.inc.php');
    }
    else
    {
        if ($draftid != -1 AND !empty($draftid))
        {
            $sql = "DELETE FROM `{$dbDrafts}` WHERE id = {$draftid}";
            $result = mysql_query($sql);
            if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);
        }
        journal(CFG_LOGGING_MAX,'Incident Updated', "Incident $id Updated", CFG_JOURNAL_SUPPORT, $id);
        html_redirect("incident_details.php?id={$id}");
    }
}

?>