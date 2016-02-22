<?php
// ldapv2.inc.php - LDAP function library and defines for SiT -Support Incident Tracker
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010-2012 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Author: Lea Anthony <stonk[at]users.sourceforge.net>
//              Paul heaney <paul[at]sitracker.org - heavily modified to support more directories

// Prevent script from being run directly (ie. it must always be included
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    exit;
}


$ldap_conn = "";

// Defines
define ('LDAP_INVALID_USER',0);
define ('LDAP_USERTYPE_ADMIN',1);
define ('LDAP_USERTYPE_MANAGER',2);
define ('LDAP_USERTYPE_USER',3);
define ('LDAP_USERTYPE_CUSTOMER',4);

// LDAP ATTRIBUTES
define ('LDAP_EDIR_SURNAME', 'sn');
define ('LDAP_EDIR_FORENAMES', 'givenName');
define ('LDAP_EDIR_REALNAME', 'fullName');
define ('LDAP_EDIR_JOBTITLE', 'title');
define ('LDAP_EDIR_EMAIL', 'mail');
define ('LDAP_EDIR_MOBILE', 'mobile');
define ('LDAP_EDIR_TELEPHONE', 'telephoneNumber');
define ('LDAP_EDIR_FAX', 'facsimileTelephoneNumber');
define ('LDAP_EDIR_DESCRIPTION', 'description');
define ('LDAP_EDIR_GRPONUSER', TRUE); // Is group membership contained on the user (more optimal)
define ('LDAP_EDIR_GRPFULLDN', TRUE); // Is the membership stored as a full DN or just the CN? ONLY Used when checking group
define ('LDAP_EDIR_USERATTRIBUTE', 'cn'); // Attribute to locate user with
define ('LDAP_EDIR_USEROBJECTTYPE', 'inetOrgPerson');
define ('LDAP_EDIR_GRPOBJECTTYPE', 'groupOfNames');
define ('LDAP_EDIR_GRPATTRIBUTEUSER', 'groupMembership');  // On user
define ('LDAP_EDIR_GRPATTRIBUTEGRP', 'member');  // On group
define ('LDAP_EDIR_ADDRESS1', 'street');
define ('LDAP_EDIR_CITY', 'physicalDeliveryOfficeName');
define ('LDAP_EDIR_COUNTY', 'st'); // State in the US
define ('LDAP_EDIR_POSTCODE', 'postalCode');
define ('LDAP_EDIR_COURTESYTITLE', 'generationQualifier');
define ('LDAP_EDIR_LOGINDISABLEDATTRIBUTE', 'loginDisabled');
define ('LDAP_EDIR_LOGINDISABLEDVALUE', 'true');  // The value when login is disabled

define ('LDAP_AD_SURNAME', 'sn');
define ('LDAP_AD_FORENAMES', 'givenName');
define ('LDAP_AD_REALNAME', 'displayName');
define ('LDAP_AD_JOBTITLE', 'title');
define ('LDAP_AD_EMAIL', 'mail');
define ('LDAP_AD_MOBILE', 'mobile');
define ('LDAP_AD_TELEPHONE', 'telephoneNumber');
define ('LDAP_AD_FAX', 'facsimileTelephoneNumber');
define ('LDAP_AD_DESCRIPTION', 'description');
define ('LDAP_AD_GRPONUSER', TRUE); // Is group membership contained on the user (more optimal)
define ('LDAP_AD_GRPFULLDN', TRUE); // Is the membership stored as a full DN or just the CN?
define ('LDAP_AD_USERATTRIBUTE', 'sAMAccountName'); // Attribute to locate user with
define ('LDAP_AD_USEROBJECTTYPE', 'user');
define ('LDAP_AD_GRPOBJECTTYPE', 'group');
define ('LDAP_AD_GRPATTRIBUTEUSER', 'memberOf'); // On User
define ('LDAP_AD_GRPATTRIBUTEGRP', 'member');  // On group
define ('LDAP_AD_ADDRESS1', 'streetAddress');
define ('LDAP_AD_CITY', 'l');
define ('LDAP_AD_COUNTY', 'st');
define ('LDAP_AD_POSTCODE', 'postalCode');
define ('LDAP_AD_COURTESYTITLE', 'personalTitle');
/*
 * NOTE: Given the way LoginDisabled works in AD this will only work in a limited number of circumstances
 * It will only work on NORMAL_ACCOUNT + ACCOUNTDISABLED
 * http://support.microsoft.com/kb/305144
 * TODO add support for a mask to handle this
 */
