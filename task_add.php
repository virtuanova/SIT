<?php
// task_add.php - Add a new task
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Authors: Ivan Lucas <ivanlucas[at]users.sourceforge.net>
//          Kieran Hogg <kieran[at]sitracker.org>


$permission=70;

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');
if (!$CONFIG['tasks_enabled'])
{
    header("Location: main.php");
}
$title = $strAddTask;

// External variables
$action = clean_fixed_list($_REQUEST['action'], array('', 'addtask'));
$incident = clean_int($_REQUEST['incident']);

if ($incident)
{
    $sql = "INSERT INTO `{$dbTasks}` (owner, name, priority, distribution, startdate, created, lastupdated) ";
    $sql .= "VALUES('$sit[2]', '".sprintf($strActivityForIncidentX, $incident)."', 1, 'incident', NOW(), NOW(), NOW())";

    mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);

    $taskid = mysql_insert_id();

    $sql = "INSERT INTO `{$dbLinks}` VALUES(4, {$taskid}, {$incident}, 'left', {$sit[2]})";
    mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);

    $sql = "SELECT status FROM `{$dbIncidents}` WHERE id = {$incident}";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);

    if (($obj = mysql_fetch_object(($result))) AND $obj->status != 1 AND $obj->status != 3)
    {
    	$sql = "UPDATE `{$dbIncidents}` SET status = 1, lastupdated = {$now} WHERE id = {$incident}";
        mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);

        $bodytext = "Status: ".incidentstatus_name($obj->status)." -&gt; <b>" . incidentstatus_name(1) . "</b>\n\n" . $srtrTaskStarted;

        $sql = "INSERT INTO `{$dbUpdates}` (incidentid, userid, type, currentowner, currentstatus, bodytext, timestamp) VALUES ";
        $sql .= "({$incident}, {$sit[2]}, 'research', {$sit[2]}, 1, '{$bodytext}', $now)";
        mysql_query($sql);
        if (mysql_error()) trigger_error(mysql_error(), E_USER_ERROR);
    }

    html_redirect("tasks.php?incident={$incident}", TRUE, $strActivityAdded);
}
else
{
    switch ($action)
    {
        case 'addtask':
            // External variables
            $name = cleanvar($_POST['name']);
            $description = cleanvar($_POST['description']);
            $priority = clean_int($_POST['priority']);
            if (!empty($_POST['duedate'])) $duedate = strtotime($_POST['duedate']);
            else $duedate = '';
            if (!empty($_POST['startdate'])) $startdate = strtotime($_POST['startdate']);
            else $startdate = '';
            $completion = clean_int($_POST['completion']);
            $value = clean_float($_POST['value']);
            $distribution = cleanvar($_POST['distribution']);
            $taskuser = clean_int($_POST['taskuser']);
            $starttime = cleanvar($_POST['starttime']);
            $duetime = cleanvar($_POST['duetime']);
            $endtime = cleanvar($_POST['endtime']);

            $_SESSION['formdata']['add_task'] = cleanvar($_POST, TRUE, FALSE, FALSE);

            // Validate input
            $errors = 0;
            if ($name=='')
            {
                $_SESSION['formerrors']['add_task']['name'] = sprintf($strFieldMustNotBeBlank, $strTitle);
                $errors++;
            }

            if ($startdate > $duedate AND $duedate != '' AND $duedate > 0 ) $startdate=$duedate. " ".$duetime;
            if ($errors != 0)
            {
                include (APPLICATION_INCPATH . 'htmlheader.inc.php');
                html_redirect($_SERVER['PHP_SELF'], FALSE);
            }
            else
            {
                if ($startdate > 0) $startdate = date('Y-m-d',$startdate)." ".$starttime;
                else $startdate = '';
                if ($duedate > 0) $duedate = date('Y-m-d',$duedate)." ".$duetime;
                else $duedate='';
                if ($startdate < 1 AND $completion > 0) $startdate = date('Y-m-d H:i:s')." ".$starttime;
                $sql = "INSERT INTO `{$dbTasks}` ";
                $sql .= "(name,description,priority,owner,duedate,startdate,completion,value,distribution,created) ";
                $sql .= "VALUES ('$name','$description','$priority','$taskuser','$duedate','$startdate','$completion','$value','$distribution','".date('Y-m-d H:i:s')."')";
                mysql_query($sql);
                if (mysql_error()) trigger_error(mysql_error(),E_USER_ERROR);
                if (mysql_affected_rows() < 1) trigger_error("Task insert failed",E_USER_ERROR);
                unset($_SESSION['formdata']['add_task']);
                unset($_SESSION['formerrors']['add_task']);
                html_redirect("tasks.php");
            }
            break;

        case '':
        default:
            include (APPLICATION_INCPATH . 'htmlheader.inc.php');
            echo show_form_errors('add_task');
            clear_form_errors('add_task');

            echo "<h2>".icon('task', 32)." ";
            echo "$title</h2>";

            echo "<form id='addtask' action='{$_SERVER['PHP_SELF']}' method='post'>";
            echo "<table class='vertical'>";
            echo "<tr><th>{$strTitle}</th>";
            echo "<td><input class='required' type='text' name='name' ";
            echo "size='35' maxlength='255'";
            if ($_SESSION['formdata']['add_task']['name'] != '')
            {
                echo "value='{$_SESSION['formdata']['add_task']['name']}'";
            }
            echo "/> <span class='required'>{$GLOBALS['strRequired']}</span></td></tr>";

            echo "<tr><th>{$strDescription}</th>";
            echo "<td><textarea name='description' rows='4' cols='30'>";
            if ($_SESSION['formdata']['add_task']['description'] != '')
            {
                echo $_SESSION['formdata']['add_task']['description'];
            }
            echo "</textarea></td></tr>";

            echo "<tr><th>{$strPriority}</th>";
            if ($_SESSION['formdata']['add_task']['priority'] != '')
            {
                echo "<td>".priority_drop_down('priority', $_SESSION['formdata']['add_task']['priority'])."</td></tr>";
            }
            else
            {
                echo "<td>".priority_drop_down('priority',1)."</td></tr>";
            }
            echo "<tr><th>{$strStartDate}</th>";
            echo "<td><input type='text' name='startdate' id='startdate' size='10'";
            if ($_SESSION['formdata']['add_task']['startdate'] != '')
            {
                echo "value='{$_SESSION['formdata']['add_task']['startdate']}'";
            }
            echo "/> ";
            echo date_picker('addtask.startdate');
            echo " ".time_dropdown("starttime", date("H:i"));
            echo "</td></tr>";

            echo "<tr><th>{$strDueDate}</th>";
            echo "<td><input type='text' name='duedate' id='duedate' size='10'";
            if ($_SESSION['formdata']['add_task']['duedate'] != '')
                echo "value='{$_SESSION['formdata']['add_task']['duedate']}'";
            echo "/> ";
            echo date_picker('addtask.duedate');
            if ($_SESSION['formdata']['add_task']['duetime'] != '')
            {
                echo " ".time_dropdown("duetime", $_SESSION['formdata']['add_task']['duetime']);
            }
            else
            {
                echo " ".time_dropdown("duetime");
            }
            echo "</td></tr>";

            echo "<tr><th>{$strCompletion}</th>";
            echo "<td><input type='text' name='completion' size='3' maxlength='3'";;
            if ($_SESSION['formdata']['add_task']['completion'] != '')
            {
                echo "value='{$_SESSION['formdata']['add_task']['completion']}'";
            }
            else
            {
                echo "value='0'";
            }
            echo "/>&#037;</td></tr>";
            //FIXME: should this be available?
            /*echo "<tr><th>{$strEndDate}</th>";
            echo "<td><input type='text' name='enddate' id='enddate' size='10' /> ";
            echo date_picker('addtask.enddate');
            echo " ".time_dropdown("endtime");
            echo "</td></tr>";*/
            echo "<tr><th>{$strValue}</th>";
            echo "<td><input type='text' name='value' size='6' maxlength='12'";
            if ($_SESSION['formdata']['add_task']['value'] != '')
            {
                echo "value='{$_SESSION['formdata']['add_task']['value']}'";
            }
            echo "/></td></tr>";
            echo "<tr><th>{$strUser}</th>";
            echo "<td>";
            if ($_SESSION['formdata']['add_task']['taskuser'] != '')
            {
                echo user_drop_down('taskuser', $_SESSION['formdata']['add_task']['taskuser'], FALSE);
            }
            else
            {
                echo user_drop_down('taskuser', $sit[2], FALSE);
            }
            echo help_link('TaskUser')."</td></tr>";
            echo "<tr><th>{$strPrivacy}".help_link('TaskPrivacy')."</th>";
            echo "<td>";
            if ($_SESSION['formdata']['add_task']['distribution'] == 'public')
            {
                echo "<label><input type='radio' name='distribution' checked='checked'";
                echo " value='public' /> {$strPublic}</label><br />";
                echo "<label><input type='radio' name='distribution' value='private' />";
                echo " {$strPrivate} ";
                echo icon('private', 16, $strPrivate, "{$strPublic}/{$strPrivate}");
                echo "</label></td></tr>";
            }
            else
            {
                echo "<label><input type='radio' name='distribution' value='public' /> {$strPublic}</label><br />";
                echo "<label><input type='radio' name='distribution' checked='checked' value='private' /> {$strPrivate} ";
                echo icon('private', 16, $strPrivate, "{$strPublic}/{$strPrivate}");
                echo "</label></td></tr>";
            }
            echo "</table>";
            echo "<p><input name='submit' type='submit' value='{$strAddTask}' /></p>";
            echo "<input type='hidden' name='action' value='addtask' />";
            echo "</form>";

            //cleanup form vars
            clear_form_data('add_task');
            clear_form_errors('add_site');

            include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
    }
}

?>