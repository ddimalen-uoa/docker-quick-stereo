<?php
#
# PAGE:		adminParticipantsEditDelete
# DESC:     Enter participation data (also edit data for administrator)
#

if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirefrontdesk();

ct_pagepath(array('index','adminUsers','adminParticipantsBrowse'));

$form_errors = array();
$participation = new CTParticipation;

# if user has no admin rights, check if phase "participation" is active
if (!$user->is_admin() && !$user->is_frontdesk() && !ct_check_phases("participation")) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}
if (!in_http('form_personID') && !in_http('form_userID') && !in_http('form_delete_ID')) {
	ct_redirect($session->get_besturl());
}

if (isset($http['cmd_update_participant'])) {
	echo "<h1>".ct('S_EDITPARTICIPANT_TITLE')."</h1>\n";
	$participation->load_by_id($http['form_personID']);
	$form_errors = $participation->process_form_adminpart();
	$participation->show_form_adminpart(ct_pageurl('adminParticipantsEditDelete'), $form_errors);
}
else if (isset($http['cmd_save_participant'])) {
	//return;
	$participation->load_by_id($http['form_personID']);
	$form_errors = $participation->process_form_adminpart(); 
	if (!$form_errors) 
	{
		$participation->persist();
        $session->put_infobox(ct('S_INFO_EDITPARTICIPANT'),ct('S_INFO_EDITPARTICIPANT_SUCCESS'));
		ct_redirect($session->get_besturl());
	} else {//print_r ($form_errors); return; 
		//return;
		ct_errorbox(ct('S_ERROR_EDITPARTICIPANT'), ct('S_ERROR_EDITPARTICIPANT_FAILED'));
		// ct_redirect(ct_pageurl('adminParticipantsDetail',array('form_id'=>$http['form_personID'])));
		$participation->show_form_adminpart(ct_pageurl('adminParticipantsEditDelete'), $form_errors);
	}
}
else if (isset($http['cmd_delete_participant'])) {
		$participation->load_by_id($http['form_userID']);
		$deleted=$participation->delete();
		if ($deleted=='true'){
		   	$session->put_infobox(ct('S_INFO_DELETEPARTICIPANT'), ct('S_INFO_DELETEPARTICIPANT_SUCCESS'));
			ct_redirect($session->get_besturl());
		}
		else {
			$session->put_errorbox(ct('S_ERROR_DELETEPARTICIPANT'), ct('S_ERROR_DELETEPARTICIPANT_FAILED'));
			ct_redirect($session->get_besturl());
		}
}
else if (isset($http['form_delete_ID'])) {
	echo "<h1>".ct('S_DELETEPARTICIPANT_TITLE')."</h1>\n";
	echo "<p class=\"standard\">".ct('S_DELETEPARTICIPANT_QUERY')."</p>\n";
	$form = new CTForm(ct_pageurl('adminParticipantsEditDelete'), 'post', array());
	$form->width='600';
	$form->align='center';
	$form->add_hidden(array(array('form_userID', $http['form_delete_ID'])));
	$form->add_submit('cmd_delete_participant', ct('S_DELETEPARTICIPANT_CONFIRM'));
	$form->add_submit('cmd_cancel', ct('S_BUTTON_CANCEL'));
	$form->show();
}
else if(isset($http['cmd_cancel'])){
	ct_redirect($session->get_besturl());
}
else if (isset($http['form_userID'])) {
	# show participation form to admin
	if ($participation->load_by_id($http['form_userID']) === true) { // Already known? -> Edit
		if ($participation->get('deleted') == 1) {
			echo "<h1>".ct('S_UNDELETEPARTICIPANT_TITLE')."</h1>\n";
		} else {
 			echo "<h1>".ct('S_EDITPARTICIPANT_TITLE')."</h1>\n";
		}
	} else {	// New participant
		echo "<h1>".ct('S_NEWPARTICIPANT_TITLE')."</h1>\n";
		// set registration date to "today"
		$participation->set('regdate',date("Y-m-d H:i:s"));
	}
	echo "<p class=\"standard\">".ct('S_EDITPARTICIPANT_INTRO')."</p>\n";
	
		$participation->show_form_adminpart(ct_pageurl('adminParticipantsEditDelete'), $form_errors);
}
?>