define ('LDAP_AD_LOGINDISABLEDATTRIBUTE', 'userAccountControl');  // CHECK
define ('LDAP_AD_LOGINDISABLEDVALUE', '514');  // This is soley NORMAL_ACCOUNT + ACCOUNTDISABLED

// TODO check
define ('LDAP_OPENLDAP_SURNAME', 'sn');
define ('LDAP_OPENLDAP_FORENAMES', 'givenName');
define ('LDAP_OPENLDAP_REALNAME', 'cn');
define ('LDAP_OPENLDAP_JOBTITLE', 'title');
define ('LDAP_OPENLDAP_EMAIL', 'mail');
define ('LDAP_OPENLDAP_MOBILE', 'mobile');
define ('LDAP_OPENLDAP_TELEPHONE', 'telephoneNumber');
define ('LDAP_OPENLDAP_FAX', 'facsimileTelephoneNumber');
define ('LDAP_OPENLDAP_DESCRIPTION', 'description');
define ('LDAP_OPENLDAP_GRPONUSER', FALSE); // Is group membership contained on the user (more optimal)
define ('LDAP_OPENLDAP_GRPFULLDN', FALSE); // Is the membership stored as a full DN or just the CN?
define ('LDAP_OPENLDAP_USERATTRIBUTE', 'uid'); // Attribute to locate user with
define ('LDAP_OPENLDAP_USEROBJECTTYPE', 'inetOrgPerson');
define ('LDAP_OPENLDAP_GRPOBJECTTYPE', 'posixGroup');
// Not LDAP_OPENLDAP_USERGROUPUSER not present as users dont store groups membership
define ('LDAP_OPENLDAP_GRPATTRIBUTEGRP', 'memberUid'); // On group
define ('LDAP_OPENLDAP_ADDRESS1', 'postalAddress');
define ('LDAP_OPENLDAP_CITY', 'l');
define ('LDAP_OPENLDAP_COUNTY', 'st'); // NOT PRESENT all in one attribute
define ('LDAP_OPENLDAP_POSTCODE', 'postalCode'); // NOT PRESENT all in one attribute
define ('LDAP_OPENLDAP_COURTESYTITLE', 'personalTitle');

/*  You need to uncomment and adjust these values if you intend to use custom mapping
// TODO move these to a config option
define ('LDAP_CUSTOM_SURNAME', 'sn2");
define ('LDAP_CUSTOM_FORENAMES', 'givenName');
define ('LDAP_CUSTOM_REALNAME', 'cn');
define ('LDAP_CUSTOM_JOBTITLE', 'title');
define ('LDAP_CUSTOM_EMAIL', 'mail');
define ('LDAP_CUSTOM_MOBILE', 'mobile');
define ('LDAP_CUSTOM_TELEPHONE', 'telephoneNumber');
define ('LDAP_CUSTOM_FAX', 'facsimileTelephoneNumber');
define ('LDAP_CUSTOM_DESCRIPTION', 'description');
define ('LDAP_CUSTOM_GRPONUSER', FALSE); // Is group membership contained on the user (more optimal)
define ('LDAP_CUSTOM_GRPFULLDN', FALSE); // Is the membership stored as a full DN or just the CN?
define ('LDAP_CUSTOM_USERATTRIBUTE', 'uid'); // Attribute to locate user with
define ('LDAP_CUSTOM_USEROBJECTTYPE', 'inetOrgPerson');
define ('LDAP_CUSTOM_GRPOBJECTTYPE', 'posixGroup');
// Not LDAP_CUSTOM_USERGROUPUSER not present as users dont store groups membership
define ('LDAP_CUSTOM_GRPATTRIBUTEGRP', 'memberUid'); // On group
define ('LDAP_CUSTOM_ADDRESS1', 'postalAddress');
define ('LDAP_CUSTOM_CITY', 'l');
define ('LDAP_CUSTOM_COUNTY', 'st'); // NOT PRESENT all in one attribute
define ('LDAP_CUSTOM_POSTCODE', 'postalCode'); // NOT PRESENT all in one attribute
define ('LDAP_CUSTOM_COURTESYTITLE', 'personalTitle');
*/


