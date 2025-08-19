<?php
// Error page.

if (!defined('CONFTOOL')) die('Hacking attempt!');

if (is_object($session) && is_object($user) && $session->loggedin()) {
	ct_error_log("Warning: Access denied. [Last: ".$session->get('url_history').", Last-1: ".$session->get('url_history_1').", Last-2: ".$session->get('url_history_2')."]");
	include("index.page.php");
} else {
	ct_error_log("Warning: Access denied. Logout!");
	ct_redirect(ct_pageurl('logout'));
}

?>