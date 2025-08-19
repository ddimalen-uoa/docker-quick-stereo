<?php
//
// PAGE:		adminUsersPurge
// DESC:		Purge all deleted users, all deleted papers and all deleted participants
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','admin','adminUsers'));

echo "<h3>".ct('S_ADMIN_USERS_PURGE_CMD')."</h3>\n";
echo "<p>".ct('S_ADMIN_USERS_PURGE_HINT')."</p>\n";


$person = new CTPerson;

$query = "select * from persons where deleted=1 order by ID";
// echo $query;
$res = $db->query($query);

for ($i=0; $i < $db->num_rows($res); $i++) {
	$person->pdata = $db->fetch($res);
	$person->check_author();
	$person->check_participant();
	$id = $person->get_id();
	if ($person->is_author()) {
		echo "<H3>Error purging person $id: the user still has submitted papers</H3>";
		continue;
	}
	if ($person->is_participant()) {
		echo "<H3>Error purgung person $id: the user is still registered participant</H3>";
		continue;
	}
	if ($person->count_assigned_reviews()>0) {
		echo "<H3>Error purgung person $id: the user still has assigned reviews</H3>";
		continue;
	}
	#$q = "select * from participants2payments where personID='$id'";
	#$r = $db->query($q);
	#if ($r && $db->num_rows($r)) {
	#	echo "<H3>Error purgung person $id: the user still has payments in the database</H3>";
	#	continue;
	#}
	//
	$db->query("delete from papers where personID='$id'");
	$db->query("delete from pc2topics where personID='$id'");
	$db->query("delete from reviews where personID='$id'");
	//
	$db->query("delete from participants where personID='$id'");
	$db->query("delete from participants2events where personID='$id'");
	#$db->query("delete from participants2payments where personID='$id'");
	//
	$db->query("delete from persons where ID='$id'");

	echo "<H3>Purged user $id: ".ct_form_encode($person->get_fullname())."<br>\n";
}

echo "<H1>Done! All Deleted Users Were Purged!</H1>";

?>