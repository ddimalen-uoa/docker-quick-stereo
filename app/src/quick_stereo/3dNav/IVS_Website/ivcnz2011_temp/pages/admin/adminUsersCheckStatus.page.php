<?php
//
// PAGE:		adminUsersCheckStatus
// DESC:		Checks the Author, CoAuthor and Participant status of all users
//				only for internal use and if there are doubts about the database consistency.
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','admin','adminUsers'));

echo "<H2>".ct('S_ADMIN_USERS_CHECKSTATUS_CMD')."</H2>";
echo "<p>".ct('S_ADMIN_USERS_CHECKSTATUS_HINT')."</p>";

ct_vspacer();

// Increase maximum execution time
if( !ini_get('safe_mode') ){
	// Does _not_ work in SAFE MODE
	set_time_limit(600); // 600s
}
ct_flush();

$person = new CTPerson();
$participant = new CTParticipation();

$query = "select * from persons order by ID";
// echo $query;
$r = $db->query($query);

for ($i=0; $i < $db->num_rows($r); $i++) {
	$person->pdata = $db->fetch($r);
	$oldstatus = $person->get('status');
	$person->check_author();
	#$person->check_coauthor();
	$person->check_participant();

 	#$reviews = $person->count_assigned_reviews();
 	#if ($reviews==0) $person->remove_status('pc');	# to remove the pc status for all persons that do not have reviews
 	#if ($reviews>0)  $person->add_status('pc');	# to add the pc status for all persons that do not have reviews

	$person->persist();

	if ($person->is_participant()) {
		$participant->load_by_id($person->get_id());
		$participant->persist();
	}

	if ($oldstatus!=$person->get('status'))
		echo "<b>".$person->get_id() ."</b>: ".ct_form_encode($person->get_fullname()).": <b class='negativebold10'>'".$oldstatus."' =&gt; '".$person->get('status')."'</b><br>\n";
	else
		echo "<b>".$person->get_id() ."</b>: ".ct_form_encode($person->get_fullname()).": <b class='positivebold10'>OK</b><br>\n";

	// Output on Screen
	if (($i % 5) == 4) 	ct_flush(10);

}

echo "<H1>Done! All Users checked!</H1>";

?>