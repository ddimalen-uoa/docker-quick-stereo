<?php
//
// PAGE: showPerson
// Show personal data.
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array('index'));

ct_load_lib('mail.lib');

$person = new CTPerson();
$person->load_by_id($user->get_id());

echo "<h1>".ct('S_SHOWPERSON_TITLE')."</h1>\n";
echo "<p class=\"fontnormal font10\">".ct('S_SHOWPERSON_INTRO2')."</p>\n";
$person->show_detailed("100%",'center');

?>
