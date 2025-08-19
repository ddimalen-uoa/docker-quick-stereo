<?php
#
# PAGE:		adminToolPhases
# DESC:		Define dates for different conference phases to activate and deactivate
#	   	    several conftool functions (also automatically by date).
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminTool'));

$form_errors=array();
if (isset($http['cmd_phases_save'])) {
	$res = $db->query("select * from phases");
	$error = false;
	if ($db->num_rows($res) > 0) {
		for ($i = 0; $i < $db->num_rows($res); $i++) {
			$row = $db->fetch($res);
			if ($http['form_'.$row['ID'].'_start_year']>0) {
				$startdate = $http['form_'.$row['ID'].'_start_year']."-".$http['form_'.$row['ID'].'_start_month']."-".$http['form_'.$row['ID'].'_start_day']." 00:00:00";
				$enddate   = $http['form_'.$row['ID'].'_end_year'].  "-".$http['form_'.$row['ID'].'_end_month'].  "-".$http['form_'.$row['ID'].'_end_day']." ".$http['form_'.$row['ID'].'_end_hour'].":".$http['form_'.$row['ID'].'_end_minute'].":".$http['form_'.$row['ID'].'_end_second'];

				if (ct_datetime_2_timestamp($startdate)>ct_datetime_2_timestamp($enddate)) {
					ct_errorbox(ct('S_ERROR_SAVEPHASES'),ct('S_ERROR_SAVEPHASES_DATES'));
					$error = true;
					$form_errors[]= 'form_'.$row['ID'].'_start';
					$form_errors[]= 'form_'.$row['ID'].'_end';
				} else {
					$query = "update phases set starts='$startdate', ends='$enddate', active='".($http['form_'.$row['ID'].'_active']=="1" ? "1" : "0")."' where ID='".$row['ID']."'";
					#echo "<!-- QUERY: $query -->\n";
					$db->query($query);
				}
			} else {
				// Entry not found => Deactivate phase!
				$query = "update phases set active='0' where ID='".$row['ID']."'";
				$db->query($query);
			}
		}
		if (!$error) {
			$session->put_infobox(ct('S_INFO_SAVE'), ct('S_INFO_SAVE_SUCCESS'));
			ct_redirect(ct_pageurl($http['page']));
		}
	}
	ct_load_phases();
}

echo "<h1>".ct('S_ADMIN_TOOL_PHASES_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_PHASES_INTRO')."</p>\n";

$res = $db->query("select ID,date_format(starts,'%Y %c %e') as starttime, ends as endtime,active from phases");
if ($res and ($db->num_rows($res) > 0)) {
	$phases = array();
	for ($i = 0; $i < $db->num_rows($res); $i++) {
		$row = $db->fetch($res);
		$phases[$row['ID']] = $row;
	}
}

$form = new CTform(ct_pageurl('adminToolPhases'), 'post', $form_errors);
$form->width='99%';
$form->align='center';
$form->warningmessage=true;
if ($ctconf['demomode']===true) $form->demomode=true;


$form->add_separator(ct('S_ADMIN_TOOL_PHASES_USERREGISTRATION'));
$form->add_label('',ct('S_ADMIN_TOOL_PHASES_USERREGISTRATION_HINT'));
$form->add_date(ct('S_ADMIN_TOOL_PHASES_STARTTIME'), 'form_userregistration_start',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['userregistration']['starttime'] ) ) ));
$form->add_datetime(ct('S_ADMIN_TOOL_PHASES_ENDTIME'), 'form_userregistration_end',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['userregistration']['endtime'] ) ) ));
$form->add_check(ct('S_ADMIN_TOOL_PHASES_ACTIVE'), array(array('form_userregistration_active','1','',$phases['userregistration']['active'])));
$form->add_label(ct('S_ADMIN_TOOL_PHASES_ISACTIVE'),  get_current_state_string("userregistration"));

