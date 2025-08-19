<?php
#
# PAGE:		adminUsers
# DESC:		Overview page of user administration options.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_pagepath(array('index'));
ct_requireadmin();

if (ctconf_get('participation/enabled')) {
	$session->set_besturl(ct('S_ADMIN_USERS_QUICK'));
	echo "<h1>".ct('S_ADMIN_USERS_TITLE')."</h1>\n";
	echo "<p class=\"standard\">".ct('S_ADMIN_USERS_INTRO')."</p>\n";
} else {
	$session->set_besturl(ct('S_ADMIN_USERSONLY_TITLE'));
	echo "<h1>".ct('S_ADMIN_USERSONLY_TITLE')."</h1>\n";
	echo "<p class=\"standard\">".ct('S_ADMIN_USERSONLY_INTRO')."</p>\n";
}

echo "<table cellpadding=5 align=center width=\"100%\">\n";
echo "<tr><td colspan=2 class=\"mediumbg\">\n";
echo "<span class=\"bold10\">".ct('S_ADMIN_USERS_CMDSTITLE')."</span>\n";
echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
echo "<dl>\n";
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminUsersBrowse')."\">".ct('S_ADMIN_USERS_BROWSE_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_USERS_BROWSE_HINT')."</dd>\n";

echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminUsersSearch')."\">".ct('S_ADMIN_USERS_SEARCH_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_USERS_SEARCH_HINT')."</dd>\n";

echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminUsersNew')."\">".ct('S_ADMIN_USERS_NEW_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_USERS_NEW_HINT')."</dd>\n";

if (ctconf_get('participation/enabled')) {
	echo "<dt>&nbsp;<dt>"; // ------------------------------------------------------

	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminParticipantsBrowse')."\">".ct('S_ADMIN_PARTICIPANTS_BROWSE_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_PARTICIPANTS_BROWSE_HINT')."</dd>\n";

	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminParticipantsTable')."\">".ct('S_ADMIN_PARTICIPANTS_TABLE_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_PARTICIPANTS_TABLE_HINT')."</dd>\n";
}

if (ctconf_get('submission/enabled')) {
	echo "<dt>&nbsp;<dt>"; // ------------------------------------------------------

	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminUsersPC')."\">".ct('S_ADMIN_USERS_PC_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_USERS_PC_HINT')."</dd>\n";
}

echo "<dt>&nbsp;<dt>"; // ------------------------------------------------------

echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminUsersCheckStatus')."\">".ct('S_ADMIN_USERS_CHECKSTATUS_CMD')."</a></dt>\n";
echo "<dd class=\"fontnormal font10\">".ct('S_ADMIN_USERS_CHECKSTATUS_HINT')."</dd>\n";

if ($user->get_id()==1 || $user->count_persons(true)<25) {
 	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminUsersPurge')."\">".ct('S_ADMIN_USERS_PURGE_CMD')."</a></dt>\n";
	echo "<dd class=\"fontnormal font10\">".ct('S_ADMIN_USERS_PURGE_HINT')."</dd>\n";
}

echo "</dl>\n";
echo "</td></tr></table>\n";


?>








