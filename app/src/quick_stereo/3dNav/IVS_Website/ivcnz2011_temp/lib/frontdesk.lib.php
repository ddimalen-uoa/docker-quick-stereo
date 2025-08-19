<?php
if (!defined('CONFTOOL')) die('Hacking attempt!');

function ct_frontdesk_searchmask() {
	GLOBAL $http,$ctconf;

   echo "<table border=0 width=100% cellpadding=2 cellspacing=0><tr>\n";
   echo "<td valign=top align=center rowspan=2>\n";

	$form = new CTForm('frontdesk', 'get');
	$form->width = '100%';
	$form->align = 'center';
	$form->add_separator(ct('S_FRONTDESK_QUERY'));
	$form->add_hidden(hash2aa(ct_http_array(array('form_nonregistered'=>'','form_query'=>''))));
	$form->add_text(ct('S_FRONTDESK_SEARCH_FORM'), 'form_query', $http['form_query'], 30, 255,false,'<br>'.ct('S_FRONTDESK_SEARCH_FORM_HINT'));
	$form->add_check(ct('S_FRONTDESK_SEARCH_NONREGISTERED'), array( array( 'form_nonregistered' ,  '1' , ct('S_FRONTDESK_SEARCH_NONREGISTERED_HINT'), (isset($http['form_nonregistered']) && $http['form_nonregistered']==1)  ) ) );
	$form->add_submit('cmd_search', ct('S_FRONTDESK_FORM_SUBMIT'));
	$form->show();

   echo "</td>\n";
   echo "<td valign=top align=center>";

    echo "<table align=\"center\" width=\"100%\" class=\"form_table\" cellspacing=0 cellpadding=2 border=0>\n";
	echo "<tr><td colspan=2 valign=top align=left class=\"form_td_separator\">\n";
	echo "<span class=\"form_separator_label\">".ct('S_FRONTDESK_PARTICIPANTS')."</span>\n";
	echo "</td></tr>\n";
	echo "<tr><td width=\"30%\" valign=top align=right class=\"form_td_label topline_white\">\n";
	echo "<span class=\"form_label\">".ct('S_FRONTDESK_LISTLETTER')."</span>&nbsp;\n";
	echo "</td><td width=\"70%\" align=left valign=top class=\"topline_white form_td_field\">\n";

	for ($i = 65; $i < 91; $i++) {
		echo "<a href=\"".ct_pageurl('frontdesk', array('listLetter'=>chr($i)))."\">".chr($i)."</a>&nbsp;&nbsp;";
		if ($i == 77) { echo "<br>\n"; }
	}
	echo "</td></tr>\n";

	echo "<tr><td width=\"30%\" valign=top align=right class=\"form_td_label topline_white\">\n";
	echo "<span class=\"form_label\">".ct('S_FRONTDESK_OPTIONS')."</span>&nbsp;\n";
	echo "</td><td width=\"70%\" align=left valign=top class=\"topline_white form_td_field\">\n";

	//echo "<a href=\"frameset.phtml?back=".urlencode($GLOBALS[REQUEST_URI'])."&url=".urlencode("/login/userdata/newUser.phtml?frontdesk=1")."\">Neuen Teilnehmer aufnehmen</a><br>\n";

	echo "<a href=\"".ct_pageurl('frontdesk', array('listLetter'=> 'all' ))."\">".ct('S_FRONTDESK_LIST_ALL')."</a><br>\n";
	echo "<a href=\"".ct_pageurl('frontdesk', array('isarrived' => 'yes' ))."\">".ct('S_FRONTDESK_LIST_ARRIVED')."</a><br>\n";
	echo "<a href=\"".ct_pageurl('frontdesk', array('isarrived' => 'no'  ))."\">".ct('S_FRONTDESK_LIST_MISSING')."</a><br>\n";
	echo "</td></tr>\n";
	echo "</table>\n";

   echo "</td></tr>\n";
   echo "<tr><td valign=top align=center>\n";

	echo "<table align=\"center\" width=\"100%\" class=\"form_table\" cellspacing=0 cellpadding=2 border=0>\n";
	echo "<tr><td colspan=2 valign=top align=left class=\"form_td_separator\">\n";
	echo "<span class=\"form_separator_label\">".ucfirst(ct('S_ADMIN_USERS_NEW_QUICK'))."</span>\n";
	echo "</td></tr>\n";
	echo "<tr><td width=\"30%\" valign=top align=right class=\"form_td_label topline_white\">\n";
	echo "&nbsp;";
	echo "</td><td width=\"70%\" align=left valign=top class=\"topline_white form_td_field\">\n";
	echo "<a class=\"cmda\" href=\"".ct_pageurl('adminUsersNew')."\">".ct('S_ADMIN_USERS_NEW_CMD')."</a><br>\n";
	echo "<span class=\"fontnormal font10\">".ct('S_ADMIN_USERS_NEW_HINT')."</span>\n";
	echo "</td></tr>\n";
	echo "</table>\n";

   echo "</td></tr>\n";
   echo "</table>\n";

}

?>