$ldap_vars = array("SURNAME", "FORENAMES", "REALNAME", "JOBTITLE", "EMAIL", "MOBILE",
                    "TELEPHONE", "FAX", "DESCRIPTION", "GRPONUSER", "GRPFULLDN", "USERATTRIBUTE",
                    "USEROBJECTTYPE", "GRPOBJECTTYPE", "GRPATTRIBUTEUSER", "GRPATTRIBUTEGRP",
                    "ADDRESS1", "CITY", "COUNTY", "POSTCODE", "COURTESYTITLE", "LOGINDISABLEDATTRIBUTE",
                    "LOGINDISABLEDVALUE");


if ($CONFIG['use_ldap'])
{
    $CONFIG['ldap_type'] = strtoupper($CONFIG['ldap_type']);

    foreach ($ldap_vars AS $var)
    {
        if (defined ("LDAP_{$CONFIG['ldap_type']}_{$var}"))
        {
            $CONFIG[strtolower("ldap_{$var}")] = constant("LDAP_{$CONFIG['ldap_type']}_{$var}");
        }
    }
}


/**
    * Opens a connection to the LDAP host
    * @author Lea Anthony
    * @return the handle of the opened connection
*/
function ldapOpen($host='', $port='', $protocol='', $security='', $user='', $password='')
{
    debug_log("ldapOpen", TRUE);
    global $CONFIG, $ldap_conn;

    if (empty($host)) $host = $CONFIG['ldap_host'];
    if (empty($port)) $port = $CONFIG['ldap_port'];
    if (empty($protocol)) $protocol = $CONFIG['ldap_protocol'];
    if (empty($security)) $security = $CONFIG['ldap_security'];
    if (empty($user)) $user = $CONFIG['ldap_bind_user'];
    if (empty($password)) $password = $CONFIG['ldap_bind_pass'];

    // Use a default port if one isn't specified
    if (empty($port))
    {
        if ($security == 'SSL') $port = '636';
        else $port = '389';
    }

    $toReturn = -1;

    $ldap_url = "ldap://{$host}:{$port}";

    if ($security == 'SSL')
    {
    	$ldap_url = "ldaps://{$host}:{$port}";
    }

    debug_log ("LDAP TYPE: {$CONFIG['ldap_type']}", TRUE);
    debug_log ("LDAP URL: {$ldap_url}", TRUE);
    $ldap_conn = @ldap_connect($ldap_url);


    if ($ldap_conn)
    {
        // Set protocol version
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, $protocol);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS,0);

        if ( $security == 'TLS' )
        {
            // Protocol V3 required for start_tls
            if ( $protocol == 3 )
            {
                if ( !ldap_start_tls($ldap_conn) )
                {
                    trigger_error("Ldap_start_tls failed", E_USER_ERROR);
                }
            }
            else
            {
                trigger_error("LDAP Protocol v3 required for TLS", E_USER_ERROR);
            }
        }

        if ( isset($user) && strlen($user) > 0 )
        {
            $r = @ldap_bind($ldap_conn, $user, $password);
            if ( ! $r )
            {
                // Could not bind!
                trigger_error("Could not bind to LDAP server with credentials '{$user}'", E_USER_WARNING);
            }
            else
            {
            	$toReturn = $ldap_conn;
            }
        }
    }

    return $toReturn;
}


