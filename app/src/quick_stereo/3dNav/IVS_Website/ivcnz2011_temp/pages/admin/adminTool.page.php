<?php
#
# PAGE:		adminTool
# DESC:		Overview page to select what configuration settings shall be displayed/ modified
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index'));

$session->set_besturl(ct('S_INDEX_CMD_TOOL'));

echo "<h1>".ct('S_ADMIN_TOOL_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_INTRO')."</p>\n";

echo "<table cellpadding=5 align=center width=\"100%\">\n";
echo "<tr><td colspan=2 class=\"mediumbg\">\n";
echo "<span class=\"bold10\">".ct('S_ADMIN_TOOL_CMDS_MAIN_TITLE')."</span>\n";
echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
echo "<dl>\n";
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolMasterData')."\">".ct('S_ADMIN_TOOL_MASTERDATA_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_MASTERDATA_HINT')."</dd>\n";
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolPhases')."\">".ct('S_ADMIN_TOOL_PHASES_CMD')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_PHASES_HINT')."</dd>\n";
echo "</dl>\n";
echo "</td></tr>\n";
if (ctconf_get('submission/enabled',true)==true) {
	echo "<tr><td colspan=2 class=\"mediumbg\">\n";
	echo "<span class=\"bold10\">".ct('S_ADMIN_TOOL_CMDS_REVIEWING_TITLE')."</span>\n";
	echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
	echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
	echo "<dl>\n";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolContributionTypes')."\">".ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_HINT')."</dd>\n";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolTopics')."\">".ct('S_ADMIN_TOOL_TOPICS_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_TOPICS_HINT')."</dd>\n";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolTracks')."\">".ct('S_ADMIN_TOOL_SESSIONS_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_SESSIONS_HINT')."</dd>\n";
	echo "</dl>\n";
	echo "</td></tr>\n";
}
if (ctconf_get('participation/enabled',true)==true) {
	echo "<tr><td colspan=2 class=\"mediumbg\">\n";
	echo "<a name='participate' id='participate'></a>\n";
	echo "<span class=\"bold10\">".ct('S_ADMIN_TOOL_CMDS_PARTICIPATE_TITLE')."</span>\n";
	echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
	echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
	echo "<dl>\n";
	echo "<dt><span class=\"bold10\">1. ".ct('S_ADMIN_TOOL_GROUP1')."</span></dt>";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolGroups')."\">".ct('S_ADMIN_TOOL_GROUPS_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_GROUPS_HINT')."</dd>\n";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolDiscounts')."\">".ct('S_ADMIN_TOOL_DISCOUNTS_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_DISCOUNTS_HINT')."</dd>\n";
	if (isset($ctconf['participation/vat']) && $ctconf['participation/vat']) {
		echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolVats')."\">".ct('S_ADMIN_TOOL_VATS_CMD')."</a></dt>\n";
		echo "<dd class=\"fontnormal font10\">".ct('S_ADMIN_TOOL_VATS_HINT')."</dd>\n";
	}
	echo "<dt><span class=\"bold10\">2. ".ct('S_ADMIN_TOOL_GROUP2')."</span></dt>";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolPricecategories')."\">".ct('S_ADMIN_TOOL_PRICECATEGORIES_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_PRICECATEGORIES_HINT')."</dd>\n";
	echo "<dt><span class=\"bold10\">3. ".ct('S_ADMIN_TOOL_GROUP3')."</span></dt>";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolPrices')."\">".ct('S_ADMIN_TOOL_PRICES_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_PRICES_HINT')."</dd>\n";
	echo "<dt><span class=\"bold10\">4. ".ct('S_ADMIN_TOOL_GROUP4')."</span></dt>";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolEventtypes')."\">".ct('S_ADMIN_TOOL_EVENTTYPES_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_EVENTTYPES_HINT')."</dd>\n";
	echo "<dt><span class=\"bold10\">5. ".ct('S_ADMIN_TOOL_GROUP5')."</span></dt>";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolEvents')."\">".ct('S_ADMIN_TOOL_EVENTS_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_EVENTS_HINT')."</dd>\n";
	echo "<dt><span class=\"bold10\">6. ".ct('S_ADMIN_TOOL_GROUP6')."</span></dt>";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolEventtypeorder')."\">".ct('S_ADMIN_TOOL_EVENTTYPEORDER_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_EVENTTYPEORDER_HINT')."</dd>\n";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminToolEventorder')."\">".ct('S_ADMIN_TOOL_EVENTORDER_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_TOOL_EVENTORDER_HINT')."</dd>\n";
	echo "</dl>\n";
	echo "</td></tr>\n";
}
echo "</table>\n";
?>
