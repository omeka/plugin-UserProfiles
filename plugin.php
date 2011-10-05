<?php

define('USER_PROFILES_DIR', dirname(__FILE__) );
require_once USER_PROFILES_DIR . '/helpers/functions.php';
require_once(USER_PROFILES_DIR . '/UserProfilesPlugin.php');
require_once(USER_PROFILES_DIR . '/OwnershipAclAssertion.php');
new UserProfilesPlugin;
