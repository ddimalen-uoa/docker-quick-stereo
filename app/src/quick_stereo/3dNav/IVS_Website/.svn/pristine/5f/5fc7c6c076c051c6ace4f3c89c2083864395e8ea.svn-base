<?php
// ------------------------------------------------------------------------
// ConfTool - Your Conference Management Tool
// ------------------------------------------------------------------------
// (c) 2001-2011 by Harald Weinreich, Hamburg, Germany
// ------------------------------------------------------------------------
//
// This file loads configuration and contains some
// bootstrapping code for loading further classes and
// functions from other files.
//

if (!defined('CONFTOOL')) die('Hacking attempt!');
global $ctconf;
$ctconf = array(); // Reset configuration array.

require('conftool.conf.php');

// YOU MUST NOT CHANGE THE FOLLOWING LINE!
$ctconf['name'] = 'VSIS ConfTool Standard - Conference Management Tool';

// If you enhance, fix or update the system, please send me your
// changes at: info@conftool.net
$ctconf['version'] = '1.7.20';


// These pages may be cached in the browser:
$ctconf['downloadpages'] = array("downloadPaper","adminExport");
// Pages that may be accessed without login:
$ctconf['publicpages']   = array('login','register',
								'sendUsername','sendPassword',
								'imprint','offline');

// Get starting time of script! Used for some timeouts, e.g. in mail.lib
$ct_starttime=ct_get_microtime();

// -------------------------------------------
// Basic functions of ConfTool Pro
// -------------------------------------------

/**
 * returns a path from configuration. Relative paths are prepended by the prefix.
 */
function ct_get_path($idx) {
	global $ctconf;

	// Return relative or absolute path.
	return (substr($ctconf['paths/'.$idx], 0, 1) == '/') ?
		$ctconf['paths/'.$idx] :
		$ctconf['paths/prefix'] . $ctconf['paths/'.$idx];
}

/**
 * class to avoid the original "include_oncs", as it is said to be slow.
 */
function ct_include_once($file){
	global $ct_included_files;
	if (!isset($ct_included_files)) $ct_included_files=array();	// initialize
    if (isset($ct_included_files[$file])) return true;  // test if file was already included.
	$ct_included_files[$file] = true; // store that file was included.
	return include($file);	// Now include the file
}


/**
* Test if a library exists
* @param $lib file name of library (with extension)
*/
function ct_test_lib($lib) {
	$etcpath = ct_get_path('etc');
	$libpath = ct_get_path('lib');
	return (file_exists($etcpath.$lib.'.php') || file_exists($libpath.$lib.'.php'));
}

/**
* @return formatted date "13.12.2010" (set in conftool.conf.php)
* @param $date any - either mysql date as String "2010-12-31 10:15:33" OR a unix timestamp as integer.
* @desc Create a readable formatted date from a mysql date format.
*/
function ct_date_format($date){
    global $ctconf,$session;
    if (!isset($date) || !$date) return "--";       // is parameter set?
    $t=-1;
    if (is_string($date)) $t=ct_datetime_2_timestamp($date);        // is it a string => create timestamp
    if (is_int($date)) $t=$date;    // is it an integer => use as timestamp
	$php_version = str_replace('.','',(substr(phpversion(),0,5)));
	if ($t<=0 && $php_version<510) return "--";		// legal value?
    $f=$ctconf['dateformat'];
    if (!isset($f) || $f=='') $f='d/M/Y';
    $d=date($f,$t);
    $d=str_replace(" ","&nbsp;",$d);
    return $d;
}


/**
* @return formatted date & time like "13.12.2010 15:32:11" (set in conftool.conf.php)
* @param $date mysql date as String "2010-12-31 10:15:33" OR a unix timestamp as integer.
* @desc Create a readable formatted date and time from a mysql date format.
*/
function ct_datetime_format($date,$html_encode=true){
       global $ctconf;
       if (!isset($date) || !$date) return "--";
       $t=-1;
       if (is_string($date)) $t=ct_datetime_2_timestamp($date);        // is it a string => create timestamp
       if (is_int($date)) $t=$date;    // is it an integer => use as timestamp
       if ($t<=0) return "--";         // legal value?
       $f=$ctconf['datetimeformat'];
       if (!isset($f) || $f=='') $f='d/M/Y H:i:s';
       $d=date($f,$t);
       $php_months=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
       for ($i=0; $i<=11; $i++) {
               if (strstr($d,$php_months[$i].' ') || strstr($d,$php_months[$i].',')) {
                       $d = str_replace($php_months[$i], ct_substr(ct('S_DATE_MON_'.($i+1)),0,3), $d);
                       break;
               }
       }
       if ($html_encode)
               $d=str_replace(" ","&nbsp;",$d);
       return $d;
}



/**
* load a library
* @param $lib file name of library (with extension)
*/
function ct_load_lib($lib) {
	// First try "etc"
	$etcpath = ct_get_path('etc');
	if (file_exists($etcpath.$lib.'.php')) {
		ct_include_once($etcpath.$lib.'.php');
		return true;
	}
	// Then try "lib"
	$libpath = ct_get_path('lib');
	$ret = ct_include_once($libpath.$lib.'.php');
	if ($ret===false) {
        ct_error_log("Error: Library $libpath$lib could not be loaded!",0);
		if (!file_exists($libpath.$lib)) {
		    echo "<H2 style='background: #ff8'><code>Configuration Error!<br>Please check you settings in etc/conftool.conf.php</code></H2>"; die();
		} else {
		    echo "<H2 style='background: #ff8'><code>Syntax error in ConfTool library: ".$lib."</H2>"; die();
		}
	}
	return false;
}


/**
* Test if a class does exists
* @param $class name of class (without)
*/
function ct_test_class($class) {
	$classpath = ct_get_path('classes');
	$etcpath = ct_get_path('etc');
	return (file_exists($classpath.$class.'.ctcls.php') || file_exists($etcpath.$class.'.ctcls.php'));
}

/**
* load a class definition.
* @param $class class name (without file extension).
*/
function ct_load_class($class) {
	global $ctconf;

	$path = ct_get_path('etc');
	if (!file_exists($path.$class.'.ctcls.php')) {
		$path = ct_get_path('classes');
		if (!file_exists($path.$class.'.ctcls.php')) {
            ct_error_log("Error: Class $path$class could not be loaded!",0);
		    echo "<H2 style='background: #ff8'><code>Configuration Error!<br>Class $class could not be loaded. Please check you settings in etc/conftool.conf.php</code></H2>";
		    echo "<H3><code>\$ctconf['paths/prefix'] is '".$ctconf['paths/prefix']."'</code></H3>"; die();
		}
	}
	$ret = ct_include_once($path.$class.'.ctcls.php');
	if ($ret===false) {
        ct_error_log("Error: Syntax error in class $path$class !",0);
	    echo "<H2 style='background: #ff8'><code>Syntax error in ConfTool class: ".$class.'.ctcls'."</H2>"; die();
	}
}


/**
 * test if a page definition exists
 */
function ct_test_page($page) {
	// Security: Allow only alphanumeric characters for the path name, remove the rest!
	$page = preg_replace("/[^a-zA-Z0-9_-]/","",$page);
	#$page = preg_replace("/\W+/","",$page);
	// First try "etc"
	$etcpath = ct_get_path('etc');
	if (file_exists($etcpath.$page.'.page.php')) return true;

	// Now try normal path
	$pagepath = ct_get_path('pages');
	if (preg_match("/^admin/",$page)) $pagepath = $pagepath.'admin/';
	// check if file exists
	if (file_exists($pagepath.$page.'.page.php')) return true;

	return false;
}


/**
 * load a page definition
 * @param $page name of page.
 */
