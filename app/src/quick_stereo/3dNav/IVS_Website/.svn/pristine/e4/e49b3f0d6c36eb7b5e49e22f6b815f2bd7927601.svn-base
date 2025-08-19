<?php
#
# functions for use on administrative pages
#
if (!defined('CONFTOOL')) die('Hacking attempt!');


function ctadm_listusers($res, $page) {
	global $db, $http;

	$person = new CTPerson;
	echo "<table width=\"100%\" cellspacing=1 cellpadding=2 border=0>\n";
	echo "<tr class=\"listheader\">\n";
	if ($page == 'adminUsersBrowse') {
		# show headers with links for different sorting...
		echo "<td width=\"5%\" align=\"right\"><a href=\"".ct_pageurl($page,array('listorder' => 'id', 'form_deleted'=>$http['form_deleted'], 'form_status'=>$http['form_status'],  'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_ID')."</a></td>\n";
		echo "<td width=\"40%\"><a href=\"".ct_pageurl($page,array('listorder' => 'name', 'form_deleted'=>$http['form_deleted'], 'form_status'=>$http['form_status'],  'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_FULLNAME')."</a></td>\n";
		echo "<td width=\"30%\"><a href=\"".ct_pageurl($page,array('listorder' => 'org', 'form_deleted'=>$http['form_deleted'], 'form_status'=>$http['form_status'],  'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_ORGANISATION')."</a> &middot; <a href=\"".ct_pageurl($page,array('listorder' => 'country', 'form_deleted'=>$http['form_deleted'], 'form_status'=>$http['form_status'],  'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_COUNTRY')."</a></td>\n";
	} else {
		# no links
		echo "<td width=\"5%\" align=\"right\"><span class=\"listheader_label\">".ct('S_USER_ID')."</span></td>\n";
		echo "<td width=\"40%\"><span class=\"listheader_label\">".ct('S_USER_FULLNAME')."</span></td>\n";
		echo "<td width=\"30%\"><span class=\"listheader_label\">".ct('S_USER_ORGANISATION')." &middot;</span>";
		echo "<span class=\"bold8\">".ct('S_USER_COUNTRY')."</span></td>\n";
	}
	echo "<td width=\"10%\" colspan=2><span class=\"listheader_label\">".ct('S_USER_STATUS')."</span></td>\n";
	echo "<td width=\"15%\" align=\"right\"><span class=\"listheader_label\">".ct('S_ADMIN_USERS_ACTIONS')."</span></td>\n";
	echo "</tr>\n";
	for ($i=0; $i < $db->num_rows($res); $i++) {
		$person->pdata = $db->fetch($res);
		$person->show_row(($i%2?"oddrow":"evenrow"), $page);
	}
	echo "<tr class=\"darkbg\">\n";
	echo "<td colspan=\"6\"><span class=\"form_label\">".ct('S_ADMIN_USERS_BROWSE_SUM1')." $i ".ct('S_ADMIN_USERS_BROWSE_SUM2')."</span></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


// List all Papers for Admin etc.
function ctadm_listpapers($res,$page) {
	global $db, $http;

	$submissions=0;
	$withdrawn = 0;
	$page_submissions = 0;
	$page_withdrawn = 0;
	if ($res >= 0) {
		$paper = new CTPaper;
		echo "<table width=\"100%\" cellspacing=1 cellpadding=2 border=0>\n";
		echo "<tr class=\"listheader\">\n";
		echo "<td width=\"4%\" align=\"center\"><a href=\"".ct_pageurl($page,array('listorder' => 'id', 'form_withdrawn'=>$http['form_withdrawn']))."\" class=\"listheader_label\">".ct('S_PAPER_ID')."</a></td>\n";
		echo "<td width=\"33%\"><a href=\"".ct_pageurl($page,array('listorder' => 'title', 'form_withdrawn'=>$http['form_withdrawn']))."\" class=\"listheader_label\">".ct('S_PAPER_TITLE')."</a></td>\n";
		echo "<td width=\"33%\"><a href=\"".ct_pageurl($page,array('listorder' => 'author', 'form_withdrawn'=>$http['form_withdrawn']))."\" class=\"listheader_label\">".ct('S_PAPER_AUTHOR')."</a></td>\n";
		echo "<td width=\"20%\"><span class=\"listheader_label\">".ct('S_PAPER_FILENAME')."</span>&nbsp;&middot;&nbsp;<a href=\"".ct_pageurl($page,array('listorder' => 'lastupload', 'form_withdrawn'=>$http['form_withdrawn']))."\" class=\"bold8\">".ct('S_PAPER_UPLOAD')."</a></td>\n";
		echo "<td width=\"15%\" align=\"right\"><span class=\"listheader_label\">".ct('S_ADMIN_PAPERS_ACTIONS')."</span></td>\n";
		echo "</tr>\n";
		for ($i=0; $i < $db->num_rows($res); $i++) {
			$row= $db->fetch($res);
			$paper->load_by_id($row['paperID']);
			if ($paper->get('withdrawn')==1) $withdrawn++; else $submissions++;
			$paper->show_row(($i%2?"oddrow":"evenrow"));
			if ($paper->get('withdrawn')==1) $page_withdrawn++; else $page_submissions++;
		}

		echo "<tr class=\"darkbg\">\n";
		echo "<td colspan=\"5\"><span class=\"form_label\">".ct('S_ADMIN_PAPERS_BROWSE_SUM1')." ".($submissions)." ".ct('S_ADMIN_PAPERS_BROWSE_SUM2')." ".($withdrawn)." ".ct('S_ADMIN_PAPERS_BROWSE_SUM3')."</span></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
}

function ctadm_listfrontdesk($res,$page,$filter=array()) {
	global $db, $http;
	$participation= new CTParticipation;
	$feetotal = 0;
	$paidtotal = 0;
	$eventsum = 0;

	echo "<table width=\"100%\" cellspacing=1 cellpadding=2 border=0>\n";
	echo "<tr class=\"listheader\">\n";
	echo "<td width=\"3%\" align=\"right\"><span class=\"listheader_label\">".ct('S_USER_COUNT')."</span></td>\n";
	echo "<td width=\"3%\" align=\"right\"><span class=\"listheader_label\">".ct('S_USER_ID')."</span></td>\n";
	echo "<td width=\"32%\">".ct('S_USER_FULLNAME')."<br>\n";
	echo "<span class=\"normal8\">".ct('S_USER_EMAIL')."</span></td>\n";
	echo "<td width=\"27%\">".ct('S_USER_ORGANISATION')."<br>";
	echo "<span class=\"normal8\">".ct('S_USER_COUNTRY')."</span></td>\n";
	echo "<td align=middle width=\"5%\">".ct('S_PARTICIPATE_PAYMENT_METHOD')."<BR>\n";
	echo "<span class=\"normal8\">".ct('S_PARTICIPATE_REGDATE_SHORT')."</span></td>\n";
	echo "<td width=\"5%\" align=\"right\">".ct('S_PARTICIPATE_TOTALFEE')."<BR>\n";
	echo "<span class=\"normal8\">".ct('S_PARTICIPATE_INVOICEDATE_SHORT')."</span></td>\n";
	echo "<td width=\"5%\" align=\"right\">".ct('S_PARTICIPATE_PAYAMOUNT')."<BR>\n";
	echo "<span class=\"normal8\">".ct('S_PARTICIPATE_PAYDATE_SHORT')."</span></td>\n";
	echo "<td width=\"5%\" align=\"right\"><span class=\"listheader_label\">".ct('S_PARTICIPATE_PRINT')."</span></td>\n";
    echo "<td width=\"5%\" align=\"right\"><span class=\"listheader_label\">".ct('S_ADMIN_USERS_ACTIONS')."</span></td>\n";
	echo "</tr>\n";
	for ($i=0; $i < $db->num_rows($res); $i++) {
		$row= $db->fetch($res);
		$participation->load_by_id($row['personID']);
		$participation->show_row(($i%2?"oddrow":"evenrow"),$personID,$i,$http['form_event'],$page);
	}
	echo "<tr class=\"darkbg\">\n";
	echo "<td colspan=\"9\"><span class=\"form_label\">".ct('S_ADMIN_PARTICIPANTS_BROWSE_SUM1')." $i ".ct('S_ADMIN_PARTICIPANTS_BROWSE_SUM2')."</span></td>\n";
	echo "</tr>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
}

?>




