<?php
#
# PAGE		adminReviewAssignPC
# DESC		Assign papers to PC members (one PC members and all papers are shown)
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirechair();
ct_pagepath(array('index', 'adminUsers', 'adminUsersPC'));

# this page is useless without an ID for a paper, so redirect if missing
if (!isset($http['form_id'])) {
	ct_redirect($session->get_besturl());
}

$person = new CTPerson();
$person->load_by_id($http['form_id']);
$person_topics=$person->get_topics();

if (in_http('form_cmd','cmd_unassign') && in_http('form_paper')) {
	foreach ($http['form_paper'] as $paper) {
		$paper = (int)$paper;
		$r = $db->delete("reviews","personID='".$http['form_id']."' and paperID='".$paper."'");
	}
	#ct_redirect($session->get_besturl());
}

if (in_http('form_cmd','cmdassign') && in_http('form_paper')) {
	foreach ($http['form_paper'] as $paper) {
		$paper = (int)$paper;
		$r = $db->query("insert into reviews set personID='".$http['form_id']."', paperID='".$paper."'");
	}
	#ct_redirect($session->get_besturl());
}

#echo "<p class=\"boldlabel12\">".ct('S_REVIEW_ASSIGN_PC')."</p>";
echo "<p class=\"standard\">".ct('S_REVIEW_ASSIGN_INTRO_PC')."</p>\n";

#echo "<p class=\"boldlabel12\">".ct('S_REVIEWER').":</p>";
echo "<div align=\"center\">\n";

echo "<table class=\"mediumbg\" width=\"90%\" align=\"center\">\n";
echo "<tr><td>\n";
echo "<a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$person->get('ID')))."\" class=\"bold10\">".ct_form_encode($person->get_fullname())."</a><br>\n";
echo "<span class=\"label10\">".ct('S_USER_ORGANISATION').":</span> ";
echo "<span class=\"bold10\">".$person->get_special('organisation')."</span><BR>\n";
echo "<span class=\"label10\">".ct('S_USER_TOPICS').":</span> ";
echo "<span class=\"bold10\">";
$j=0;
while (list(,$v) = each($person_topics)) {
	if ($j>0) echo ",";
	echo " ".$v;
	$j++;
}
if ($j == 0) echo "--";
echo "</span><BR>\n";
echo "</td></tr>\n";
echo "</table>\n";
echo "</div>\n";

echo "<p class=\"boldlabel12\">".ct('S_REVIEW_ASSIGNED_PAPERS').":</p>";

$paper = new CTPaper();