function ct_load_page($page) {
	global $session, $user, $ctconf, $http, $db;

	// get user object
	$user =& $session->get_user();

	// Security: Allow only alphanumeric characters for the path name, remove the rest!
	$page = preg_replace('/\W+/',"",$page);

	//set_error_handler("ct_error_handler");

	// First try "etc"
	$etcpath = ct_get_path('etc');
	if (file_exists($etcpath.$page.'.page.php')) {
		include($etcpath.$page.'.page.php');
		//restore_error_handler();
		return true;
	}

	// Now try normal path
	$pagepath = ct_get_path('pages');

	// all "admin" pages are in subfolder /admin/ to improve the overview...
	if (preg_match("/^admin/",$page)) $pagepath = $pagepath.'admin/';

	// check if file exists
	if (!file_exists($pagepath.$page.'.page.php')) {
        ct_error_log("Error: Page $pagepath$page could not be loaded!",0);
		$session->put_errorbox(ct('S_ERROR_SYSTEM'), ct('S_ERROR_SYSTEM_FILENOTFOUND'));
		ct_redirect(ct_pageurl('error'));
	}
	include($pagepath.$page.'.page.php');
	//restore_error_handler();
	return true;
}


/**
 * Show error page for fatal errors and quit execution
 */
function ct_fatal_error() {
    ct_error_log("Fatal error!",0);
	ct_load_page('cthead');
	#require('siteheader.inc.php');
	#ct_load_page('ctheadbar');
	ct_load_page('offline');
	#ct_load_page('ctfootbar');
	ct_load_page('ctfoot');
	die();
}

/**
 * print an object or array nicely formatted on the screen.
 */
function ct_print_r($object, $name='') {
	if ($name) echo ( "'" . $name . "' : " ) ;
	echo "<pre>\n";
	if ( is_array ( $object ) )
		{ print_r($object); }
   	else
		{ var_dump($object); }
	echo "</pre>\n";
}


// load base classes and database adapter
function ct_base_classes() {
	global $ctconf;

	ct_load_class('CTSession');
	ct_load_class('CTForm');
	ct_load_class('CTPerson');
	ct_load_class('CTPaper');
	ct_load_class('CTParticipation');
	ct_load_class('CTReview');
	ct_load_lib('phases.lib');
	ct_load_lib('participation.lib');
}


/**
 * A function to read all main settings of conftool. These parameters might be
 * set in conftool.conf.php or in the table 'ctconf'
 * @return the value of this parameter
 * @param the name of the parameter
 * @param default value. Will be returned if no value can be found in db.
 */
function ctconf_get($name,$default='') {
	global $ctconf,$session,$db;
	if (isset($ctconf[$name])) return $ctconf[$name];
	else {
		if (is_object($session) && is_array($session->get('db_ctconf'))) {
			$c=$session->get('db_ctconf');
		} elseif (is_object($db) && $db->dberror===false) {
			$c = array();
			$res = $db->select("ctconf","name,value"); // ,"name='$name'");
			if ($res) {
				if ($db->num_rows($res) > 0) {
					for ($i = 0; $i < $db->num_rows($res); $i++) {
						$g = $db->fetch_raw($res);
						$c[$g['name']] = $g['value'];
					}
					if (is_object($session)) {
						$session->put('db_ctconf', $c);
					}
				}
			} else {
	            ct_error_log("Database error: table 'ctconf' not found!",0);
			    echo "<H2 style='background: #ff8'>Database Error!</h2><code>The table 'ctconf' could not be found in the database '".$ctconf['db/database']."'</code>";
			    die();
			}
		}
		if ($c[$name]=="|||") return "";
		elseif ($c[$name] || $c[$name]=='0') return $c[$name];
		else return $default;
	}
}

/**
 * A function to wite the main settings of conftool to the database.
 * @return success or not.
 * @param the name of the parameter
 * @param the value of the parameter. Must be a string.
 */
function ctconf_set($name,$value) {
	global $db, $session;
	if ($value=="") $value="|||";
	// Save in DB
	$db->replace_into('ctconf',array('name'=>$name,'value'=>$value));
	// Save in session
	$c = $session->get('db_ctconf');
	$c[$name] = stripslashes($value);
	$session->put('db_ctconf', $c);
	return true;
}


/**
 * A function to delete a settings of conftool from the database.
 * @return success or not.
 * @param the name of the parameter
 */
function ctconf_del($name) {
	global $db, $session;
	// delete from session
	$c = $session->get('db_ctconf');
	if(isset($c[$name])) {
		unset($c[$name]);
		$session->put('db_ctconf', $c);
	}
	// delete from DB
	$db->delete('ctconf','name="'.$name.'" LIMIT 1');
	return true;
}


/**
 * A function to read a current value from the ctconf table and increment it just afterwards.
 * @return the value of this parameter
 * @param the name of the parameter
 */
function ctconf_increment($name) {
	global $db;
	if (ctconf_get($name)) {
		$res = $db->query("SELECT value FROM ctconf WHERE name='$name' FOR UPDATE;");
		$upd = $db->query("UPDATE ctconf SET value = value + 1 WHERE name='$name';");
		if ($res && $upd) {
			if ($db->num_rows($res) == 1) {
				$row = $db->fetch($res);
				return $row['value'];
			}
		}
		return "???";
	} else {
		return "???";
	}
}


/**
 * An explode function that works for many different kinds of elements.
 *
 * @param array $spacer array with spacer elements
 * @param string to split $string
 * @return array with the splitted string. Empty elements will be removed, strings are trimmed.
 */

function ct_multi_explode($spacer,$string)  {
	$astring = array($string);
	if (is_string($spacer)) $spacer=array($spacer);
	// Do this for all elements of $spacer
	foreach ($spacer as $s) {
		$helper = array();
		// Do it for every substring in the array and merge the results.
		foreach ($astring as $a) {
			$helper = array_merge($helper,array_map('trim', explode($s,$a)));
		}
		$astring = $helper;
	}
	// now remove all empty elements. They can occur if two separators followed each other.
	$return = array();
	foreach ($astring as $a) { if ($a!='') $return[]=$a; }
	return $return;
}

/**
 * Explode csv to array, remove leading and trailing spaces and empty elements.
 */
function ct_csv_explode($csv_string,$separator=',') {
	$a = array_map('trim', explode($separator,$csv_string));
	$b = array();
	foreach ($a as $k=>$v) if ($v!="") $b[]=$v; // remove empty strings
	return $b;
}

/**
 * Implode array to a "readable" csv string.
 */
function ct_csv_implode($array) {
	if (is_array($array)) return implode(", ",$array);
	elseif (is_string($array)) return $array;
	return '';
}

/**
 * load database adapter (usually mysql)
 */
function ct_database_adapter() {
	global $ctconf;
	ct_load_lib($ctconf['db/dbms'].'.dbi');
}

/**
 * Create a url for ConfTool
 *
 * @param string $page name of the page like "index", "login", "adminTool"
 * @param string $getvars hash array of get variables
 * @param string $pathinfo add a path info...
 * @param string $fragment add a fragment identifyer to address a position within a page.
 * @return string the encoded URL
 */
