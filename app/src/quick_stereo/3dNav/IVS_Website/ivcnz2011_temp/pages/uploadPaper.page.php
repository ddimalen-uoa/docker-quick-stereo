<?php
//
// PAGE:		uploadPaper
// DESC:		Upload a contribution. If there are any problems with big files, see the INSTALL documentation!
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

// this page does only make sense if an ID for a paper was provided. If not,
// we redirect to the index page
if (!isset($http['form_id'])) {
	$session->put_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_FAILED'));
	ct_redirect(ct_pageurl('index'));
}

$paper = new CTPaper;
// redirect to index if id does not correspond to a real paper
if (!$paper->load_by_id($http['form_id'])) {
	$session->put_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_EDIT_NOACCESS'));
	ct_redirect(ct_pageurl('index'));
}

// if user has no admin rights, check if phase "submission" or "finalupload" is active
if (!$user->is_admin() and !ct_check_phases(array("submission","finalupload"))) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}

$author =& $paper->get_author();

// check if user is allowed to edit paper information. access is denied
// if user has no admin rights and is not the author of the paper
if (!$user->is_admin() && ($user->get('ID') != $author->get('ID'))) {
	$session->put_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_NOACCESS'));
	ct_redirect(ct_pageurl('index'));
}

if (isset($http['cmd_paper_upload'])) {
	global $session;

	$upload_ok = $paper->process_uploadform();
	if ($upload_ok) {
		ct_load_lib('mail.lib');
		ct_mail_author_submission_confirmation($paper,false);
		$paper->persist();
		$session->put_infobox(ct('S_INFO_PAPER_UPLOAD'), ct('S_INFO_PAPER_UPLOAD_SUCCESS'));
		ct_redirect($session->get_besturl());
	}
}
echo "<h1>".ct('S_PAPER_UPLOAD_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_PAPER_UPLOAD_INTRO')."</p>";
$paper->show_uploadform(ct_pageurl('uploadPaper'));

?>
