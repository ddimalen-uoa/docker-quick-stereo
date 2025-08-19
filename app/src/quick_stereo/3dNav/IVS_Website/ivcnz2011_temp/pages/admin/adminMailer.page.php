<?php
//
// PAGE:		adminMailer
// DESC:		Overview page for all Bulk Mailer functions...
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index'));

$session->set_besturl(ct('S_ADMIN_MAILER'));

echo "<h1>".ct('S_ADMIN_MAILER')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_MAILER_INTRO')."</p>\n";

echo "<table cellpadding=5 align=center width=\"100%\">\n";
echo "<tr><td colspan=2 class=\"mediumbg\">\n";
echo "<span class=\"bold10\">".ct('S_ADMIN_MAILER_CMDS_TITLE').":</span>\n";
echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
echo "<dl>\n";
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminMailerPersons')."\">".ct('S_ADMIN_MAILER_PERSONS')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_MAILER_PERSONS_INTRO')."</dd>\n";
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminMailerAuthors')."\">".ct('S_ADMIN_MAILER_AUTHORS')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_MAILER_AUTHORS_INTRO')."</dd>\n";
echo "</dl>\n";
echo "</td></tr></table>\n";
