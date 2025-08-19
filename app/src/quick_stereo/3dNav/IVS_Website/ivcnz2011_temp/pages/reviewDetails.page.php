<?php
//
// PAGE:		reviewDetails
// DESC:		Show details to a review
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array("index", "paperDetails"));

// check if access is possible...
if (!
	(ct_check_phases("reviewresults") ||	$user->is_admin() || $user->is_chair()
	|| ($user->is_pc() && $user->get_id() == $http['form_personID'] && ct_check_phases("reviewing")) )) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}


if (in_http('form_personID') && in_http('form_paperID')) {

	$paper = new CTPaper();
	$paper->load_by_id($http['form_paperID']);
	$author =& $paper->get_author();

	$review = new CTReview();

	// check if user is allowed to view this review. access is denied
	// if user has no admin rights and is not the author of the paper or the reviewer.
	if (($user->is_admin() ||                              // Admin is OK
   		 $user->is_chair() ||							  // Chair is OK as well
  		 $user->get_id() == $http['form_personID'] ||      // Reviewer may see his own review
  		 $user->get_id() == $paper->get('personID')) &&    // Authors may see their reviews
	     $review->load($http['form_paperID'], $http['form_personID'])) { // does review exist?
	     	ct_vspacer();
	    	$review->show_detailed(650,'center');
	     	ct_vspacer();
	    } else { // Access denied...
			$session->put_errorbox(ct('S_ERROR_PAPER_DETAILED'), ct('S_ERROR_PAPER_DETAILED_NOACCESS'));
			ct_redirect(ct_pageurl('error'));
		}
} else {
	ct_redirect(ct_pageurl('index'));
}
?>
