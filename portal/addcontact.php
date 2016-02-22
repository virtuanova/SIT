<?php
// portal/addcontact.php - Add a site contact
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Author Kieran Hogg <kieran[at]sitracker.org>

require ('..'.DIRECTORY_SEPARATOR.'core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

$accesslevel = 'admin';

include (APPLICATION_LIBPATH . 'portalauth.inc.php');
include (APPLICATION_INCPATH . 'portalheader.inc.php');

if (isset($_POST['submit']))
{
    echo process_add_contact('external');
}

echo show_add_contact($_SESSION['siteid'], 'external');

include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
?>