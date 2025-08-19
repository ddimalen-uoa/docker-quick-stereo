<?php
#
# PAGE:		register
# Register new Conftool users
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

if ($session->loggedin()) {
	$session->put_infobox(ct('S_INFO_USERS_LOGOUT'), ct('S_INFO_USERS_LOGOUT_SUCCESS',array(ct_form_encode(trim($user->get_fullname())))));
	$session->logout();
}


$person = new CTPerson;
$form_errors = array();

ct_load_lib('mail.lib');
ct_load_lib('password.lib');

# if user has no admin rights, check if phase "userregistration" is active
if (!ct_check_phases("userregistration")) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}


// Save was pressed
if (isset($http['cmd_save_person']) || isset($http['cmd_save_person_register'])) {
	$r = $db->query("select * from persons where (username='".$http['form_username']."' OR email='".$http['form_email']."') and (deleted=0)");
	if ($db->num_rows($r) == 0) {
		$form_errors = $person->process_form();
		if (!$form_errors) {
			$person->persist();

			# sending email
			ct_mail_user_registration_confirmation($person, isset($http['form_passwmail']));

			$session->put_infobox(ct('S_INFO_REGISTER'), ct('S_INFO_REGISTER_SUCCESS'));
			$session->login($http['form_username'], $http['form_pwd']);

			# if the registration for participation is open and submissions and finaluploads and reviews are finished, jump
		    # directly to the participation registration page...
			if (isset($http['cmd_save_person_register'])) {
				ct_redirect(ct_pageurl('participate'));
			} else {
				ct_redirect(ct_pageurl('index'));
			}
		} else {
			ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_INCOMPLETE'));
			// => show form again (see below...)
			if (is_array($form_errors) && (in_array('form_email',$form_errors) || in_array('form_email2',$form_errors)))
				ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_EMAIL_INVALID'));
			if (is_array($form_errors) && in_array('form_pwd',$form_errors)) {
				$msg = ct_validate_password($http['form_pwd'],$person);
				ct_errorbox(ct('S_ERROR_REGISTER'), '<ul><li>'.implode('<li>',$msg).'</ul>');
			}
		}
	} else {
		$form_errors = $person->process_form();
		$row = $db->fetch($r);
		if ($row[email] == $http['form_email']) {
			$form_errors[] = "form_email";
			ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_EMAILEXISTS'));
		} else {
			$form_errors[] = "form_username";
			ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_USERNAMEEXISTS'));
		}
	}
}

echo "<h1>".ct('S_REGISTER_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_REGISTER_INTRO')."</p>\n";

if (!is_array($form_errors)) {
	$form_errors = array();
}

if (isset($http['ctusername'])) {
	if (preg_match("/^.*@.*\.[a-z]+/i",$http['ctusername'])) {
		$person->set("email",$http['ctusername']);
		$person->set("username",$http['ctusername']);
	} else {
		$person->set("username",$http['ctusername']);
	}
}

$person->show_form(ct_pageurl('register'), $form_errors);


?>

