<?php
// dashboard_holidays.php - List of who's away today
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.

$PLUGININFO['dashboard_holidays']['version'] = 1.00;
$PLUGININFO['dashboard_holidays']['description'] = 'Shows users who are away';
$PLUGININFO['dashboard_holidays']['author'] = 'SiT! Developers';
$PLUGININFO['dashboard_holidays']['legal'] = 'GPL';
$PLUGININFO['dashboard_holidays']['sitminversion'] = 3.45;
$PLUGININFO['dashboard_holidays']['sitmaxversion'] = 3.69;


function dashboard_holidays($dashletid)
{
    global $sit, $CONFIG, $iconset;
    global $dbUsers;
    $user = $sit[2];
    echo "<div class='windowbox' style='width: 95%;' id='$dashletid'>";
    echo "<div class='windowtitle'>".icon('holiday', 16)." {$GLOBALS['strWhosAwayToday']}</div>";
    echo "<div class='window'>";
    $sql  = "SELECT * FROM `{$dbUsers}` WHERE status!=0 AND status!=1 ";  // status=0 means left company
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    if (mysql_num_rows($result) >=1)
    {
        while ($users = mysql_fetch_array($result))
        {
            $title = userstatus_name($users['status']);
            $title.=" - ";
            if ($users['accepting'] == 'Yes') $title .= "{$GLOBALS['strAcceptingIncidents']}";
            else $title .= "{$GLOBALS['strNotAcceptingIncidents']}";
            if (!empty($users['message'])) $title.= "\n(".$users['message'].")";

            echo "<strong>{$users['realname']}</strong>, $title";
            echo "<br />";
        }
    }
    else echo "<p align='center'>{$GLOBALS['strNobody']}</p>\n";
    echo "</div></div></div>";
}

function dashboard_holidays_get_version()
{
    global $PLUGININFO;

    return $PLUGININFO['dashboard_holidays']['version'];
}



?>