/**
 * @author Paul Heaney
 * @todo TODO document this function
*/
function ldap_storeDetails($password, $id = 0, $user=TRUE, $populateOnly=FALSE, &$ldap_conn, $user_attributes)
{
    global $CONFIG;
    $toReturn = false;

    if ($populateOnly)
    {
        $user_bind = true;
    }
    else
    {
        // Authentocate
        $user_bind = @ldap_bind($ldap_conn, $_SESSION['ldap_user_dn'], $password);
    }

    if (!$user_bind)
    {
        // Auth failed
        debug_log("LDAP Invalid credentials {$_SESSION['ldap_user_dn']}", TRUE);
        $toReturn = false;
    }
    else
    {
        // Sucessfull
        debug_log("LDAP Valid Credentials", TRUE);
        $usertype = LDAP_INVALID_USER;

        if ($CONFIG['ldap_grponuser'])
        {
            if (is_array($user_attributes[$CONFIG['ldap_grpattributeuser']]))
            {
                // Group stored on user
                foreach ($user_attributes[$CONFIG['ldap_grpattributeuser']] AS $group)
                {
                    if ($user)
                    {
                        // User/Staff
                        // NOTE: we dont have to check about overwriting ADMIN type as we break
                        if (strtolower($group) == strtolower($CONFIG['ldap_admin_group']))
                        {
                            $usertype = LDAP_USERTYPE_ADMIN;
                            break;
                        }
                        elseif (strtolower($group) == strtolower($CONFIG['ldap_manager_group']))
                        {
                            $usertype = LDAP_USERTYPE_MANAGER;
                        }
                        elseif (strtolower($group) == strtolower($CONFIG['ldap_user_group']))
                        {
                            if ($usertype != LDAP_USERTYPE_MANAGER) $usertype = LDAP_USERTYPE_USER;
                        }
                    }
                    else
                    {
                        //Customer
                        if (strtolower($group) == strtolower($CONFIG['ldap_customer_group']))
                        {
                            $usertype = LDAP_USERTYPE_CUSTOMER;
                            break;
                        }
                    }
                }
            }
        }
        else
        {
            ldap_close($ldap_conn);
            $ldap_conn = ldapOpen(); // Need to get an admin thread

            if ($CONFIG['ldap_grpfulldn'])
            {
                $filter = "(&(objectClass={$CONFIG['ldap_grpobjecttype']})({$CONFIG['ldap_grpattributegrp']}={$_SESSION['ldap_user_dn']}))";
            }
            else
            {
                $filter = "(&(objectClass={$CONFIG['ldap_grpobjecttype']})({$CONFIG['ldap_grpattributegrp']}={$user_attributes[$CONFIG['ldap_userattribute']][0]}))";
            }


            if ($user)
            {
                debug_log("USER: {$filter}" , TRUE);
                /*
                 * Locate
                 */
                if (ldap_count_entries($ldap_conn, ldap_search($ldap_conn, $CONFIG['ldap_admin_group'], $filter)))
                {
                    $usertype = LDAP_USERTYPE_ADMIN;
                    debug_log("ADMIN", TRUE);
                }
                elseif (ldap_count_entries($ldap_conn, ldap_search($ldap_conn, $CONFIG['ldap_manager_group'], $filter)))
                {
                    $usertype = LDAP_USERTYPE_MANAGER;
                    debug_log("MANAGER", TRUE);
                }
                elseif (ldap_count_entries($ldap_conn, ldap_search($ldap_conn, $CONFIG['ldap_user_group'], $filter)))
                {
                    $usertype = LDAP_USERTYPE_USER;
                    debug_log("USER", TRUE);
                }
                else
                {
                    debug_log("INVALID USER", TRUE);
                }
            }
            else
            {
                // get back customer group
                $result = ldap_search($ldap_conn, $CONFIG['ldap_customer_group'], $filter);
                if (ldap_count_entries($ldap_conn, $result))
                {
                    $usertype = LDAP_USERTYPE_CUSTOMER;
                    debug_log("CUSTOMER", TRUE);
                }
                else
                {
                    debug_log("INVALID CUSTOMER", TRUE);
                }
            }
        }

        if ($usertype != LDAP_INVALID_USER AND $user)
        {
            // get attributes
            $user = new User();
            $user->username = $user_attributes[$CONFIG['ldap_userattribute']][0];
            if ($CONFIG['ldap_cache_passwords']) $user->password = $password;
            $user->realname = $user_attributes[$CONFIG['ldap_realname']][0];
            $user->jobtitle = $user_attributes[$CONFIG['ldap_jobtitle']][0];
            $user->email = $user_attributes[$CONFIG['ldap_email']][0];
            $user->phone = $user_attributes[$CONFIG['ldap_telephone']][0];
            $user->mobile = $user_attributes[$CONFIG['ldap_mobile']][0];
            $user->fax = $user_attributes[$CONFIG['ldap_fax']][0];
            $user->message = $user_attributes[$CONFIG['ldap_description']][0];
            $user->source = 'ldap';

            // TODO FIXME this doesn't take into account custom roles'
            switch ($usertype)
            {
                case LDAP_USERTYPE_ADMIN:
                    $user->roleid =  1;
                    break;
                case LDAP_USERTYPE_MANAGER:
                    $user->roleid = 2;
                    break;
                default:
                    $user->roleid = 3;
            }

            if ($id == 0)
            {
                $user->status = $CONFIG['ldap_default_user_status'];
                $user->holiday_entitlement = $CONFIG['default_entitlement'];
                $status = $user->add();
            }
            else
            {
                // Modify
                $user->id = $id;
                $status = $user->edit();
            }

            if ($status) $toReturn = true;
            else $toReturn = false;
        }
        elseif ($usertype == LDAP_USERTYPE_CUSTOMER AND !$user)
        {
            // Contact
            debug_log("Adding contact TYPE {$usertype} USER {$user}", TRUE);
            debug_log("User attributes: ".print_r($user_attributes, TRUE), TRUE);
            $contact = new Contact();
            $contact->username = $user_attributes[$CONFIG['ldap_userattribute']][0];
            if ($CONFIG['ldap_cache_passwords']) $contact->password = $password;
            $contact->surname = $user_attributes[$CONFIG['ldap_surname']][0];
            $contact->forenames = $user_attributes[$CONFIG['ldap_forenames']][0];
            $contact->jobtitle = $user_attributes[$CONFIG['ldap_jobtitle']][0];
            $contact->email = $user_attributes[$CONFIG['ldap_email']][0];
            $contact->phone = $user_attributes[$CONFIG['ldap_telephone']][0];
            $contact->mobile = $user_attributes[$CONFIG['ldap_mobile']][0];
            $contact->fax = $user_attributes[$CONFIG['ldap_fax']][0];
            $contact->address1 = $user_attributes[$CONFIG['ldap_address1']][0];
            $contact->city = $user_attributes[$CONFIG['ldap_city']][0];
            $contact->county = $user_attributes[$CONFIG['ldap_county']][0];
            $contact->postcode = $user_attributes[$CONFIG['ldap_postcode']][0];
            $contact->courtesytitle = $user_attributes[$CONFIG['ldap_courtesytitle']][0];
            $contact->emailonadd = false;
            $contact->source = 'ldap';

            if ($id == 0)
            {
            	// Set a couple of defaults on first login
                $contact->siteid = $CONFIG['ldap_default_customer_siteid'];
                $status = $contact->add();
            }
            else
            {
                debug_log("MODIFY CONTACT {$id}", TRUE);
                $contact->id = $id;
                $status = $contact->edit();
            }

            if ($status)  $toReturn = true;
            else $toReturn = false;
        }
        else
        {
            $toReturn = false;
        }
    }

    return $toReturn;
}


