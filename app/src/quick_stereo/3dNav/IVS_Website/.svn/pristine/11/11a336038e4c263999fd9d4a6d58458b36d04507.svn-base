<?php
#
# PAGE:		invoice
# Show invoice for printout.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

$participation = new CTParticipation;
$participation->load_by_id($user->get('ID'));
$participation->show_invoice_data('640','center');

?>