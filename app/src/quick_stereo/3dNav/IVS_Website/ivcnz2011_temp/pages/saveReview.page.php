<?php
#
# PAGE		saveReview
# REQUIRES	PC-status, assignment to paper
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

# if user has no admin rights, check if phase "reviewing" is active
if (!$user->is_admin() and !ct_check_phases("reviewing")) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}

if (in_http('cmd_savereview')) {
	$review = new CTReview();
	# check if this review is assigned
	if ($review->load($http['form_paperID'], $http['form_personID'])) {
		# check if user is allowed to save this review
		if (!$user->is_admin() && ($user->get('ID') != $http['form_personID'])) {
			$session->put_errorbox(ct('S_ERROR_REVIEW'), ct('S_ERROR_REVIEW_NOTALLOWED'));
			ct_redirect(ct_pageurl('index'));
		}
		$errors = $review->process_form();
		if ($errors) {
			$session->put('__review', $review);
			$session->put_errorbox(ct('S_ERROR_REVIEW'), ct('S_ERROR_REVIEW_INCOMPLETE'));
			ct_redirect(ct_pageurl('reviewPaper', array('error'=>'yes')));
		}
		if ($review->get('creationdate') == 0) {
			$review->set('creationdate',date('Y-m-d H-i-s'));
		}
		echo ($review->get('creationdate'));
		$review->persist();
        $session->del('__review');
		$session->put_infobox(ct('S_INFO_REVIEW'), ct('S_INFO_REVIEW_SAVED'));
	} else {
		$session->put_errorbox(ct('S_ERROR_REVIEW'), ct('S_ERROR_REVIEW_NOTASSIGNED'));
	}
}
ct_redirect(ct_pageurl('browseAssignedPapers'));

?>