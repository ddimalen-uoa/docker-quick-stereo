<?php
#
# PAGE:		adminParticipantsDetails
# DESC:		Show participation details of any participant to the admin or frontdesk member...
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array('index','adminUsers','adminParticipantsBrowse'));
ct_requirefrontdesk();

if (isset($http['form_id'])) {
	$person = new CTPerson;
	$participation = new CTParticipation;
	if ($person->load_by_id($http['form_id'])) {
		echo "<h1>".ct('S_PARTICIPATE_DATA_TITLE')."</h1>\n";
		echo "<p class=\"standard\">".ct('S_PARTICIPATE_DATA_INTRO')."</p>\n";
		$participation->load_by_id($http['form_id']);
		$participation->show_participant_data($person,'95%','center','true');
	} else {
		ct_redirect($session->get_besturl());
	}
} else {
	ct_redirect($session->get_besturl());
}




