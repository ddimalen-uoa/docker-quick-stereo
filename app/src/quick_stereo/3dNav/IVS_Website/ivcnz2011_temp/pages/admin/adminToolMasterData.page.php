<?php
//
// PAGE:		adminToolPhases
// DESC:		Define dates for different conference phases to activate and deactivate
//			several conftool functions (also automatically by date).
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminTool'));

ct_load_lib('papers.lib');
ct_load_lib('participation.lib');
ct_load_lib('mail.lib');
ct_load_lib('ctconf_admintools.lib');

$form_errors=array();

$session->del('db_ctconf'); // reset configuration in session...

if (isset($http['cmd_masterdata_save'])) {
	$old = ct_get_basesettings();
	$form_errors[]=save_ctconf_setting('conferenceShortName','required');
	$form_errors[]=save_ctconf_setting('conferenceName','required');
	$form_errors[]=save_ctconf_setting('conferenceSubtitle','optional');
	$form_errors[]=save_ctconf_setting('conferenceSubtitle2','optional');
	$form_errors[]=save_ctconf_setting('conferenceURI','uri');
	$form_errors[]=save_ctconf_setting('conferenceOrganizer','required');
	$form_errors[]=save_ctconf_setting('conferenceCity','required');
	$form_errors[]=save_ctconf_setting('conferenceContactEmail','email');

	$form_errors[]=save_ctconf_setting('conferenceSenderName','required');
	$form_errors[]=save_ctconf_setting('conferenceSenderEmail','email');
	$form_errors[]=save_ctconf_setting('conferenceReplytoEmail');

	if (ctconf_get('submission/enabled',true)==true) {
		$http['form_uploadFileTypes']=ct_strtolower($http['form_uploadFileTypes']);
	 	$form_errors[]=save_ctconf_setting('uploadFileTypes','csv');
		$form_errors[]=save_ctconf_setting('uploadFileInfo','lang');
	}

	if (ctconf_get('participation/enabled',true)==true) {
		$form_errors[]=save_ctconf_setting('currencyCode','required');
		$form_errors[]=save_ctconf_setting('currencySymbol','required');

		$form_errors[]=save_ctconf_setting('invoiceSender','required');
		$form_errors[]=save_ctconf_setting('invoiceSenderCity');
		$form_errors[]=save_ctconf_setting('invoiceTaxNo');
		$form_errors[]=save_ctconf_setting('invoicePrefix');
		$form_errors[]=save_ctconf_setting('invoiceDigits','number3-11');
		if ($http['form_invoiceDigits']>=3 && $http['form_invoiceDigits']<=11)
			$http['form_invoiceNo']=ct_substr('000000000000'.trim($http['form_invoiceNo']),-1*$http['form_invoiceDigits']);
		$form_errors[]=save_ctconf_setting('invoiceNo','number');
		$form_errors[]=save_ctconf_setting('invoiceShow');
		$form_errors[]=save_ctconf_setting('invoiceProforma');

		$form_errors[]=save_ctconf_setting('registrationTerms','boolean');
		$form_errors[]=save_ctconf_setting('memberNumber','boolean');

		$form_errors[]=save_ctconf_setting('paymentCash','boolean');

		$form_errors[]=save_ctconf_setting('paymentCreditCard','boolean');
		$form_errors[]=save_ctconf_setting('paymentCreditCardTypes','csv');
		$form_errors[]=save_ctconf_setting('paymentCreditCardUseCVC','boolean');

		$form_errors[]=save_ctconf_setting('paymentTransfer','boolean');
		$form_errors[]=save_ctconf_setting('paymentTransferAccountHolder');
		$form_errors[]=save_ctconf_setting('paymentTransferAccountNo');
		$form_errors[]=save_ctconf_setting('paymentTransferBankName');
		$form_errors[]=save_ctconf_setting('paymentTransferBankCode');
		$form_errors[]=save_ctconf_setting('paymentTransferSWIFT');
		$form_errors[]=save_ctconf_setting('paymentTransferIBAN');

		$form_errors[]=save_ctconf_setting('paymentCheque','boolean');
		$form_errors[]=save_ctconf_setting('paymentChequePayableTo');
		$form_errors[]=save_ctconf_setting('paymentChequeReceiver');

	}

	// remove empty entries
	foreach ($form_errors as $index => $value) { if (empty($value)) unset($form_errors[$index]); }

	// Send an update to conftool.net about new event for official list of conferences.
	if ($old != ct_get_basesettings())
		ct_mail('update@conftool.net','ConfTool: '.ctconf_get('conferenceShortName'),ct_get_basesettings(),'','','',false,false);

	// Show confirmation message to user
	if (count($form_errors)==0) {
		$session->del('db_ctconf'); // reset configuration in session...
		$session->put_infobox(ct('S_INFO_SAVE'),ct('S_INFO_SAVE_SUCCESS'));
		ct_redirect(ct_pageurl('adminToolMasterData')); // Show new title etc.
	}

} else {

	if (ct_participants_exist()) {
		ct_warningbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_PARTICIPATS_EXISTS'));
	}
	if (ct_papers_exist()) {
		ct_warningbox(ct('S_ERROR_EDITPAPERDATA'),ct('S_ERROR_EDITPAPERDATA_PAPERS_EXIST'));
	}
}

