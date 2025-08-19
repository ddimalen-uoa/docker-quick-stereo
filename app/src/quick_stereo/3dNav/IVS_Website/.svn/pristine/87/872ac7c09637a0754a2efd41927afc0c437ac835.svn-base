<?php
#
# PAGE:		adminPapers
# DESC:		Overview page for all options according the contributions
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirechair();
ct_pagepath(array('index'));

$session->set_besturl(ct('S_ADMIN_PAPERS_QUICK'));

echo "<h1>".ct('S_ADMIN_PAPERS_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_PAPERS_INTRO')."</p>\n";

echo "<table cellpadding=5 align=center width=\"100%\">\n";
echo "<tr><td colspan=2 class=\"mediumbg\">\n";
echo "<span class=\"bold10\">".ct('S_ADMIN_PAPERS_CMDSTITLE').":</span>\n";
echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
echo "<dl>\n";
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminPapersBrowse')."\">".ct('S_ADMIN_PAPERS_BROWSE_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_PAPERS_BROWSE_HINT')."</dd>\n";
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminPapersSearch')."\">".ct('S_ADMIN_PAPERS_SEARCH_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_PAPERS_SEARCH_HINT')."</dd>\n";

echo "<dt>&nbsp;<dt>"; // ------------------------------------------------------

echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminPapersResults')."\">".ct('S_ADMIN_PAPERS_RESULTS_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_PAPERS_RESULTS_HINT')."</dd>\n";

echo "<dt>&nbsp;<dt>"; // ------------------------------------------------------

echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminUsersPC')."\">".ct('S_ADMIN_USERS_PC_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_USERS_PC_HINT')."</dd>\n";

$paper = new CTPaper();

if ($user->get_id()==1 || $paper->count_papers()<10) {
	echo "<dt>&nbsp;<dt>"; // ------------------------------------------------------
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminPapersPurge')."\">".ct('S_ADMIN_PAPERS_PURGE_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_PAPERS_PURGE_HINT')."</dd>\n";
}
echo "</dl>\n";
echo "</td></tr></table>\n";
