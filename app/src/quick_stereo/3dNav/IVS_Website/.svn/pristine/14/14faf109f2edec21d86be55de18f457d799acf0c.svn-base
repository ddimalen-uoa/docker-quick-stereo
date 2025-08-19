<?php
if (!defined('CONFTOOL')) die('Hacking attempt!');

class CTSession {

	/**
	 * default constructor
	 * starts HTTP-session and registers array
	 *
	 */
	function CTSession($checkActive=true) {
		global $ctconf, $http;

		// Set ConfTool session identifier name
		$sid_name = 'CTSID';
		if (isset($ctconf['web/sessionname']) && ct_strlen(preg_replace('/[^A-Z0-9]/','',strtoupper($ctconf['web/sessionname'])))>0)
			$sid_name .= '_'.preg_replace('/[^A-Z0-9_]/','',ct_strtoupper($ctconf['web/sessionname']));
		session_name($sid_name);

		// Determine session ID - PHP knows many methods...
		$sid = null;
		if (isset($_COOKIE[$sid_name])) {// In Cookie?
			$sid = $_COOKIE[$sid_name];
			// if (session_id()=='' && $sid!='') session_id($sid); 	// Not required here! Otherwise the cookie key will be added to each URL!
		}
		elseif (isset($http[$sid_name])) {	// in POST or GET?
			$sid = $http[$sid_name];
			if (session_id()=='' && $sid!='') session_id($sid); 	// if session ID was transmitted and PHP had problems to find it, set it manually.
		}
		elseif (isset($_SERVER['HTTP_COOKIE'])) {	// Maybe somewhere else in Cookie?
			$cookies = split (";", $_SERVER{'HTTP_COOKIE'});
			foreach ($cookies as $cookie) {
			    $cookie = explode('=', $cookie);
			    $key    = preg_replace('|\s|', '', $cookie[0]);
		    	$value  = isset($cookie[1]) ? $cookie[1] : '';
		    	#if (!isset($_COOKIE[$key])) $_COOKIE[$key] = $value;
		    	if ($key==$sid_name) {
		    		$sid = $value;
					if (session_id()=='' && $sid!='') session_id($sid); 	// if session ID was transmitted and PHP had problems to find it, set it manually.
		    	}
			}
		}

		// Start Session!
		// When Session ID was found, check it it is valid. Otherwise create new session...
		if(isset($sid)) {
			// Check if session key is valid
	        $sbpc = ini_get("session.hash_bits_per_character");
	        // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ","). 4 is default.
	        // Before PHP 5, this value is 0 or '' and 4 bits (=hex string) were used.
			#if ( preg_match('/^[0-9a-f]+$/',$sid) ||
			#	 ($sbpc==5 && preg_match('/^[0-9a-v]+$/',$sid)) ||
			#	 ($sbpc==6 && preg_match('/^[-,0-9a-z]+$/i',$sid)) ) {
			if ( preg_match('/^[-,0-9a-z]+$/i',$sid) ) {
				session_start();
			} else {
				// Session ID check failed!
				if (function_exists('session_regenerate_id')) {
					@session_start();	// Start new session and suppress error messages
					session_regenerate_id();	// Create a new session ID. (php > 4.3.2)
				} else {
					session_id(md5(microtime()));
					@session_start();
				}
				ct_redirect(ct_pageurl('logout'));
			}
		} else {
			// Start new session, no session ID found.
			@session_start();
		}

		// Delete queries (used for debugging.)
		$this->del('queries'); $this->put('querycount',0);

		// Do not check if session is active or BASEURL is correct. This is only required
		// For some direct access libraries (usually for credit card processing)
		if ($checkActive===false) return true;

		if (!isset($_SESSION['active'])) {
			$request_host = $_SERVER['HTTP_HOST'];
			$request_scheme = "http";
			if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == "on" || $_SERVER['HTTPS'] == "1")) {
				$request_scheme = "https";
			}
			$baseurl = parse_url(ct_getbaseurl());
			$basehost = $baseurl['port'] ? $baseurl['host'].':'.$baseurl['port'] : $baseurl['host'];
			if ($ctconf['web/redirecttobaseurl'] &&
				     ($request_host != $basehost || $request_scheme != $baseurl['scheme'])) { // if another host was explicitly defined, redirect.
				ct_redirect($ctconf['web/baseurl']);
				if (is_object($db)) $db->disconnect();
				exit();
			}
			$_SESSION['active'] = true;
			$_SESSION['baseurl'] = ct_getbaseurl();
			//ct_redirect(ct_pageurl('',array(),'page=login')); // To allow search engines ;-)
			//ct_redirect(ct_pageurl('login'));
		}

		// if the user changed to a conference with a differenz baseurl log him out and read default configuration file!
		if ($_SESSION['baseurl']!=ct_getbaseurl()) {
			$this->put('conf');
			$this->logout();
		}

		// Keep a history of last URLs visited for debugging purposes...
		$url_now = (isset($_SERVER['HTTPS'])?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if (!isset($_SESSION['url_history']) ||
				(isset($_SESSION['url_history']) && ct_thisurl()!=$_SESSION['url_history'])) {
			for ($i=4; $i>0; $i--) {
				if (isset($_SESSION["url_history_".($i-1)])) $_SESSION["url_history_$i"]=$_SESSION["url_history_".($i-1)];
			}
			$_SESSION["url_history_1"]=$_SESSION["url_history"];
			$_SESSION["url_history"]=$_SESSION["url_now"];
			$_SESSION["url_now"]=$url_now;
		}
	}

	/**
	 * try to login for this session
	 */
	function login($uname, $pword) {
		$user = new CTPerson;
		if ($user->login($uname, $pword)) {
			$_SESSION['loggedin'] = true;
			$_SESSION['user'] =& $user;
			if (function_exists('session_regenerate_id')) {
				session_regenerate_id();	// Create a new session ID. (php > 4.3.2)
			}
			return true;
		} else {
			$_SESSION['loggedin'] = false;
			return false;
		}
	}

	/**
	 * logout this session
	 */
	function logout() {
		global $http, $db;

		// logout user
		$user =& $this->get_user();
		if (is_object($user) && is_object($db)) $user->logout(); // Logout user if user exists and db is already loaded (does not apply if the conference is switched).

		// save some parameters
		$page='login'; if (in_http('page') && $http['page']!='logout') $page=$http['page'];
		$conf = $this->get('conf');  // Save the conference.
		$msgi=$this->get('infobox');$msge=$this->get('errorbox');$msgw=$this->get('warningbox'); // Messageboxes...
		#$formpara=array(); reset ($http);
		#while (list($u,$v) = each($http)) {	if (substr($u,0,5)=='form_') $formpara=array_merge($formpara, array($u=>stripslashes($v))); }
		$formpara=ct_http_array();

		session_unset();

		$this->put('conf',$conf);    // and restore
		$this->put('infobox', $msgi); $this->put('errorbox', $msge); $this->put('warningbox', $msgw);
		$_SESSION['active'] = true;	// session key still exists...
		$_SESSION['baseurl'] = ct_getbaseurl(); // remember new baseurl
		#ct_redirect(ct_pageurl($page,array('key'=>$http['key']))); => Mit form_...
		ct_redirect(ct_pageurl($page,$formpara));
	}

	/**
	 * logout this and return to conference site
	 */
	function logout_return($uri='') {
		global $db;

		if ($uri=='') $uri=ctconf_get('conferenceURI','http://www.conftool.net/');

		// Logout user if user exists and db is already loaded
		$user =& $this->get_user();
		if (is_object($user) && is_object($db)) $user->logout();

		$conf = $this->get('conf');  // Save the conference name.
		session_unset();
		$this->put('conf',$conf);    // and restore

		ct_redirect($uri);
	}

	/**
	 * logout old user, login new user...
	 */
	function become($person) {
		$person->set('logindate',ct_timestamp_2_datetime(ct_time()));  // Save last login...
		$person->set('logoutdate','0000-00-00 00:00:00');
		$person->persist();
		$_SESSION['user'] =& $person;
	}

	/**
	 * check if user is logged in
	 */
	function loggedin() {
		$user=$this->get_user();
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true &&
				 $user->get('deleted')==0 &&
				 ($user->get('logindate')!='0000-00-00 00:00:00' ||
				  $user->get('logoutdate')=='0000-00-00 00:00:00') )
			return true;
		else
			return false;
	}

	/**
	 * Create a (usually green) information box that will displayed on the next page
	 */
	function put_infobox($title, $msg) {
		$oldmsg = $this->get('infobox');
		if (is_array($oldmsg)) $oldmsg[]=array($title, $msg);
		else $oldmsg=array(array($title, $msg));
		$this->put('infobox', $oldmsg);
	}

	/**
	 * Create a (usually red) error box that will displayed on the next page
	 */
	function put_errorbox($title, $msg) {
		$oldmsg = $this->get('errorbox');
		if (is_array($oldmsg)) $oldmsg[]=array($title, $msg);
		else $oldmsg=array(array($title, $msg));
		$this->put('errorbox', $oldmsg);
	}

	/**
	 * Create a (usually yellow) warning box that will displayed on the next page
	 */
	function put_warningbox($title, $msg) {
		$oldmsg = $this->get('warningbox');
		if (is_array($oldmsg)) $oldmsg[]=array($title, $msg);
		else $oldmsg=array(array($title, $msg));
		$this->put('warningbox', $oldmsg);
	}

	/**
	 * Figure out if a warning or error is "in the queue"
	 */
	function has_error_or_warningbox() {
		return (is_array($this->get('warningbox')) || is_array($this->get('errorbox')));
	}

	/**
	 * Show all messageboxes (error, warning and info boxes).
	 */
	function show_messageboxes() {
		// Show info, error and warning box
		$errorbox = $this->get('errorbox');
		if ($errorbox && is_array($errorbox)) {
			$errorbox = $this->merge_messages($errorbox);
			foreach ($errorbox as $box) ct_errorbox($box[0], $box[1]);
		}
		$warningbox = $this->get('warningbox');
		if ($warningbox && is_array($warningbox)) {
			$warningbox = $this->merge_messages($warningbox);
			foreach ($warningbox as $box) ct_warningbox($box[0], $box[1]);
		}
		$infobox = $this->get('infobox');
		if ($infobox && is_array($infobox)) {
			$infobox = $this->merge_messages($infobox);
			foreach ($infobox as $box) ct_infobox($box[0], $box[1]);
		}
		$this->clear_messageboxes();
	}

	/**
	 * Merge messages if they have the same title to show them in one box...
	 *
	 * @param array $messages array of messages
	 * @return array new merged array of messages
	 */
	function merge_messages($messages) {
		$anew=array();
		if ($messages && is_array($messages)) {
			foreach ($messages as $m) {
				$found=false;
				foreach ($anew as $n=>$v) {
					if ($m[0]===$v[0]) {
						$anew[$n][1].="<br>".$m[1];
						$found=true;
					}
				}
				if (!$found) $anew[]=$m;
			}
		}
		return $anew;
	}

	/**
	 * Remove all message boxes from session variable...
	 */
	function clear_messageboxes() {
		$this->del('errorbox');
		$this->del('warningbox');
		$this->del('infobox');
	}

	/**
	 * get a value from session data
	 *
	 * @param string $key Key of the data to get from this session...
	 */
	function &get($key) {
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			$x = false;
			return $x;  // Workaround for stupid php warning message.
		}
	}

	/**
	 * remove a value from session data
	 */
	function del($key) {
		if (isset($_SESSION[$key])) {
			// if (gettype($_SESSION[$key])=="object") $_SESSION[$key]=NULL;
			$_SESSION[$key]=NULL;
			$val = $_SESSION[$key];
			unset($_SESSION[$key]);
			unset($val); // needed for a bug in some php versions
			session_unregister($key);    // needed for another bug in php 4.2!
		}
	}

	/**
	 * returns the current user object
	 */
	function &get_user() {
		return $this->get('user');
	}

	/**
	 * reload the user object from DB
	 */
	function reload_user() {
		$u = $this->get('user');
		if ($u==null) return false;
		$u->reload();
		$this->put('user',$u);
		return true;
	}

	/**
	 * reload and save in DB that user did do something
	 */
	function reload_save_useraction() {
		$u = $this->get('user');
		if ($u==null) return false;
		$u->reload_save_useraction();
		$this->put('user',$u);
		return true;
	}


	// put a key-value pair into session data
	function put($key, $val="") {
		$_SESSION[$key] = $val;
	}

	// put a key-value pair into session data (by reference!)
	function putref($key, &$val) {
		$_SESSION[$key] = $val;
	}

	function check_request() {
		return false;
	}

	// Store the URL to return to from edit pages
	function set_besturl($pageinfo="") {
		global $http;
//		if (isset($_SERVER['REQUEST_URI']))
//			$url = ct_getbaseurl(false).str_replace("&","&amp;",$_SERVER['REQUEST_URI']);
//		else {
//		    $url = ct_getbaseurl(false).$_SERVER['PHP_SELF'];
//		    if (isset($_SERVER['QUERY_STRING'])) $url = $url."?".str_replace("&","&amp;",$_SERVER['QUERY_STRING']);
//        }
		$url = ct_pageurl($http['page'],ct_http_array());
		$this->put('besturl', $url);
		$this->put('besturlinfo', $pageinfo);
	}

	// Get the URL
	function get_besturl() {
		return $this->get('besturl');
	}
	// Get the Info (One word that describes the page)
	function get_besturlinfo() {
		return $this->get('besturlinfo');
	}

}
?>