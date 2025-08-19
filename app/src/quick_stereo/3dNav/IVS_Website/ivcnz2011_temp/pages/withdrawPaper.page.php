<?php
#
# PAGE:		withdrawPaper
# DESC:		Withdraw a contribution... Either your own or any for the admin.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

# this page does only make sense if an ID for a paper was provided. If not,
# we redirect to the index page
if (!isset($http['form_id'])) {
	ct_redirect(ct_pageurl('error'));
}

$paper = new CTPaper;
# redirect to index if id does not correspond to a real paper
if (!$paper->load_by_id($http['form_id'])) {
	$session->put_errorbox(ct('S_ERROR_PAPER_WITHDRAW'), ct('S_ERROR_PAPER_EDIT_NOACCESS'));
	ct_redirect($session->get_besturl());
}

# if user has no admin rights, check if phase "submission" is active
if (!$user->is_admin() and !ct_check_phases("submission")) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}

$author =& $paper->get_author();

# check if user is allowed to delete the paper. access is denied
# if user has no admin rights and is not the author of the paper
if (!$user->is_admin() && ($user->get('ID') != $author->get('ID'))) {
	$session->put_errorbox(ct('S_ERROR_PAPER_WITHDRAW'), ct('S_ERROR_PAPER_WITHDRAW_NOACCESS'));
	ct_redirect($session->get_besturl());
}

if (isset($http['cmd_delete_yesconfirm'])) {
	if ($paper->withdraw()) {
		if ($author->check_author()) {
			$author->persist();
		}
		$user->reload();
		$session->put_infobox(ct('S_INFO_PAPER_WITHDRAW'), ct('S_INFO_PAPER_WITHDRAW_SUCCESS'));
	} else {
		$session->put_errorbox(ct('S_ERROR_PAPER_WITHDRAW'), ct('S_ERROR_PAPER_WITHDRAW_FAILED'));
	}
	ct_redirect($session->get_besturl());
} else {
	if (isset($http['cmd_delete_noconfirm'])) {
		ct_redirect($session->get_besturl());
	} else {
		$form = new CTForm(ct_pageurl('withdrawPaper'), 'post');
		$form->width = '90%';
		$form->align = 'center';
		$form->add_hidden(array(array('form_id', $paper->get_id())));
		$form->add_label(ct('S_PAPER_FORM_ID'), $paper->get_id());
		$form->add_label(ct('S_PAPER_FORM_TITLE'), $paper->get_special('title'));
		$form->add_label(ct('S_PAPER_FORM_AUTHOR'), $paper->get_special('author'));
		$form->add_submit('cmd_delete_yesconfirm', ct('S_PAPER_WITHDRAW_YESCONFIRM'));
		$form->add_submit('cmd_delete_noconfirm', ct('S_PAPER_WITHDRAW_NOCONFIRM'));

		echo "<h1>".ct('S_PAPER_WITHDRAW_TITLE')."</h1>\n";
		echo "<p class=\"standard\">".ct('S_PAPER_WITHDRAW_INTRO')."</p>\n";
		$form->show();
	}
}


