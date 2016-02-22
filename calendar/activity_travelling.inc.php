<?php
// activity_travelling.inc.php - Travelling activity information
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Author: Tom Gerrard <tom.gerrard[at]salfordsoftware.co.uk>
//
// Included by timesheet.inc.php

// Prevent script from being run directly (ie. it must always be included
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    exit;
}

$activity_types['Travelling'] = '';

echo "<script type='text/javascript'>
\n//<![CDATA[\n
    function activityTravelling(level)
    {
        $('newactivityalias').value = 'Travelling';
    }

    activityTypes['Travelling'] = activityTravelling;
\n//]]>\n
</script>
";

?>