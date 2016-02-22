<?php
// opensearch.php - A open search plugin for SiT
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Author: Paul Heaney <paulheaney[at]users.sourceforge.net>

// Supports both Firefox2 and IE7



$permission = 0; // not required
require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');
echo "<?xml version=\"1.0\"?>";
echo "<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">";
echo "<ShortName>{$CONFIG['application_shortname']}</ShortName>";
echo "<Description>Search for {$CONFIG['application_shortname']}</Description>";
echo "<Image height=\"16\" width=\"16\" type=\"image/png\">{$CONFIG['application_uriprefix']}{$CONFIG['application_webpath']}images/sit_favicon.png</Image>";
echo "<Url type=\"text/html\" method=\"GET\"  template=\"{$CONFIG['application_uriprefix']}{$CONFIG['application_webpath']}search.php?search_domain=all&amp;search_string={searchTerms}&amp;submit=Search\"/>";
echo "<InputEncoding>UTF-8</InputEncoding>";

echo "<AdultContent>false</AdultContent>";
echo "</OpenSearchDescription>";

?>