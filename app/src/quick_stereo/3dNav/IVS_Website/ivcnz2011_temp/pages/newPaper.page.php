<?php
//
// PAGE:		newPaper
// DESC:		create a new paper record and upload a file
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array('index'));

// if user has no admin	rights,	check if phase "submission"	is active
if (!$user->is_admin() and !ct_check_phases("submission")) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}
// Get ID of author if user is admin...
if (in_http('form_personID') && $user->is_admin())
	$authorID = (int)$http['form_personID'];
else
	$authorID = $user->get_id();


if (isset($http['cmd_paper_saveinfo']))	{
	// Process data	of step	1
	$paper =& $session->get('new_paper');
	if (!is_object($paper) || $paper==NULL || $paper->get("title")=="")	{
		$paper = new CTPaper;
		$paper->set('personID',	$authorID);
	}
	$errors	= $paper->process_infoform();
	$session->putref('new_paper', $paper);
	if ($errors===false) {
			show_uploadform_step2();
	} else {
		ct_errorbox(ct('S_ERROR_NEWPAPER'), ct('S_ERROR_NEWPAPER_INCOMPLETE'));
		echo "<h1>". ct('S_NEWPAPER_TITLE_STEP1')."</h1>\n";
		echo "<p class=standard>".ct('S_NEWPAPER_INTRO_STEP1')."</p>\n";
		$paper->show_infoform(ct_pageurl('newPaper'), $errors);
	}
// Process data	of step	2: Upload selected
} elseif (isset($http['cmd_paper_upload']))	{
	$paper =& $session->get('new_paper');
	if (!is_object($paper) || $paper==NULL || $paper->get("title")=="")	{
		$session->put_errorbox(ct('S_ERROR_NEWPAPER'), ct('S_ERROR_NEWPAPER_MISSINGINFO'));
		ct_redirect(ct_pageurl('newPaper'));
	}
	$upload_ok = $paper->process_uploadform();
	if ($upload_ok)	{
		$paper->persist();
		ct_load_lib('mail.lib');
		ct_mail_author_submission_confirmation($paper,true);

		$person = new CTPerson();
		$person->load_by_id($paper->get('personID'));
		$person->add_status('author');
		$person->persist();

		$session->del('new_paper');
		$session->put_infobox(ct('S_INFO_NEWPAPER'), ct('S_INFO_NEWPAPER_SUBMITTED'));
		ct_redirect($session->get_besturl());
	} else {
		// Upload failed!
		show_uploadform_step2();
	}
// Process data of setp2: Skip Upload selected
} elseif (isset($http['cmd_newpaper_skipupload'])) {
	// Skipped upload
	$paper =& $session->get('new_paper');
	if (is_object($paper) && $paper!=NULL && $paper->get("title")!="")	{
		$paper->persist();
		ct_load_lib('mail.lib');
		ct_mail_author_submission_confirmation($paper,true);

		$person = new CTPerson();
		$person->load_by_id($paper->get('personID'));
		$person->add_status('author');
		$person->persist();

		$session->del('new_paper');
		$session->put_infobox(ct('S_INFO_NEWPAPER'), ct('S_INFO_NEWPAPER_SUBMITTED'));
		ct_redirect($session->get_besturl());
	} else {
		$session->put_errorbox(ct('S_ERROR_NEWPAPER'), ct('S_ERROR_NEWPAPER_MISSINGINFO'));
		ct_redirect(ct_pageurl('newPaper'));
	}
// nothing set: Show Form Step 1
} else {
	// Show standard form if normal page request
	if ($_SERVER["REQUEST_METHOD"]=="GET") {
		$paper =& $session->get('new_paper'); // Try to load paper data from session
		if (!is_object($paper)) {
			$paper = new CTPaper;	// New upload.
			$session->putref('new_paper', $paper);
		}
		$paper->set('personID', $authorID);
		echo "<h1>".ct('S_NEWPAPER_TITLE_STEP1')."</h1>\n";
		echo "<p class=standard>".ct('S_NEWPAPER_INTRO_STEP1')."</p>\n";
		$paper->show_infoform(ct_pageurl('newPaper'));
	// Something strange is	going on. Most likely the user tried to	upload a big
	// file	and	the	server settings	are	wrong, so the post message was truncated...
	} else {
		$session->put_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_FAILED'));
		ct_redirect($session->get_besturl());
	}
}

// Show	step 2 of upload
function show_uploadform_step2() {
	global $session;
	echo "<h1>".ct('S_NEWPAPER_TITLE_STEP2')."</h1>\n";
	echo "<p class=standard>".ct('S_NEWPAPER_INTRO_STEP2_1')."</p>\n";

	$paper =& $session->get('new_paper');

	$paper->show_detailed('95%');
	$form =	new	CTForm(ct_pageurl('newPaper'), 'post');
	$form->width = "95%";
	$form->align = "center";
	$form->waitmessage=true;
	$form->add_separator(ct('S_NEWPAPER_SKIPUPLOAD'));
	$form->add_submit("cmd_newpaper_skipupload", ct('S_NEWPAPER_SUBMIT_SKIPUPLOAD'));
	$form->show();

	echo "<p class=standard>".ct('S_NEWPAPER_INTRO_STEP2_2')."</p>\n";
	$paper->show_uploadform(ct_pageurl('newPaper'));

	echo "<p class=standard>".ct('S_NEWPAPER_INTRO_STEP2_3')."</p>\n";
}


?>
