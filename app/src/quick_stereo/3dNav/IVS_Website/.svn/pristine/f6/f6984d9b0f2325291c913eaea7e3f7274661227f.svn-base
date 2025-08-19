<?php
// Set constant to allow execution of clases, libs and pages
define('CONFTOOL', 1);

#ob_end_flush();

// see settings.php!
include('settings.php');

$check = new CheckSystem();
$check->run();

class CheckSystem {

	var $line=0;

	function run() {
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
		echo "\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>ConfTool Setup Info Page</title>\n";
		// set character-set
		echo "<meta HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=ISO-8859-1\">\n";
		// Load standard ConfTool CSS file:
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"conftool.css\">\n";
		echo "</head>\n<body>\n";
		echo "<H1>ConfTool System Check</H1>";

		echo "<table width=700 cellpadding=3 cellspacing=0 border=0>";

		ini_set("display_errors",true); // this should always be 'false' in an productive environment!
		ini_set("display_startup_errors",true); // this should always be 'false' in an productive environment!
		error_reporting(E_ALL);

		if (include('conftool.inc.php')) {
			$this->info('PHP Include Path OK', 'The conftool include files could be loaded.<br>include_path is: <code>'.ini_get('include_path').'</code><br>Location of this script: <code>'.$_SERVER['SCRIPT_FILENAME'].'</code>');
		} else {
			$this->error('PHP Include Path ERROR!', 'The conftool include files could <b>not</b> be loaded.<br>include_path is: <code>'.ini_get('include_path').'</code><br>Location of this script: <code>'.$_SERVER['SCRIPT_FILENAME'].'</code>');
			echo "</table>";
			die();
		}

		ini_set("display_errors",false); // this should always be 'false' in an productive environment!
		ini_set("display_startup_errors",false); // this should always be 'false' in an productive environment!

		// Get also global parameters...
		$param = ini_get_all();
		#ct_print_r($param);

		// Set to false to remove the e-mail test! --------------------------------------------
		$test_mail=true;
		if ($test_mail && function_exists('ct_test_lib') && function_exists('ctconf_get') && ctconf_get('mail/contact')!='') {
			if (ct_test_lib('mail.lib')) {
				ct_load_lib('mail.lib');
				$receiver = ctconf_get('mail/contact');
				if (ct_mail($receiver,'ConfTool Test E-Mail',"This is a test e-mail of the ConfTool installation information page.\n\n".ct_getbaseurl()."\n\n",$receiver,'info.php Test Mailer','','',array('testing@conftool.net'))) {
					$this->info('Test E-Mail Sent', 'ConfTool just sent a test e-mail to: <code><b>'.$receiver.'</b></code>.<br><b>Please check your inbox for the e-mail</b>. If the mail does <b>not</b> arrive, check your settings (e.g. the mail parameters in conftool.conf, the firewall settings, the mail server configuration).<br><b>'.(ctconf_get('mail/phpmailer')?"PHPMailer was used":"The build-in PHP mail function was used (not recommended)").'</b>');
				} else {
					$error_message = '';
					if ($_SESSION) $error_message = "<br>Error Message: ".$_SESSION['last_mail_error'];
					$this->error('Error Sending Test E-Mail', 'ConfTool could not send a test e-mail to: <code><b>'.$receiver.'</b></code>.<br>1. Does the address exist?<br>2. Please check your settings (mail parameters in conftool.conf, <b>firewall</b> settings, <b>mail server</b> configuration, virus scanners).<br><b>'.(ctconf_get('mail/phpmailer')?"PHPMailer was used":"The build-in PHP mail function was used (not recommended)").'</b>'.$error_message);
				}
			} else {
				$this->error('ConfTool Mail Library NOT Found', 'Your installation is broken. Please have a look at the installation instructions.<br>Path of lib directory: <code>'.ct_get_path('lib').'</code>');
			}
		}

		$this->spacer();

		$php_version = str_replace('.','',(substr(phpversion(),0,5)));
		if ( $php_version < 430) {
			$this->error('PHP Version Too Old!', 'Please update your php version! Version 4.3 or later required, PHP 5 recommended.<br>PHP version found: '.phpversion() );
		} elseif ($php_version < 448 || ($php_version >500 && $php_version < 528)) {
			$this->warning('PHP Version Outdated', 'ConfTool should work with your PHP version, but a newer version is available. Please update for security reasons!<br>PHP version found: '.phpversion() );
        } else {
			$this->info('PHP version OK', 'The PHP version should work fine with conftool.<br>PHP version found: '.phpversion() );
        }

        if (function_exists('mysql_get_client_info')) {
			$this->info('PHP MySQL Extension Found', 'The PHP extension to access the mysql database was found.');

	        if (mysql_connect('localhost', 'root', '')) {
				$this->error('MySQL root password empty!', 'The root password for your mysql database is empty!<br>This is a severe security problem.');
	        }

	        $mysql_api_version = mysql_get_client_info();
	        $mysql_server_version = mysql_get_server_info();
	        if ($mysql_api_version!='' && $mysql_server_version!='') {
				if (substr($mysql_api_version, 0, 3) != substr($mysql_server_version, 0, 3)) {
					$this->warning('MySQL Server and PHP Client Version Mismatch', 'Your MySQL server version and PHP client API version do <b>not</b> match. This may cause problems!<br>Please fix your configuration!<br>PHP Client API version: <code>'.$mysql_api_version.'</code> <br>MySQL Server version: <code>'.$mysql_server_version.'</code>');
				}
	        }

        } else {
			$this->error('PHP MySQL Extension not Found', 'The PHP extension to access the mysql database was <b>not</b> found.<br>You need to enable the extension to use ConfTool!');
        }

        global $ctconf;
        if (isset($ctconf)) {
			$db_link = mysql_connect($ctconf['db/host'].':'.$ctconf['db/port'],$ctconf['db/username'], $ctconf['db/password']);
			if (!$db_link) {
				$this->error('Could Not Connect to MySQL Server!', mysql_error());
	        }  else {
				$this->info('Connected To MySQL Server', 'Username and password in conftool.conf.php have been accepted.');
	       		$db_selected = mysql_select_db($ctconf['db/database'],$db_link);
				if (!$db_selected) {
					$this->error('Cannot Select Database \''.$ctconf['db/database'].'\'', 'Please check your mysql configuration and the ConfTool configuration in conftool.conf.php<br>' . mysql_error());
				} else {
					$this->info('ConfTool Database Selected', 'The database <b>\''.$ctconf['db/database'].'\'</b> has been selected');
					$r1 = mysql_query('SELECT * FROM ctconf');
					$r2 = mysql_query('SELECT * FROM phases');
					if ($r1 && $r2) {
						#$this->info('The database has been initialized','ConfTool should work with your database configuration.');
					} else {
						#$this->error('Default data missing','Several tables are missing, please create the required tables and install the default data!');
					}

				}
	        }

        }

        $this->spacer();

		if (function_exists("exec")) {
			$this->info('PHP Function "exec" found', 'The PHP function "exec" was found. It is required for DNS-verification of user emails');
        } else {
			$this->warning('PHP Function "exec" not found', 'The PHP function "exec" is disabled. Please enable, it is recommended for DNS-verification of user emails');
        }

		if (ct_test_lib('mail.lib')) {
			ct_load_lib('mail.lib');
			if (ctconf_get('mail/checkdns')===false) {
				$this->warning('DNS Check Disabled', 'You have disabled the DNS check in your configuration file "conftool.conf".');
			} else {
				if (ct_checkdnsrr('conftool.net') && ct_checkdnsrr('weinreichs.de'))
					$this->info('DNS check works fine', 'The Domain Name check of ConfTool seems to work fine. It is used to test the validity of e-mails');
				else
					$this->error('DNS check failed', 'The <b>Domain Name check</b> of ConfTool does not work. It is required to test the <b>validity of e-mails</b>. Please check if "host" and/or "nslookup" are installed on your server or if the PHP function "checkdnsrr" works correctly. If all this does not help, please disable the DNS check in "conftool.conf.php"');
			}
		}

		# POPEN is not required, if SMTP is used => Standard in ConfTool!
		#if (function_exists("popen")) {
		#	$this->info('PHP Function "popen" found', 'The PHP function "popen" was found. It is required for sending e-mails.');
        #} else {
		#	$this->warning('PHP Function "popen" not found', 'The PHP function "popen" is disabled. Please enable, it is required for sending emails with phpmailer.');
        #}

        if (@extension_loaded('iconv')) {
            $this->info('PHP Extension "iconv" Found', 'The PHP extension iconf was found.');
        } else {
	  		if (@extension_loaded('mbstring')) {
    	        $this->warning('PHP Extension "iconv" Not Found', 'The PHP extension iconf could not be found. Some ConfTool functions will not work properly.<br>Please update your PHP configuration');
	  		} else {
    	        $this->error('PHP Extension "iconv" Not Found', 'The PHP extension iconf could not be found. Some ConfTool functions will not work properly.<br>Please update your PHP configuration');
	  		}
        }

		if (function_exists("gd_info")) {
			$this->info('GD Extension Graphics Library Installed', 'The PHP GD library extension to manipulate graphics was found.<br>GD library info: '.str_replace(array(', , ',' 1,'),array(', ',''),implode(', ',gd_info())));
        } else {
			$this->error('GD Extension Graphics Library NOT Installed', 'Please install the PHP GD library extension to manipulate uploaded graphics. Only required for Conftool Pro.');
        }

        if (@extension_loaded('curl')) {
            $this->info('PHP Extension "curl" Found', 'The PHP extension curl was found.');
        } else {
            $this->warning('PHP Extension "curl" Not Found', 'The PHP extension curl could not be found. Some ConfTool functions will not work properly.<br>Please update your PHP configuration');
        }

  		if (@extension_loaded('mbstring')) {
            $this->info('PHP Extension "mbstring" Found', 'The PHP extension mbstring was found. It is used for UTF-8 encoded strings.');
        } else {
	        global $ctconf;
    	    if (isset($ctconf) && substr(strtoupper($ctconf['charset']),0,3)=='UTF') {
	            $this->error('PHP Extension "mbstring" Not Found', 'The PHP extension mbstring could not be found. Some ConfTool functions <b>will not work properly</b> if you use UTF-8. <b>Please update your PHP configuration!</b>');
    	    } else {
                $this->warning('PHP Extension "mbstring" Not Found', 'The PHP extension mbstring could not be found. If you plan to use UFT-8, some ConfTool functions will not work properly. Please update your PHP configuration.');
    	    }
        }

        $this->spacer();


		$memory_limit=$param['memory_limit']['global_value'];
		if ($memory_limit==0 || $memory_limit>ini_get('memory_limit')) $memory_limit=ini_get('memory_limit');
		if ($memory_limit==0) {
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
				$this->info('PHP Memory Limit Unknown', 'The php memory could not be read.<br>If you are using a Windows server, this setting does not exist, so do not be alarmed.<br>Please <b>test if uploads of big files</b> work fine.');
			else
				$this->warning('PHP Memory Limit Unknown', 'The php memory could not be read.<br>Please <b>test if uploads of big files</b> work fine.');
		} elseif ($this->_get_bytes($memory_limit)>(10240000*1.4+1000000))  {
			$this->info('PHP Memory Limit OK', 'Memory Limit OK, Uploads of Up to 10M should work.<br>memory_limit is: <code>'.$memory_limit.'</code>');
		} else {
			$this->error('PHP Memory Limit Error', 'The php memory limit is probably too small to allow the upload of bigger files and some functions like data exports<br>memory_limit is: <code>'.$memory_limit.'</code>');
		}


		$upload_max_filesize=$param['upload_max_filesize']['global_value'];
		if ($upload_max_filesize==0 || $upload_max_filesize>ini_get('upload_max_filesize')) $upload_max_filesize=ini_get('upload_max_filesize');
		if ($this->_get_bytes($upload_max_filesize)>10240000)  {
			$this->info('PHP Maximum File Size Setting for Uploads OK', 'Uploads of more than 10M are permitted.<br>upload_max_filesize is: <code>'.$upload_max_filesize.'</code>');
		} else {
			$this->error('Wrong PHP Maximum File Size Setting for Uploads', 'Uploads of up to 10M are <b>NOT</b> permitted.<br>upload_max_filesize is: <code>'.$upload_max_filesize.'</code>');
		}

		$post_max_size=$param['post_max_size']['global_value'];
		if ($post_max_size==0 || $post_max_size>ini_get('post_max_size')) $post_max_size=ini_get('post_max_size');
		if ($this->_get_bytes($post_max_size)>(10240000*1.1+1000000))  {
			$this->info('PHP Maximum Size of Posts is OK', 'Post commands of more than 12M are permitted.<br>post_max_size is: <code>'.$post_max_size.'</code>');
		} else {
			$this->error('Wrong PHP Maximum Size Setting for HTTP Posts', 'Post commands of more than 12M are <b>NOT</b> permitted, bigger uploads might fail.<br>post_max_size is: <code>'.$post_max_size.'</code>');
		}



		if (function_exists('ct_get_path')) {
			$uploaddir = ct_get_path('uploads');
		    if (!file_exists($uploaddir) && !ini_get('safe_mode')){
				$this->error('Directory for Uploads NOT Found', 'Path of upload directory: <code>'.$uploaddir.'</code>');
		    } elseif (!is_writeable($uploaddir)){
				$this->error('Directory for Uploads is NOT Writeable', 'The web server cannot write to the upload directory. Please have a look at the installation instructions.<br>Path of upload directory: <code>'.$uploaddir.'</code>');
			} else {
				$this->info('Directory for Uploads Found and Writeable', 'You still have to <b>test</b> if up- and downloads work!<br>Path of upload directory: <code>'.$uploaddir.'</code>');
			}
		}

		$uploadtempdir = ini_get('upload_tmp_dir');
	    if ($uploadtempdir==''){
			$this->warning('Temporary Directory for Uploads NOT set', 'The system will use your default temp (or tmp) directory for uploads. If you enconter any upload problems, please try to set this value in your php.ini. Current value: <br><code>upload_tmp_dir="'.$uploadtempdir.'"</code>');
	    } elseif (!file_exists($uploadtempdir) && !ini_get('safe_mode')){
			$this->error('Temporary Directory for Uploads NOT Found', 'The temporary upload directory was not found: <br><code>upload_tmp_dir="'.$uploadtempdir.'"</code>');
	    } elseif (!is_writeable($uploadtempdir)){
			$this->error('Temporary Directory for Uploads is NOT Writeable', 'The web server cannot write to the temporary upload directory. Please fix this setting. <br><code>upload_tmp_dir="'.$uploadtempdir.'"</code>');
		} else {
			$this->info('Temporary Directory for Uploads Set, Found and Writeable', 'You still have to <b>test</b> if up- and downloads work!<br><code>upload_tmp_dir="'.$uploadtempdir.'"</code>');
		}

		$this->spacer();


		// Test some settings: Safe_Mode Execution time etc.
        if ( ini_get('safe_mode') ){
            $this->warning('PHP Safe Mode enabled', 'PHP "Safe Mode" is enabled. Please note that this might cause problems with ConfTool, especially with file up- and downloads, bulk mails and all functions that are time-consuming (data exports, paper processing, auto-assignments etc.)<br>If possible, please update your php configuration!');
        } else {
            $this->info('PHP Safe Mode disabled', 'PHP "Safe Mode" is disabled. It may cause problems with ConfTool, please leave it this way.');
        }

		if (ini_get('max_execution_time')>=30)  {
			$this->info('Maximum Execution Time for Scripts', 'The maximum execution time for scripts is OK.<br>max_execution_time is: <code>'.ini_get('max_execution_time').'</code> (seconds)');
		} else {
			$this->warning('Maximum Execution Time for Scripts', 'The maximum execution time for scripts is quite short, it may cause problems for some functions.<br>max_execution_time is: <code>'.ini_get('max_execution_time').'</code> (seconds)');
		}

		$session_gc_maxlifetime=$param['session.gc_maxlifetime']['global_value'];
		#if ($session_gc_maxlifetime==0) $session_gc_maxlifetime=ini_get('session.gc_maxlifetime');
		if ($session_gc_maxlifetime>=5*60*60)  {
			$this->info('Long Session Life Time', 'The life time of PHP sessions should be long enough for most users.<br>session.gc_maxlifetime is: <code>'.$session_gc_maxlifetime.'</code> (seconds = '.(intval($session_gc_maxlifetime)/60).' minutes)');
		} elseif ($session_gc_maxlifetime>=2*60*60)  {
			$this->warning('Medium Session Life Time', 'The life time of PHP sessions is less than five hours. It might be too short for some reviewers.<br>session.gc_maxlifetime is: <code>'.$session_gc_maxlifetime.'</code> (seconds = '.(intval($session_gc_maxlifetime)/60).' minutes)');
		} else {
			$this->error('Short Session Life Time', 'The life time of PHP sessions is below two hours. <b>This will cause problems for many reviewers when they enter their evaluation.</b><br>Please increase the value in php.ini!<br>session.gc_maxlifetime is: <code>'.$session_gc_maxlifetime.'</code> (seconds = '.(intval($session_gc_maxlifetime)/60).' minutes)');
		}

		$this->spacer();

        if ( ini_get('register_globals')==1 || ini_get('register_globals')=='on' ){
            $this->error('PHP "Register Globals" enabled', 'PHP "Register Globals" is enabled.<br>Please disable this setting for security reasons!');
        } else {
            $this->info('PHP "Register Globals" disabled', 'PHP "register_globals" is disabled. This is <b>good and recommended</b>, as "register_globals" is known to cause security problems in many PHP applications.');
        }


		if (function_exists("show_source") || function_exists("system") ||
				function_exists("shell_exec") || function_exists("passthru") ||
				function_exists("phpinfo") || function_exists("proc_open") ||
				function_exists("proc_nice")) {
			$this->warning('Several Unrequired PHP System Functions Are Enabled', 'The following PHP functions should be disabled with <code>disable_functions</code> if not required by other PHP applications, as they could be used for attacking your server. They are not used by ConfTool:<br><code>'.
			(function_exists("show_source")?'show_source(), ':'').
			(function_exists("system")?'system(), ':'').
			(function_exists("shell_exec")?'shell_exec(), ':'').
			(function_exists("passthru")?'passthru(), ':'').
			(function_exists("phpinfo")?'phpinfo(), ':'').
			(function_exists("proc_open")?'proc_open(), ':'').
			(function_exists("proc_nice")?'proc_nice(), ':'').'</code>');
		}

		#if (error_reporting()) {
			#echo "<code><b>error_reporting</b>        is <b>'".error_reporting()."'</b></code>\n";
		#}

		#echo "<code><b>short_open_tag</b>         is <b>'".ini_get('short_open_tag')."'</b></code>\n";

		// Test suhosin settings
		#if (@ini_get('suhosin.request.max_value_length')) {
		#    $this->warning('Warning: Suhosing Setting May Cause Problems',
		#    	'max_value_length is set to: '. ini_get('suhosin.request.max_value_length'));
		#}


		echo "</table><br><br><br>\n";

		if (isset($http['test_timeout']) && $http['test_timeout']) {
		 	#while (ob_get_level() > 0) { ob_end_flush(); }
			for($i=0;$i<900;$i++) {
				echo $i."<br>\n"; sleep(1); ob_flush();	flush();
			}
		}

		echo "</body>\n";

	}