echo "<h1>".ct('S_ADMIN_TOOL_MASTERDATA_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_MASTERDATA_INTRO')."</p>\n";

$form = new CTform(ct_pageurl('adminToolMasterData'), 'post',$form_errors);
$form->width='99%';
$form->align='center';
$form->formname='masterdata';
$form->warningmessage=true;
$form->return2tab=true;
$form->waitmessage=true;

$form->add_separator(ct('S_ADMIN_TOOL_MASTERDATA_MAIN'));
$form->add_text('* '.ct('S_ADMIN_TOOL_MASTERDATA_SHORTNAME'),'form_conferenceShortName', ctconf_get('conferenceShortName','Demo \'11'), 15, 255,false, ct('S_ADMIN_TOOL_MASTERDATA_SHORTNAME_INFO').'<br>'.ct('S_FORM_PLAINTEXT_ONLY'));
$form->add_textarea('* '.ct('S_ADMIN_TOOL_MASTERDATA_NAME'),'form_conferenceName', ctconf_get('conferenceName','Demonstration Conference 2011'), 50, 2, '', ct('S_ADMIN_TOOL_MASTERDATA_NAME_INFO').'<br>'.ct('S_FORM_HTML_OK'));
$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_SUBTITLE'),'form_conferenceSubtitle', ctconf_get('conferenceSubtitle',''), 50, 255, false, ct('S_ADMIN_TOOL_MASTERDATA_SUBTITLE_INFO').'<br>'.ct('S_FORM_HTML_OK'));
$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_SUBTITLE2'),'form_conferenceSubtitle2', ctconf_get('conferenceSubtitle2',''), 50, 255, false, ct('S_ADMIN_TOOL_MASTERDATA_SUBTITLE2_INFO').'<br>'.ct('S_FORM_HTML_OK'));
$form->add_spacer();
$form->add_text('* '.ct('S_ADMIN_TOOL_MASTERDATA_URI'),'form_conferenceURI', ctconf_get('conferenceURI','http://www.conftool.net/'), 50, 255,false, ct('S_ADMIN_TOOL_MASTERDATA_URI_INFO'));
$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_ORGANIZER'),'form_conferenceOrganizer', ctconf_get('conferenceOrganizer','Jane Doe'), 50, 255);
$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_CONFERENCECITY'),'form_conferenceCity', ctconf_get('conferenceCity','Hamburg'), 30, 64);

$form->add_separator(ct('S_ADMIN_TOOL_MAILSETTINGS_CMD'));
$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_CONTACTEMAIL'),'form_conferenceContactEmail', ctconf_get('conferenceContactEmail',ctconf_get('mail/contact','info@conftool.net')), 20, 255);
$form->add_spacer();
$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_SENDERNAME'),'form_conferenceSenderName', ctconf_get('conferenceSenderName',''), 20, 255);
$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_SENDEREMAIL'),'form_conferenceSenderEmail', ctconf_get('conferenceSenderEmail',ctconf_get('mail/contact','info@conftool.net')), 20, 255);
$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_REPLYTOEMAIL'),'form_conferenceReplytoEmail', ctconf_get('conferenceReplytoEmail',''), 20, 255);

