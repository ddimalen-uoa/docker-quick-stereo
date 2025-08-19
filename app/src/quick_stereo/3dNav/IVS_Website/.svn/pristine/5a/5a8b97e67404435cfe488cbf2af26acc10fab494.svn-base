<?php
#
# PAGE:		adminUsersBrowse
# DESC:		Show a list of all Conftool users
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminUsers'));

$session->set_besturl(ct('S_ADMIN_USERS_BROWSE_QUICK'));

$order = ' ORDER BY name asc, firstname asc';
if (isset($http['listorder'])) {
	switch ($http['listorder']) {
	 case 'org':
		$order = ' ORDER BY organisation asc, name asc';
		break;
	 case 'username':
		$order = ' ORDER BY username asc';
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

$where = "";
if (!isset($http['form_deleted']) || $http['form_deleted']=="no") {
	$where .= " && deleted=0";
} elseif ($http['form_deleted']=="yes") {
	# NIL
} elseif ($http['form_deleted']=="only") {
	$where .= " && deleted=1";
}

if (isset($http['form_status']) && !$http['form_status']==0 ) {
		$where .= " && FIND_IN_SET('".$http['form_status']."',status)>0";
}
// now replace first && by where
if ($where!="") {
	$where="where".substr($where,3);
}

$r = $db->query("select * from persons $where".$order);

echo "<H1>".ct('S_ADMIN_USERS_BROWSE_CMD')."</H1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_USERS_BROWSE_INTRO')."</p>\n";

#echo "<table width=100% align=center class='whitebg'><tr><td>\n";
#echo "&lt;&lt;&lt; <a class=\"bold10\" href=\"".ct_pageurl('adminUsersBrowse', array('filter'=>'hide', 'listorder'=>$http['listorder'], 'form_deleted'=>$http['form_deleted']))."\">".ct('S_ADMIN_FILTER_HIDE')."</a>\n";
#echo "</tr></td></table>\n";
$form1 = new CTform(ct_pageurl('adminUsersBrowse'), 'post');
$form1->width='100%';
$form1->align='center';
$form1->add_hidden(array(array('filter','show'), array('listorder',$http['listorder']), array('form_deleted',$http['form_deleted'])));
$form1->add_select(ct('S_USER_STATUS_ONLY'), 'form_status', 1, array(array('0',ct('S_ADMIN_TOOL_ALLPERSONS')),
                    array('admin',ct('S_USER_STATUS_ADMIN')),
                    array('author',ct('S_USER_STATUS_AUTHOR')),
                    array('participant',ct('S_USER_STATUS_PARTICIPANT')),
                    array('pc',ct('S_USER_STATUS_PC')),
                    array('chair',ct('S_USER_STATUS_CHAIR')),
                    array('frontdesk',ct('S_USER_STATUS_FRONTDESK')),
                    array('assistant',ct('S_USER_STATUS_ASSISTANT'))),
                    array($http['form_status']), false);
$form1->add_select(ct('S_ADMIN_USERS_BROWSE_DELETED'), 'form_deleted', 1, array(
			array('no',ct('S_ADMIN_USERS_BROWSE_HIDEDELETED')),
			array('yes',ct('S_ADMIN_USERS_BROWSE_SHOWDELETED')),
			array('only',ct('S_ADMIN_USERS_BROWSE_ONLYDELETED'))),
			array($http['form_deleted']), false);
$form1->add_submit('cmd_search', ct('S_ADMIN_TOOL_SUBMIT'));
$form1->show();

if (($r >= 0) && ($db->num_rows($r) > 0)) {
	ctadm_listusers($r, 'adminUsersBrowse'); // -> admin.lib
}


?>






