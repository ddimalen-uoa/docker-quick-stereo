<?php
#
# PAGE:		adminToolEventtypes
# DESC:		Define different event types to group them, e.g. main event, tutorial, workshops
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminTool'));

if (isset($http['cmd_eventtypes_save']) && in_http('form_title') && $http['form_title']) {
	$db->replace_into('eventtypes', array('ID'=>$http['form_id'], 'title'=>$http['form_title'], 'seqorder'=>$http['form_seqorder'], 'info'=>$http['form_info'], 'mode'=>$http['form_mode']));
}
if (isset($http['cmd_eventtypes_create']) && in_http('form_title') && $http['form_title']) {
	$db->insert_into('eventtypes', array('ID'=>0, 'title'=>$http['form_title'], 'seqorder'=>0, 'info'=>$http['form_info'], 'mode'=>$http['form_mode']));
}
if (isset($http['cmd_eventtypes_delete'])) {
	$r = $db->select('events','ID','eventtype="'.$http['form_eventtype'].'"');
	if ($r && ($db->num_rows($r) > 0))
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NODELETE'));
    else
	   	$db->delete("eventtypes","id='".$http['form_eventtype']."'");
}

echo "<h1>".ct('S_ADMIN_TOOL_EVENTTYPES_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_EVENTTYPES_INTRO')."</p>\n";

$form1 = new CTform(ct_pageurl('adminToolEventtypes'), 'post');
$form1->width='600';
$form1->align='center';
$form1->add_select(ct('S_PARTICIPATION_FORM_EVENTTYPES'), 'form_eventtype', 6, ct_list_eventtypes(), array(), false);
$form1->add_submit('cmd_eventtypes_edit', ct('S_ADMIN_TOOL_EVENTTYPES_EDITCMD'));
$form1->add_submit('cmd_eventtypes_delete', ct('S_ADMIN_TOOL_EVENTTYPES_DELETECMD'));
if ($ctconf['demomode']===true) $form->demomode=true;
$form1->show();

$form2 = new CTForm(ct_pageurl('adminToolEventtypes'), 'post');
$form2->width='95%';
$form2->align='center';
if ($ctconf['demomode']===true) $form2->demomode=true;

$list_modes = array(array('normal',ct('S_ADMIN_TOOL_EVENTTYPES_FORM_MODE_NORMAL')),array('exclusive',ct('S_ADMIN_TOOL_EVENTTYPES_FORM_MODE_EXCLUSIVE')) );

if (isset($http['cmd_eventtypes_edit']) && isset($http['form_eventtype']) && ($http['form_eventtype'] != "")) {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_EVENTTYPES_EDITINTRO')."</p>\n";
	$form2->add_hidden(array(array('form_id', $http['form_eventtype'])));
	$r = $db->query("select * from eventtypes where id='".$http['form_eventtype']."'");
	if ($r && ($db->num_rows($r) > 0)) {
		$eventtype = $db->fetch($r);
	}
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTTYPES_FORM_EVENTTYPE'),'form_title', $eventtype['title'], 50, 255);
	$form2->add_textarea(ct('S_ADMIN_TOOL_EVENTTYPES_FORM_INFO'),'form_info',$eventtype['info'], 50,5);
    $form2->add_select(ct('S_ADMIN_TOOL_EVENTTYPES_FORM_MODE'),'form_mode', 1, $list_modes, array($eventtype['mode']), false,ct('S_ADMIN_TOOL_EVENTTYPES_FORM_EXCLUSIVE_INFO') );
	$form2->add_hidden(array(array('form_seqorder',$eventtype['seqorder'])));
	$form2->add_submit('cmd_eventtypes_save', ct('S_ADMIN_TOOL_EVENTTYPES_SAVECMD'));
// new eventtype:
} else {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_EVENTTYPES_NEWINTRO')."</p>\n";
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTTYPES_FORM_EVENTTYPE'),'form_title', '', 50, 255);
	$form2->add_textarea(ct('S_ADMIN_TOOL_EVENTTYPES_FORM_INFO'),'form_info','', 50,5);
    $form2->add_select(ct('S_ADMIN_TOOL_EVENTTYPES_FORM_MODE'),'form_mode', 1, $list_modes, array('normal'),false,ct('S_ADMIN_TOOL_EVENTTYPES_FORM_EXCLUSIVE_INFO') );
	$form2->add_submit('cmd_eventtypes_create', ct('S_ADMIN_TOOL_EVENTTYPES_CREATECMD'));
}
$form2->show();
?>