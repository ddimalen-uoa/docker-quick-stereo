<?php
#
# PAGE		adminReviewAssign
# DESC		Assign PC members to a paper (one paper and all PC members are shown)
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirechair();
ct_pagepath(array('index', 'adminPapers', 'adminPapersBrowse'));

# this page is useless without an ID for a paper, so redirect if missing
if (!isset($http['form_id'])) {
	ct_redirect($session->get_besturl());
}

$paper = new CTPaper();
$paper->load_by_id($http['form_id']);
$paper_topics = $paper->get_topics();
$author = new CTPerson();
$author = $paper->get_author();

# unassign reviewers
if (in_http('form_cmd','cmd_unassign') && in_http('form_reviewer')) {
	foreach ($http['form_reviewer'] as $reviewer) {
		$reviewer = (int)$reviewer;
		$r = $db->delete("reviews","paperID='".$http['form_id']."' and personID='".$reviewer."'");
	}
	#ct_redirect($session->get_besturl());
}

# assign reviewers
if (in_http('form_cmd','cmdassign') && in_http('form_reviewer')) {
	foreach ($http['form_reviewer'] as $reviewer) {
		$reviewer = (int)$reviewer;
		$r = $db->query("insert into reviews set paperID='".$http['form_id']."', personID='".$reviewer."'");
	}
	#ct_redirect($session->get_besturl());
}

# show page
echo "<H1>".ct('S_REVIEW_ASSIGN')."</H1>\n";
echo "<p class=\"standard\">".ct('S_REVIEW_ASSIGN_INTRO')."</p>\n";

echo "<div align=\"center\">\n";
echo "<table width=\"90%\" cellspacing=1 border=0 cellpadding=2 class=\"darkbg\">\n";
echo "<tr><td class=\"tbldialog\" align=left valign=top width=\"70%\"><span class=\"label10\">";
echo ct('S_PAPER_TITLE').": </span>";
echo "<a href=\"".ct_pageurl('adminPapersDetails', array('form_id'=>$paper->get_id()))."\" class=\"normal10\"><span class=\"bold10\">".$paper->get_special('title')."</span></a>&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<span class=\"normal8\">(".$paper->get_contributiontype().")</span>";
if ($paper->get('externalremark') != "")
	echo ' <img src="'.ct_getbaseurl().'images/remark.png" title="'.$paper->get_special('externalremark').'">';
echo "<br>";
echo "<span class=\"label10\">".ct('S_INDEX_PAPER_SUBMITTEDBY').": <a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$paper->get('personID')))."\" class=\"normal10\">".ct_form_encode($author->get_reversename())."</a></span><BR>";
echo "<span class=\"label10\">".ct('S_USER_ORGANISATION').": <b>";
echo $author->get_special('organisation')."</b></span><BR>";
echo "<span class=\"label10\">".ct('S_PAPER_AUTHOR').": ".$paper->get_special('author')."</span><br>";
echo "<span class=\"label10\">".ct('S_PAPER_TOPICS').":</span> ";
echo "<span class=\"bold10\">";
$j=0;
while (list(,$v) = each($paper_topics)) {
	if ($j>0) echo ",";
	echo " ".$v;
	$j++;
}
if ($j == 0) echo "--";

echo "</td>\n";
echo "<td class=\"darkbg\" align=center valign=middle width=\"10%\"><span class=\"lightbold20\">".$paper->get_id()."</span></td>";
echo "</tr>\n</table>\n";
echo "</div>\n";

echo "<p class=\"boldlabel12\">".ct('S_REVIEW_ASSIGN_ASSIGNEDMEMBERS').":</p>";

$person = new CTPerson();

