<?php
#
# PAGE:		adminToolEventtypeorder
# DESC:		Define order of event types (how they are displayed).
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminTool'));


if (isset($http['cmd_eventtypeorder_save'])) {
	$etypes = ct_show_eventtypes();
	foreach ($etypes as $e){
		$db->update('eventtypes','ID='.$e['ID'],array('ID'=>$e['ID'],'title'=>$e['title'],'seqorder'=>$http['form_'.$e['ID']], 'info'=>$e['info'], 'mode'=>$e['mode'] ) );
	}
	ct_infobox(ct('S_INFO_SAVE'),ct('S_INFO_SAVE_SUCCESS'));
}

$form = new CTform(ct_pageurl('adminToolEventtypeorder'), 'post');
$form->width='80%';
$form->align='center';
if ($ctconf['demomode']===true) $form->demomode=true;

$etypes = ct_show_eventtypes();
foreach ($etypes as $e){
	$form->add_text($e['title'],'form_'.$e['ID'],$e['seqorder'], 2, 2);
}

$form->add_submit('cmd_eventtypeorder_save', ct('S_ADMIN_TOOL_EVENTTYPEORDER_SAVECMD'));
echo "<h1>".ct('S_ADMIN_TOOL_EVENTTYPEORDER_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_EVENTTYPEORDER_INTRO')."</p>\n";

$form->show();
?>
