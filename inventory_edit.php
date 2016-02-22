<?php
// inventory_edit.php - Edit inventory items
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.

$permission = 0;

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');
require (APPLICATION_LIBPATH . 'auth.inc.php');

$title = "$strInventory - $strEdit";

include (APPLICATION_INCPATH . 'htmlheader.inc.php');

if(!$CONFIG['inventory_enabled'])
{
    html_redirect('index.php', FALSE);
    exit;
}

$id = clean_int($_GET['id']);
$siteid = clean_int($_REQUEST['site']);

// if (!empty($_GET['newsite']))
// {
//     $newsite = TRUE;
// }
// else
// {
//     $newsite = FALSE;
//     $siteid = intval($_GET['site']);
// }

if (isset($_POST['submit']))
{
    $active = clean_dbstring($_POST['active']);
    $address = clean_dbstring($_POST['address']);
    $username = clean_dbstring($_POST['username']);
    $password = clean_dbstring($_POST['password']);
    $type = clean_dbstring($_POST['type']);
    $notes = clean_dbstring($_POST['notes']);
    $name = clean_dbstring($_POST['name']);
    $owner = clean_int($_POST['owner']);
    $siteid = clean_int($_POST['site']);
    $identifier = clean_dbstring($_POST['identifier']);
    $privacy = clean_fixed_list($_POST['privacy'], array('none', 'adminonly', 'private'));

    if ($active == 'on')
    {
        $active = 1;
    }
    else
    {
        $active = 0;
    }

    $sql = "UPDATE `{$dbInventory}` ";
    $sql .= "SET address='{$address}', ";
    $sql .= "username='{$username}', ";
    $sql .= "password='{$password}', ";
    $sql .= "type='{$type}', ";
    $sql .= "notes='{$notes}', modified=NOW(), ";
    $sql .= "modifiedby='{$sit[2]}', ";
    $sql .= "name='{$name}', ";
    $sql .= "siteid={$siteid}, ";
    $sql .= "contactid={$owner}, identifier='{$identifier}' ";
    $sql .= ", privacy='{$privacy}' ";


    if (isset($active))
    {
        $sql .= ", active='{$active}' ";
    }
    else
    {
        $sql .= ", active='0' ";
    }

    $sql .= " WHERE id='{$id}'";

    mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    else html_redirect("inventory_site.php?id={$siteid}");
}
else
{
    $sql = "SELECT * FROM `{$dbInventory}` WHERE id='{$id}'";
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
    $row = mysql_fetch_object($result);

    if (($row->privacy == 'private' AND $sit[2] != $row->createdby) OR
            $row->privacy == 'adminonly' AND !user_permission($sit[2], 22))
    {
        html_redirect('inventory.php', FALSE);
        exit;
    }
    echo "<h2>".icon('edit', 32)." {$strEdit}</h2>";

    echo "<form action='{$_SERVER['PHP_SELF']}?id={$id}' method='post'>";

    echo "<table class='vertical' align='center'>";
    echo "<tr><th>{$strName}</th>";
    echo "<td><input class='required' name='name' value='{$row->name}' />";
    echo "<span class='required'>{$strRequired}</span></td></tr>";
    echo "<tr><th>{$strType}</th>";
    echo "<td>".array_drop_down($CONFIG['inventory_types'], 'type', $row->type, '', TRUE)."</td></tr>";

    echo "<tr><th>{$strSite}</th><td>";
    echo site_drop_down('site', $row->siteid, TRUE);
    echo " <span class='required'>{$strRequired}</span></td></tr>";
    echo "<tr><th>{$strOwner}</th><td>";
    echo contact_site_drop_down('owner', $row->contactid);
    echo "</td></tr>";

    echo "<tr><th>{$strID} ".help_link('InventoryID')."</th>";
    echo "<td><input name='identifier' value='{$row->identifier}' /></td></tr>";
    echo "<tr><th>{$strAddress}</th>";
    echo "<td><input name='address' value='{$row->address}' /></td></tr>";

    if (!is_numeric($id)
        OR (($row->privacy == 'adminonly' AND user_permission($sit[2], 22))
            OR ($row->privacy == 'private' AND ($row->createdby == $sit[2])) OR $row->privacy == 'none'))
    {
        echo "<tr><th>{$strUsername}</th>";
        echo "<td><input name='username' value='{$row->username}' /></td></tr>";
        echo "<tr><th>{$strPassword}</th>";
        echo "<td><input name='password' value='{$row->password}' /></td></tr>";
    }

    echo "<tr><th>{$strNotes}</th>";
    echo "<td><textarea rows='15' cols='80' name='notes'>$row->notes</textarea></td></tr>";

    if (($row->privacy == 'adminonly' AND user_permission($sit[2], 22)) OR
        ($row->privacy == 'private' AND $row->createdby == $sit[2]) OR
        $row->privacy == 'none')
    {
        echo "<tr><th>{$strPrivacy} ".help_link('InventoryPrivacy')."</th>";
        echo "<td><input type='radio' name='privacy' value='private' ";
        if ($row->privacy == 'private')
        {
            echo " checked='checked' ";
            $selected = TRUE;
        }
        echo "/>{$strPrivate}<br />";

        echo "<input type='radio' name='privacy' value='adminonly'";
        if ($row->privacy == 'adminonly')
        {
            echo " checked='checked' ";
            $selected = TRUE;
        }
        echo "/>";
        echo "{$strAdminOnly}<br />";

        echo "<input type='radio' name='privacy' value='none'";
        if (!$selected)
        {
            echo " checked='checked' ";
        }
        echo "/>";
        echo "{$strNone}<br />";
    }

    echo "</td></tr>";

    echo "<tr><th>{$strActive}</th>";
    echo "<td><input type='checkbox' name='active' ";
    if ($row->active == '1')
    {
        echo "checked = 'checked' ";
    }
    echo "/>";

    echo "</td></tr>";
    echo "</table>";
    echo "<p align='center'>";
    echo "<input name='submit' type='submit' value='{$strUpdate}' /></p>";
    echo "</form>";
    echo "<p align='center'>";

    echo "<a href='inventory_site.php?id={$row->siteid}'>{$strBackToList}</a>";

    echo "</p>";

    include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
}

?>