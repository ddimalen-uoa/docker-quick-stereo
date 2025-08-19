<?php
#
# PAGE:		adminReviewDetails
# DESC:		Show details of a review to author etc.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array("index", "adminPapers", "adminPapersResults"));

if (in_http('form_personID') && in_http('form_paperID')) {
	$review = new CTReview;
	$review->load($http['form_paperID'], $http['form_personID']);

	$review->show_detailed(600,'center');
} else {
	ct_redirect(ct_pageurl('adminPapersBrowse'));
}
?>