// Show assigned reviews
$qr = $db->query("select * from reviews r where personID=".$person->get('ID'));
echo "<div align=\"center\">\n";
echo "<table width=\"90%\">\n";
if ($qr && ($db->num_rows($qr) > 0)) {
	echo "<form method=\"post\" action=\"".ct_pageurl('adminReviewAssignPC')."\">\n";
	echo "<input type=hidden name=form_cmd value=\"cmd_unassign\">\n";
	echo "<input type=hidden name=form_id value=\"".$http['form_id']."\">";
	for ($i = 0; $i < $db->num_rows($qr); $i++) {
		$review = $db->fetch($qr);
		$paper->load_by_id($review['paperID']);
		$author=$paper->get_author();
		if ($i % 2) {
			echo "<tr class=\"oddrow\">\n";
		} else {
			echo "<tr class=\"evenrow\">\n";
		}
		if ($review['creationdate'] > 0)
			echo "<td class=\"positivebg\" align=center valign=middle width=\"6%\">";
		else
			echo "<td class=\"negativebg\" align=center valign=middle width=\"6%\">";
		echo "<span class=\"lightbold20\">".$paper->pdata['ID']."</span></td>\n";
		echo "<td width=\"84%\"><span class=\"bold10\"><a href=\"".ct_pageurl('adminPapersDetails')."&form_id=".$paper->get('ID')."\">".$paper->get_special('title')."</a></span> &nbsp;&nbsp;&nbsp;\n";
		echo "<span class=\"normal8\">(".$paper->get_contributiontype().")</span>&nbsp;&nbsp;";
		echo "<a href=\"".ct_pageurl('adminReviewAssign')."&form_id=".$paper->get('ID')."\"><span class=\"light8\"> =&gt; ".ct('S_ADMIN_PAPERS_ACTION_ASSIGN')."</span></a>";
		if ($paper->get('withdrawn'))
			echo "&nbsp;&nbsp;&nbsp;<span class=\"negativebold10\">".ct('S_PAPER_WITHDRAWN')."</span>";
		echo "<BR>";

		# Show author. mark if same as reviewer
		echo "<span class=\"label10\">".ct('S_INDEX_PAPER_SUBMITTEDBY').": </span><a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$author->get_id()))."\">";
		echo "<span ";
		if ($author->get_id()==$person->get_id())
			echo "class=\"negativebold10\">";
		else
			echo "class=\"normal10\">";
		echo ct_form_encode($author->get_reversename())."</span></a>&nbsp;";
		# show organization. Mark if similar to reviwers.
		echo "<span ";
		$s = similar_text($author->get('organisation'),$person->get('organisation'));
		$l = max(strlen($author->get('organisation')),strlen($person->get('organisation')));
		if ($l>0) $similarity_pst = $s/$l * 100;
		else $similarity_pst=0;
		if (number_format($similarity_pst, 0) > 66)
			echo "class=\"negativebold10\">";
		elseif (number_format($similarity_pst, 0) > 33)
			echo "class=\"negative10\">";
		else
			echo "class=\"normal10\">";
		echo " (".$author->get_special('organisation').")</span><BR>";

		echo "<span class=\"label10\">".ct('S_PAPER_AUTHOR').":</span> <span class=\"normal10\">".$paper->get_special('author')."</span>\n";
		if ($review['creationdate'] > 0)
			echo "<BR><span class=\"normal8\">".ct('S_REVIEW_DATE_ENTERED').": ".$review['creationdate']."</span>\n";
		else if ($review['downloaddate'] > 0)
			echo "<BR><span class=\"normal8\">".ct('S_REVIEW_DATE_DOWNLOADED').": ".$review['downloaddate']."</span>\n";
		echo "</td>\n";
		echo "<td align=\"right\" class=\"mediumbg\" width=\"10%\"nowrap><span class=\"label10\">&nbsp;";
		echo " ".ct('S_REVIEW_ASSIGN_UNASSIGN_CMD')." <input type=checkbox name=\"form_paper[]\" value=\"".$paper->get('ID')."\">&nbsp;</span></td></tr>\n";
//		echo "<td align=\"right\" class=\"mediumbg\"><span class=\"bold10\">&nbsp;";
//		echo "<a href=\"".ct_pageurl('adminReviewAssignPC', array('form_cmd'=>'cmd_unassign','form_id'=>$http['form_id'],'form_paper'=>$paper->get('ID')))."\">".ct('S_REVIEW_ASSIGN_UNASSIGN_CMD')."</a>&nbsp;</span></td></tr>\n";	}
	}
	echo "<tr><td colspan=3 align=right class=\"mediumbg\"><input type=submit class='button' value=\"".ct('S_BUTTON_SAVE')."\"></td></tr>\n";
} else {
	echo "<tr class=\"oddrow_del\"><span class=\"bold10\">&nbsp;".ct('S_REVIEW_ASSIGN_NONEYET')."</span></td></tr>\n";
}
echo "</table>\n</form>\n</div>\n";

echo "<p class=\"boldlabel12\">".ct('S_REVIEW_ASSIGN_PAPERS').":</p>";

