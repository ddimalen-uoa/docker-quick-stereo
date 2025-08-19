<?php
#
# PAGE:		adminToolTopics
# DESC:     Enter, edit and show all conference topics (e.g. used as meta data for papers to assign them)
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminTool'));

if (isset($http['cmd_topics_save'])) {
	$db->replace_into('topics', array('ID'=>$http['form_id'], 'title'=>$http['form_title']));
}
if (isset($http['cmd_topics_create'])) {
	$db->insert_into('topics', array('ID'=>0, 'title'=>$http['form_title']));
}
if (isset($http['cmd_topics_delete']) && $http['form_topic']!="") {
	$r = $db->query("select paperID from topics2papers where topicID='".$http['form_topic']."'");
	if ($r && ($db->num_rows($r) > 0)) 
		ct_errorbox(ct('S_ERROR_EDITPAPERDATA'),ct('S_ERROR_EDITPAPERDATA_NODELETE'));
 	else
 		$db->delete("topics","id='".$http['form_topic']."'");
}

$paper = new CTPaper;

echo "<h1>".ct('S_ADMIN_TOOL_TOPICS_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_TOPICS_INTRO')."</p>\n";

$form1 = new CTform(ct_pageurl('adminToolTopics'), 'post');
$form1->width='400';
$form1->align='center';
$form1->add_select(ct('S_PAPER_FORM_TOPICS'), 'form_topic', 6, $paper->_list_topics(), array(), false);
$form1->add_submit('cmd_topics_edit', ct('S_ADMIN_TOOL_TOPICS_EDITCMD'));
$form1->add_submit('cmd_topics_delete', ct('S_ADMIN_TOOL_TOPICS_DELETECMD'));
if ($ctconf['demomode']===true) $form1->demomode=true;
$form1->show();

$form2 = new CTForm(ct_pageurl('adminToolTopics'), 'post');
$form2->width='400';
$form2->align='center';
if ($ctconf['demomode']===true) $form2->demomode=true;

if (isset($http['cmd_topics_edit']) && isset($http['form_topic']) && ($http['form_topic'] != "")) {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_TOPICS_EDITINTRO')."</p>\n";
	$form2->add_hidden(array(array('form_id', $http['form_topic'])));
	$r = $db->query("select title from topics where id='".$http['form_topic']."'");
	if ($r && ($db->num_rows($r) > 0)) {
		$topic = $db->fetch($r);
	}
	$form2->add_text(ct('S_ADMIN_TOOL_TOPICS_FORM_TOPIC'),'form_title', $topic['title'], 50, 255);
	$form2->add_submit('cmd_topics_save', ct('S_ADMIN_TOOL_TOPICS_SAVECMD'));
} else {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_TOPICS_NEWINTRO')."</p>\n";
	$form2->add_text(ct('S_ADMIN_TOOL_TOPICS_FORM_TOPIC'),'form_title', '', 50, 255);
	$form2->add_submit('cmd_topics_create', ct('S_ADMIN_TOOL_TOPICS_CREATECMD'));
}
$form2->show();
?>
