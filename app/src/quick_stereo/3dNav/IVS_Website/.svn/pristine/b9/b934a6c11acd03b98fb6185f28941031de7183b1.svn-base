<?php
#
# PAGE:		adminUsersPC
# DESC:		Show list of all PC members
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirechair();
ct_pagepath(array('index','adminUsers'));
$session->set_besturl(ct('S_ADMIN_USERS_PC_QUICK'));

$order = ' order by name asc, firstname asc';
if (isset($http['listorder'])) {
	switch ($http['listorder']) {
	 case 'org':
		$order = ' order by organisation asc, name asc';
		break;
	 case 'username':
		$order = ' order by username asc';
		break;
	 case 'id':
		$order = ' order by id asc';
		break;
	 case 'name':
		$order = ' order by name asc, firstname asc';
		break;
	 case 'country':
		$order = ' order by country asc, name asc, firstname asc';
		break;
	}
}
if (in_http('form_deleted', 'yes')) {
	$withdrawn = "";
} else {
	$withdrawn = "where deleted=0 ";
}
$r = $db->query("select * from persons where deleted=0 and status LIKE '%pc%'".$order);
if (($r >= 0) && ($db->num_rows($r) > 0)) {
	echo "<h1>".ct('S_ADMIN_USERS_PC_CMD')."</h1>\n";
	echo "<p class=\"standard\">".ct('S_ADMIN_USERS_PC_INTRO')."</p>\n";
	ctadm_listusers($r, 'adminUsersPC'); // -> admin.lib
}

?>








