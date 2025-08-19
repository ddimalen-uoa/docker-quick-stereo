<?php
#
# PAGE:		adminUsersDetails
# DESC:     Show all details of a user.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirefrontdeskorchair();
ct_pagepath(array('index','adminUsers'));


echo "<h2>".ct('S_SHOWPERSON_TITLE')."</h2>";

if (isset($http['form_id'])) {
	$person = new CTPerson;
	if ($person->load_by_id($http[form_id])) {
		$person->show_detailed('100%', 'center');
	}
}


















