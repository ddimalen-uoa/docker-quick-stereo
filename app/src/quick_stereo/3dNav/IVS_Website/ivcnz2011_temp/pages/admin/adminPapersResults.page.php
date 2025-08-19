<?php
#
# PAGE:		adminPapersResults
# DESC:		This page shows all results... This is a bit of a hack and not really object-oriented
#           but we wanted it to be really FAST!!!
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirechair();
ct_pagepath(array('index','adminPapers'));
$session->set_besturl(ct('S_ADMIN_PAPERS_RESULTS_QUICK'));

$colors = array("review0","review1","review2","review3","review4","review5","review6","review7","review8","review9","review10");
$accstatcolor = array("-3"=>"otherbg","-2"=>"otherbg","-1"=>"negativebg","0"=>"","1"=>"positivebg");

$paper = new CTPaper();

if (in_http("form_action","status") and in_http("form_status") and in_http("form_paperID")) {
	$paper->load_by_id($http['form_paperID']);
	$paper->set("acceptstatus", $http['form_status']);
	$paper->persist();
} elseif (in_http("form_action","track") and in_http("form_track") and in_http("form_paperID")) {
	$paper = new CTPaper();
	$paper->load_by_id($http['form_paperID']);
	$paper->set("track", $http['form_track']);
	$paper->persist();
}

// change order.
$sort = "ORDER BY score desc, span desc, title asc";
if (isset($http['form_sort']) && $http['form_sort']=='score')
	$sort = "ORDER BY score desc, span desc, title asc";
if (isset($http['form_sort']) && $http['form_sort']=='count')
	$sort = "ORDER BY count desc, score desc, title asc";
if (isset($http['form_sort']) && $http['form_sort']=='span')
	$sort = "ORDER BY span desc, title asc";
if (isset($http['form_sort']) && $http['form_sort']=='ID')
	$sort = "ORDER BY ID";
if (isset($http['form_sort']) && $http['form_sort']=='title')
	$sort = "ORDER BY title asc";
if (isset($http['form_sort']) && $http['form_sort']=='author')
	$sort = "ORDER BY per.name, per.firstname, author asc, title asc";
if (isset($http['form_sort']) && $http['form_sort']=='acceptstatus')
	$sort = "ORDER BY acceptstatus desc, score desc, title asc";
if (isset($http['form_sort']) && $http['form_sort']=='track')
	$sort = "ORDER BY acceptstatus desc, track, title";

$tracks = array();
array_push($tracks, array('ID'=>0,'shorttitle'=>'---'));

$q = "select ID, shorttitle from tracks order by shorttitle";
$r = $db->query($q);
if ($r and ($db->num_rows($r) > 0)) {
	for ($i = 0; $i < $db->num_rows($r); $i++) {
		array_push($tracks,$db->fetch($r));
	}
}

$q = "select p.ID as id, p.personID as authorID, author, title, ".
			"greatest(0,(sum(evaluation+significance+originality+relevance+readability+(5*overall))".
			"/count(r.paperID))) as score, ".
			"count(r.paperID) as count, ".
			"(max((evaluation+significance+originality+relevance+readability+(5*overall)))-".
			"min((evaluation+significance+originality+relevance+readability+(5*overall)))) as span, ".
			"acceptstatus, track, contributiontypeID ".
			"FROM papers p ".
			"left join reviews r on p.ID = r.paperID and r.creationdate > 0 ".
			"where p.withdrawn=0 ". # and r.creationdate != '' ".
			"group by p.ID $sort";
			//echo $q; return;
$r = $db->query($q);



echo "<h1>".ct('S_ADMIN_PAPERS_RESULTS_CMD')."</h1>";
echo "<p class=\"standard\">".ct('S_ADMIN_PAPERS_RESULTS_HINT')."</p>";
// echo "\n<!--\n\n $q \n\n-->\n";

