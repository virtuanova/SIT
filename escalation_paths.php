<?php
// escalation_paths.php - List escalation paths
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Author: Ivan Lucas <ivanlucas[at]users.sourceforge.net>

//// This Page Is Valid XHTML 1.0 Transitional!  (7 Oct 2006)


$permission = 64; // Manage escalation paths

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

$title = $strEscalationPaths;

include (APPLICATION_INCPATH . 'htmlheader.inc.php');
echo "<h2>".icon('escalation', 32, $strEscalationPaths)." {$title}</h2>";

$sql = "SELECT * FROM `{$dbEscalationPaths}` ORDER BY name";
$result = mysql_query($sql);
if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
if (mysql_num_rows($result) >= 1)
{
    echo "<table align='center'>";
    echo "<tr>";
    echo colheader('name',$strName);
    echo colheader('track_url',$strTrackURL);
    echo colheader('home_url',$strHomeURL);
    echo colheader('url_title',$strURLTitle);
    echo colheader('email_domain',$strEmailDomain);
    echo colheader('edit',$strOperation);
    echo "</tr>";
    while ($path = mysql_fetch_object($result))
    {
        echo "<tr>";
        echo "<td>{$path->name}</td>";
        echo "<td>{$path->track_url}</td>";
        echo "<td>{$path->home_url}</td>";
        echo "<td>{$path->url_title}</td>";
        echo "<td>{$path->email_domain}</td>";
        echo "<td><a href='edit_escalation_path.php?id={$path->id}'>{$strEdit}</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
else echo "<p align='center'>{$strNoRecords}</p>";

echo "<p align='center'><a href='escalation_path_add.php'>{$strAdd}</a></p>";

include (APPLICATION_INCPATH . 'htmlfooter.inc.php');

?>