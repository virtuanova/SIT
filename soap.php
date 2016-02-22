<?php
// soap.php - SOAP interface to SiT!
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Author: Paul Heaney <paul[at]sitracker.org>

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

require (APPLICATION_LIBPATH . DIRECTORY_SEPARATOR . 'nusoap' . DIRECTORY_SEPARATOR . 'nusoap.php');

if ($CONFIG['soap_enabled'])
{
    $soap_namespace = 'http://sitracker.org';
    $server = new soap_server();
    $server->configureWSDL('sitsoap', $soap_namespace);

// Commented out by Ivan 3 June 2009
//    require (APPLICATION_LIBPATH . 'soap_core.inc.php');
//    require (APPLICATION_LIBPATH . 'soap_incidents.inc.php');

    $server->service(isset($HTTP_RAW_POST_DATA) ?  $HTTP_RAW_POST_DATA : '');
}
else
{
    // Return an error -- FIXME better error wouldn't go amiss
    trigger_error('SOAP is not enabled for this instance of SiT!', E_USER_ERROR);
}



?>