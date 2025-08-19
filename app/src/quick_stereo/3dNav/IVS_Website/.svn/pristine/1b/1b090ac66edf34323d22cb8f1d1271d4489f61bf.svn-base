<?php
#
# PAGE:         participate
# Register as conference participant
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array('index'));
ct_load_lib('mail.lib');

$participation = new CTParticipation;
$form_errors = array();
global $db,$ctconf,$http;

# check if phase "participation" is active
if ($ctconf['participation/enabled']==false || !ct_check_phases("participation")) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}

if ($user->is_participant()) {

	echo "<h1>".ct('S_PARTICIPATE_DATA_TITLE')."</h1>\n";
	echo "<p class=\"standard\">".ct('S_PARTICIPATE_DATA_INTRO')."</p>\n";
	$participation->load_by_id($user->pdata['ID']);
	$participation->show_participant_data($user,'700','center');

} else {

	if (isset($http['cmd_save_participant'])) {
		$form_errors = $participation->process_form_step2();
		if (count($form_errors)) { // Something went badly wrong! Just restart...
			$session->put_errorbox(ct('S_ERROR_PARTICIPATE'), ct('S_ERROR_PARTICIPATE_INCOMPLETE'));
			ct_redirect(ct_pageurl('participate'));
		}
		$form_errors = $participation->process_form_step3();
		if (count($form_errors)) {
			echo "<h1>".ct('S_PARTICIPATE_STEP3_TITLE')."</h1>\n";
			echo "<p class=\"yellowbg\">".ct('S_PARTICIPATE_STEP3_INTRO')."</p>\n";
			$participation->show_participant_data($user,'90%', 'center');
			echo "<br>";
			$participation->show_form_step3(ct_pageurl('participate'), $form_errors );
		} else {

	   		$participation->persist($user->pdata['ID']);  # user ID is necessary, as no personID was set.

			#send confirmation email:
			$participation->load_by_id($user->pdata[ID]);
			$user->reload();
			$participant_data = $participation->pdata[$user->pdata['ID']];

			$mail_participation=ct_currency_format($participation->pdata[total],true,true).".\n\n";

			if ($http['form_paymethod']=="cc") {
				$mail_participation.=ct('S_USER_PART_EMAIL_CC');
			}
			if ($http['form_paymethod']=="cash") {
				$mail_participation.=ct('S_USER_PART_EMAIL_CASH');
	        }

			if ($http['form_paymethod']=="transfer") {
				$mail_participation.=ct('S_USER_PART_EMAIL_TRANSFER')."\n\n";
				$mail_participation.=ct('S_INVOICE_BANK_DETAILS')."\n";
				$mail_participation.=ct('S_INVOICE_ACCHOLDER').' '.ctconf_get('paymentTransferAccountHolder')."\n";
				$mail_participation.=ct('S_INVOICE_ACCOUNTNO').' '.ctconf_get('paymentTransferAccountNo')."\n";
				$mail_participation.=ct('S_INVOICE_BANK_NAME').' '.ctconf_get('paymentTransferBankName')."\n";
				if (ctconf_get('paymentTransferBankCode')!='')
					$mail_participation.=ct('S_INVOICE_BANK_CODE').' '.ctconf_get('paymentTransferBankCode')."\n";
				if (ctconf_get('paymentTransferSWIFT')!='' || ctconf_get('paymentTransferIBAN')!='') {
					$mail_participation.="\n".ct('S_INVOICE_INTERNATIONAL_BANK_DETAILS')."\n";
					$mail_participation.=ct('S_INVOICE_SWIFTCODE').' '.ctconf_get('paymentTransferSWIFT')."\n";
					$mail_participation.=ct('S_INVOICE_IBAN_CODE').' '.ctconf_get('paymentTransferIBAN')."\n";
				}
				$mail_participation.="\n".ct('S_INVOICE_TRANSFER_REASON').' '.ct('S_USER_ID').' '.$user->get('ID').', '.ctconf_get('conferenceShortName')."\n";
			}

			if ($http['form_paymethod']=="cheque") {
				$mail_participation.=ct('S_INVOICE_PAYMENT_CHEQUE')."\n\n";
				$mail_participation.=ct('S_INVOICE_PAYMENT_CHEQUE_PAYABLETO').": ".ctconf_get('paymentChequePayableTo')."\n\n";
				$mail_participation.=ct('S_INVOICE_PAYMENT_CHEQUE_SENDTO').":\n".ctconf_get('paymentChequeReceiver')."\n";
			}

			$mail_content =ct_get_mail_salutation($user->get('gender'), stripslashes($user->get('firstname').' '.$user->get('name'))).",\n\n";
			$mail_content.=ct('S_USER_PART_EMAIL_CONTENT1')." ".$mail_participation."\n\n";
			$mail_content.=ct('S_USER_PART_EMAIL_CONTENT2')."\n\n-- \n";
			$mail_content.=strip_tags(ctconf_get('conferenceName'))."\n";
			$mail_content.=ct_getbaseurl()."index.php";

			if (strstr(ct('S_USER_PART_EMAIL_SUBJECT'),"%1"))
				$subject = ct('S_USER_PART_EMAIL_SUBJECT',array('"'.ctconf_get('conferenceShortName').'"'));
			else
				$subject = ct('S_USER_PART_EMAIL_SUBJECT').' "'.ctconf_get('conferenceShortName').'"';

			ct_mail(stripslashes($user->get('email')),$subject,$mail_content);

			if ($user->is_participant()){
				$session->put_infobox(ct('S_INFO_SAVEPARTICIPANT'),ct('S_INFO_SAVEPARTICIPANT_SUCCESS'));
				ct_redirect(ct_pageurl("index"));
			}
			else{
				ct_errorbox(ct('S_ERROR_PARTICIPATE'), ct('S_ERROR_PARTICIPATE_INCOMPLETE'));
			}
		}

	} else {

		if (isset($http['cmd_send_status'])) {
			$form_errors = $participation->process_form_step1();
			if (count($form_errors)) {
				echo "<h1>".ct('S_PARTICIPATE_STEP1_TITLE')."</h1>\n";
				echo "<p class=\"standard\">".ct('S_PARTICIPATE_STEP1_INTRO')."</p>\n";
				$participation->show_form_step1(ct_pageurl('participate'), $form_errors );
			} else {
				echo "<h1>".ct('S_PARTICIPATE_STEP2_TITLE')."</h1>\n";
				echo "<p class=\"standard\">".ct('S_PARTICIPATE_STEP2_INTRO')."</p>\n";
				$participation->show_form_step2(ct_pageurl('participate'), array());
			}
		 }
		else if (isset($http['cmd_send_participantdata']) || isset($http['cmd_go_participantdata'])){
			$form_errors = $participation->process_form_step2();
			if(array_sum($participation->eventdata)==0) { // nothing selected!
				ct_errorbox(ct('S_ERROR_PARTICIPATE'), ct('S_ERROR_PARTICIPATE_NOSELECTION'));
				echo "<h1>".ct('S_PARTICIPATE_STEP2_TITLE')."</h1>\n";
				echo "<p class=\"standard\">".ct('S_PARTICIPATE_STEP2_INTRO')."</p>\n";
				$participation->show_form_step2(ct_pageurl('participate'),$form_errors );
			} elseif(!empty($form_errors)) { // an error
				ct_errorbox(ct('S_ERROR_PARTICIPATE'), ct('S_ERROR_PARTICIPATE_INCOMPLETE'));
				echo "<h1>".ct('S_PARTICIPATE_STEP2_TITLE')."</h1>\n";
				echo "<p class=\"standard\">".ct('S_PARTICIPATE_STEP2_INTRO')."</p>\n";
				$participation->show_form_step2(ct_pageurl('participate'),$form_errors );
			} elseif(isset($http['cmd_go_participantdata'])) { // an error occured or user went back to Step 2
				echo "<h1>".ct('S_PARTICIPATE_STEP2_TITLE')."</h1>\n";
				echo "<p class=\"standard\">".ct('S_PARTICIPATE_STEP2_INTRO')."</p>\n";
				$participation->show_form_step2(ct_pageurl('participate'),$form_errors );
			} else {
				echo "<h1>".ct('S_PARTICIPATE_STEP3_TITLE')."</h1>\n";
				echo "<p class=\"yellowbg\">".ct('S_PARTICIPATE_STEP3_INTRO')."</p>\n";
				$participation->show_participant_data($user,'90%', 'center');
				echo "<br>";
				$participation->show_form_step3(ct_pageurl('participate') );
			}
		}
		else {
			// User went back to page 1
			if (in_http('cmd_go_status')) $participation->process_form_step2();

			echo "<h1>".ct('S_PARTICIPATE_STEP1_TITLE')."</h1>\n";
			echo "<p class=\"standard\">".ct('S_PARTICIPATE_STEP1_INTRO')."</p>\n";
			// Check if already at least an one event was entered...?
			$r = $db->select('events','ID','pricecategory>0 && eventtype>0');
			if ($r && ($db->num_rows($r) > 0)) {
				$participation->show_form_step1(ct_pageurl('participate') );
			} else {
				$session->put_errorbox(ct('S_ERROR_PARTICIPATE'),ct('S_ERROR_PARTICIPATE_NOT_INSTALLED'));
				ct_redirect(ct_pageurl('index'));
			}
		}
	}

}
?>












