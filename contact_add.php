<?php
// contact_add.php - Adds a new contact
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// This Page Is Valid XHTML 1.0 Transitional!  31Oct05


$permission = 1; // Add new contact

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');
// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

// External variables
$siteid = clean_int($_REQUEST['siteid']);
$submit = cleanvar($_REQUEST['submit']);
$title = $strNewContact;

if (empty($submit) OR !empty($_SESSION['formerrors']['add_contact']))
{
    include (APPLICATION_INCPATH . 'htmlheader.inc.php');
    echo show_add_contact($siteid, 'internal');
    include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
}
else
{
    echo process_add_contact();
}
?>