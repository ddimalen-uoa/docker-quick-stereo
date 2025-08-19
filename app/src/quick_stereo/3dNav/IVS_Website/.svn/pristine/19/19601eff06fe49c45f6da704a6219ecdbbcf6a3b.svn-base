<?php
#
# PAGE:		adminUserSearch
# DESC:		Search for a user by (part of) name
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();

ct_pagepath(array('index', 'admin', 'adminUsers'));

ct_vspacer();

$form = new CTForm(ct_pageurl('adminUsersSearch'), 'post');
$form->width = '80%';
$form->align = 'center';
$form->add_separator(ct('S_ADMIN_USERS_SEARCH_FORM_TITLE'));
if (isset($http['form_query']))
	$form->add_text(ct('S_ADMIN_USERS_SEARCH_FORM_QUERY'), 'form_query', $http['form_query'], 30, 255);
else
	$form->add_text(ct('S_ADMIN_USERS_SEARCH_FORM_QUERY'), 'form_query', '', 30, 255);
$form->add_submit('cmd_search', ct('S_ADMIN_USERS_SEARCH_FORM_SUBMIT'));

$form->show();


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
	}
}

if (isset($http['form_query'])) {
	echo "<hr size=1 noshade>\n";
	$form_query = ct_strtolower($http['form_query']);
	$q =  "select * from persons where deleted=0 and (";
	$q .= "lower(name) like '%".$form_query."%' or ";
	$q .= "lower(firstname) like '%".$form_query."%' or ";
	$q .= "lower(organisation) like '%".$form_query."%' or ";
	$q .= "lower(username) like '%".$form_query."%')";
	$r = $db->query($q.$order);
	if (($r >= 0) && ($db->num_rows($r) > 0)) {
		ctadm_listusers($r, 'adminUsersSearch'); // -> admin.lib
	}
} else {
	ct_vspacer('30');
}

?>