if (ctconf_get('submission/enabled',true)==true) {
	$form->add_separator(ct('S_ADMIN_TOOL_MASTERDATA_UPLOADDATA'));
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_UPLOAD_FILETYPES'),'form_uploadFileTypes', ctconf_get('uploadFileTypes','pdf,doc,zip'), 15, 32, false, ct('S_ADMIN_TOOL_MASTERDATA_UPLOAD_FILETYPES_INFO').'<br>'.ct('S_FORM_CSV'));
	$form->add_textarea(ct('S_ADMIN_TOOL_MASTERDATA_UPLOAD_FILEINFO'),'form_uploadFileInfo',
					ctconf_get('uploadFileInfo','Please use <strong>PDF</strong> as document format and compress the file with <strong>ZIP</strong> if required.'),
					70, 3, ct('S_FORM_HTML_OK'));
}

if (ctconf_get('participation/enabled',true)==true) {
	$form->add_separator(ct('S_ADMIN_TOOL_MASTERDATA_CURRENCYDATA'));
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_CURRENCY_CODE'),'form_currencyCode', ctconf_get('currencyCode','EUR'), 12, 16);
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_CURRENCY_SYMBOL'),'form_currencySymbol', ctconf_get('currencySymbol','&euro;'), 12, 16);

	$form->add_separator(ct('S_ADMIN_TOOL_MASTERDATA_REGISTRATION'));
	$form->add_select(ct('S_PARTICIPATE_MEMBERID'), 'form_memberNumber', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('memberNumber',ctconf_get('participation/status_membernumber'))), false, ct('S_ADMIN_TOOL_MASTERDATA_REGISTRATION_MEMBERNUMBER'));
	#$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_REGISTRATION_REFERENCEID'),'form_invoiceReferenceID', 1, array(array('false', ct('S_NO')), array('true', ct('S_FORM_OPTIONAL')), array('required', ct('S_FORM_REQUIRED'))), array(ctconf_get('invoiceReferenceID','false')), false, '<br>'.ct('S_ADMIN_TOOL_MASTERDATA_REGISTRATION_REFERENCEID_HINT'));
	$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_REGISTRATIONTERMS'),'form_registrationTerms', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('registrationTerms',ctconf_get('participation/confirmation'))), false, '<span class="font8">'.ct('S_ADMIN_TOOL_MASTERDATA_REGISTRATIONTERMS_HINT').'<br>'.ct('S_PARTICIPATION_REGISTRATIONTERMS').'</span>');

	$form->add_separator(ct('S_ADMIN_TOOL_MASTERDATA_INVOICEDATA'));
	$form->add_textarea(ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_SENDER'),'form_invoiceSender', ctconf_get('invoiceSender',"Department abc<br>\nUniversity of ABC<br>\nProf. Dr. XYZ<br>\nStreet 000<br>\nZIP, Town<br>\nCountry"), 50, 5, false, ct('S_FORM_HTML_OK'));
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_SENDERCITY'),'form_invoiceSenderCity', ctconf_get('invoiceSenderCity','Hamburg'), 30, 64);
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_TAXNO'),'form_invoiceTaxNo', ctconf_get('invoiceTaxNo','DE-123451234'), 20, 30, false, ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_TAXNO_INFO'));
	$form->add_spacer();
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_INVOICEPREFIX'),'form_invoicePrefix', ctconf_get('invoicePrefix','CT-'), 15, 255);
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_INVOICENO_DIGITS'),'form_invoiceDigits', ctconf_get('invoiceDigits','5'), 2, 2);
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_INVOICENO_NEXT'),'form_invoiceNo', ctconf_get('invoiceNo','00001'), 11, 11);
	$form->add_spacer();
	$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_SHOW'),'form_invoiceShow', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('invoiceShow','1')), false, ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_SHOW_HINT').'<br><i>'.ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_SHOW_HINT2').'</i>');
	$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_PROFORMA'),'form_invoiceProforma', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('invoiceProforma','0')), false, ct('S_ADMIN_TOOL_MASTERDATA_INVOICE_PROFORMA_HINT'));

	$form->add_separator(ct('S_ADMIN_TOOL_MASTERDATA_PAYMENT'));
	$form->add_label('',ct('S_ADMIN_TOOL_MASTERDATA_PAYMENT_INFO'));
	$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_PAYMENT_CASH'),'form_paymentCash', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('paymentCash','0')), false, ct('S_ADMIN_TOOL_MASTERDATA_PAYMENT_CASH_HINT'));

	$form->add_subseparator(ct('S_ADMIN_TOOL_MASTERDATA_CREDITCARDDATA'));
	$form->add_label('',ct('S_ADMIN_TOOL_MASTERDATA_CREDITCARD_GENERAL'));
	$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_CREDITCARD'),'form_paymentCreditCard', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('paymentCreditCard','1')), false, '');
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_CREDITCARD_TYPES'),'form_paymentCreditCardTypes', ctconf_get('paymentCreditCardTypes','Visa, MasterCard, Diners, American Express'), 50, 255, false, ct('S_ADMIN_TOOL_MASTERDATA_CREDITCARD_TYPES_INFO').'<br>'.ct('S_FORM_CSV'));
	$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_CREDITCARD_USE_CVC'),'form_paymentCreditCardUseCVC', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('paymentCreditCardUseCVC','0')), false, ct('S_ADMIN_TOOL_MASTERDATA_CREDITCARD_USE_CVC_HINT'));

	$form->add_subseparator(ct('S_ADMIN_TOOL_MASTERDATA_TRANSFERDATA'));
	$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER'),'form_paymentTransfer', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('paymentTransfer','1')), false, '');
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER_ACCOUNTHOLDER'),'form_paymentTransferAccountHolder', ctconf_get('paymentTransferAccountHolder',''), 40, 40);
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER_ACCOUNTNO'),'form_paymentTransferAccountNo', ctconf_get('paymentTransferAccountNo','012345678'), 20, 32);
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER_BANKNAME'),'form_paymentTransferBankName', ctconf_get('paymentTransferBankName','Test Bank, Teststreet 12, D-20000 Hamburg'), 40, 64);
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER_BANKCODE'),'form_paymentTransferBankCode', ctconf_get('paymentTransferBankCode',''), 20, 32);
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER_SWIFT'),'form_paymentTransferSWIFT', ctconf_get('paymentTransferSWIFT',''), 12, 20, false, ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER_INFO'));
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER_IBAN'),'form_paymentTransferIBAN', ctconf_get('paymentTransferIBAN',''), 30, 32, false, ct('S_ADMIN_TOOL_MASTERDATA_TRANSFER_INFO'));

	$form->add_subseparator(ct('S_ADMIN_TOOL_MASTERDATA_PAYMENT_CHEQUE'));
	$form->add_select(ct('S_ADMIN_TOOL_MASTERDATA_PAYMENT_CHEQUE'),'form_paymentCheque', 1, array(array('1', ct('S_YES')), array('0', ct('S_NO'))), array(ctconf_get('paymentCheque','0')), false, '');
	$form->add_text(ct('S_ADMIN_TOOL_MASTERDATA_PAYMENT_CHEQUE_PAYABLETO'),'form_paymentChequePayableTo', ctconf_get('paymentChequePayableTo','Jane Doe, University of Hamburg'), 50, 255);
	$form->add_textarea(ct('S_ADMIN_TOOL_MASTERDATA_PAYMENT_CHEQUE_RECEIVER'),'form_paymentChequeReceiver', ctconf_get('paymentChequeReceiver',"Department abc\nUniversity of ABC\nMrs. Jane Smith\nStreet 000\nZIP, Town\nCountry"), 60, 5, false, ct('S_FORM_PLAINTEXT_ONLY'));

}

$form->add_submit('cmd_masterdata_save', ct('S_BUTTON_SAVE'));
$form->add_submit('cmd_masterdata_cancel', ct('S_BUTTON_CANCEL'));

if (ctconf_get('demomode')) $form->demomode=true;
$form->show();


?>