$qr = $db->query("select * from papers where withdrawn=0 order by id");
if ($qr && ($db->num_rows($qr)>0)) {
	echo "<div align=\"center\">\n";
	echo "<form method=\"post\" action=\"".ct_pageurl('adminReviewAssignPC')."\">\n";
	echo "<input type=hidden name=form_cmd value=\"cmdassign\">\n";
	echo "<input type=hidden name=form_id value=\"".$http['form_id']."\">";
	echo "<table width=\"90%\">\n";
	for ($i = 0; $i < $db->num_rows($qr); $i++) {
		$pdata = $db->fetch($qr);
		$paper->pdata = $pdata;
		$author=$paper->get_author();
		if ($i % 2) {
			echo "<tr class=\"oddrow\">\n";
		} else {
			echo "<tr class=\"evenrow\">\n";
		}
		echo "<td class=\"darkbg\" align=center valign=middle width=\"6%\"><span class=\"lightbold20\">";
		echo $paper->pdata['ID']."</span></td>\n";
		echo "<td width=\"84%\"><span class=\"bold10\"><a href=\"".ct_pageurl('adminPapersDetails')."&form_id=".$paper->get('ID')."\">".$paper->get_special('title')."</a></span> &nbsp;&nbsp;&nbsp;";
		echo "<span class=\"normal8\">(".$paper->get_contributiontype().")</span>&nbsp;&nbsp;";
		echo "<a href=\"".ct_pageurl('adminReviewAssign')."&form_id=".$paper->get('ID')."\"><span class=\"light8\"> =&gt; ".ct('S_ADMIN_PAPERS_ACTION_ASSIGN')."</span></a><br>";
		# Show author. mark if same as reviewer
		echo "<span class=\"label10\">".ct('S_INDEX_PAPER_SUBMITTEDBY').": </span><a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$author->get_id()))."\">";
		echo "<span ";
		if ($author->get_id()==$person->get_id())
			echo "class=\"negativebold10\">";
		else
			echo "class=\"normal10\">";
		echo ct_form_encode($author->get_reversename())."</span></a>&nbsp;";
		# show organization. Mark if similar to reviwers.
		echo "<span ";
		$s = similar_text($author->get('organisation'),$person->get('organisation'));
		$l = max(strlen($author->get('organisation')),strlen($person->get('organisation')));
		if ($l>0) $similarity_pst = $s/$l * 100;
		else $similarity_pst=0;
		if (number_format($similarity_pst, 0) > 66)
			echo "class=\"negativebold10\">";
		elseif (number_format($similarity_pst, 0) > 33)
			echo "class=\"negative10\">";
		else
			echo "class=\"normal10\">";
		echo " (".$author->get_special('organisation').")</span><BR>";

		echo "<span class=\"label10\">".ct('S_PAPER_AUTHOR').":</span> <span class=\"normal10\">".$paper->get_special('author')."</span><BR>\n";
		echo "<span class=\"label10\">".ct('S_PAPER_TOPICS').":</span> <span class=\"normal10\">";
		$j=0;
		$topics = $paper->get_topics();
		while (list(,$v) = each($topics)) {
			if ($j>0) echo ",";
			if (in_array($v, $person_topics)) echo "<span class=\"positivebold10\"> ".$v."</span>";
			else echo " ".$v;
			$j++;
		}
		if ($j == 0) echo "--";
		echo "</span><BR>\n";
		echo "<span class=\"normal8\">".ct('S_REVIEW_COUNT').": ".$user->get_review_count("",$paper->pdata['ID'],0)."</span>";
		echo "</td>\n";
		if (!$paper->is_reviewed_by($http['form_id'])) {
			echo "<td align=\"right\" class=\"mediumbg\" nowrap width=\"10%\"><span class=\"label10\">&nbsp;";
	//		echo "<a href=\"".ct_pageurl('adminReviewAssignPC', array('form_cmd'=>'cmdassign','form_id'=>$http['form_id'],'form_paper'=>$paper->get('ID')))."\">".ct('S_REVIEW_ASSIGN_ASSIGN_CMD')."</a>&nbsp;</span></tr>\n";
			echo " ".ct('S_REVIEW_ASSIGN_ASSIGN_CMD')." <input type=checkbox name=\"form_paper[]\" value=\"".$paper->get('ID')."\">&nbsp;</span></td></tr>\n";
		} else {
			echo "<td class=\"mediumbg\">&nbsp;</td></tr>\n";
		}
	}
	echo "<tr><td colspan=3 align=right class=\"mediumbg\"><input type=submit class='button' value=\"".ct('S_BUTTON_SAVE')."\"></td></tr>\n";
	echo "</table>\n</form>\n</div>\n";
}

?>