/**
 * @author Paul Heaney
 * @todo TODO document this function
*/
function ldap_getDetails($username, $searchOnEmail, &$ldap_conn)
{
    global $CONFIG, $ldap_vars;
    $toReturn = false;

    $base = $CONFIG['ldap_user_base'];

    if (strpos($username, ",") != FALSE)
    {
        $filter = "(ObjectClass={$CONFIG['ldap_userobjecttype']})";
        $base = $username;
    }
    else if (!$searchOnEmail)
    {
        $filter = "(&(ObjectClass={$CONFIG['ldap_userobjecttype']})({$CONFIG['ldap_userattribute']}={$username}))";
    }
    else
    {
        $filter = "(&(ObjectClass={$CONFIG['ldap_userobjecttype']})({$CONFIG['ldap_email']}={$username}))";
    }

    foreach ($ldap_vars AS $var)
    {
        $attributes[] = $CONFIG[strtolower("ldap_{$var}")];
    }

    debug_log("LDAP Filter: {$filter}", TRUE);
    debug_log("LDAP Base: {$base}", TRUE);
    $sr = ldap_search($ldap_conn, $base, $filter, $attributes);

    if (ldap_count_entries($ldap_conn, $sr) != 1)
    {
        // Multiple or zero
        debug_log("LDAP unable to locate object: '$username', or multiple matches where found. filter: {$filter}");
        $toReturn = false;
    }
    else
    {
        // just one
        debug_log("LDAP got details for object: '$username'", TRUE);
        $toReturn  = ldap_first_entry($ldap_conn, $sr);
    }

    return $toReturn;
}


