<?php
#
# PAGE:		showAbstract
# DESC:     Shows a paper summary in a nicely formatted format.
# HINT:
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

if (in_http('form_paperID')) $http['form_id']=$http['form_paperID']; // Both parameters are allowed...
if (!in_http('form_id')) {
	ct_redirect(ct_pageurl('index'));
}
ct_load_class('CTPaper');
$paper = new CTPaper;

# check if paper exists
if (!$paper->load_by_id($http['form_id'])) {
	$session->put_errorbox(ct('S_ERROR_PAPER_DOWNLOAD'), ct('S_ERROR_PAPER_DOWNLOAD_NOACCESS'));
	ct_redirect(ct_pageurl('error'));
}

# check user rights
if (! ( $user->is_admin() || $user->is_chair() ||
     $paper->is_reviewed_by($user->get('ID')) ||
	(is_object($user) && $paper->get('personID') == $user->get_id()) )) {
	$session->put_errorbox(ct('S_ERROR_PAPER_DOWNLOAD'), ct('S_ERROR_PAPER_DOWNLOAD_NOACCESS'));
	ct_redirect(ct_pageurl('error'));
}

ct_vspacer('14');

$paper->show_detailed('100%');
ct_vspacer();
$paper->show_abstract('100%');

ct_vspacer('14');



?>