<?php
#
# PAGE:		adminParticipantsInvoice
# DESC:		Show and print Invoice for any participant
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirefrontdesk();
if (isset($http['form_userID'])) {
	$participation = new CTParticipation;
	if ($participation ->load_by_id($http['form_userID'])) {
		$participation->show_invoice_data('640','center');
	}
}
?>
