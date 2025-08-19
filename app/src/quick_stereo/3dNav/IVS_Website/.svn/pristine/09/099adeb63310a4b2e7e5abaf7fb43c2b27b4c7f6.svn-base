<?php
#
# PAGE:		adminUsersNew
# DESC:		allows admins to create a new user account
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirefrontdesk();
ct_pagepath(array('index','admin','adminUsers'));

ct_load_lib('mail.lib');

$person = new CTPerson;
$form_errors = array();

if (isset($http['cmd_save_person']) || isset($http['cmd_save_person_register'])) {
	$r = $db->query("select * from persons where (username='".$http['form_username']."' OR email='".$http['form_email']."') and (deleted=0)");
	if ($db->num_rows($r) == 0) {
		$form_errors = $person->process_form();
		if (!$form_errors) {
			$person->persist();

			// email settings
			if ($person->is_admin())     $mail_content.=ct('S_USER_STATUS').": ".ct('S_USER_STATUS_ADMIN_SHORT').".\n";
			if ($person->is_pc())        $mail_content.=ct('S_USER_STATUS').": ".ct('S_USER_STATUS_PC').".\n";
			if ($person->is_chair())     $mail_content.=ct('S_USER_STATUS').": ".ct('S_USER_STATUS_CHAIR').".\n";
			if ($person->is_frontdesk()) $mail_content.=ct('S_USER_STATUS').": ".ct('S_USER_STATUS_FRONTDESK').".\n";
			if ($person->is_assistant()) $mail_content.=ct('S_USER_STATUS').": ".ct('S_USER_STATUS_ASSISTANT').".\n";

			// send email
			ct_mail_user_registration_confirmation($person,true, $mail_content);

			$session->put_infobox(ct('S_INFO_ADMIN_USERS_NEW'), ct('S_INFO_ADMIN_USERS_NEW_SUCCESS'));
			if (isset($http['cmd_save_person_register']))
				ct_redirect(ct_pageurl('adminParticipantsEditDelete',array("form_userID"=>$person->get_id())));
			else
				ct_redirect($session->get_besturl());
		} else {
			ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_INCOMPLETE'));
            if (in_array("form_email",$form_errors)) {
				ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_EMAIL_INVALID'));
			} elseif (in_array("form_pwd",$form_errors)) {
				$msg = ct_validate_password($http['form_pwd'],$person);
				ct_errorbox(ct('S_ERROR_REGISTER'), '<ul><li>'.implode('<li>',$msg).'</ul>');
			}
		}
	} else {
		$form_errors = $person->process_form();
		$row = $db->fetch($r);
		if ($row['email'] == $http['form_email']) {
			$form_errors[] = "form_email";
			ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_EMAILEXISTS'));
		} else {
			$form_errors[] = "form_username";
			ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_USERNAMEEXISTS'));
		}
	}
}

echo "<h1>".ct('S_ADMIN_USERS_NEW_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_USERS_NEW_INTRO')."</p>\n";

$person->show_form(ct_pageurl('adminUsersNew'), $form_errors);

?>