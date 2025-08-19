<?php
#
# PAGE		reviewPaper
# DESC		Enter or change the review to a paper that was assigned to you...
# REQUIRES	PC-status, assignment to paper
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array('index','browseAssignedPapers'));

# if user has no admin rights, check if phase "reviewing" is active
if (!$user->is_admin() and !ct_check_phases("reviewing")) {
	$session->put_errorbox(ct('S_ERROR_PHASE'), ct('S_ERROR_PHASE_INACTIVE'));
	ct_redirect(ct_pageurl('error'));
}

echo "<H3>".ct('S_REVIEW_PAPER')."</H3>\n";
echo "<p class=\"standard\">".ct('S_REVIEW_PAPER_INTRO')."&nbsp;\n";
  if ($_SERVER['QUERY_STRING'] != "" && $http['print']!="yes") {
		$qry = str_replace("&","&amp;",$_SERVER['QUERY_STRING'])."&amp;print=yes";
		echo " &nbsp;&nbsp;<a target='_blank' href=\"".$_SERVER['SCRIPT_NAME']."?".$qry."\" >".ct('S_CTFOOT_PRINTVIEW')."&nbsp;<img src=\"images/printer.gif\" alt=\"".ct('S_CTFOOT_PRINTVIEW')."\" border=0 style=\"vertical-align: bottom\"></a>";
  }
echo "&nbsp;</p>\n";

if (in_http('error', 'yes')) {
	$review =& $session->get('__review');
	if (!is_object($review)) {
		ct_redirect(ct_pageurl('browseAssignedPapers'));
	}
} else {
	$review = new CTReview();
	if (!in_http('form_personID') || !$user->is_admin() ) {  # only admin may see other reviews.
		$http['form_personID'] = $user->get('ID');
	}
	if (!$review->load($http['form_paperID'], $http['form_personID'] )) {
		$session->put_errorbox(ct('S_ERROR_REVIEW'), ct('S_ERROR_REVIEW_NOTALLOWED'));
		ct_redirect(ct_pageurl('error'));
	}
}

$review->show_reviewform("90%", $review->errors);
?>
