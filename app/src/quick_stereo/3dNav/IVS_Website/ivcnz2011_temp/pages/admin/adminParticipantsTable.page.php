<?php
#
# PAGE:		adminParticipantsTable
# DESC:		Show an overview (cross-classified table) of all events/products and all participants with prices and sums.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

	ct_pagepath(array('index','adminUsers'));
	ct_requireadmin();

	echo "<h1>".ct('S_ADMIN_PARTICIPANTS_TABLE_CMD')."</h1>\n";
	echo "<p class=\"standard\">".ct('S_ADMIN_PARTICIPANTS_TABLE_HINT')."</p>\n";

	# get all status groups
	#
  	$groups = array(); 		// ID, title,
	$groupCount = array(); 	// Sum of Persons
	$groupAmount= array();	// Sum of Amounts
	$query = "SELECT * FROM groups order by ID";
  	$r = $db->query($query);
 	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$t = $db->fetch($r);
			$groups[] = array($t['ID'],$t['title'],0,0);
			$groupCount[$i] = 0;
			$groupAmount[$i]= 0;
		}
	}

	# get all events
	#
	$events = array(); // ID, title
  	$query = "SELECT events.ID as ID, events.title as title FROM events, eventtypes where events.eventtype=eventtypes.ID order by eventtypes.seqorder,events.seqorder";
  	$r = $db->query($query);
 	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$t = $db->fetch($r);
			$events[] = array($t['ID'],$t['title']);
		}
	}

	#create table
	#
	$row=0;
	echo "<P><table cellpadding=2 cellspacing=1 border=0>";
	echo "<tr class=\"listheader\"><td bgcolor=#F0F0F0>&nbsp;</td>";
	foreach ($groups as $group) {
		echo "<td align=center class=\"bold10\">$group[1]</td>";
	}
	echo "<td align=center class=\"bold10\">".ct('S_ADMIN_PARTICIPANTS_TABLE_COUNT')."</td>";
	echo "<td align=center class=\"bold10\">".ct('S_ADMIN_PARTICIPANTS_TABLE_AMOUNT')."</td></tr>";
	# show details
	foreach ($events as $event) {
		$count = 0;
		$amount= 0;
		echo "<tr class=\"".($row%2?"oddrow":"evenrow")."\">\n";
		echo "<td class=\"bold10\">$event[1]</td>";
		$col=0;
		foreach ($groups as $group) {
			$num = 0;
			$fee = 0;
			echo "<td align=center class=\"normal10\">";
			# echo "$event[0] / $group[0] /";
			$query = "SELECT participants2events.number as number, participants.regdate AS regdate, participants.status AS status, events.pricecategory AS pricecategory, participants.paymethod AS paymethod  FROM participants, participants2events, events WHERE participants.personID=participants2events.personID and participants2events.eventID=events.ID and participants.status=$group[0] and participants2events.eventID=$event[0] and participants2events.deleted=0 and participants2events.number!=0";
		  	$r = $db->query($query);
		 	if ($r && ($db->num_rows($r) > 0)) {
				for ($i=0; $i < $db->num_rows($r); $i++) {
					$t = $db->fetch($r);
					$num += $t['number'];
					# calculate fee
					if ($t['paymethod']<>"free") {
						$pricecategory=$t['pricecategory'];
						$status=$t['status'];
						$timediscountID=get_timediscountID($t['regdate']);
						$r2 = $db->query("select gross from prices where groupID='".$status."' and timediscountID='".$timediscountID."' and pricecategoryID='".$pricecategory."'");
						if ($r2 && ($db->num_rows($r2) == 1)) {
							$p = $db->fetch($r2);
							$fee += ($p['gross']*$t['number']);
						}
					}
				}
			}
			$count            += $num;
			$amount			  += $fee;
			$groupCount[$col] += $num;
			$groupAmount[$col]+= $fee;
			$col++;
			if ($num<>0) {
				echo "$num<BR>".ct_currency_format($fee, false, false)."</td>";
			} else {
				echo "<span class=\"light10\">$num<BR>$fee</span></td>";
			}
		}
		echo "<td align=center class=\"bold10\">$count</td>";
		echo "<td align=center class=\"bold10\">".ct_currency_format($amount, false, false)."</td>";
		echo "</tr>";
		$row++;
	}
	#show count
	$i=0;
	echo "<tr class=\"listheader\"><td class=\"bold10\">".ct('S_ADMIN_PARTICIPANTS_TABLE_COUNT')."</td>";
	foreach ($groups as $group) {
		echo "<td align=center class=\"bold10\">$groupCount[$i]</td>";
		$i++;
	}
	echo "<td bgcolor=#F0F0F0>&nbsp;</td><td bgcolor=#F0F0F0>&nbsp;</td>";
	echo "</tr>";
	#show amount
	$i=0;
	$total=0;
	echo "<tr class=\"listheader\"><td class=\"bold10\">".ct('S_ADMIN_PARTICIPANTS_TABLE_AMOUNT')."</td>";
	foreach ($groups as $group) {
		echo "<td align=center class=\"bold10\">".ct_currency_format($groupAmount[$i], false, false)."</td>";
		$total += $groupAmount[$i];
		$i++;
	}
	echo "<td bgcolor=#F0F0F0>&nbsp;</td><td align=center><span class=\"bold10\">".ct_currency_format($total, false, false)."</span></td>";
	echo "</tr>";
	echo "</table></p>";

	# helper functions...
	function get_timediscountID($date){
		global $db;
		$timediscounts = array();
		$r = $db->query('select * from timediscounts order by effectiveuntil');
		if ($r && ($db->num_rows($r) > 0)) {
			for ($i=0; $i < $db->num_rows($r); $i++) {
				$t = $db->fetch($r);
				$regdate=create_timestamp($date);
				$efffrom=create_timestamp($t['effectivefrom']);
				$effuntil=create_timestamp($t['effectiveuntil']);
				if ($regdate >= $efffrom && $regdate <= $effuntil){
					$timediscountID=$t['ID'];
				break;
				}
			}
		}
		return $timediscountID;
	}

	function create_timestamp($date){
		$t=array();
		$t= explode(" ", $date);
		$t1=explode("-", $t[0]);
		$t2=explode(":", $t[1]);
		$timestamp=mktime($t2[0],$t2[1],$t2[2],$t1[1],$t1[2],$t1[0]);
		return $timestamp;
	}

?>