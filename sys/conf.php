<?php
if (!defined('LCMS')) exit;

// access
define("ROOT_URL", "http://141.163.232.95:8888/www/");

//define("ROOT_URL", "http://192.168.1.14:8888/www/");

// debug
define("SYSEMAIL","web@example.com"); // email to send any warnings to
define("DEBUG",TRUE); // set to TRUE to display errors
define("ERROR_FILE","sys/errors.log"); // sets the application PHP error log file

// security
define("SECURITY_LOG",TRUE); // if true user actions will be recorded in a file
define("SECURITY_LOG_FILE","sys/security.log");

// database details
define("DB_HOST","localhost");
define("DB_NAME","things2do");
define("DB_USER","root");
define("DB_PASS","root");
?>