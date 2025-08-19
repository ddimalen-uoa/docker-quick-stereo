<?php
#
# PAGE:		adminToolTracks
# DESC:		Enter, edit and show tracks of conference.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminTool'));

if (isset($http['cmd_tracks_save'])) {
	$db->replace_into('tracks', array('ID'=>$http['form_id'], 'title'=>$http['form_title'], 'shorttitle'=>$http['form_shorttitle'], 'time'=>$http['form_time']));
}
if (isset($http['cmd_tracks_create'])) {
	$db->insert_into('tracks', array('ID'=>0, 'title'=>$http['form_title'], 'shorttitle'=>$http['form_shorttitle'], 'time'=>$http['form_time']));
}
if (isset($http['cmd_tracks_delete'])) {
	$db->delete("tracks","id='".$http['form_track']."'");
}

echo "<h1>".ct('S_ADMIN_TOOL_SESSIONS_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_SESSIONS_INTRO')."</p>\n";

$tracks = array();
$r = $db->query("select * from tracks order by shorttitle");
if ($r and ($db->num_rows($r) > 0)) {
	for ($i = 0; $i < $db->num_rows($r); $i++) {
		$row = $db->fetch($r);
		array_push($tracks, array($row['ID'], $row['shorttitle']));
	}
}

$form1 = new CTform(ct_pageurl('adminToolTracks'), 'post');
$form1->width='400';
$form1->align='center';
$form1->add_select(ct('S_PAPER_FORM_SESSIONS'), 'form_track', 6, $tracks, array(), false);
$form1->add_submit('cmd_tracks_edit', ct('S_ADMIN_TOOL_SESSIONS_EDITCMD'));
$form1->add_submit('cmd_tracks_delete', ct('S_ADMIN_TOOL_SESSIONS_DELETECMD'));
if ($ctconf['demomode']===true) $form1->demomode=true;
$form1->show();

$form2 = new CTForm(ct_pageurl('adminToolTracks'), 'post');
$form2->width='600';
$form2->align='center';
if ($ctconf['demomode']===true) $form2->demomode=true;

if (isset($http['cmd_tracks_edit']) && isset($http['form_track']) && ($http['form_track'] != "")) {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_SESSIONS_EDITINTRO')."</p>\n";
	$form2->add_hidden(array(array('form_id', $http['form_track'])));
	$r = $db->query("select * from tracks where id='".$http['form_track']."'");
	if ($r && ($db->num_rows($r) > 0)) {
		$track = $db->fetch($r);
	}
	$form2->add_text(ct('S_ADMIN_TOOL_SESSIONS_FORM_TITLE'),'form_title', $track['title'], 50, 255);
	$form2->add_text(ct('S_ADMIN_TOOL_SESSIONS_FORM_SHORTTITLE'),'form_shorttitle', $track['shorttitle'], 10, 10);
	#$form2->add_text(ct('S_ADMIN_TOOL_SESSIONS_FORM_TIME'),'form_time', $track['time'], 50, 255);
	$form2->add_submit('cmd_tracks_save', ct('S_ADMIN_TOOL_SESSIONS_SAVECMD'));
} else {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_SESSIONS_NEWINTRO')."</p>\n";
	$form2->add_text(ct('S_ADMIN_TOOL_SESSIONS_FORM_TITLE'),'form_title', '', 50, 255);
	$form2->add_text(ct('S_ADMIN_TOOL_SESSIONS_FORM_SHORTTITLE'),'form_shorttitle', '', 10, 10);
	#$form2->add_text(ct('S_ADMIN_TOOL_SESSIONS_FORM_TIME'),'form_time', '', 50, 255);
	$form2->add_submit('cmd_tracks_create', ct('S_ADMIN_TOOL_SESSIONS_CREATECMD'));
}
$form2->show();
?>
