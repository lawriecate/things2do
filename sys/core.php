<?php
if (!defined('LCMS')) exit;

// environment controls
setlocale(LC_ALL, 'en_GB.UTF8');
date_default_timezone_set('Europe/London');

ini_set('error_log',ERROR_FILE);
if(DEBUG==TRUE) {
	error_reporting(-1);
//	ini_set('display_errors', 1);
} else {
	error_reporting(0);
}

// security log
function security_log($msg) {
	if(SECURITY_LOG == TRUE) {
		$appendmsg = "[" . gmdate("d-m-Y H:i:s e") . "] " . $msg . "\n";
		file_put_contents(SECURITY_LOG_FILE, $appendmsg, FILE_APPEND);
	}
}

// error log

function error_log_msg($msg) {
	//if(ERROR_FILE) {
		$appendmsg = "[" . gmdate("d-m-Y H:i:s e") . "] " . $msg . "\n";
		file_put_contents(ERROR_FILE, $appendmsg, FILE_APPEND);
	
}

/*

UTILITY FUNCTIONS

*/

// restrict page access
function restrictAccess() {
	if(isset($_SESSION['user_id'])) {
		// logged in

	} else {
		// force log in
		redirect(ROOT_URL . 'auth/login/?r=' . urlencode(url()) );
	}
}

// boolean logged in function
function isLoggedIn() {
	return isset($_SESSION['user_id']);
}

// generates slug strings
function toAscii($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}

// mysql date format
function mysqlDateTime($phpdate=NULL) {
	if($phpdate == NULL ) {
		$phpdate = time();
	}
	return date( 'Y-m-d H:i:s', $phpdate );
}

// array to html list

function array2ul($array,$additional='') {
	$html = '<ul '.$additional.'>';
	foreach($array as $item) {
		$html.= '<li>' . htmlentities($item) . '</li>';
	}
	$html .= '</ul>';
	return $html;
}

// redirect client
function redirect($url) {
	header('Location: ' . $url);
	return true;
}

// url 
function url() {
	return ROOT_URL . trim($_GET['_url'], '/');
}

// json page
function json_render($array) {
	header('Content-Type: application/json');
	return json_encode($array);
}

// validate things