function ct_pageurl($page='', $getvars=array(), $pathinfo="", $fragment="") {
	global $session;
	$q = "?";
	$url = ct_getbaseurl(true, $page);
	$url .= "index.php";
	if ($pathinfo<>"") {
		if (substr($pathinfo,0,1)!="/") $pathinfo = "/".$pathinfo;
		$url .= $pathinfo;
	}
	// if Page variable is empty, don't add other parameters either.
	if ($page=='') {
		return $url; // End here.
	}
	$url .= $q."page=".$page;
	$q = "&amp;";

	// Add the conference name (only for multi-conference module).
	if (is_object($session)) {
		$conference = $session->get('conf');
		if ($conference<>"") {
			$url .= $q."conf=".$conference;
			$q = "&amp;";
		}
	}

	//
	if (is_array($getvars) && count($getvars)>0) { // check to avoid errors.
		while (list($k,$v) = each($getvars)) {
			if (is_array($v)) {
				// $v may be an array, when check-boxes or multiple select fields are being used.
				while (list($va,$vb) = each($v)) {
					if (!is_array($vb)) // No further level of recursion, sorry!
						$url .= $q.urlencode($k)."[]=".urlencode($vb);
				}
			} else {
				$url .= $q.urlencode($k)."=".urlencode($v);
			}
			$q = "&amp;";
		}
	}

	// Add session ID if cookies are disabled and URL rewritig is allowed.
	if (!ct_detect_robot() && !ini_get("session.use_only_cookies")) { // Don't add it for robots!
		if (SID && strpos($url,session_name())===FALSE && !(substr($pathinfo,0,6)=="/page=")) {
			$url.= $q.strip_tags(SID);
			$q = "&amp;";
			// so try another (my) method as well
		} else if ((!isset($_COOKIE[session_name()]) || ($_COOKIE[session_name()]=="")) && strpos($url,session_name())===FALSE && !(substr($pathinfo,0,6)=="/page=")) {
			$url.= $q.strip_tags(session_name())."=".session_id();
			$q = "&amp;";
	    }
	}

	// Add fragment identifier
	if ($fragment!="" && substr($fragment,0,1)!="#") $fragment = "#".$fragment;
	$url .= $fragment;

	return $url;
}

/**
 * Get URL of current page with parameters
 *
 * @return string URL of current page.
 */
function ct_thisurl() {
	global $http;
	return ct_pageurl($http['page'],ct_http_array());
}

/**
 * @return string from language array (in session).
 * @param $include_path return the path of the installation as well? If set to false, only the protocpl and domain name will be returned.
 * @param $page name of page
 * @desc get baseurl of conftool. this is either %ctconf['web/baseurl'] or if not set it will be extracted
 *       from the URL of the current document.
 */
function ct_getbaseurl($include_path = true, $page="") {
	global $ctconf;

	// if web/baseurl was set, return it a s the basis url of ConfTool
	if (isset($ctconf['web/baseurl']) && $ctconf['web/baseurl'] != "" ) {
		// http is forced for downloads
		if ($ctconf['web/forcehttpdownloads']===true && in_array($page,$ctconf['downloadpages']) && substr($ctconf['web/baseurl'],0,8)=='https://')
			$baseurl = 'http://'.substr($ctconf['web/baseurl'],8); // remove httpS
		else
			$baseurl = $ctconf['web/baseurl'];

		if ($include_path)
			return $baseurl;
		else
			// Return only domain name from web/baseurl!
			return substr($baseurl, 0, strpos($baseurl,"/",8));  // skip "/" from "https://" etc.
	}
	// Otherwise return the submitted domain name and the current path as baseurl
	else {
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
			// for downloads use http if required.
			if ($ctconf['web/forcehttpdownloads']===true && in_array($page,$ctconf['downloadpages']))
				$protocol="http";
			else // use default.
				$protocol="https";
		} else {
			$protocol="http";
		}

	    // Remove filename (index.php, imprint.php, browseSessions.php) and query parameters
	    // Request URI is everything behind the host, always starting with "/"
	    $path = preg_replace('|[#\?].*|', '', $_SERVER['REQUEST_URI']);	# remove everything after the first "?"
	    $path = preg_replace('|([a-z-_]+\.php.*)|i','',$path);	# remove filename

	    $host = $_SERVER['HTTP_HOST'];
	    if (strlen($host)<5) $host = $_SERVER['SERVER_NAME'];
	    if (strlen($host)<5) $host = $_SERVER['SERVER_ADDR']; # Use IP if the above is missing...
		// Return domain name or also base path?
		if ($include_path)
			return $protocol."://".$host.$path;
		else
			return $protocol."://".$host;
	}
}


/**
 * Get HTTP PATH_INFO (what is behind the /index.php/ (with a slash!))
 * For Apache 2.0: Requires that "AcceptPathInfo On" is set in .htaccess
 * (or in httpd.conf)
 */
function ct_getpathinfo() {
	$pathInfoArray = array();
	if (isset($_SERVER['PATH_INFO'])) {
		$ps = explode("&",urldecode(substr($_SERVER['PATH_INFO'],1)));
		while (list(, $pi) = each($ps)) {
			$k = explode("=",$pi);
			$pathInfoArray = array_merge($pathInfoArray, array($k[0]=>$k[1]));
		}
	}
	return $pathInfoArray;
}


/**
 * set navigation path to be displayed at foot of page.
 */
function ct_pagepath($path) {
	global $pagepath;
	$pagepath = $path;
}

/**
 * redirect browser to another URL, script execution ends here!
 */
function ct_redirect($url) {
	global $db;
	ob_end_clean();
	//	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".str_replace("&amp;","&",$url));
	#header("Location: ".str_replace("&amp;","&",$url), true, 301);
	//	header("Connection: close");
	//	flush();
	if (is_object($db)) $db->disconnect();
	exit();
}

/**
 * send a redirect response to browser to reload the current page
 */
function ct_reload_page() {
	ct_redirect($_SERVER['REQUEST_URI']);
}

/**
 * Flush screen output to browser and wait for X ms
 *
 * @param int $msec - time to wait in milliseconds. 1000ms = 1 second.
 */
function ct_flush($msec=0) {
	global $ct_end_flushed;
	#while (ob_get_level() > 0) { ob_end_flush(); } // Sometimes does not work...
	if (!isset($ct_end_flushed)) {
		$ct_end_flushed = true; // End flush only once, as some php versions are buggy with this!
		ob_end_flush();
	}
	ob_flush();	flush();
	if ($msec>1000) {
		sleep(floor($msec/1000));
	} elseif ($msec>0) {
		$usec = $msec * 1000;
		$read=NULL; $write=NULL; $sock=NULL;
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && substr(phpversion(),0,5)<=4 && function_exists('socket_select'))
			socket_select($read = NULL, $write = NULL, $sock = array(socket_create (AF_INET, SOCK_RAW, 0)), 0, $usec);
		else
			usleep($usec);
	}
}


// FUNCTIONS THAT CHECK PERMISSIONS
// =====================================================================

// function ct_check_phases($phases) can be found in phases.lib


/**
 * check if the current user has administration rights...
 */
function ct_requireadmin() {
	global $session,$http;
	$user =& $session->get_user();
	if (!$user->is_admin()) {
		$session->put_errorbox(ct('S_ERROR_ACCESSDENIED'), ct('S_ERROR_ACCESS_ADMINONLY'));
		ct_redirect(ct_pageurl('error'));
	}
	ct_load_lib('admin.lib');
}

/**
 * Access only for conference PC Chair
 */
function ct_requirechair() {
	global $session;
	$user =& $session->get_user();
	if (!$user->is_chair() && !$user->is_admin()) {
		$session->put_errorbox(ct('S_ERROR_ACCESSDENIED'), ct('S_ERROR_ACCESS_ADMINONLY'));
		ct_redirect(ct_pageurl('error'));
	}
	ct_load_lib('admin.lib');
}

/**
 * Access only for staff people - conference assistants with access to Users, Participants and Payments.
 */
function ct_requireassistant() {
	global $session;
	$user =& $session->get_user();
	if (!$user->is_assistant() && !$user->is_admin()) {
        ct_error_log("Warning: Assistant access denied.");
		$session->put_errorbox(ct('S_ERROR_ACCESSDENIED'), ct('S_ERROR_ACCESS_ADMINONLY'));
		ct_redirect(ct_pageurl('error'));
	}
	ct_load_lib('admin.lib');
}

/**
 * Access for staff and PC chairs: e.g. for list of PC members...
 */
function ct_requireassistantorchair() {
	global $session;
	$user =& $session->get_user();
	if (!$user->is_assistant() && !$user->is_chair() && !$user->is_admin()) {
		$session->put_errorbox(ct('S_ERROR_ACCESSDENIED'), ct('S_ERROR_ACCESS_ADMINONLY'));
		ct_redirect(ct_pageurl('error'));
	}
	ct_load_lib('admin.lib');
}