// Show assigned PC-Members
$qr = $db->query("select * from reviews r where paperID=".$paper->get('ID'));
echo "<div align=\"center\">\n";
echo "<form method=\"post\" action=\"".ct_pageurl('adminReviewAssign')."\">\n";
echo "<input type=hidden name=form_cmd value=\"cmd_unassign\">\n";
echo "<input type=hidden name=form_id value=\"".$http['form_id']."\">";
echo "<table width=\"90%\">\n";
if ($qr && ($db->num_rows($qr) > 0)) {
	for ($i = 0; $i < $db->num_rows($qr); $i++) {
		$review = $db->fetch($qr);
		$person->load_by_id($review['personID']);
		if ($i % 2) {
			echo "<tr class=\"oddrow\"><td width=\"100%\">\n";
		} else {
			echo "<tr class=\"evenrow\"><td width=\"100%\">\n";
		}

		# show reviewer name
		echo "<span ";
		if ($author->get_id()==$person->get_id())
			echo "class=\"negativebold10\">";
		else
			echo "class=\"bold10\">";
		echo ct_form_encode($person->get_reversename())."</span>&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<a href=\"".ct_pageurl('adminReviewAssignPC')."&form_id=".$person->get('ID')."\"><span class=\"light8\"> =&gt; ".ct('S_ADMIN_USERS_REVIEWS')."</span></a><br>";

		# show organization. Mark if similar to paper's
		echo "<span class=\"label10\">".ct('S_USER_ORGANISATION').":</span> ";
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
		echo $person->get_special('organisation')."</span><BR>";

		echo "<span class=\"label10\">".ct('S_USER_TOPICS').":</span> <span class=\"normal10\">";
		$j=0;
		$topics = $person->get_topics();
		while (list(,$v) = each($topics)) {
			if ($j>0) echo ",";
			if (in_array($v, $paper_topics)) echo "<span class=\"positivebold10\"> ".$v."</span>";
			else echo " ".$v;
			$j++;
		}
		if ($j == 0) echo "--";
		echo "</span><BR>\n";
		# show number of reviews
		echo "<span class=\"normal8\">".ct('S_REVIEW_PAPERS').": ".$user->get_review_count($person->pdata['ID'],"",0)."</span>";
		# if review already entered, show date
		if ($review['creationdate'] > 0)
			echo "<BR><span class=\"normal8\">".ct('S_REVIEW_DATE_ENTERED').": ".$review['creationdate']."</span>\n";
		echo "</td>\n";
		echo "<td align=\"right\" class=\"mediumbg\" nowrap><span class=\"label10\">&nbsp;";
		echo " ".ct('S_REVIEW_ASSIGN_UNASSIGN_CMD')." <input type=checkbox name=\"form_reviewer[]\" value=\"".$person->get('ID')."\">&nbsp;</span></td></tr>\n";
//		echo "<td align=\"right\" class=\"mediumbg\"><span class=\"bold10\">&nbsp;";
//		echo "<a href=\"".ct_pageurl('adminReviewAssign', array('form_cmd'=>'cmd_unassign','form_id'=>$http['form_id'],'form_reviewer'=>$person->get('ID')))."\">".ct('S_REVIEW_ASSIGN_UNASSIGN_CMD')."</a>&nbsp;</span></td></tr>\n";	}
	}
	echo "<tr><td colspan=2 align=right class=\"mediumbg\"><input type=submit class='button' value=\"".ct('S_BUTTON_SAVE')."\"></td></tr>\n";
} else {
	echo "<tr class=\"oddrow_del\"><span class=\"bold10\">&nbsp;".ct('S_REVIEW_ASSIGN_NONEYET')."</span></td></tr>\n";
}
echo "</table>\n</form>\n</div>\n";

// Show all PC members...
echo "<p class=\"boldlabel12\">".ct('S_REVIEW_ASSIGN_PCMEMBERS').":</p>";

$qr = $db->query("select * from persons where deleted=0 and status like '%pc%' order by name");
if ($qr && ($db->num_rows($qr)>0)) {
	echo "<div align=\"center\">\n";
	echo "<form method=\"post\" action=\"".ct_pageurl('adminReviewAssign')."\">\n";
	echo "<input type=hidden name=form_cmd value=\"cmdassign\">\n";
	echo "<input type=hidden name=form_id value=\"".$http['form_id']."\">";
	echo "<table width=\"90%\">\n";
	for ($i = 0; $i < $db->num_rows($qr); $i++) {
		$pdata = $db->fetch($qr);
		$person->pdata = $pdata;
		if ($i % 2) {
			echo "<tr class=\"oddrow\"><td width=\"100%\">\n";
		} else {
			echo "<tr class=\"evenrow\"><td width=\"100%\">\n";
		}

		# show reviewer name
		echo "<span ";
		if ($author->get_id()==$person->get_id())
			echo "class=\"negativebold10\">";
		else
			echo "class=\"bold10\">";
		echo ct_form_encode($person->get_reversename())."</span></a>&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<a href=\"".ct_pageurl('adminReviewAssignPC')."&form_id=".$person->get('ID')."\"><span class=\"light8\"> =&gt; ".ct('S_ADMIN_USERS_REVIEWS')."</span></a><br>";

		# show organization. Mark if similar to paper's
		echo "<span class=\"label10\">".ct('S_USER_ORGANISATION').":</span> ";
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
		echo $person->get('organisation')."</span><BR>";

		echo "<span class=\"label10\">".ct('S_USER_TOPICS').":</span> <span class=\"normal10\">";
		$j=0;
		$topics = $person->get_topics();
		while (list(,$v) = each($topics)) {
			if ($j>0) echo ",";
			if (in_array($v, $paper_topics)) echo "<span class=\"positivebold10\"> ".$v."</span>";
			else echo " ".$v;
			$j++;
		}
		if ($j == 0) echo "--";
		echo "</span><BR>\n";

		echo "<span class=\"normal8\">".ct('S_REVIEW_PAPERS').": ".$user->get_review_count($person->pdata['ID'],"",0)."</span>";
		echo "</td>\n";
		if (!$person->is_reviewer_for($http['form_id'])) {
			echo "<td align=\"right\" class=\"mediumbg\" nowrap><span class=\"label10\">&nbsp;";
	//		echo "<a href=\"".ct_pageurl('adminReviewAssign', array('form_cmd'=>'cmdassign','form_id'=>$http['form_id'],'form_reviewer'=>$person->get('ID')))."\">".ct('S_REVIEW_ASSIGN_ASSIGN_CMD')."</a>&nbsp;</span></tr>\n";
			echo " ".ct('S_REVIEW_ASSIGN_ASSIGN_CMD')." <input type=checkbox name=\"form_reviewer[]\" value=\"".$person->get('ID')."\">&nbsp;</span></td></tr>\n";
		} else {
			echo "<td class=\"mediumbg\">&nbsp;</td></tr>\n";
		}
	}
	echo "<tr><td colspan=2 align=right class=\"mediumbg\"><input type=submit class='button' value=\"".ct('S_BUTTON_SAVE')."\"></td></tr>\n";
	echo "</table>\n</form>\n</div>\n";
}

?>