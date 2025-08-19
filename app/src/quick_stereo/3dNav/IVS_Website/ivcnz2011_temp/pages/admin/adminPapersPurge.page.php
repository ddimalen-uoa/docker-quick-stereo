<?php
//
// PAGE:		adminPapersPurge
// DESC:		Purge all deleted papers, if no reviewers were assigned.
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','admin','adminPapers'));

if( !ini_get('safe_mode') ){
	// Does _not_ work in SAFE MODE
	set_time_limit(600); // 600s
}
ct_flush();

ct_load_class('CTPaper');
$paper = new CTPaper;

$query = "select * from papers where withdrawn=1 order by ID";
// echo $query;
$res = $db->query($query);

echo "<H1>".ct('S_ADMIN_PAPERS_PURGE_CMD')."</H1>";

for ($i=0; $i < $db->num_rows($res); $i++) {
	$paper->pdata = $db->fetch($res);
	$id=$paper->get_id();

	$reviews = $paper->get_reviews(true);
	if (!in_http('force') && count($reviews)>0) {
		echo "<p class='negativebold10'>Error purging paper ID $id: reviewers are still assigned</p>";
		continue;
	}
	// Now delete this paper...
	$db->query("delete from papers where ID='$id'");
	$db->query("delete from emails2papers where paperID='$id'");
	$db->query("delete from topics2papers where paperID='$id'");
	$db->query("delete from reviews where paperID='$id'");

	echo "<p>Purged paper ID $id: ".ct_form_encode($paper->get('title'))."</p>\n";

	if (($i % 5) == 4) {
		ct_flush();
	}
}

echo "<H3>Done!</H3>";

?>