// Access only for frontdesk people (and assistants...)
function ct_requirefrontdesk() {
	global $session;
	$user =& $session->get_user();
	if (!$user->is_assistant() && !$user->is_frontdesk() && !$user->is_admin()) {
		$session->put_errorbox(ct('S_ERROR_ACCESSDENIED'), ct('S_ERROR_ACCESS_ADMINONLY'));
		ct_redirect(ct_pageurl('error'));
	}
	ct_load_lib('admin.lib');
	ct_load_lib('frontdesk.lib');
}

/**
 * Access only for chairs and frontdesk people (and assistants...)
 */
function ct_requirefrontdeskorchair() {
	global $session;
	$user =& $session->get_user();
	if (!$user->is_chair() && !$user->is_assistant() && !$user->is_frontdesk() && !$user->is_admin()) {
		$session->put_errorbox(ct('S_ERROR_ACCESSDENIED'), ct('S_ERROR_ACCESS_ADMINONLY'));
		ct_redirect(ct_pageurl('error'));
	}
	ct_load_lib('admin.lib');
	ct_load_lib('frontdesk.lib');
}


/**
 * Write error message in server log.
 *
 * @param string $msg error message string
 * @param int $type mesage type (not used)
 */
function ct_error_log($msg,$type=0) {
	global $http, $session;
	if (is_object($session)) 				$user =& $session->get_user();
	if (isset($user) && is_object($user)) 	$id = $user->get_id(); else $id="-";
	$page = $http['page'];
	$uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")?"https://":"http://";
	$uri.= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if ($page!='error')
		error_log("ConfTool (".date('Y-m-d G:i:s')."): [$msg (ID:$id, Page:$page, URI:$uri)]",0);
	else
		error_log("ConfTool (".date('Y-m-d G:i:s')."): [$msg (ID:$id)]",0);
}


/**
 * display an errorbox on the current page
 */
function ct_errorbox($title, $msg) {
	_ct_messagebox($title, $msg, "errorboxTitleTD", "errorboxTitle");
}

/**
 * display an warning on the current page
 */
function ct_warningbox($title, $msg) {
	_ct_messagebox($title, $msg, "warningboxTitleTD", "warningboxTitle");
}

/**
 * show an information box on the current page
 */
function ct_infobox($title, $msg) {
	_ct_messagebox($title, $msg, "infoboxTitleTD", "infoboxTitle");
}

/**
 * Show message box (used by errorbox, infobox asf.)
 *
 * @param string $title title string of message box
 * @param string $msg body string of message box
 * @param string $tdclass css class of message title background
 * @param string $spanclass css class of title text
 */
function _ct_messagebox($title, $msg, $tdclass, $spanclass) {
#	echo "<hr size=1 noshade>\n";
	ct_vspacer();
	echo "<table width=\"100%\" cellspacing=0 cellpadding=5 border=0 align=center>\n";
	echo "<tr><td class=\"$tdclass\" align=left colspan=3>\n";
	echo "<span class=\"$spanclass\">&nbsp;&nbsp;$title</span></td></tr>\n";
	echo "<tr>";
	echo "<td class=\"$tdclass\" align=left valign=top width=\"5%\">&nbsp;</td>\n";
	echo "<td class=\"boxmsgTD\" align=left valign=top width=\"85%\">\n";
	echo "<span class=\"boxmsg\">$msg</span></td>\n";
	echo "<td class=\"boxmsgTD\" align=left valign=top width=\"10%\">&nbsp;</td>\n";
	echo "</tr></table>\n";
	ct_vspacer();
}

/**
 * Create a spacer of about X pixels height, that considers the average screen size.
 *
 * @param int $pixel height
 */
function ct_vspacer($pixel='4') {
	echo "\n<div style=\"font-size:".$pixel."pt; clear:both;\">&nbsp;</div>\n"; // small empty line.
}

/**
 * detect language and load language file...
 */
function ct_load_lang() {
	global $ctconf,$session;

	if ($session->get('language_loaded') !== true || $ctconf['language/debug'] === true ) {
		// read default language (english) if other definitions are still missing...
		$loaded = true;
		$loaded &= ct_read_language_file_parse_lines(ct_get_path('etc')."english.lang");
		if ($ctconf['language']!='english')
			$loaded &= ct_read_language_file_parse_lines(ct_get_path('etc').$ctconf['language'].".lang");
		if ($loaded) $session->put('language_loaded', true);
		// Now load custom language file from etc/main
		if (file_exists(ct_get_path('etc')."main/".$ctconf['language'].".lang")) { // read other language file of "main" conference if existing
			ct_read_language_file_parse_lines(ct_get_path('etc')."main/".$ctconf['language'].".lang");
		}

	}
}

/**
 * Parse one language file
 */
function ct_read_language_file_parse_lines($lang) {
	global $session;
	if ($lang=="" or !file_exists($lang)) {
		echo "<H1 class='yellowbg'>ConfTool setup error!<br>Language file '$lang' not found.</H1><h3>Please contact the conference organizers.</h3>\n\n";
		return false;
	}
	$file = fopen($lang, "r");
	while ($file!==false && !feof($file)) {
		$line = trim(fgets($file, 4096));
		if (($line != "") && !preg_match("/^#/", $line)) {
			$x = explode("=", $line, 2);
			if (!$x[1]=="")
				$session->put($x[0], $x[1]);
			else
				$session->put($x[0], "|||"); // ||| for empty values...
		}
	}
	return fclose($file);
}

/**
 * @return string from language array (in session).
 * @param $c constant name for this expression
 * @param $var_array array of variables that shall be inserted at %1, %2, %3 etc.
 * @desc Language constants are defined in the etc/xxxx.lang files.
 *       If you want to return a value empty use "_" as constant value
 *       If you want to replace parts of the text use %1, %2 etc. The values in
 *           the array will replace these placeholders.
 */
function ct($c,$var_array=array()) {
	global $session;
	$text = $session->get($c);

	if ($text=="|||") { // Language strings with a value of "_" will be interpreted as empty.
		$text = "";
	} else if (!$text===false) {
		; // OK that constant exists! :-)
	} else {
		$text = $c; // not found! Return name of constant...
	}

	// now replace variables if available.
	$i=1;
	foreach($var_array as $var) {
		$text = ereg_replace('%'.$i, ''.$var, $text);
		$i++;
	}
	return $text;
}

/**
  * Dunny function for better compatibility with ConfTool Pro.
  */
function ctlx($array_name,$field_name) {
	return $field_name;			// take default (e.g. title)
}


/**
 * look if post parameter is present and has a certain parameter
 *
 * @param string $name name of form field like "form_personID"
 * @param string or array $xue test if value of form field is equal to this string or one of these strings
 * @return boolean
 */
function in_http($name, $value=false) {
	global $http;
	if (isset($http[$name])) {
		if (is_string($value)) {
			return (stripslashes($http[$name]) == $value);
		} elseif (is_array($value)) {
			return in_array(stripslashes($http[$name]),$value);
		} else {
			return true;
		}
	} else {
		return false;
	}
}


/**
 * ct_http_array returns an array of all current http variables, not including $page (the current page),
 * $cmd_xxx (the last pushed button), PHPSESSID (the session ID...)
 *
 * @param array $new_array Replace current http variable values with values in $new_array
 * @return array with all significant http values
 */
function ct_http_array($new_array=array(), $listempty=false) {
	global $http;
	reset ($http);
	$array = array();
	while (list($u,$v) = each($http)) {
		// Skip some system or "secret" parameters
		if ($u!="page" && 	// Page name
			$u!="lang" &&	// Language key
			$u!="conf" &&	// Conference name
			$u!="ctpassword" && $u!="form_pwd" && $u!="form_pwd2" &&  // do not show passwords in URL!
			$u!="x" && $u!="y" && // scroll position
			substr($u,0,3)!="cmd" && // all commands, e.g. clicked buttons
			#$u!=session_name() &&	// Do not remove, or Cookies will be required...
			!array_key_exists($u,$new_array) &&
			($listempty || $v!=='') ) {
				if (is_array($v)) { // this was an array!
					array_map(stripslashes,$v); // strip slashes for each element of array...
					$array = array_merge($array, array($u=>$v));
				} else {
					$array = array_merge($array, array($u=>stripslashes($v)));
				}
		}
	}
	while (list($u,$v) = each($new_array)) {
		if ($v!='')
		$array = array_merge($array,array($u=>$v));
	}
	reset ($http);
	return $array;
}

