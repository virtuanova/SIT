<?php
// site_types.php - Page to list/add/edit site types
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Author: Paul Heaney <paul[at]sitracker.org>
//

$permission = 56; // Add software

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');
// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');
require (APPLICATION_LIBPATH . 'sitform.inc.php');

$title = $strSiteTypes;

include (APPLICATION_INCPATH . 'htmlheader.inc.php');

$mode = clean_fixed_list($_REQUEST['mode'], array('','new','edit'));

if (empty($mode))
{
    echo "<h2>{$strSiteTypes}</h2>";

    $sql = "SELECT * FROM `{$dbSiteTypes}` ORDER BY typename";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
    if (mysql_num_rows($result) > 0)
    {
        echo "<table class='vertical' align='center'>";
        echo "<tr><th>{$strSiteType}</th><th>{$strOperation}</th></tr>";
        $shade = 'shade1';
        while ($obj = mysql_fetch_object($result))
        {
            echo "<tr class='{$shade}'><td>{$obj->typename}</td>";
            echo "<td><a href='{$_SERVER['PHP_SELF']}?mode=edit&amp;typeid={$obj->typeid}'>{$strEdit}</a></td></tr>";

            if ($shade == 'shade1') $shade = 'shade2';
            else $shade = 'shade1';
        }
        echo "</table>";
    }
    else
    {
        echo "<p align='center'>{$strNoRecords}</p>";
    }
    echo "<p align='center'><a href='{$_SERVER['PHP_SELF']}?mode=new'>{$strNewSiteType}</a></p>";
}
elseif ($mode == 'new')
{
    $form = new Form("sitetypes", $strAdd, $dbSiteTypes, "insert", $strNewSiteType);
    $form->setReturnURLFailure($_SERVER['PHP_SELF']);
    $form->setReturnURLSuccess($_SERVER['PHP_SELF']);
    $c1 = new Cell();
    $c1->setIsHeader(TRUE);
    $c1->addComponent(new Label($strSiteType));
    $c2 = new Cell();
    $c2->addComponent(new SingleLineEntry("typename", 10, "typename"));

    $r = new Row();
    $r->addComponent($c1);
    $r->addComponent($c2);
    $form->addRow($r);
    $hr = new HiddenRow();
    $hr->addComponent(new HiddenEntry("mode", "", "new"));
    $form->addRow($hr);

    $form->run();
}
elseif ($mode == 'edit')
{
    $typeid = clean_int($_REQUEST['typeid']);
    $sql = "SELECT typename FROM `{$dbSiteTypes}` WHERE typeid = {$typeid}";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);
    if (mysql_num_rows($result) > 0)
    {
        list($typename) = mysql_fetch_array($result);
    }
    $form = new Form("sitetypes", $strEdit, $dbSiteTypes, "update", $strEditSiteType);
    $form->setReturnURLFailure($_SERVER['PHP_SELF']);
    $form->setReturnURLSuccess($_SERVER['PHP_SELF']);
    $c1 = new Cell();
    $c1->setIsHeader(TRUE);
    $c1->addComponent(new Label($strSiteType));
    $c2 = new Cell();
    $c2->addComponent(new SingleLineEntry("typename", 10, "typename", $typename));

    $r = new Row();
    $r->addComponent($c1);
    $r->addComponent($c2);
    $form->addRow($r);
    $hr = new HiddenRow();
    $hr->addComponent(new HiddenEntry("mode", "", "edit"));
    $hr->addComponent(new HiddenEntry("typeid", "", $typeid));
    $form->addRow($hr);
    $form->setKey("typeid", $typeid);

    $form->run();
}

include (APPLICATION_INCPATH . 'htmlfooter.inc.php');

?>