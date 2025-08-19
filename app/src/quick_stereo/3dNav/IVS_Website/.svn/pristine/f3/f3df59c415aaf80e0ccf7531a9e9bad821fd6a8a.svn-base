<?php
#
# PAGE:		adminPapersDetails
# DESC: 	show all details to a paper to administrator or PC chair...
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirechair();
ct_pagepath(array('index', 'adminPapers', 'adminPapersBrowse'));

if (in_http('form_id')) {
	ct_vspacer();
	$paper = new CTPaper;
	if ($paper->load_by_id($http['form_id'])) {
		$width='98%';
		$paper->show_detailed($width,'center');
		$paper->show_abstract($width,'center');
		ct_vspacer('10');
		$paper->show_review_results(true, $width,'center');
		ct_vspacer('10');
		$paper->show_admin_options($width,'center');
	}
} else {
	ct_redirect(ct_pageurl('adminPapersBrowse'));
}

?>