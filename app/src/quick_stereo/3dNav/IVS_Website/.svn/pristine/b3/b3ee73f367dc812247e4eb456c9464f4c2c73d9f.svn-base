<?php

// Make compatible with older version of PHP (<4.2)
if (!isset($_SERVER) && isset($HTTP_SERVER_VARS)) 	define('_SERVER', 'HTTP_SERVER_VARS');
if (!isset($_POST) 	 && isset($HTTP_POST_VARS)) 	define('_POST', 'HTTP_POST_VARS');
if (!isset($_GET) 	 && isset($HTTP_GET_VARS)) 		define('_GET', 'HTTP_GET_VARS');
if (!isset($_COOKIE) && isset($HTTP_COOKIE_VARS)) 	define('_COOKIE', 'HTTP_COOKIE_VARS');
if (!isset($_FILES)  && isset($HTTP_POST_FILES)) 	define('_FILES', 'HTTP_POST_FILES');

// Set constant to allow execution of clases, libs and pages
define('CONFTOOL', 1);

// Turn on output buffering
ob_start();

// Load basic php settings (usually only necessary on a shared host)
@include('settings.php');

// load configuration and basic functions
$ret = include('conftool.inc.php');
if ($ret===false) {
    echo "<H2 style='background: #ff8'><code>Configuration Error!<br>Please check you Apache / php settings</code></H2>";
    echo "<H3 style='background: #fc8; padding: 12px'><code>'include_path' is '".ini_get('include_path')."'</code></H3>";
    die();
}

// Parse HTTP variables
$http = array();
$path_info = ct_getpathinfo();  // Load variables in path info - an alternative to post and get...
if (is_array($path_info)) {
	while (list($k, $v) = each($path_info)) {
		$http[$k] = $v;
	}
}
if (isset($_GET) && is_array($_GET)) {
	while (list($k, $v) = each($_GET)) {
		$http[$k] = $v;
	}
}
if (isset($_POST) && is_array($_POST)) {
	while (list($k, $v) = each($_POST)) {
		$http[$k] = $v;
	}
}

// Disable magic quotes for database / file access.
set_magic_quotes_runtime(0);

// Enable Magic Quotes for all HTTP variables: Add slashes to all post and get values.
if(!get_magic_quotes_gpc()){
	$http = array_map("ct_deepslash", $http);
    #$_SERVER = array_map("ct_deepslash", $_SERVER);
} else {
  	$_SERVER = array_map("ct_deepunslash", $_SERVER);
}

// Do not add Session ID automatically
ini_set('url_rewriter.tags','');
ini_set('session.use_trans_sid', false);

// Some security settings
ini_set('register_globals','Off');
ini_set('allow_url_fopen','Off');

// Sanitize 'page' variable
$http['page']=(isset($http['page'])?preg_replace("/[^a-zA-Z0-9_\-]/",'',$http['page']):"");


// pages must not be cached
ini_set("session.cache_expire", "");  	// Pages expire immediately - usually useless when nocache is set, but...
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . " GMT");	 // always changed...
header('Expires: Sun, 13 Dec 1998 05:00:00 GMT');			     // always outdated...
// However for a bug in Internet Explorer we need some special treatment for downloads and exports
if ( ! (isset($http['page']) && in_array($http['page'],$ctconf['downloadpages']) &&
		(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") )) {
	session_cache_limiter('nocache');
	header('Pragma: no-cache');     							   // HTTP 1.0
	header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, private');  // HTTP 1.1
} else {
	// Explicitly allow caching for download pages and https
	session_cache_limiter('public');
	header('Pragma: public');	// HTTP 1.0
	header('Cache-Control: cache, must-revalidate');
}
// Code against clickjacking
header("x-frame-options: SAMEORIGIN");

// load standard classes
ct_base_classes();

// initialize session object
// this will redirect to the actual URL if not already in a session
$session = new CTSession();

// Load database adapter (and select database for the conference)
ct_database_adapter();
if ($db->dberror===true) ct_fatal_error();

// send charset header...
if (isset($ctconf['charset']))
	header('Content-Type: text/html; charset='.$ctconf['charset']);
else  // default
    header('Content-Type: text/html; charset=iso-8859-1');

// is conftool offline?
if (isset($ctconf['offline']) && $ctconf['offline']) $http['page']='offline';

// load language file
ct_load_lang();

// retrieve phases when module is loaded
if (!ct_load_phases()) ct_fatal_error();

// check if user is logged in
if ($session->loggedin()) {
	if ($session->get_user()=="") {
		$session->logout();  // User object got lost! Logout and start again...
		exit();
	} else {
		$session->reload_user();
		if (!$session->loggedin()) $session->logout(); // Test again after this reload.
	}
	// die ("logged in!");
	if (!isset($http['page']) || ($http['page'] == "")) {
		ct_redirect(ct_pageurl("index"));
	}
} // if (!$session->loggedin())
else {
	// the following pages do NOT require a login:
	$publicpages = $ctconf['publicpages'];
	if (!isset($http['page']) || !in_array($http['page'],$publicpages)) {
		$http['page']="login";
	}
}

// Print view?
if (!isset($http["print"])) {
    ct_load_page('cthead');
	require('siteheader.inc.php');
	ct_load_page('ctnavbar');
} else {
	ct_load_page('printheader');
}

$session->show_messageboxes();

ct_load_page($http['page']);

if (!isset($http["print"])) {
	// load footer bar
	ct_load_page('ctfootbar');
	require('sitefooter.inc.php');
	ct_load_page('ctfoot');
} else {
	ct_load_page('printfooter');
}
ct_flush();

// Close connection (if not persistent...
$db->disconnect();
// Close session.
session_write_close();

?>