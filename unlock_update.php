<?php
// unlock_update.php - Unlocks incident updates
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// This page is called from incident_html_top.inc.php


$permission = 42;
require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

// External variables
$incomingid = clean_int($_REQUEST['id']);

if (empty($incomingid)) trigger_error("Incoming ID was not set:{$incomingid}", E_USER_WARNING);

$sql = "UPDATE `{$dbTempIncoming}` SET locked = NULL, lockeduntil = NULL ";
$sql .= "WHERE id='{$incomingid}' AND locked = '{$sit[2]}'";
$result = mysql_query($sql);
if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
else
{
    // FIXME Have temporarily disabled the inbox feature by removing it from the menu for v3.50 release
    // header('Location: inbox.php');
    header('Location: holding_queue.php');
}

?>