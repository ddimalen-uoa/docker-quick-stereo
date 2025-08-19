<?php
if (!defined('CONFTOOL')) die('Hacking attempt!');

/**
 * returns an array that contains all available groups. Each element is an array with two
 * elements, first is group ID, second is group title.
 */
function ct_list_groups() {
	global $db;

	$groups = array();
	$r = $db->query('select * from groups order by title asc');
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$g = $db->fetch($r);
			$groups[] = array($g['ID'], $g['title']);
		}
	}
	return $groups;
}

/**
 * returns an array that contains all available discounts. Each element is an array with two
 * elements, first is group ID, second is group title.
 */
function ct_list_timediscounts() {
	global $db;

	$timediscounts = array();
	$r = $db->query('select * from timediscounts order by title asc');
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$t = $db->fetch($r);
			$effectivefrom = ct_date_format($t['effectivefrom']);
			$effectiveuntil = ct_date_format($t['effectiveuntil']);
			$timediscounts[] = array($t['ID'],"$t[title] ($effectivefrom - $effectiveuntil)");
		}
	}
	return $timediscounts;
}

/**
 * returns an array that contains all available pricecategories. Each element is an array with two
 * elements, first is pricecategory ID, second is pricecategory title.
 */
function ct_list_pricecategories() {
	global $db;

	$pricecategories = array();
	$r = $db->query('select * from pricecategories order by title asc');
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$p = $db->fetch($r);
			$pricecategories[] = array($p['ID'], $p['title']);
		}
	}
	return $pricecategories;
}

/**
 * returns an array that contains all available value added taxes. Each element is an array with two
 * elements, first is vat ID, second is vat percentage.
 */
function ct_list_vats() {
	global $db;

	$vats = array();
	$r = $db->query('select ID,percentage from vats order by percentage asc');
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$v = $db->fetch($r);
			$vats[] = array($v['ID'], ct_number_format($v['percentage']));
		}
	}
	return $vats;
}

/**
 * returns an array that contains all available eventtypes. Each element is an array with three
 * elements, first is eventtype ID, second is eventtype title, the third is the exclusive status.
 */
function ct_list_eventtypes() {
	global $db;

	$eventtypes = array();
	$r = $db->query('select * from eventtypes order by seqorder, title asc');
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$e = $db->fetch($r);
			$eventtypes[] = array($e['ID'], $e['title'], $e['mode']);
		}
	}
	return $eventtypes;
}

/**
 * returns an array that contains all eventtypes ordered by seqorder.
 * Each element is an array with four elements, first is event ID,
 * second is event title, third is seqorder, last event information.
 */
function ct_show_eventtypes() {
	global $db;
	$eventtypes = array();
	$r = $db->query('select * from eventtypes order by seqorder asc');
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$e = $db->fetch($r);
			$eventtypes[$i] = array();
			$eventtypes[$i]['ID']=$e['ID'];
			$eventtypes[$i]['title']=$e['title'];
			$eventtypes[$i]['seqorder']=$e['seqorder'];
			$eventtypes[$i]['info']=$e['info'];
			$eventtypes[$i]['mode']=$e['mode'];
		}
	}
	return $eventtypes;
}

/**
 * returns an array that contains all events. Each element is an array with two
 * elements, first is event ID, second is event title.
 */
function ct_list_events($orderedByEventgroups=false) {
	global $db;
	$events = array();
	if ($orderedByEventgroups) { // ordered by event groups
		$r = $db->query('select events.ID as ID, events.title as title, eventtypes.ID as typeID '.
						 'FROM events, eventtypes WHERE events.eventtype=eventtypes.ID ORDER by eventtypes.seqorder, eventtypes.title, events.seqorder, events.title asc');
	} else { // ordered by title
		$r = $db->query('select ID,title from events order by title asc');
	}
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$e = $db->fetch($r);
			$events[] = array($e['ID'], $e['title']);
		}
	}
	return $events;
}


/**
 * returns an array that contains all events ordered by title
 * (if the eventtype is given, ordered by seqorder).
 * Each element is an array with 15 elements
 */
function ct_show_events($eventtypeID) {
	global $db;
	$events = array();
	if (!$eventtypeID){
		$r = $db->query('select * from events order by title asc');
	}
	else {
		$r = $db->query("select * from events where eventtype='".$eventtypeID."' order by seqorder asc");
	}
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$e = $db->fetch($r);
			$events[$i] = $e;
		}
	}
	return $events;
}

/**
 * returns if any users did already register for participation.
 */
function ct_participants_exist() {
	global $db;
    $r = $db->query("select * from participants where deleted=0");
    return ($db->num_rows($r) >0) ? true : false;
}


/**
 * Format Credit Card number (enter spaces.)
 *
 * @param string $cardnumber
 * @return string the formatted cc number
 */
function ct_format_ccnumber( $cardnumber ) {
	$cardnumber = str_replace(array(" ","-","/","_"),array("","","",""),$cardnumber);
	return trim(ct_substr($cardnumber,0,4).' '.ct_substr($cardnumber,4,4).' '.ct_substr($cardnumber,8,4).' '.ct_substr($cardnumber,12,4));
}


/**
 * Check Credit Card number (check the checksum of the card.). See: http://www.sitepoint.com/print/card-validation-class-php
 *
 * @param string $cardnumber
 * @return boolean
 */
function ct_check_ccnumber( $cardnumber ) {
	// remove special characters.
	$cardnumber = ereg_replace("[^0-9]", "", $cardnumber);
	// check length
 	if(ct_strlen($cardnumber)<13)  return false;

 	// Check if the CC is any valid CC-Number...
	$validFormat = false;
    $validFormat |= ereg("^5[1-5][0-9]{14}$", $cardnumber);				    // Mastercard
    $validFormat |= ereg("^4[0-9]{12}([0-9]{3})?$", $cardnumber);			// Visa
    $validFormat |= ereg("^3[47][0-9]{13}$", $cardnumber);					// Amex
    $validFormat |= ereg("^3(0[0-5]|[68][0-9])[0-9]{11}$", $cardnumber);	// Diners
    $validFormat |= ereg("^6011[0-9]{12}$", $cardnumber);				    // Discover
    $validFormat |= ereg("^(3[0-9]{4}|2131|1800)[0-9]{11}$", $cardnumber);	//JCB

    // Is the number valid? Do LUHN-Check
    $cardrev = strrev($cardnumber);
	$numSum = 0;
	for($i = 0; $i < ct_strlen($cardrev); $i++) {
	  	$currentNum = ct_substr($cardrev, $i, 1);
		// Double every second digit
		if($i % 2 == 1)	$currentNum *= 2;
		// Add digits of 2-digit numbers together (what is number=-9 as 9<number<19)
		if($currentNum > 9) $currentNum -= 9;
		$numSum += $currentNum;
	}
	// The $numSum variable will contain the sum of all of the variables from step two of the Mod 10 algorithm, which we described earlier. PHP's symbol for the modulus operator is '%', so we assign true/false to the $passCheck variable, depending on whether or not $numSum has a modulus of zero:
	// If the total has no remainder it's OK
	$validChecksum = ($numSum % 10 == 0);
	return $validFormat && $validChecksum;
}

/**
 * Mask credit card number, show something like 12XX XXXX XXXX 7890
 *
 * @param string $cardnumber
 * @return string
 */
function ct_mask_ccnumber( $cardnumber ) {
	if (ct_strlen($cardnumber)>4)
		return 	ct_substr($cardnumber,0,2).'XX XXXX XXXX '.ct_substr($cardnumber,-4);
	else
		return $cardnumber;
}

?>