/**
 * Trim all http variables which names are declared in the array.
 */
function ct_http_trim($totrim=array()) {
	global $http;
	#reset ($http);
	#if (count($totrim)==0) $totrim=$http;
	while (list($u,$v) = each($totrim)) {
		{
			if (isset($http['form_'.$v])) $http['form_'.$v]=trim($http['form_'.$v]);
			if (isset($http[$v])) $http[$v]=trim($http[$v]);
		}
	}
	#reset ($http);
}


/**
 * Take a simple an array with all values and create an hash array
 * with all the according http values...
 * Add '?lang' to your value and the function will try to read all according language values, like 'title_lang' for title, title2, title3 and title4.
 * Add '?boolean' to your value and the function will assign "true" if a checkbox was set (e.g. value was 1) and "false" if no http value was found.
 * Add '?date' for the interpretation of date fields (time is set to 00:00:00)
 * Add '?datetime' for interpretation of datetime fields
 * Add '?arraycsv' a list of values in CSV format
 */
function ct_create_hash_read_http($values=array()) {
	global $http;
	$ret = array();
	while (list(,$u) = each($values)) {
		if (in_http('form_'.$u)) {
			$ret = array_merge($ret, array($u=>$http['form_'.$u]));
		}
		elseif (!(stristr($u,'?lang')===false)) {
			$v = substr($u,0,-5);
			$ret = array_merge($ret, array($v=>$http['form_'.$v]));
			$ret = array_merge($ret, array($v.'2'=>$http['form_'.$v.'2']));
			$ret = array_merge($ret, array($v.'3'=>$http['form_'.$v.'3']));
			$ret = array_merge($ret, array($v.'4'=>$http['form_'.$v.'4']));
		}
		elseif (!(stristr($u,'?boolean')===false)) {
			$v = substr($u,0,-8);
			if (in_http('form_'.$v)) $b="true"; else $b="false";
			$ret = array_merge($ret, array($v=>$b));
		}
		elseif (!(stristr($u,'?isset')===false)) {
			$v = substr($u,0,-6);
			if (in_http('form_'.$v)) $b="1"; else $b="0";
			$ret = array_merge($ret, array($v=>$b));
		}
		elseif (!(stristr($u,'?datetime')===false)) {
			$v = substr($u,0,-9);
			$http['form_'.$v] = $http['form_'.$v.'_year']."-".$http['form_'.$v.'_month']."-".$http['form_'.$v.'_day']." ".$http['form_'.$v.'_hour'].":".$http['form_'.$v.'_minute'].":".$http['form_'.$v.'_second'];
			if ($http['form_'.$v.'_day'] > 0 && $http['form_'.$v.'_year'] > 0) {
				$ret = array_merge($ret, array($v=>$http['form_'.$v]));
			}
		}
		elseif (!(stristr($u,'?date')===false)) {
			$v = substr($u,0,-5);
			$http['form_'.$v] = $http['form_'.$v.'_year']."-".$http['form_'.$v.'_month']."-".$http['form_'.$v.'_day']." 00:00:00";
			if ($http['form_'.$v.'_day'] > 0 && $http['form_'.$v.'_year'] > 0) {
				$ret = array_merge($ret, array($v=>$http['form_'.$v]));
			}
		}
		elseif (!(stristr($u,'?arraycsv')===false)) {
			$v = substr($u,0,-9);
			if (in_http('form_'.$v) && is_array($http['form_'.$v])) {
				$array = $http['form_'.$v];
				$w = '';
				foreach ($array as $a) {
					if ($w!='') $w .=",";
					$w .= $a;
				}
				$ret = array_merge($ret, array($v=>$w));
			}
		}


	}
	return $ret;
}

/**
 * @desc Converts all array an array with all values that have a key starting like "form_" to a new array
 */
function ct_form_array_convert($form_array) {
	$result = array();
	reset($form_array);
	while (list($key, $val) = each($form_array)) {
		if (strpos($key,"form_") == 0)
		    $result[substr($key,5)] = $val;
	}
	// print_r($result);
	return $result;
}


/**
 * Take an hash array and transform it to an array of arrays...
 * Usually used for $form->add_hidden like $form->add_hidden(hash2aa(ct_http_array(array('filter'=>'show','form_paperID'=>''))));
 *
 * @param array $hash the hash array like array('a'=>'Value A','b'=>'Value B').
 * @return array and array of arrays like array(array('a','Value A'),array('b','Value B'))
 */
function hash2aa($hash) {
	$array = array();
	while (list($u,$v) = each($hash))
		array_push($array, array($u,$v));
	return $array;
}


/**
 * format database fields for HTML and form output avoiding XSS problems.
 */
function ct_form_encode($text) {
	$text = stripslashes($text);
	$text = str_replace("&","&amp;",$text);
	$text = str_replace("\"","&quot;",$text);
	$text = str_replace("'","&#39;",$text);
	$text = ereg_replace("&amp;#([0-9]{3,5});","&#\\1;",$text);
	$text = str_replace("<","&lt;",$text);
	$text = str_replace(">","&gt;",$text);
	return $text;
}


/**
 * format string for textareas and other form fields, entities will not be decoded.
 */
function ct_form_encode_noentities($text) {
	$text = stripslashes($text);
	$text = str_replace("<","&lt;",$text);
	$text = str_replace(">","&gt;",$text);
	$text = str_replace("\"","&quot;",$text);
	$text = str_replace("'","&#39;",$text);
	return $text;
}


/**
 * format text from input fields for HTML output. Replaces nl2br of PHP and calls ct_form_encode.
 * Please note: "<br>\r\n" asf. will also be replaced by only one "<br>"!
 */
function ct_nl2br($text, $do_form_encode=true) {
	if ($do_form_encode) {
		$text = ct_form_encode($text);
		$text = str_replace("  ","&nbsp;&nbsp;",$text);
	} else {
		$text = stripslashes($text);
	}
	// Do not add extra empty lines for lists and paragraphs.
    $text = preg_replace('/(<\/?ul\s*\/?>|<\/?ol\s*\/?>|<\/?li\s*\/?>|<\/?p\s*\/?>)\r?\n/i', "$1", $text);
    // Do not double br if follwed by a newline.
    $text = preg_replace('/(<br\s*\/?>)?\r?\n/i', "<br />\n", $text);
    return $text;
}

/**
 * Reformat/convert text for E-Mail plain text output. Replaces br2nl of PHP.
 * Please note: "<br>\r\n" asf. will also be replaced by only one "\n"!
 */
function ct_br2nl($text, $do_html_decode=true) {
	if ($do_html_decode) {
		$text = ct_html_entity_decode($text,ENT_QUOTES);
		$text = str_replace("  "," ",$text);
	} else {
		$text = stripslashes($text);
	}
    // Do not double br if follwed by a newline.
    $text = preg_replace('/(<br\s*\/?>)\r?\n?/i', "\n", $text);
    $text = strip_tags($text);
    return $text;
}

/**
 * Format text for output. Everything will be put into paragraphs, nl creates a new paragraph
 *
 * @param string $text the text to be formatted
 * @param string $do_form_encode encode all entities?
 * @param string $class use a specific class?
 * @return string formatted text
 */
