<?php
#
# PAGE:	adminParticipantsReceipt
# DESC:	Show (and print) receipt for participants - available only to admin and frontdesk!
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirefrontdesk();

if (isset($http['form_userID'])) {
	$participation = new CTParticipation;
	if ($participation->load_by_id($http['form_userID'])) {
		$participation->show_receipt_data('640','center');
	}
}
?>