/**
    * Authenticate a user
    * If successful and the user is new, the user is created in the database
    * If successful and the user is returning, the user record is resynced
    * @author Lea Anthony and Paul Heaney
    * @param string $username. Username
    * @param string $password. Password
    * @param int $id. The userid or contactid, > 0 if you wish to update, else creates new
    * @param bool $user. True for user, false for customer
    * @return mixed, true if sucessful, false if unsucessful or -1 if connection to LDAP server failed
    * @retval 0 the credentials were wrong or the user was not found.
    * @retval 1 to indicate user is authenticated and allowed to continue.
*/
function authenticateLDAP($username, $password, $id=0, $user=TRUE, $populateOnly=FALSE, $searchOnEmail=FALSE)
{
    debug_log("authenticateLDAP {$username}", TRUE);

    global $CONFIG;

    $toReturn = false;
    $ldap_conn = ldapOpen();

    if ($ldap_conn != -1)
    {
       /*
        * Search for user DN
        * Authenticate
        * Verify roles
        */
        $entry = ldap_getDetails($username, $searchOnEmail, $ldap_conn);

        if (!$entry)
        {
            // Multiple or zero
            debug_log("Unable to locate user in LDAP");
            $toReturn = false;
        }
        else
        {
            // just one
            debug_log("One entry found", TRUE);

            $_SESSION['ldap_user_dn'] = ldap_get_dn($ldap_conn, $entry);
            $user_attributes = ldap_get_attributes($ldap_conn, $entry);

            $toReturn = ldap_storeDetails($password, $id, $user, $populateOnly, $ldap_conn, $user_attributes);
        }
    }
    else
    {
        $toReturn = -1;
    }

    @ldap_close($ldap_conn);

    return $toReturn;
}

/**
    * Gets the details of a contact from the database from their email
    * @author Lea Anthony
    * @param string $email. Email
*/
function getContactDetailsFromDBByEmail($email)
{
    global $dbContacts;

    $sql = "SELECT * FROM `{$dbContacts}` WHERE email='$email'";

    $result = mysql_query($sql);
    if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_ERROR);

    return mysql_fetch_array($result);

}

/**
    * Checks that the email address given is a contact that has not yet
    * been imported into the DB, then imports them.
    * @author Lea Anthony
    * @param string $email. Email
    * @return An array of the user data (if found)
*/
function ldapImportCustomerFromEmail($email)
{
    global $CONFIG;
    $toReturn = false;

    debug_log ("ldapImportCustomerFromEmail {$email}", TRUE);
    if (!empty($email))
    {
        $sql = "SELECT id, username, contact_source FROM `{$GLOBALS['dbContacts']}` WHERE email = '{$email}'";
        debug_log($sql, TRUE);
        $result = mysql_query($sql);
        if (mysql_error()) trigger_error("MySQL Query Error ".mysql_error(), E_USER_WARNING);
        if (mysql_num_rows($result) == 1)
        {
            // Can only deal with the case where one exists, if multiple contacts have the same email address its difficult to deal with
            $obj = mysql_fetch_object($result);

            if ($obj->contact_source == 'sit')
            {
                $toReturn = true;
            }
            elseif ($obj->contact_source == 'ldap')
            {
                if (authenticateLDAP($obj->username, '', $obj->id, false, true, false)) $toReturn = true;
            }
            else
            {
                // Exists but of some other type
                $toReturn = true;
            }
        }
        elseif (mysql_num_rows($result) > 1)
        {
            debug_log ("More than one contact was found in LDAP with this address '{$email}', not importing", TRUE);
            // Contact does exists with these details, just theres more than one of them
            $toReturn = true;
        }
        else
        {
            // Zero found
            if ($CONFIG['use_ldap'])
            {
                // Try and search
                if (authenticateLDAP($email, '', 0, false, true, true)) $toReturn = true;
            }
        }
    }

    return $toReturn;
}


/**
 * Checks if a group exists in LDAP
 * @auther Paul Heaney
 * @param string $dn the DN of the group to check it exists
 * @param string $mapping the LDAP name mapping to use
 * @return bool TRUE for exists, FALSE otherwise
 */
function ldapCheckGroupExists($dn, $mapping)
{
	global $CONFIG, $ldap_vars;
    $toReturn = false;

    $ldap_conn = ldapOpen(); // Need to get an admin thread

    $mapping = strtoupper($mapping);
    // $CONFIG[strtolower("ldap_{$var}")] = constant("LDAP_{$CONFIG['ldap_type']}_{$var}");

    $o = constant("LDAP_{$mapping}_GRPOBJECTTYPE");

    $filter = "(ObjectClass={$o})";

    debug_log("LDAP Filter: {$filter}", TRUE);
    debug_log("LDAP Object: {$dn}", TRUE);
    $sr = ldap_search($ldap_conn, $dn, $filter);

    if (ldap_count_entries($ldap_conn, $sr) != 1)
    {
        // Multiple or zero
        $toReturn = false;
    }
    else
    {
        // just one
        $toReturn  = true;
    }

    return $toReturn;
}

?>
