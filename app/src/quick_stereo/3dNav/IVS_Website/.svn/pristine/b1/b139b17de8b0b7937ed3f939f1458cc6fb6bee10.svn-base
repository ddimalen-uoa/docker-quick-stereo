<?php
#
# PAGE:		frontdeskStatus
# Show status information about participants to frontdesk people...
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirefrontdesk();
$person = new CTPerson;
$participation = new CTParticipation;
if (isset($http['cmd_save_participant'])) {
	$participation->load_by_id($http['form_personID']);
	$participation->process_form_frontdeskpart();
	$participation->persist();
 	$session->put_infobox(ct('S_INFO_EDITPARTICIPANT'),ct('S_INFO_EDITPARTICIPANT_SUCCESS'));
	ct_redirect(ct_pageurl('frontdesk', array('form_query' => $http['form_personID'])));
} else if (isset($http['form_userID'])) {
	if ($person->load_by_id($http['form_userID'])) {
  		$participation->load_by_id($person->pdata['ID']);
		$participation->show_participant_data($person, '90%', 'center');
  		$participation->show_form_frontdesk(ct_pageurl('frontdeskStatus'), array() );
	}
}
?>