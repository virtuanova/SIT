<?php
// dashboard_statistics.php - Display summary statistics on the dashboard
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Authors: Paul Heaney <paulheaney[at]users.sourceforge.net>
//          Ivan Lucas <ivanlucas[at]users.sourceforge.net>

$PLUGININFO['dashboard_statistics']['version'] = 1;
$PLUGININFO['dashboard_statistics']['description'] = 'Displays some database statistics';
$PLUGININFO['dashboard_statistics']['author'] = 'SiT! Developers';
$PLUGININFO['dashboard_statistics']['legal'] = 'GPL';
$PLUGININFO['dashboard_statistics']['sitminversion'] = 3.45;
$PLUGININFO['dashboard_statistics']['sitmaxversion'] = 3.69;

$dashboard_statistics_version = $PLUGININFO['dashboard_statistics']['version'];

function dashboard_statistics($dashletid)
{
    echo dashlet('statistics', $dashletid, icon('statistics', 16), $GLOBALS['strTodaysStats'], 'statistics.php', $content);
}


function dashboard_statistics_display()
{
    global $todayrecent, $dbIncidents, $dbKBArticles, $iconset;

    // Count incidents logged today
    $sql = "SELECT id FROM `{$dbIncidents}` WHERE opened > '$todayrecent'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    $todaysincidents=mysql_num_rows($result);
    mysql_free_result($result);

    // Count incidents updated today
    $sql = "SELECT id FROM `{$dbIncidents}` WHERE lastupdated > '$todayrecent'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    $todaysupdated=mysql_num_rows($result);
    mysql_free_result($result);

    // Count incidents closed today
    $sql = "SELECT id FROM `{$dbIncidents}` WHERE closed > '$todayrecent'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    $todaysclosed=mysql_num_rows($result);
    mysql_free_result($result);

    // count total number of SUPPORT incidents that are open at this time (not closed)
    $sql = "SELECT id FROM `{$dbIncidents}` WHERE status!=2 AND status!=9 AND status!=7 AND type='support'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    $supportopen=mysql_num_rows($result);
    mysql_free_result($result);

    // Count kb articles published today
    $sql = "SELECT docid FROM `{$dbKBArticles}` WHERE published > '".date('Y-m-d')."'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    $kbpublished=mysql_num_rows($result);
    mysql_free_result($result);
    echo "<strong><a href='statistics.php'>{$GLOBALS['strIncidents']}</a></strong><br />";
    echo "{$todaysincidents} {$GLOBALS['strLogged']}</a><br />";
    echo "{$todaysupdated} {$GLOBALS['strUpdated']}<br />";
    echo "{$todaysclosed} {$GLOBALS['strClosed']}<br />";
    echo "{$supportopen} {$GLOBALS['strCurrentlyOpen']}<br />";

    echo "<br /><strong><a href='kb.php?mode=today'>";
    echo "{$GLOBALS['strKnowledgeBaseArticles']}</a></strong><br />";
    echo "{$kbpublished} {$GLOBALS['strPublishedToday']}</a><br />";
}

function dashboard_statistics_get_version()
{
    global $dashboard_statistics_version;
    return $dashboard_statistics_version;
}


?>