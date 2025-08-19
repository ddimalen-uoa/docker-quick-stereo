<?php
//
// PAGE:		sendPassword
// DESC:		Send forgotten password to user... If there are any problems, check your mail server configuration!
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_load_lib("mail.lib");

if (isset($http['cmd_send_password']) || isset($http['form_email'])) {

	if ($http['form_email']=="") {
		$session->put_errorbox(ct('S_ERROR_PASSWORD'), ct('S_ERROR_PASSWORD_EMPTY'));
	} else {
		$query="select * from persons where (email='".$http['form_email']."' || username='".$http['form_email']."') and deleted=0";
		$r = $db->query($query);
		if ($r && ($db->num_rows($r) ==0)) {
			$session->put_errorbox(ct('S_ERROR_PASSWORD'), ct('S_ERROR_PASSWORD_FAILED1')." <code><b>".ct_form_encode($http['form_email'])."</b></code> ".ct('S_ERROR_PASSWORD_FAILED2')." <a href=\"mailto:".ctconf_get('conferenceContactEmail')."\">".ctconf_get('conferenceContactEmail')."</a>");
		} elseif ($r && ($db->num_rows($r) == 1)) {
			// user exists!
			$t = $db->fetch($r);

			// create email
			$mail_content= ct_get_mail_salutation($t['gender'],stripslashes($t['firstname']." ".$t['name'])).",";
			$mail_content.="\n\n";
			$mail_content.=ct('S_USER_REG_EMAIL_CONTENT4')." ".stripslashes($t['username'])."\n";
			$mail_content.=ct('S_USER_REG_EMAIL_CONTENT5')." ".stripslashes($t['password']);
			$mail_content.="\n\n";
			$mail_content.=ct_br2nl(ct('S_USER_REG_EMAIL_REGARDS',array(strip_tags(ctconf_get('conferenceShortName')))))."\n";
			$mail_content.=ct_get_mail_signature();
			// sending email
			if (ct_mail($t['email'],strip_tags(ctconf_get('conferenceShortName')).': '.ct('S_USER_SENDPASSWORD_EMAIL_SUBJECT'),$mail_content))
			$session->put_infobox(ct('S_INFO_PASSWORD_SUCCESS'), ct('S_INFO_PASSWORD_SUCCESS_INFO'));
			else
			$session->put_errorbox(ct('S_INFO_PASSWORD_FAILED'), ct('S_INFO_PASSWORD_FAILED_INFO'));
			ct_redirect(ct_pageurl("login", array("ctusername"=>$http['form_email'])));
		} else {
			$session->put_errorbox(ct('S_ERROR_PASSWORD'), ct('S_ERROR_PASSWORD_SEVERAL')." <a href=\"mailto:".ctconf_get('conferenceContactEmail')."\">".ctconf_get('conferenceContactEmail')."</a>");
		}
	}
	ct_redirect(ct_pageurl("sendPassword"));

} else {

	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	$form = new CTForm(ct_pageurl('sendPassword'),"post");
	$form->width = '80%';
	$form->align = 'center';

	$form->add_separator(ct('S_LOGIN_SENDPASSWORD'));
	$form->add_label("",ct('S_SENDPW_DESCRIPTION'));
	$form->add_text(ct('S_SENDPW_EMAIL'), 'form_email', '', 30, 255);
	$form->add_submit('cmd_send_password', ct('S_SENDPW_SUBMIT'));

	$form->show();
	echo "<br>";
	echo "<br>";
	echo "<br>";
}


?>