function ct_nl2p($text, $do_form_encode=true, $class='') {
	if ($do_form_encode) {
		$text = ct_form_encode($text);
		$text = str_replace("  ","&nbsp;&nbsp;",$text);
	} else {
		$text = stripslashes($text);
	}
	$text = str_replace('<p></p>', '', '<p>' . preg_replace('#\n|\r#', '</p>$0<p>', $text) . '</p>');
	if ($class!='')
		$text = str_replace('<p>',"<p class='$class'>",$text);
	return $text;
}


/**
 * Remove all empty lines from a string and chop off newlines at the end.
 *
 * @param string $text text to process
 * @return string processed text
 */
function ct_remove_empty_lines($text) {
	return chop(preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $text));
}


/**
 * Add slashes to every element of an array and any subarray
 */
function ct_deepslash($v){
	return (is_array($v)) ? array_map("ct_deepslash", $v) : addslashes($v);
}

/**
 * Remove slashes from every element of an array and the subarrays
 */
function ct_deepunslash($v){
	return (is_array($v)) ? array_map("ct_deepunslash", $v) : stripslashes($v);
}

/**
 * convert any string or blob to mysql format. Self-written, as there
 * are different functions in different versions of php for this
 */
function ct_mysql_escape_string($s) {
	#return addslashes($s);
	#$search =  array("\x00", "\x5c"    ,'"'       , "'"       , "\x0a", "\x0d", "\x1a", "\x09", "\x08");
	#$replace = array('\0'  , "\x5c\x5c","\x5c\x22", "\x5c\x27", '\n'  , '\r'  , '\Z'  , '\t'  , '\b');
	$search =  array("\x5c"    ,'"'       , "'"       , "\x0a", "\x0d");
	$replace = array("\x5c\x5c","\x5c\x22", "\x5c\x27", '\n'  , '\r'  );
	return str_replace($search, $replace, $s);
}


/**
 * return a number formatted as currency
 * @param float $number The number as value
 * @param long $long currency format (US$) or short ($)
 * @param boolean $plaintext return as plain text instead of HTML?
 */
function ct_currency_format($number,$long=true,$plaintext=false) {
        global $ctconf;
        $spacer = ($plaintext)?' ':'&nbsp;';
        $value = ct_number_format($number);
        if ($long)
                $code = ctconf_get('currencyCode','EUR');
        else
                $code = ctconf_get('currencySymbol','&euro;');
        if ($plaintext) $code = ct_html_entity_decode($code);
        if ($ctconf['currency/style']) { // 1 = German format
                return $value.$spacer.$code; // 150,00 EUR
        } else  {       // 0 = US-Style
                return $code.$spacer.$value; // EUR 150,00
        }
}


/**
 * number formatting for currencies etc...
 * @param float $number the number to format...
 */
function ct_number_format($number) {
	global $ctconf;
	return number_format($number,$ctconf['currency/decimals'],$ctconf['currency/decimal_point'],$ctconf['currency/thousands_separator']);
}

/**
 * Unformat a number: Return it to a PHP float number.
 *
 * @param string $number the number string...
 * @return float
 */
function ct_number_unformat($number) {
	global $ctconf;
	$number = str_replace($ctconf['currency/thousands_separator'],"",$number);
	$number = str_replace($ctconf['currency/decimal_point'],".",$number);
	return $number;
}

/**
 * Returns the current unix timestamp in ConfTool (the Pro version considers  time offset as well)
 *
 * @return int Unix timestamp: seconds since 1st Jan 1970
 */
function ct_time() {
	return time();
}

/**
 * @return convernt timestamp in sec since 1970. If invalid or empty return -1
 * @param $date mysql date "2004-12-31 10:15:33"
 * @desc Create a unix timestamp (sec from 1970) from a mysql date format.
 */
function ct_datetime_2_timestamp($datetime) {
   if (!isset($datetime) || !$datetime || strlen($datetime)!=19 || $datetime=='0000-00-00 00:00:00') return -1;
   return( mktime(substr($datetime, 11, 2), substr($datetime, 14, 2), substr($datetime, 17, 2), substr($datetime, 5, 2), substr($datetime, 8, 2), substr($datetime, 0, 4)) );
}

/**
 * @return datetime format for mysql database
 * @param $timestamp unix timestamp (sec from 1970)
 * @desc Createa mysql date format mysql "2004-12-31 10:15:33" from a unix timestamp (sec from 1970).
 */
function ct_timestamp_2_datetime($timestamp) {
   if (!isset($timestamp) || $timestamp<1) return "0000-00-00 00:00:00";
   return date("Y-m-d H:i:s", $timestamp);
}

/**
 * Returns the current time with ms.
 */
function ct_get_microtime() {
   	$mtime = explode(" ",microtime());
	return ((float)$mtime[1] + (float)$mtime[0]);
}

/**
 * Explode a datetime string into an array of 6 numbers
 *
 * @param $datetime datetime
 * @return array exploded datetime
 */
function ct_explode_datetime($datetime) {
	return explode(" ", str_replace(array("-",":"),array(" "," "), $datetime) );
}

/**
 * Send a confirmation about the new installation to info@conftool.net
 */
function ct_firstlogin($person) {
	#ct_load_lib('mail.lib'); ct_mail('info@conftool.net',ct_getbaseurl());
	ctconf_set('firstlogin',time());
}

/**
 * Returns part of a string
 *
 * @param string $str string to convert to upper case.
 * @return string converted string.
 */
function ct_substr($str,$start=0,$length=null) {
	global $ctconf;
	if ($length==null) $length=ct_strlen($str); // null does not work well...
	if (function_exists('mb_substr'))
		return mb_substr($str, $start, $length, strtoupper($ctconf['charset']));
	return substr($str, $start, $length);
}

/**
 * Find position of first occurence of a string in another string
 *
 * @param string $haystack the string to be checked
 * @param string $needle the position counted from the beginning of haystack
 * @param int offset the search offset, default 0
 * @return int position of first occurence of needle in haystack
 */
function ct_strpos($haystack,$needle,$offset=0) {
	global $ctconf;
	if (function_exists('mb_strpos'))
		return mb_strpos($haystack,$needle,$offset, strtoupper($ctconf['charset']));
	return strpos($haystack,$needle,$offset);
}

/**
 * Find position of LAST occurence of a string in another string
 *
 * @param string $haystack the string to be checked
 * @param string $needle the position counted from the beginning of haystack
 * @param int offset the search offset, default 0
 * @return int position of first occurence of needle in haystack
 */
function ct_strrpos($haystack,$needle) {
	global $ctconf;
	if (function_exists('mb_strrpos'))
		return mb_strrpos($haystack, $needle, strtoupper($ctconf['charset'])); // For PHP 5.2 encoding should be the 4th parameter...
	return strrpos($haystack,$needle);
}

/**
 * Get length of a string
 *
 * @param string $str string to analyse
 * @return int number of characters in string
 */
function ct_strlen($str) {
	global $ctconf;
	if (function_exists('mb_strlen'))
		return mb_strlen($str, strtoupper($ctconf['charset']));
	return strlen($str);
}

/**
 * Multi-Byte-safe str_pad
 *
 * @param string $input input string
 * @param int $pad_length length of the output string. If negative or length than length of string, no padding takes place.
 * @param string $pad_string character to use for padding.
 * @param unknown_type $pad_type
 * @return unknown
 */
function ct_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT) {
    $length = $pad_length - ct_strlen($input);
    if ($pad_string=='') $pad_string=' ';
	if($length > 0) {
		if( $pad_type == STR_PAD_RIGHT ) {
			return $input . ct_substr(str_repeat($pad_string, $length), 0, $length);
		} elseif( $pad_type == STR_PAD_BOTH ) {
			$l1=floor($length/2);
			$l2=ceil($length/2);
			return ct_substr(str_repeat($pad_string,$l1),0,$l1).$input.ct_substr(str_repeat( $pad_string, $l2),0,$l2);
		} else { // or $pad_type == STR_PAD_LEFT
			return ct_substr(str_repeat($pad_string, $length),0,$length).$input;
        }
    }
    return $input;
}

