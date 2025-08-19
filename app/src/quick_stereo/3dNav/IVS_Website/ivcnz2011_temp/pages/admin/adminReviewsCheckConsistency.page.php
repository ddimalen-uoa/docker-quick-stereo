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

ct_load_class('CTReview');
$review = new CTReview();

$query = "select paperID,personID from reviews order by paperID";
// echo $query;
$res = $db->query($query);

for ($i=0; $i < $db->num_rows($res); $i++) {
	$row = $db->fetch($res);
	$paperID=$row['paperID'];
	$personID=$row['personID'];
	$q2 = "select ID from papers where ID=$paperID";
	$r2 = $db->query($q2);
	if (!$r2 || $db->num_rows($r2)==0) {
		echo "<H3>Consistency Error: Paper with ID $paperID not found! Deleting Review!</H3>";
		$db->query("delete from reviews where paperID=$paperID");
	}
	$q2 = "select ID from persons where ID=$personID";
	$r2 = $db->query($q2);
	if (!$r2 || $db->num_rows($r2)==0) {
		echo "<H3>Consistency Error: Person with ID $personID not found! Deleting Review!</H3>";
		$db->query("delete from reviews where personID=$personID");
	}
	if (($i % 5) == 4) {
		ob_flush();	flush();
	}

}

echo "<H1>Done! All reviews checked!</H1>";

?>