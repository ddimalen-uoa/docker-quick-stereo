<?php
// This file contains some php settings that you may use if your other attempts did not work.
if (!defined('CONFTOOL')) die('Hacking attempt!');

//Settings to find the main conftool files.
ini_set('include_path', '../etc');

// Short Open Tags are not required for ConfTool
ini_set('short_open_tag', '0');

// 900s Execution time may be better for export functions, bulk mails etc.
ini_set('max_execution_time', '900');

// Disable compression, as it may cause problems on some servers.
@ini_set('zlib.output_compression', 0);

// This DOES NOT Work! You have to change this in php.ini!
ini_set('session.gc_maxlifetime',12000);

// Disable the output of notices (that is usually php default!)
error_reporting ( 2039 ); // Disable "notices".
#error_reporting ( 2048 ); // For debugging you may want to see all infos.

// Do not show error messages to the user.
ini_set("display_errors",0); // this should always be '0' in an productive environment!
ini_set("display_startup_errors",0); // this should always be '0' in an productive environment!

// Enable short session keys.
ini_set("session.hash_bits_per_character",'6');

// Enable or disable the use of cookies.
// Usually it is recommendable NOT to use URL encoded session keys for security reasons,
// but still many users have problems with this...
#ini_set("session.use_cookies", 1);
#//ini_set("session.use_only_cookies", 1);
#ini_set("session.use_trans_sid", (1-ini_get("session.use_only_cookies")) );
#ini_set("session.cookie_httponly", ini_get("session.use_only_cookies") ); // PHP >=5.2

?>