if (ctconf_get('submission/enabled',true)==true) {
	$form->add_separator(ct('S_ADMIN_TOOL_PHASES_SUBMISSION'));
	$form->add_date(ct('S_ADMIN_TOOL_PHASES_STARTTIME'), 'form_submission_start',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['submission']['starttime'] ) ) ));
	$form->add_datetime(ct('S_ADMIN_TOOL_PHASES_ENDTIME'), 'form_submission_end',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['submission']['endtime'] ) ) ));
	$form->add_check(ct('S_ADMIN_TOOL_PHASES_ACTIVE'), array(array('form_submission_active','1','',$phases['submission']['active'])));
	$form->add_label(ct('S_ADMIN_TOOL_PHASES_ISACTIVE'),  get_current_state_string("submission"));

	$form->add_separator(ct('S_ADMIN_TOOL_PHASES_REVIEWING'));
	$form->add_label('',ct('S_ADMIN_TOOL_PHASES_REVIEWING_HINT',array(ct_pageurl('adminMailerPersons'))));
	$form->add_date(ct('S_ADMIN_TOOL_PHASES_STARTTIME'), 'form_reviewing_start',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['reviewing']['starttime'] ) ) ));
	$form->add_datetime(ct('S_ADMIN_TOOL_PHASES_ENDTIME'), 'form_reviewing_end',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['reviewing']['endtime'] ) ) ));
	$form->add_check(ct('S_ADMIN_TOOL_PHASES_ACTIVE'), array(array('form_reviewing_active','1','',$phases['reviewing']['active'])));
	$form->add_label(ct('S_ADMIN_TOOL_PHASES_ISACTIVE'),  get_current_state_string("reviewing"));

	$form->add_separator(ct('S_ADMIN_TOOL_PHASES_REVIEWRESULTS'));
	$form->add_label('',ct('S_ADMIN_TOOL_PHASES_REVIEWRESULTS_HINT',array($onhold,ct_pageurl('adminMailerAuthors'))));
	$form->add_date(ct('S_ADMIN_TOOL_PHASES_STARTTIME'), 'form_reviewresults_start',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['reviewresults']['starttime'] ) ) ));
	$form->add_datetime(ct('S_ADMIN_TOOL_PHASES_ENDTIME'), 'form_reviewresults_end',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['reviewresults']['endtime'] ) ) ));
	$form->add_check(ct('S_ADMIN_TOOL_PHASES_ACTIVE'), array(array('form_reviewresults_active','1','',$phases['reviewresults']['active'])));
	$form->add_label(ct('S_ADMIN_TOOL_PHASES_ISACTIVE'), get_current_state_string("reviewresults"));

	$form->add_separator(ct('S_ADMIN_TOOL_PHASES_FINALUPLOAD'));
	$form->add_label('',ct('S_ADMIN_TOOL_PHASES_FINALUPLOAD_HINT'));
	$form->add_date(ct('S_ADMIN_TOOL_PHASES_STARTTIME'), 'form_finalupload_start',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['finalupload']['starttime'] ) ) ));
	$form->add_datetime(ct('S_ADMIN_TOOL_PHASES_ENDTIME'), 'form_finalupload_end',    true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['finalupload']['endtime'] ) ) ));
	$form->add_check(ct('S_ADMIN_TOOL_PHASES_ACTIVE'), array(array('form_finalupload_active','1','',$phases['finalupload']['active'])));
	$form->add_label(ct('S_ADMIN_TOOL_PHASES_ISACTIVE'),  get_current_state_string("finalupload"));
}

if (ctconf_get('participation/enabled',true)==true) {
	$form->add_separator(ct('S_ADMIN_TOOL_PHASES_PARTICIPATION'));
	$form->add_label('',ct('S_ADMIN_TOOL_PHASES_PARTICIPATION_HINT',array(ct_pageurl('adminTool',array(),'','participate'))));
	$form->add_date(ct('S_ADMIN_TOOL_PHASES_STARTTIME'), 'form_participation_start',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['participation']['starttime'] ) ) ));
	$form->add_datetime(ct('S_ADMIN_TOOL_PHASES_ENDTIME'), 'form_participation_end',	true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['participation']['endtime'] ) ) ));
	$form->add_check(ct('S_ADMIN_TOOL_PHASES_ACTIVE'), array(array('form_participation_active','1','',$phases['participation']['active'])));
	$form->add_label(ct('S_ADMIN_TOOL_PHASES_ISACTIVE'),  get_current_state_string("participation"));

	/*
	$form->add_separator(ct('S_ADMIN_TOOL_PHASES_CONFERENCE'));
	$form->add_date(ct('S_ADMIN_TOOL_PHASES_STARTTIME'), 'form_conference_start',true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['conference']['starttime'] ) ) ));
	$form->add_datetime(ct('S_ADMIN_TOOL_PHASES_ENDTIME'), 'form_conference_end',	true,explode(" ",str_replace("-"," ", str_replace(":"," ", $phases['conference']['endtime'] ) ) ));
	$form->add_check(ct('S_ADMIN_TOOL_PHASES_ACTIVE'), array(array('form_conference_active','1','',$phases['conference']['active'])));
	$form->add_label(ct('S_ADMIN_TOOL_PHASES_ISACTIVE'),  get_current_state_string("conference"));
	*/
}

$form->add_submit('cmd_phases_save', ct('S_ADMIN_TOOL_PHASES_SAVECMD'));

$form->show();

function get_current_state_string($phase) {
	if (ct_check_phases($phase))
		return '<span class="positivebold10">'.ct('S_ADMIN_TOOL_PHASES_ACTIVE_YES').'</span>';
	else
		return '<span class="negativebold10">'.ct('S_ADMIN_TOOL_PHASES_ACTIVE_NO').'</span>';
}

?>