$num = $db->num_rows($r);
if ($num > 0) {

	echo "<table width=\"100%\" cellspacing=1 cellpadding=3 border=0>";
	echo "<tr class=\"listheader\">";
	echo "<td align=center width=\"1%\"><span class=\"listheader_label\">".ct('S_ADMIN_PAPERS_RESULTS_POS')."</span></td>";
	echo "<td align=center width=\"2%\"><a href=\"".ct_pageurl("adminPapersResults", array("form_sort"=>"desc"))."\" class=\"bold8\">".ct('S_ADMIN_PAPERS_RESULTS_POINTS')."</a></td>";
	echo "<td align=center width=\"2%\"><a href=\"".ct_pageurl("adminPapersResults", array("form_sort"=>"count"))."\" class=\"bold8\">".ct('S_ADMIN_PAPERS_RESULTS_COUNT')."</a><br><img src=\"".ct_getbaseurl()."images/darkblue.gif\" height=1 width=40><br>";
	echo "<a href=\"".ct_pageurl("adminPapersResults", array("form_sort"=>"span"))."\" class=\"bold8\">".ct('S_ADMIN_PAPERS_RESULTS_SPAN')."</a></td>";
	echo "<td align=center width=\"1%\"><a href=\"".ct_pageurl("adminPapersResults", array("form_sort"=>"ID"))."\" class=\"listheader_label\">".ct('S_ADMIN_PAPERS_RESULTS_ID')."</a></td>";
	echo "<td width=\"58%\"><a href=\"".ct_pageurl("adminPapersResults", array("form_sort"=>"title"))."\" class=\"listheader_label\">".ct('S_ADMIN_PAPERS_RESULTS_TITLE')."</a></td>";
	echo "<td width=\"30%\"><span class=\"listheader_label\">".ct('S_ADMIN_PAPERS_RESULTS_AUTHORS')."</span></td>";
	echo "<td align=center width=\"3%\"><a href=\"".ct_pageurl("adminPapersResults", array("form_sort"=>"acceptstatus"))."\" class=\"listheader_label\">";
	echo ct('S_ADMIN_PAPERS_RESULTS_STATUS')."</a></td>";
	echo "<td align=center width=\"3%\"><a href=\"".ct_pageurl("adminPapersResults", array("form_sort"=>"track"))."\" class=\"listheader_label\">";
	echo ct('S_ADMIN_PAPERS_RESULTS_SESSION')."</a></td>";
	echo "</tr>\n";

	for ($i = 0; $i < $num; $i++) {

		$row = $db->fetch($r);

		$author = new CTPerson;
		$author->load_by_id($row['authorID']);

		echo "<tr class=\"".($i%2?"oddrow":"evenrow")."\">\n";

		echo "<td align=center><span class=bold10>".($i+1)."</span></td>\n";

		echo "<td align=center ";
		if ($row['count'] != 0) {
			echo "class=\"".$colors[round(($row['score']/10)-1)]."\" ";
		}
		echo ">";
		echo "<span class=\"bold12\">".($row['score']!=""?round($row['score'],1):"-")."</span></td>\n";
		echo "<td align=center>";
		if ($row['count'] < 2) { echo "<span class=\"negativebold10\">"; }
		else { echo "<span class=\"normal10\">"; }
		echo $row['count']." / ";
		echo "<a href=\"".ct_pageurl('adminReviewAssign', array('form_id' => $row['id']))."\" title=\"".ct('S_REVIEW_ASSIGN_PC')."\">";
		echo $user->get_review_count("",$row['id'],0);  # not optimized yet...
		echo "</a></span><br>";
		if ($row['span'] >= 30) { echo "<span class=\"negativebold10\">"; }
		else { echo "<span class=\"bold10\">"; }
		echo ($row['span']!=""?$row['span']:"-");
		echo "</span></td>\n";

		echo "<td align=center><span class=\"bold10\">".$row['id']."</span></td>\n";

		echo "<td valign=\"top\"><a class=\"bold8\" href=\"".ct_pageurl("adminPapersDetails", array("form_id"=>$row['id']))."\">".ct_form_encode($row['title'])."</a><BR>";
		echo "<span class=\"normal8\">".$paper->get_contributiontype($row['contributiontypeID'])."</span>";
		echo "</td>";

		echo "<td align=left valign=top><span class=\"normal8\">".ct_form_encode($row['author'])."</span><br>\n";
		echo "<a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$row['authorID']))."\" class=\"normal8\">".ct_form_encode($author->get_reversename())."&nbsp;</a></td>\n";

		echo "<form action=\"".ct_pageurl('adminPapersResults')."\" method=post>";
		echo "<td class=\"".$accstatcolor[$row['acceptstatus']]."\" align=center>";
		echo "<input type=hidden name=form_action value=status>\n";
		echo "<input type=hidden name=form_paperID value=\"".$row['id']."\">\n";  // id in lower letters!!!
	    echo "<input type=hidden name='form_sort' value='".$http['form_sort']."'>\n";

		echo "<select class=\"optionsmall\" size=1 name=form_status onChange=\"this.form.submit();\">";
		echo "<option class=\"optionsmall\" value=1 ".($row['acceptstatus']==1 ? "selected" : "").">".ct('S_ADMIN_PAPERS_RESULTS_STATUS_P1_SHORT')."&nbsp;</option>\n";
		echo "<option class=\"optionsmall\" value=0 ".($row['acceptstatus']==0 ? "selected" : "").">".ct('S_ADMIN_PAPERS_RESULTS_STATUS_0_SHORT')."&nbsp;</option>\n";
		echo "<option class=\"optionsmall\" value=-1 ".($row['acceptstatus']==-1 ? "selected" : "").">".ct('S_ADMIN_PAPERS_RESULTS_STATUS_N1_SHORT')."&nbsp;</option>\n";
		echo "<option class=\"optionsmall\" value=-2 ".($row['acceptstatus']==-2 ? "selected" : "").">".ct('S_ADMIN_PAPERS_RESULTS_STATUS_N2_SHORT')."&nbsp;</option>\n";
		echo "<option class=\"optionsmall\" value=-3 ".($row['acceptstatus']==-3 ? "selected" : "").">".ct('S_ADMIN_PAPERS_RESULTS_STATUS_N3_SHORT')."&nbsp;</option>\n";
		echo "</select>";
		echo "</td>\n";
		echo "</form>\n";

		if ($row['acceptstatus']!=0 && $row['acceptstatus']!=-1) { // maybe do not show...
    		echo "<form action=\"".ct_pageurl('adminPapersResults')."\" method=post>";
    		echo "<td align=center>";
	    	echo "<input type=hidden name='form_action' value=track><input type=hidden name=form_paperID value=\"".$row['id']."\">\n";
		    echo "<input type=hidden name='form_sort' value='".$http['form_sort']."'>\n";
    		echo "<select class=\"optionsmall\" size=1 name=form_track onChange=\"this.form.submit();\">";
    		foreach ($tracks as $track) {
	    		echo "<option class=\"optionsmall\" value=".$track['ID']." ".($row['track']==$track['ID'] ? "selected" : "").">".$track['shorttitle']."</option>";
    		}
    		echo "</select>";
	    	echo "</td>\n";
		    echo "</form>\n";
	    } else {
    		echo "<td align=center>";
    	    echo "&nbsp;";
    	    echo "</td>";
    	}
		echo "</tr>\n";
	}

	echo "</table>";
}

?>
