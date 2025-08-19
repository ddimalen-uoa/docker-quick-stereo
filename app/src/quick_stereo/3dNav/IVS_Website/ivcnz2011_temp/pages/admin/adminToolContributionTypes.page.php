<?php
#
# PAGE:		adminToolContributionTypes
# DESC:		Define different contribution types like papers, short papers, posters, workshops etc.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminTool'));
global $http, $session, $ctconf;

ct_load_lib('papers.lib');
$session->del('db_contributiontypes');

if (isset($http['form_active'])) { $active="true"; } else { $active="false"; }

if (isset($http['cmd_contributiontypes_save']) && $http['form_id']!="") {
	$db->replace_into('contributiontypes', array('ID'=>$http['form_id'], 'title'=>$http['form_title'], 'info'=>$http['form_info'], 'upload'=>$http['form_upload'], 'active'=>$active));
} elseif (isset($http['cmd_contributiontypes_create'])) {
	$db->insert_into('contributiontypes', array('ID'=>0, 'title'=>$http['form_title'], 'info'=>$http['form_info'], 'upload'=>$http['form_upload'], 'active'=>$active));
} elseif (isset($http['cmd_contributiontypes_delete']) && $http['form_contributiontype']!="") {
	if (ct_papers_exist("contributiontypeID='".$http['form_contributiontype']."'")) ct_errorbox(ct('S_ERROR_EDITPAPERDATA'),ct('S_ERROR_EDITPAPERDATA_NODELETE'));
	else $db->delete("contributiontypes","id='".$http['form_contributiontype']."'");
} elseif (ct_papers_exist()) {
	ct_warningbox(ct('S_ERROR_EDITPAPERDATA'),ct('S_ERROR_EDITPAPERDATA_PAPERS_EXIST'));
}

#if (isset($http['cmd_contributiontypes_save'])) {
#}
#if (isset($http['cmd_contributiontypes_create'])) {
#}
#if (isset($http['cmd_contributiontypes_delete'])) {
#}

$paper = new CTPaper;

echo "<h1>".ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_INTRO')."</p>\n";

$form1 = new CTform(ct_pageurl('adminToolContributionTypes'), 'post');
$form1->width='600';
$form1->align='center';
$form1->add_select(ct('S_PAPER_FORM_CONTRIBUTIONTYPE'), 'form_contributiontype', 6, $paper->_list_types(), array(), false);
$form1->add_submit('cmd_contributiontypes_edit', ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_EDITCMD'));
$form1->add_submit('cmd_contributiontypes_delete', ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_DELETECMD'));
if ($ctconf['demomode']===true) $form1->demomode=true;
$form1->show();

$form2 = new CTForm(ct_pageurl('adminToolContributionTypes'), 'post');
$form2->width='600';
$form2->align='center';
if ($ctconf['demomode']===true) $form2->demomode=true;

if (isset($http['cmd_contributiontypes_edit']) && isset($http['form_contributiontype']) && ($http['form_contributiontype'] != "")) {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_EDITINTRO')."</p>\n";
	$form2->add_hidden(array(array('form_id', $http['form_contributiontype'])));
	$r = $db->query("select * from contributiontypes where ID='".$http['form_contributiontype']."'");
	if ($r && ($db->num_rows($r) > 0)) {
		$contributiontype = $db->fetch($r);
	}
	$form2->add_text(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_CONTRIBUTIONTYPE'),'form_title', $contributiontype['title'], 50, 255);
	$form2->add_textarea(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_INFO'),'form_info',$contributiontype['info'], 50,5);
	#$form2->add_select(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_UPLOAD'), 'form_upload', 1, array( array('0','0 ('.ct('S_FORM_DISABLED').')'), array('1','1') ), array($contributiontype['upload']), false, ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_UPLOADS'));
	$form2->add_hidden(array(array('form_upload',1)));
	if ($contributiontype['active']=='true'){
		$active=1;
	} else {
		$active=0;
	}
	$form2->add_check(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_ACTIVE'),array(array('form_active','1','',$active,ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_ACTIVE_INFO'))));
	$form2->add_submit('cmd_contributiontypes_save', ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_SAVECMD'));
// new contributiontype:
} else {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_NEWINTRO')."</p>\n";
	$form2->add_text(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_CONTRIBUTIONTYPE'),'form_title', '', 50, 255);
	$form2->add_textarea(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_INFO'),'form_info','', 50,5);
	#$form2->add_select(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_UPLOAD'), 'form_upload', 1, array( array('0','0 ('.ct('S_FORM_DISABLED').')'), array('1','1') ), array('1'), false, ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_UPLOADS'));
	$form2->add_hidden(array(array('form_upload',1)));
	$form2->add_check(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_ACTIVE'),array(array('form_active','1','','1',ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_ACTIVE_INFO'))));
	$form2->add_submit('cmd_contributiontypes_create', ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_CREATECMD'));
}
$form2->show();
?>
