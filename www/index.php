<?php

// define LCMS so that included files can test they're not being run independently
define("LCMS",TRUE);
// First let's define our apps root directory
define("LCMS_ROOT_DIR", realpath(dirname(__FILE__) . '/../') . '/');
chdir(LCMS_ROOT_DIR);

// load settings
require('sys/conf.php');

// include libraries
require('sys/core.php');
require('sys/database.class.php');
//require('sys/auth.class.php');
require('sys/facebook/facebook.php');
require('sys/fbauth.class.php');
require('sys/magicpage.class.php');
require('sys/template.class.php');
require('sys/runtime.php');
require('sys/routing.php');

dispatch();


?>