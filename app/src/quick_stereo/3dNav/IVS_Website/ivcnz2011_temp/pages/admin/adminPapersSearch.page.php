<?php
#
# PAGE:		adminPapersSearch
# DESC:		Search for a paper by name, title, abstracts or even keywords
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirechair();

ct_pagepath(array('index', 'adminPapers'));
$session->set_besturl(ct('S_ADMIN_PAPERS_BROWSE_QUICK'));

ct_vspacer('10');

$form = new CTForm(ct_pageurl('adminPapersSearch'), 'post');
$form->width = '70%';
$form->align = 'center';
$form->add_separator(ct('S_ADMIN_PAPERS_SEARCH_FORM_TITLE'));
$form->add_text(ct('S_ADMIN_PAPERS_SEARCH_FORM_QUERY'), 'form_query', (isset($http['form_query']))?$http['form_query']:'', 30, 255,false , ct('S_ADMIN_PAPERS_SEARCH_FORM_HINT'));
$form->add_submit('cmd_search', ct('S_ADMIN_PAPERS_SEARCH_FORM_SUBMIT'));

$form->show();

ct_vspacer();

#$order = ' order by name asc, firstname asc';
#if (isset($http['listorder'])) {
#	switch ($http[listorder]) {
#	 case 'org':
#		$order = ' order by organisation asc, name asc';
#		break;
#	 case 'username':
#		$order = ' order by username asc';
#		break;
#	 case 'id':
#		$order = ' order by id asc';
#		break;
#	 case 'name':
#		$order = ' order by name asc, firstname asc';
#		break;
#	}
#}

$order= ' order by author';

if (isset($http['form_query'])) {
	echo "<hr>\n";
	ct_vspacer();
	$form_query = ct_strtolower($http['form_query']);
	$q =  "select papers.ID as paperID from papers where withdrawn=0 and (";
	$q .= "lower(author) like '%".$form_query."%' or ";
	$q .= "lower(title) like '%".$form_query."%')";
	$r = $db->query($q.$order);
	if (($r >= 0) && ($db->num_rows($r) > 0)) {
		ctadm_listpapers($r, 'adminUsersSearch'); // -> admin.lib
	}
}

?>