/**
 * Convert string to upper case using charset of ConfTool (if possible)
 *
 * @param string $str string to convert to upper case.
 * @return string converted string.
 */
function ct_strtoupper($str) {
	global $ctconf;
	if (function_exists('mb_strtoupper'))
		return mb_strtoupper($str,strtoupper($ctconf['charset']));
	return strtoupper($str);
}

/**
 * Convert string to lower case using charset of ConfTool (if possible)
 *
 * @param string $str string to convert to lower case.
 * @return string converted string.
 */
function ct_strtolower($str) {
	global $ctconf;
	if (function_exists('mb_strtolower'))
		return mb_strtolower($str,strtoupper($ctconf['charset']));
	return strtolower($str);
}

/**
 * abbreviate trims text to a certain length, considering word breaks and adds ellipses (...) if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $strip_tags if html tags are to be stripped
 * @param bool $add_ellipses if ellipses (...) are to be added
 * @return string
 */
function ct_abbreviate($text, $max_length, $strip_tags = true, $add_ellipses = true) {
	//strip tags, if desired
	if ($strip_tags)
		$text = strip_tags($text);

	//no need to trim, already shorter than trim length
	if (ct_strlen($text) <= $max_length || $max_length<1)
		return $text;

	//find last space within length and cut string there...
	$last_space = ct_strrpos(ct_substr($text, 0, $max_length), ' ');
	if ($max_length<15 || $last_space<1 || $last_space<$max_length-10) $last_space=$max_length;
	$text = ct_substr($text, 0, $last_space);

	//add ellipses (...)
	if ($add_ellipses)
		$text .= '...';

	return $text;
}

/**
 * This function replaces special ISO-8859-1 Characters with their ASCII equivalents.
 * e.g. used for a bug in PayPal or to create the user name.
 *
 * @param string $s
 * @param boolean $onlyascii - Leave only ASCII characters?
 * @return string - the reformatted string.
 */
function ct_replace_iso_chars($s, $onlyascii=true) {
	// Translate UTF8-Codes
	$exceptions = array("&#160;"=>" ","&#161;"=>"!","&#162;"=>"c","&#163;"=>"L","&#164;"=>"$","&#165;"=>"Y","&#166;"=>"|",
						"&#167;"=>"%","&#168;"=>"-","&#169;"=>"(C)","&#170;"=>"a","&#171;"=>"<<","&#172;"=>"-","&#173;"=>"--",
						"&#174;"=>"(R)","&#175;"=>"-","&#176;"=>" ","&#177;"=>"+-","&#178;"=>"2","&#179;"=>"3","&#180;"=>"'",
						"&#181;"=>"u","&#182;"=>"q","&#183;"=>".","&#184;"=>",","&#185;"=>"1","&#186;"=>"o","&#187;"=>">>",
						"&#188;"=>"1/4","&#189;"=>"1/2","&#190;"=>"3/4","&#191;"=>"?","&#192;"=>"A","&#193;"=>"A","&#194;"=>"A",
						"&#195;"=>"A","&#196;"=>"A","&#197;"=>"A","&#198;"=>"AE","&#199;"=>"C",
						"&#200;"=>"E","&#201;"=>"E","&#202;"=>"E","&#203;"=>"E",
						"&#204;"=>"I","&#205;"=>"I","&#206;"=>"I","&#207;"=>"I","&#208;"=>"D","&#209;"=>"N",
						"&#210;"=>"O","&#211;"=>"O","&#212;"=>"O","&#213;"=>"O","&#214;"=>"OE","&#215;"=>"x","&#216;"=>"O",
						"&#217;"=>"U","&#218;"=>"U","&#219;"=>"U","&#220;"=>"UE","&#221;"=>"Y","&#222;"=>"TH","&#223;"=>"ss",
						"&#224;"=>"a","&#225;"=>"a","&#226;"=>"a","&#227;"=>"a","&#228;"=>"ae","&#229;"=>"a","&#230;"=>"ae",
						"&#231;"=>"c","&#232;"=>"e","&#233;"=>"e","&#234;"=>"e","&#235;"=>"e","&#236;"=>"i","&#237;"=>"i","&#238;"=>"i","&#239;"=>"i",
						"&#240;"=>"d","&#241;"=>"n","&#242;"=>"o","&#243;"=>"o","&#244;"=>"o","&#245;"=>"o","&#246;"=>"o","&#247;"=>"/","&#248;"=>"o",
						"&#249;"=>"u","&#250;"=>"u","&#251;"=>"u","&#252;"=>"u","&#253;"=>"y","&#254;"=>"th","&#255;"=>"y",
						"&#256;"=>"A","&#257;"=>"a","&#258;"=>"A","&#259;"=>"a","&#260;"=>"A","&#261;"=>"a",
						"&#262;"=>"C","&#263;"=>"c","&#264;"=>"C","&#265;"=>"c","&#266;"=>"C","&#267;"=>"c","&#268;"=>"C","&#269;"=>"c",
						"&#270;"=>"D","&#271;"=>"d","&#272;"=>"D","&#273;"=>"d",
						"&#274;"=>"E","&#275;"=>"e","&#276;"=>"E","&#277;"=>"e","&#278;"=>"E","&#279;"=>"e","&#280;"=>"E","&#281;"=>"e","&#282;"=>"E","&#283;"=>"e",
						"&#284;"=>"G","&#285;"=>"g","&#286;"=>"G","&#287;"=>"g","&#288;"=>"G","&#289;"=>"g","&#290;"=>"G","&#291;"=>"g","&#292;"=>"H",
						"&#293;"=>"h","&#294;"=>"H","&#295;"=>"h",
						"&#296;"=>"I","&#297;"=>"i","&#298;"=>"I","&#299;"=>"i","&#300;"=>"I","&#301;"=>"i","&#302;"=>"I","&#303;"=>"i","&#304;"=>"I","&#305;"=>"i",
						"&#306;"=>"IJ","&#307;"=>"ij","&#308;"=>"J","&#309;"=>"j",
						"&#310;"=>"K","&#311;"=>"k","&#312;"=>"k",
						"&#313;"=>"L","&#314;"=>"l","&#315;"=>"L","&#316;"=>"l","&#317;"=>"L","&#318;"=>"l","&#319;"=>"L","&#320;"=>"l","&#321;"=>"L","&#322;"=>"l",
						"&#323;"=>"N","&#324;"=>"n","&#325;"=>"N","&#326;"=>"n","&#327;"=>"N","&#328;"=>"n","&#329;"=>"'n","&#330;"=>"N","&#331;"=>"n",
						"&#332;"=>"O","&#333;"=>"o","&#334;"=>"O","&#335;"=>"o","&#336;"=>"O","&#337;"=>"o","&#338;"=>"OE","&#339;"=>"oe",
						"&#340;"=>"R","&#341;"=>"r","&#342;"=>"R","&#343;"=>"r","&#344;"=>"R","&#345;"=>"r",
						"&#346;"=>"S","&#347;"=>"s","&#348;"=>"S","&#349;"=>"s","&#350;"=>"S","&#351;"=>"s","&#352;"=>"S","&#353;"=>"s",
						"&#354;"=>"T","&#355;"=>"t","&#356;"=>"T","&#357;"=>"t","&#358;"=>"T","&#359;"=>"t",
						"&#360;"=>"U","&#361;"=>"u","&#362;"=>"U","&#363;"=>"u","&#364;"=>"U","&#365;"=>"u","&#366;"=>"U","&#367;"=>"u","&#368;"=>"U","&#369;"=>"u","&#370;"=>"U","&#371;"=>"u",
						"&#372;"=>"W","&#373;"=>"w","&#374;"=>"Y","&#375;"=>"y","&#376;"=>"Y",
						"&#377;"=>"Z","&#378;"=>"z","&#379;"=>"Z","&#380;"=>"z","&#381;"=>"Z","&#382;"=>"z","&#383;"=>"s");
	$s = strtr($s, $exceptions); // replace some special characters
	$s = ct_htmlentities($s);
	$exceptions = array('&auml;'=>'ae', '&Auml;'=>'Ae', '&ouml;'=>'oe', '&Ouml;'=>'Oe', '&uuml;'=>'ue', '&Uuml;'=>'Üe', '&szlig;'=>'ss', '&OElig;'=>'Oe', '&oelig;'=>'oe', '&AElig;'=>'Ae', '&aelig;'=>'ae', '&eth;'=>'th', '&ETH;'=>'TH', '&middot;'=>'.', '&nbsp;'=>' ');
	$s = strtr($s, $exceptions); // replace some special characters with their 2 letter equivalents
	$s = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|slash|ring|cedil|caron);/','$1',$s); // replace other special characters with their 1 letter equivalent.
	if ($onlyascii) {
		$s = str_replace(array('&amp;','/','\\'),array('+','-','-'),$s);
		$s = preg_replace('/&[0-9a-zA-Z]+;/','',$s); 	  // remove remaining entities.
		$s = ereg_replace('[^0-9a-zA-Z_,\. -:]', "", $s); // Removes all non-ascii characters and also all remaining entities!
	}
	return ct_html_entity_decode($s);
}

