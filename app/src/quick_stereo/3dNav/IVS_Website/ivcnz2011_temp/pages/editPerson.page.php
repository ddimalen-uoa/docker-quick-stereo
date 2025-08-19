<?php
#
# PAGE:		editPerson
# Edit personal data.
#

if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array('index'));

$person = new CTPerson;
$form_errors = array();

# check if user is allowed to edit the user data!
# if user has no admin rights and is not the person himself he musn't.
if (!$user->is_admin() &&
		((isset($http['form_userID']) && $user->get('ID') != $http['form_userID']) ||
		 (isset($http['form_id']) && $user->get('ID') != $http['form_id'])            ) ) {
	$session->put_errorbox(ct('S_ERROR_PERSON_EDIT'), ct('S_ERROR_PERSON_EDIT_NOACCESS'));
	ct_redirect(ct_pageurl('error'));
}

if (isset($http['cmd_save_person'])) {
	$person->load_by_id($http['form_id']);
	$form_errors = $person->process_form();
	if (!$form_errors || $user->is_admin()) {
		if (ct_strlen($person->get("password")) < 5) {
			ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_PASSWORD_TOO_SHORT'));
			$form_errors = array("form_pwd");
		} else {
			$r = $db->query("select * from persons where (username='".$http['form_username']."' OR email='".$http['form_email']."') and (deleted=0) AND ID<>'".$http['form_id']."'");
			if ($db->num_rows($r) > 0) {
				$row = $db->fetch($r);
				if ($row[email] == $http['form_email']) {
					$form_errors[] = "form_email";
					ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_EMAILEXISTS'));
				} else {
					$form_errors[] = "form_username";
					ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_USERNAMEEXISTS'));
				}
			} else {
				$person->persist();
				$user->reload(); // reload user data, maybe it changed...
				$session->put_infobox(ct('S_INFO_SAVEUSER'),ct('S_INFO_SAVEUSER_SUCCESS'));
    			ct_redirect($session->get_besturl());
			}
		}
	} else {
		ct_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_SAVEUSER_FAILED'));
	}
}
else if (isset($http['form_delete_ID']) && $user->is_admin() ) {
	# Don't delete Administrator!
	if ($http['form_delete_ID']<>$user->get('ID')) {
		$person->load_by_id($http['form_delete_ID']);
		echo "<h1>".ct('S_DELETEUSER_TITLE')."</h1>\n";
		echo "<p class=\"standard\">".ct('S_DELETEUSER_QUERY')."</p>\n";
		$person->show_shortinfo('600');
		$form = new CTForm(ct_pageurl('editPerson'), 'post', array());
		$form->width='600';
		$form->align='center';
		$form->add_hidden(array(array('form_userID', $http['form_delete_ID'])));
		$form->add_submit('cmd_delete_person', ct('S_DELETEUSER_CONFIRM'));
		$form->add_submit('cmd_cancel', ct('S_BUTTON_CANCEL'));
		$form->show();
	}
	else {
		$session->put_errorbox(ct('S_ERROR_DELETEUSER'), ct('S_ERROR_DELETEUSER_FAILED'));
		ct_redirect($session->get_besturl());
	}

}
else if(isset($http['cmd_cancel'])){
  	ct_redirect($session->get_besturl());
}
else if (isset($http['cmd_delete_person'])) {
	if ($user->is_admin()) {
		$person->load_by_id($http['form_userID']);
		if ($person->delete()) {
			$session->put_infobox(ct('S_INFO_DELETEUSER'), ct('S_INFO_DELETEUSER_SUCCESS'));
			ct_redirect($session->get_besturl());
		} else {
			$session->put_errorbox(ct('S_ERROR_DELETEUSER'), ct('S_ERROR_DELETEUSER_FAILED'));
			ct_redirect($session->get_besturl());
		}
	}
} else if (isset($http['cmd_undelete_person'])) {
	if ($user->is_admin()) {
		$person->load_by_id($http['form_id']);
		if ($person->undelete()) {
			$session->put_infobox(ct('S_INFO_UNDELETEUSER'), ct('S_INFO_UNDELETEUSER_SUCCESS'));
			ct_redirect($session->get_besturl());
		} else {
			$session->put_errorbox(ct('S_ERROR_UNDELETEUSER'), ct('S_ERROR_UNDELETEUSER_FAILED'));
			ct_redirect(ct_pageurl('error'));
		}
	}
} else if (isset($http['cmd_cancel'])) {
	ct_redirect($session->get_besturl());
} else {
	if (isset($http['form_userID']) && ($user->is_admin() === true)) {
		$person->load_by_id($http['form_userID']);
	} else {
		$person->load_by_id($user->get('ID'));
	}
}

if ( ! (isset($http['form_delete_ID'])   ) ) {
	echo "<h1>".ct('S_EDITPERSON_TITLE')."</h1>\n";
	echo "<p class=\"standard\">".ct('S_EDITPERSON_INTRO')."</p>\n";

	$person->show_form(ct_pageurl('editPerson'), $form_errors);
}
?>
