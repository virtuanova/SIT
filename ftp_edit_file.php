<?php
// ftp_edit_file.php -
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Author: Ivan Lucas <ivanlucas[at]users.sourceforge.net>

// This Page Is Valid XHTML 1.0 Transitional!   4Nov05


$permission=44; // Publish Files to FTP site

require ('core.php');
require (APPLICATION_LIBPATH . 'functions.inc.php');

// This page requires authentication
require (APPLICATION_LIBPATH . 'auth.inc.php');

// External Vars
$id = clean_int($_REQUEST['id']);
$mode = clean_fixed_list($_REQUEST['mode'], array('form','save'));
$title = $strEditFTPdetailsUpload;

if (empty($mode)) $mode='form';

switch ($mode)
{
    case 'form':
        // display file details
        include (APPLICATION_INCPATH . 'htmlheader.inc.php');
        $sql = "SELECT * FROM `{$dbFiles}` WHERE id='{$id}' AND category = 'ftp'";
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
        $obj = mysql_fetch_object($result);

        // calculate filesize
        $j = 0;
        $ext = array($strBytes, $strKBytes, $strMBytes, $strGBytes, $strTBytes);
        $pretty_file_size = $obj->size;
        while ($pretty_file_size >= pow(1024,$j)) ++$j;
        $pretty_file_size = round($pretty_file_size / pow(1024,$j-1) * 100) / 100 . ' ' . $ext[$j-1];

        echo "<h2>{$title}</h2>";
        echo "<form action='{$_SERVER['PHP_SELF']}' method='post'>";
        echo "<table summary='edit file details' align='center' width='60%' class='vertical'>";
        echo "<tr><th>{$strFile}:</th><td>";
        echo "<img src='".getattachmenticon($obj->filename)."' alt='{$obj->filename} ({$pretty_file_size})' border='0' />";
        echo "<strong>{$frow['filename']}</strong> ({$pretty_file_size})</td></tr>";
        if ($obj->path == '')
        {
            $ftp_path = $CONFIG['ftp_path'];
        }
        else
        {
            $ftp_path=$CONFIG['ftp_path'].substr($obj->path,1).'/';
        }

        echo "<tr><th>{$strLocation}:</th><td><a href=\"ftp://{$CONFIG['ftp_hostname']}{$ftp_path}{$obj->filename}\"><code>";
        echo "ftp://{$CONFIG['ftp_hostname']}{$ftp_path}{$obj->filename}</code></a></td></tr>\n";
        echo "<tr><th>{$strTitle}:</th><td>";
        echo "<input type='text' size='40' name='shortdescription' value='{$obj->shortdescription}' />";
        echo "</td></tr>\n";
        echo "<tr><th>{$strCategory}:</th><td>";
        echo "<input type='text' size='40' name='webcategory' value='{$obj->webcategory}' />";
        echo "</td></tr>\n";
        echo "<tr><th>{$strDescription}:</th><td>";
        echo "<textarea rows='6' cols='40' name='longdescription'>{$obj->longdescription}</textarea>";
        echo "</td></tr>\n";
        echo "<tr><th>{$strFileVersion}:</th><td>";
        echo "<input type='text' size='40' name='fileversion' value='{$obj->fileversion}' />";
        echo "</td></tr>\n";
        echo "<tr><th>{$strFileDate}:</th><td>".ldate('D jS M Y @ g:i A',$obj->filedate)." {$strby} ".user_realname($obj->userid,TRUE). "</td></tr>\n";

        if ($obj->expiry>0)
        {
            echo "<tr><th>{$strExpiryDate}</th><td>".ldate('D jS M Y @ g:i A',$obj->expiry)." </td></tr>\n";
        }

        echo "</table>\n\n";
        echo "<input type='hidden' name='id' value='{$id}' />";
        echo "<input type='hidden' name='mode' value='save' />";
        echo "<p align='center'><input type='submit' value='{$strSavePublish}' /></p>";
        echo "</form>";
        include (APPLICATION_INCPATH . 'htmlfooter.inc.php');
    break;

    case 'save':
        $shortdescription = clean_dbstring($_REQUEST['shortdescription']);
        $longdescription = clean_dbstring($_REQUEST['longdescription']);
        $fileversion = clean_dbstring($_REQUEST['fileversion']);
        $webcategory = clean_dbstring($_REQUEST['webcategory']);
        $sql = "UPDATE `{$dbFiles}` SET ";
        $sql .= "shortdescription='{$shortdescription}', longdescription='{$longdescription}', fileversion='{$fileversion}', ";
        $sql .= "webcategory='{$webcategory}', published='yes'";
        $sql .= " WHERE id='{$id}' LIMIT 1";
        mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);
        header("Location: ftp_list_files.php");
        exit;
    break;
}
?>