/**
 * Encode as HTML entities, using the current character set of ConfTool
 *
 * @param string $str string to encode
 * @param int $quote_style shall quotes and double codes be encoded? see htmlentities.
 * @return string encoded string.
 */
function ct_htmlentities($str, $quote_style=ENT_COMPAT) {
	global $ctconf;
	return htmlentities($str, $quote_style, $ctconf['charset']);
}

/**
 * Decode HTML Entities using the current Charset of ConfTool Pro
 *
 * @param string $str string to decode
 * @param int $quote_style shall quotes and double codes be decoded? see htmlentities.
 * @return string decoded string.
 */
function ct_html_entity_decode($str, $quote_style=ENT_COMPAT, $charset=null) {
	global $ctconf;
	if (!$charset)
		$charset = strtoupper($ctconf['charset']);
	if ($charset=='UTF-8' && substr(phpversion(),0,1)<5) {
		ct_load_lib('conversion.lib');
		return html_entity_decode_php4($str, $quote_style);
	} else {
		return html_entity_decode($str, $quote_style, $charset);
	}
}

/**
 * Detect browser version
 * Returns array with keys "name", "version"
 * browser may be: "opera", "msie", "koqueror", "safari", "firefox", "mozilla"
 * Based on code by Gary White
 *
 * @return array array with name and version of the browser
 */
function ct_detect_browser() {
	global $session;
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);

	if (is_object($session) && is_array($session->get('user_agent')))
		 return $session->get('user_agent');

	$b['name']  = "";
	$b['version']  = "";

	// test for Opera
	if (eregi("opera",$ua)){
		$x = stristr($ua, "opera");
		if (eregi("/", $x)){
			$x = explode("/",$x);
			$b['name'] = $x[0];
			$x = explode(" ",$x[1]);
			$b['version'] = $x[0];
		}else{
			$x = explode(" ",stristr($x,"opera"));
			$b['name'] = $x[0];
			$b['version'] = $x[1];
		}

	// test for MS Internet Explorer
	}elseif(eregi("msie",$ua) && !eregi("opera",$ua)){
		$x = explode(" ",stristr($ua,"msie"));
		$b['name'] = $x[0];
		$b['version'] = $x[1];

	// test for Konqueror
	}elseif(eregi("konqueror",$ua)){
		$x = explode(" ",stristr($ua,"konqueror"));
		$x = explode("/",$x[0]);
		$b['name'] = $x[0];
		$b['version'] = $x[1];

	// test for Safari
	}elseif(eregi("safari", $ua)){
		$b['name'] = "safari";
		$x = explode(" ",stristr($ua,"Version"));
		if (isset($x[0])) {
			$x = explode("/",$x[0]);
			if (isset($x[1])) $b['version'] = $x[1];
		}

	// test for Firefox
	}elseif(eregi("firefox", $ua)){
		$b['name']="firefox";
		$x = stristr($ua, "firefox");
		$x = explode("/",$x);
		$b['version'] = $x[1];

	// test for Mozilla
	}elseif(eregi("mozilla",$ua) &&
		eregi('rv:[0-9]\.[0-9]',$ua) && !eregi("netscape",$ua)){
		$b['name'] = "mozilla";
		$x = explode(" ",stristr($ua,"rv:"));
		eregi('rv:[0-9]\.[0-9]\.[0-9]',$ua,$x);
		$b['version'] = str_replace("rv:","",$x[0]);

	// tests for Netscape
	}elseif(eregi("netscape",$ua)){
		$x = explode(" ",stristr($ua,"netscape"));
		$x = explode("/",$x[0]);
		$b['name'] = $x[0];
		$b['version'] = $x[1];
	}elseif(eregi("mozilla",$ua) && !eregi('rv:[0-9]\.[0-9]\.[0-9]',$ua)){
		$x = explode(" ",stristr($ua,"mozilla"));
		$x = explode("/",$x[0]);
		$b['name'] = "netscape";
		$b['version'] = $x[1];
	}

	// clean up browser and version strings
	$b['name'] = ereg_replace("[^a-z,A-Z]", "", $b['name']);
	$b['version'] = ereg_replace("[^0-9,.,a-z,A-Z]", "", $b['version']);
	if (is_object($session)) $session->put('user_agent',$b);

	return $b;
}

/**
 * Detect if a robot is accessing ConfTool
 *
 * @return boolean true if it is (probably) a web robot.
 */
function ct_detect_robot() {
	global $session;
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);

	if (is_object($session) && $session->get('is_robot'))
		 return ($session->get('is_robot')=='true'?true:false);

	$ret = 'false';
	$robot_agents = array('robot','crawl','appie','architext','bjaaland','cfetch','ferret',
		'googlebot','gulliver','harvest','htdig','jeeves','linkwalker','lycos_','moget',
		'muscatferret','myweb','nomad','scooter','slurp','voyager','weblayers',
		'antibot','bruinbot','digout4u','echo','fast-webcrawler','ia_archiver',
		'jennybot','mercator','netcraft','msnbot','petersnews','unlost_web_crawler',
		'virus_detector','voila','webbase','webcollage','wget','wisenutbot','zyborg');
	foreach ($robot_agents as $r) {
		if (eregi($r,$ua)) $ret = 'true';
	}
	if (is_object($session)) $session->put('is_robot',$ret);
	return ($ret=='true'?true:false);
}




/**
 * Encode e-mails to avoid spam robots (C) by Harald Weinreich 2000 and later (and still works!)
 */
function ct_encodeMail($email,$name="") {
	$c="";
	$regs="";
	eregi('^([_\.0-9a-z-]+)\@([0-9a-z][\.0-9a-z-]+)\.([a-z]{2,4})$',$email,$regs);
	#$c .= $regs[1]."--".$regs[2]."--".$regs[3];
	if ($regs[0]=="")
        $c = $email;
	else
	{
        $c .= "<script type=\"text/javascript\">\n<!--\n";
        $c .= "document.write('<A HREF=\"mailto:'+'".$regs[1]."'+unescape('%40')+'".$regs[2]."'+unescape('%2E')+'".$regs[3]."\">'+'";
        if ($name=="")
			$c .= $regs[1]."'+unescape('%40')+'".$regs[2]."'+unescape('%2E')+'".$regs[3]."<\\/A>');\n";
        else
			$c .= $name."<\\/A>');\n";
        $c .= "// -->\n</script>";
        $c .= "<noscript>\n";
        $c .= "<nobr>".$regs[1]."<span style='color:#800;' title='replace by @'>&#x7b;&#x61;&#x74;&#x7d;</span>".$regs[2]."<img src=\"images/dot.gif\" align=\"bottom\">".$regs[3]."</nobr>\n";         // &#x40; ist "@" , &#x2E; ist "."
        $c .= "</noscript>";
	}
	return $c;
}

?>