<?php
#
# PAGE:		adminUsersLoginAs
# DESC:		Allow administrator to login as (any) other user.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();

if (isset($http['form_id'])) {
	$person = new CTPerson;
	if ($person->load_by_id($http['form_id']) && ($person->get('deleted') == '0')) {
		$person->check_author();
		$person->check_participant();
		$person->set('logoutdate','0000-00-00 00:00:00');
		$person->persist();
		$session->become($person);
		$session->put_infobox(ct('S_INFO_ADMIN_USERS_LOGINAS'), ct('S_INFO_ADMIN_USERS_LOGINAS_SUCCESS'));
		ct_redirect(ct_pageurl('index'));
	} else {
		$session->put_errorbox(ct('S_ERROR_ADMIN_USERS_LOGINAS'), ct('S_ERROR_ADMIN_USERS_LOGINAS_NOTFOUND'));
		ct_redirect($session->get_besturl());
	}
} else {
	$session->put_errorbox(ct('S_ERROR_ADMIN_USERS_LOGINAS'), ct('S_ERROR_ADMIN_USERS_LOGINAS_NOID'));
	ct_redirect($session->get_besturl());
}

?>