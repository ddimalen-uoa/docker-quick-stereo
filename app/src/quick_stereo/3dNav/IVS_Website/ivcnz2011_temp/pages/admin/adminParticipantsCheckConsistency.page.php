<?php
//
// PAGE:		adminParticipantsCheckConsistency
// DESC:		Checks the consistency of ConfTool Participant data, total prices
//				 and total payments.
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','admin'));
if( !ini_get('safe_mode') ){
	// Does _not_ work in SAFE MODE
	set_time_limit(1200); // 1200s = 20min
}
ct_flush();

$participation = new CTParticipation;
$person = new CTPerson();

$query = "select personID from participants";
// echo $query;
$r = $db->query($query);

echo "<H1>Checking all participants...</H1>";

for ($i=0; $i < $db->num_rows($r); $i++) {
	$row = $db->fetch($r);
	$participation->load_by_id($row['personID']);
	$oldtotal = $participation->get('total');
	$oldpayamount = $participation->get('payamount');
	$participation->persist();
	if ($oldtotal!=$participation->get('total') || $oldpayamount != $participation->get('payamount')) {
		// ERROR!!!
		echo "<span class='font9 fontnegative'><b>UPDATE</b>: ".$participation->get('personID').': '.ct_form_encode($participation->person->get_fullname())." ".($participation->get('deleted')?' <b>Cancelled!</b> ':'')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Old</b> Total: ".$oldtotal." / Paid: ".$oldpayamount."&nbsp;&nbsp;&nbsp;&nbsp;<b>New</b> Total: ".$participation->get('total')." / Paid: ".$participation->get('payamount')."</span><br>\n";
	} else {
		echo "<span class='font9'>OK: ".$participation->get('personID').': '.ct_form_encode($participation->person->get_fullname())." ".($participation->get('deleted')?' <b>Cancelled!</b> ':'')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total: ".$participation->get('total')." / Paid: ".$participation->get('payamount')."</span><br>\n";
	}
	if ($i%5==0) {
		ob_flush(); flush();
		sleep(0.1);
	}
}

echo "<H1>Consistency of all Participant Registration Amounts Checked.</H1>\n";

echo "<br><hr>\n\n";

ob_flush(); flush();
sleep(1);

// Do another check: Do all participants and persons exist that are stored in the
// participants2events database?
$query = "select personID from participants2events group by personID order by personID";
// echo $query;
$r = $db->query($query);
for ($i=0; $i < $db->num_rows($r); $i++) {
	$row = $db->fetch($r);
	if (!$person->load_by_id($row['personID'])) {
		// Fatal ERROR! Person with ID not found!
		echo "<br><span class='font9 fontnegative'><b>Error! Person with ID:".$row['personID']." not found in user database.</b></span><br>\n";
		$db->query('DELETE FROM participants2events WHERE personID='.$row['personID']);
		$db->query('DELETE FROM participants WHERE personID='.$row['personID']);
		continue;
	}
	if (!$participation->load_by_id($row['personID'],false)) {
		// Fatal ERROR! Participant with ID not found. In fact this should never happen...
		echo "<br><span class='font9 fontnegative'><b>Error! Participant with ID:".$row['personID']." not found in participant database.</b></span><br>\n";
		$db->query('DELETE FROM participants2events WHERE personID='.$row['personID']);
		continue;
	}
	echo $row['personID'].": OK, ";
	if ($i%10==9) { echo "<br>\n"; ob_flush(); flush(); }
	if ($i%100==0) sleep(1);
}

echo "<H1>Consistency of Participant Database Checked!</H1>\n\n";
ob_flush(); flush();

?>