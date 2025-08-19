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
//$paper->show_uploadpresentationform('http://www.ivs.auckland.ac.nz/ivcnz2011/uploadPresentation.php');//action to some page

?>
<form action="http://www.ivs.auckland.ac.nz/ivcnz2011/uploadPresentation.php" method="post"
enctype="multipart/form-data">
<table width="99%" cellspacing="1" cellpadding="2" border="0" align="center" class="form_table">
<tbody><tr><td valign="top" align="left" class="form_td_separator" colspan="3">
<span class="form_separator_label">Information on This Contribution</span>
</td></tr>
<tr><td width="30%" valign="top" align="right" class="form_td_label">
<span class="form_label">Title of Contribution</span>&nbsp;
</td><td width="70%" valign="top" align="left" colspan="2" class="form_td_field">
<span class="standard"><? echo $paper->get_special('title'); ?></span>
</td></tr>
<tr><td width="30%" valign="top" align="right" class="form_td_label">
<span class="form_label">Author(s)</span>&nbsp;
</td><td width="70%" valign="top" align="left" colspan="2" class="form_td_field">
<span class="standard"><? echo $paper->get_special('author'); ?></span>
</td></tr>
<tr><td valign="top" align="left" class="form_td_separator" colspan="3">
<span class="form_separator_label">Upload file(s) to server</span>
</td></tr>
<tr><td width="30%" valign="top" align="right" class="form_td_label">
<span class="form_label">Submission Details</span>&nbsp;
</td>
<td width="70%" valign="top" align="left" colspan="2" class="form_td_field">
<span class="standard">Oral Presentation or 1 slide Poster Presentation (ppt, pptx, pdf, zip, rar, 7z)</span>
</td></tr>
<tr><td valign="top" align="left" class="form_td_separator" colspan="3"><img width="1" height="1" src="http://www.ivs.auckland.ac.nz/ivcnz2011_temp/htdocs/images/spacer.gif"></td></tr>
<tr><td width="30%" valign="top" align="right" class="form_td_label">&nbsp;

</td>
<td width="70%" valign="top" align="left" colspan="2" class="form_td_field">
<span class="standard">The maximum file size allowed is 100MB.</span>
</td>
</tr>
<tr><td width="30%" valign="top" align="right" class="form_td_label">
<span class="form_label">Select filename</span>&nbsp;
</td><td width="70%" valign="top" align="left" colspan="2" class="form_td_field">
<input type="file" name="file" id="file"  size="40" />
</td></tr>
<tr><td valign="top" align="right" class="form_td_buttons" colspan="3">
<input name="paper_id" type="hidden" id="paper_id" value="<? echo $paper->pdata['ID'] ?>" />
<input type="submit" name="submit" value="Upload File(s) and Complete Submission" />
</td></tr>
</tbody></table>
</form>
