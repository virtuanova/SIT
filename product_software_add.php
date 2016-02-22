<?php
// product_software_add.php - Associates software with a product
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Author: Ivan Lucas <ivanlucas[at]users.sourceforge.net>

// This Page Is Valid XHTML 1.0 Transitional!  11Oct06


$permission = 24;  // Add Product
require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

// External variables
$action = clean_fixed_list($_REQUEST['action'], array('showform','add'));
$productid = clean_int($_REQUEST['productid']);
$softwareid = clean_int($_REQUEST['softwareid']);
$context = cleanvar($_REQUEST['context']);
$return = clean_fixed_list($_REQUEST['return'], array('false','true'));

if (empty($action) OR $action == "showform")
{
    $title = $strAddLink;
    include (APPLICATION_INCPATH . 'htmlheader.inc.php');
    echo "<h2>{$title}</h2>";
    echo "<form action='{$_SERVER['PHP_SELF']}?action=add' method='post'>\n";
    echo "<input type='hidden' name='context' value='{$context}' />\n";

    if (empty($productid))
    {
        if (!empty($softwareid))
        {
            $name = db_read_column('name', $dbSoftware, $softwareid);
            echo "<h3>".icon('skill',16)." ";
            echo "{$strSkill}: $name</h3>";
        }
        echo "<input name=\"softwareid\" type=\"hidden\" value=\"$softwareid\" />\n";
        echo "<p align='center'>{$strProduct}: ".icon('product', 16)." ";
        echo product_drop_down("productid", 0);
        echo "</p>";
    }
    else
    {
        $sql = "SELECT name FROM `{$dbProducts}` WHERE id='$productid' ";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);

        list($product) = mysql_fetch_row($result);
        echo "<h3>{$strProduct}: $product</h3>";
        echo "<input name=\"productid\" type=\"hidden\" value=\"$productid\" />\n";
    }
    if (empty($softwareid))
    {
        echo "<p align='center'>{$strSkill}: ".icon('skill', 16)." ";
        echo skill_drop_down("softwareid", 0);
        echo "</p>\n";
    }
    echo "<p align='center'><input name='submit' type='submit' value='{$strSave}' />";
    echo "<input type='checkbox' name='return' value='true' ";
    if ($return == 'true') echo "checked='checked' ";
    echo "/> {$strReturnAfterSaving}</p>\n";
    echo "</form>";

    echo "<p align='center'><a href='products.php?productid={$productid}'>{$strReturnWithoutSaving}</a></p>";
    include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
}
elseif ($action == "add")
{
    $errors = 0;
    // check for blank
    if ($productid == 0)
    {
        $errors = 1;
        $errors_string .= "<p class='error'>".sprintf($strSelectionXmustNotBeEmpty, $strProduct)."</p>\n";
    }
    // check for blank software id
    if ($softwareid == 0)
    {
        $errors = 1;
        $errors_string .= "<p class='error'>".sprintf($strSelectionXmustNotBeEmpty, $strSkill)."</p>\n";
    }

    // add record if no errors
    if ($errors == 0)
    {
        // First have a look if we already have this link
        $sql = "SELECT productid FROM `{$dbSoftwareProducts}` WHERE productid='$productid' AND softwareid='$softwareid'";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
        if (mysql_num_rows($result) >= 1)
        {
            html_redirect("product_software_add.php?productid={$productid}&return=$return", FALSE, $strAvoidDupes);
            // TODO $strAvoidDupes isn't the perfect string to use here, replace with something better when
            // we have a message about duplicates.
            exit;
        }

        $sql  = "INSERT INTO `{$dbSoftwareProducts}` (productid, softwareid) VALUES ($productid, $softwareid)";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);

        // show error message if addition failed
        if (!$result)
        {
            include (APPLICATION_INCPATH . 'htmlheader.inc.php');
            trigger_error("Addition of skill/product failed: {$sql}", E_USER_WARNING);
            include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
        }
        // update db and show success message
        else
        {
            journal(CFG_LOGGING_NORMAL, 'Product Added', "Skill $softwareid was added to product $productid", CFG_JOURNAL_PRODUCTS, $productid);
            if ($return=='true') html_redirect("product_software_add.php?productid={$productid}&return=true");
            else html_redirect("products.php?productid={$productid}");
        }
    }
    else
    {
        // show error message if errors
        include (APPLICATION_INCPATH . 'htmlheader.inc.php');
        echo $errors_string;
        include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
    }
}
?>