    /**
     * Returns the value from a php.ini setting in bytes
     */
    function _get_bytes($val) {
		$val = trim($val);
		$last = $val{strlen($val)-1};
		switch ($last) {
		case 'g': case 'G':
	    	$val *= 1024;
		case 'm': case 'M':
		    $val *= 1024;
		case 'k': case 'K':
	    	$val *= 1024;
		}
		return $val;
    }

    function spacer() {
		echo "<tr><td colspan=2 valign=top class='form_td_separator_gradient'><img src='spacer.gif' width=1 height=1></td></tr>\n";

    }

	// Output functions...
	function error($title, $message) {
		$class=($this->line++%2?'class="oddrow_del"':'class="evenrow_del"');
		echo "<tr><td valign=top $class><span class='font10 fontbold'>$title</span><br>";
		echo "<span class='font10'>$message</span><br><br></td>";
		echo "<td valign=top $class><img src='images/error.gif'></td></tr>\n";
	}

	function warning($title, $message) {
		$class=($this->line++%2?'class="brightbg"':'');
		echo "<tr><td valign=top $class><span class='font10 fontbold'>$title</span><br>";
		echo "<span class='font10'>$message</span><br><br></td>";
		echo "<td valign=top $class><img src='images/warning-yellow.gif'></td></tr>\n";
	}

	function info($title, $message) {
		$class=($this->line++%2?'class="brightbg"':'');
		echo "<tr><td valign=top $class><span class='font10 fontbold'>$title</span><br>";
		echo "<span class='font10'>$message</span><br><br></td>";
		echo "<td valign=top $class><img src='images/success.gif'></td></tr>\n";
	}

}



#phpinfo();

/*for($i=0;$i<900;$i++) {
	echo $i."<br>\n";
	sleep(1);
	flush();
}*/

?>
