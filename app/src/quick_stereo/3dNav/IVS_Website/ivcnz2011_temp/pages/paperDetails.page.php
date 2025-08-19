<?php
#
# PAGE:		paperDetails
# Show details to a paper including a survey of all reviews.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array('index'));

# check if phase "reviewresults" is active
if (!ct_check_phases("reviewresults")) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}

# this page does only make sense if an ID for a paper was provided. If not,
# we redirect to the index page
if (!in_http('form_id')) {
	ct_redirect(ct_pageurl('index'));
}

$paper = new CTPaper;
$paper->load_by_id($http['form_id']);

$author =& $paper->get_author();

# check if user is allowed to edit paper information. access is denied
# if user has no admin rights and is not the author of the paper
if (!$user->is_chair() && !$user->is_admin() && ($user->get('ID') != $author->get('ID'))) {
	$session->put_errorbox(ct('S_ERROR_PAPER_DETAILED'), ct('S_ERROR_PAPER_DETAILED_NOACCESS'));
	ct_redirect(ct_pageurl('error'));
}
echo "<br>";
$paper->show_detailed('99%','center');
#$paper->show_abstract(650,'center');
echo "<br>";
$paper->show_review_results(false, '99%','center');
echo "<br>";

?>