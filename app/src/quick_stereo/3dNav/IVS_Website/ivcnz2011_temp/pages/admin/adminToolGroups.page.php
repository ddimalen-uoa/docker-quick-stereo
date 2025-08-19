<?php
#
# PAGE:		adminToolGroups
# DESC:		Define different participant groups like "Standard", "Student", Member of...
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminTool'));


if (isset($http['cmd_groups_save'])) {
	$db->replace_into('groups', array('ID'=>$http['form_id'], 'title'=>$http['form_title']));
}
if (isset($http['cmd_groups_create'])) {
	$db->insert_into('groups', array('ID'=>0, 'title'=>$http['form_title']));
	$res = $db->query('select LAST_INSERT_ID() from groups');
	$gid = $db->fetch($res);
}
if (isset($http['cmd_groups_delete'])) {
	$r = $db->select('participants','personID','deleted=0 && status="'.$http['form_group'].'"');
	if ($r && ($db->num_rows($r) > 0))
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NODELETE'));
    else
    	$db->delete("groups","id='".$http['form_group']."'");
}

echo "<h1>".ct('S_ADMIN_TOOL_GROUPS_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_GROUPS_INTRO')."</p>\n";

$form1 = new CTform(ct_pageurl('adminToolGroups'), 'post');
$form1->width='600';
$form1->align='center';
if ($ctconf['demomode']===true) $form1->demomode=true;
$form1->add_select(ct('S_PARTICIPATION_FORM_GROUPS'), 'form_group', 6, ct_list_groups(), array(), false);
$form1->add_submit('cmd_groups_edit', ct('S_ADMIN_TOOL_GROUPS_EDITCMD'));
$form1->add_submit('cmd_groups_delete', ct('S_ADMIN_TOOL_GROUPS_DELETECMD'));
$form1->show();

$form2 = new CTForm(ct_pageurl('adminToolGroups'), 'post');
$form2->width='600';
$form2->align='center';
if ($ctconf['demomode']===true) $form2->demomode=true;

if (isset($http['cmd_groups_edit']) && isset($http['form_group']) && ($http['form_group'] != "")) {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_GROUPS_EDITINTRO')."</p>\n";
	$form2->add_hidden(array(array('form_id', $http['form_group'])));
	$r = $db->query("select title from groups where id='".$http['form_group']."'");
	if ($r && ($db->num_rows($r) > 0)) {
		$group = $db->fetch($r);
	}
	$form2->add_text(ct('S_ADMIN_TOOL_GROUPS_FORM_GROUP'),'form_title', $group['title'], 50, 255);
	$form2->add_submit('cmd_groups_save', ct('S_ADMIN_TOOL_GROUPS_SAVECMD'));
} else {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_GROUPS_NEWINTRO')."</p>\n";
	$form2->add_text(ct('S_ADMIN_TOOL_GROUPS_FORM_GROUP'),'form_title', '', 50, 255);
	$form2->add_submit('cmd_groups_create', ct('S_ADMIN_TOOL_GROUPS_CREATECMD'));
}
$form2->show();
?>


