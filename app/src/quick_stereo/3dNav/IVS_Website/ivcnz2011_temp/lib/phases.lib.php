<?php

if (!defined('CONFTOOL')) die('Hacking attempt!');

/**
 * ct_load_phases loads all phases from database. It checks which phases are active and
 * stores the results in the session.
 */
function ct_load_phases() {
	global $db, $session, $ctconf;

	// $id = $db->query("select ID,((to_days(now()) between to_days(starts) and to_days(ends)) and (active = 1)) as isactive from phases");
	$id = $db->query("select ID, starts, ends, (active = 1) as isactive from phases");
	$phases = array();
	$phasesends = array();
	if (!$id) {
	    echo "<H2 style='background: #ff8'><code>Database Error!<br>The table 'phases' could not be found in the database '".$ctconf['db/database']."'</code></H2>";
    	echo "Probably the wrong database was selected.<br>";
		return false;
	}
	if ($db->num_rows($id)==0) {
	    echo "<H2 style='background: #ff8'><code>Database Error!<br>The table 'phases' is missing essential data in your database '".$ctconf['db/database']."'</code></H2>";
    	echo "Probably the default data was not inserted.<br>";
		return false;
	}
	for ($i=0;$i < $db->num_rows($id); $i++) {
		$ph = $db->fetch($id);
		if ((ct_datetime_2_timestamp($ph['starts']) < time()) && (ct_datetime_2_timestamp($ph['ends']) > time())) {
			$phases[$ph["ID"]] = $ph["isactive"];
			$phasesends[$ph["ID"]] = $ph['ends'];
		}
	}
	$session->put("phases",$phases);
	$session->put("phasesends",$phasesends);
	return true;
}

/**
 * ct_check_phases takes as argument either an array with phase IDs or a single phase ID
 * and checks if all provided phases are active at the moment. It returns true or false.
 */
function ct_check_phases($phs) {
	global $session;

#	$u = $session->get_user();
#	if (is_object($u) and $u->is_admin()) {
#		return true;
#	}
	if(is_array($phs)) {
		$okay = false;
		foreach ($phs as $ph) {
			$okay = $okay || ct_check_phases($ph);
		}
		return $okay;
	} else {
		$phases = $session->get("phases");
		if($phases[$phs] == 1) {
			return true;
		} else {
			return false;
		}
	}
}

?>
