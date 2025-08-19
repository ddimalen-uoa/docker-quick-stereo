<?php

if (!defined('CONFTOOL')) die('Hacking attempt!');

# get all data of all contributiontypes
function ct_get_contributiontypes($id='') {
	global $db, $session;

	if ($session->get('db_contributiontypes')) {
		$c = $session->get('db_contributiontypes');
	} else {
		$c = array();
		$q = "select * from contributiontypes order by title";
		$r = $db->query($q);
		if ($r and ($db->num_rows($r) > 0)) {
			for ($i = 0; $i < $db->num_rows($r); $i++) {
				$g = $db->fetch($r);
				$key=$g['ID'];
				$c[$key] = $g;
			}
		}
		$session->put('db_contributiontypes',$c);
	}
	if ($id!='')
		return $c[$id];
	else
		return $c;
}


# returns an array that contains all available tracks / contribution types. Each element is an array with two
# elements, first is the ID, second is the title.
# If filter is true, list only active contributiontypes!
function ct_list_contributiontypes($filter=false) {
	global $db,$session;

	$types = array();
	$ctypes = ct_get_contributiontypes();
	while (list(,$v) = each($ctypes)) {
		#echo $v[active]."--".$v[deadline]."<br>";
		if ($filter===false || ($v['active']=='true' && ct_datetime_2_timestamp($v['deadline'])>time()))
			$types[] = array($v['ID'], $v['title']);
	}
	return $types;
}

/**
 * gets all topics available for this conference
 * $id returns the data of only this contributiontype
 */
function ct_get_topics($topicID=0) {
	global $db, $session;

	$c = array();
	$q = "select * from topics order by seqorder, title";
	$r = $db->query($q);
	if ($r and ($db->num_rows($r) > 0)) {
		for ($i = 0; $i < $db->num_rows($r); $i++) {
			$g = $db->fetch_raw($r);
			$key=$g['ID'];
			$c[$key] = $g;
		}
	}
	if ($topicID>0)
		return $c[$topicID];
	else
		return $c;
}

/**
 * returns an array that contains all available topics.
 * Each element is an array with two elements, first is the ID, second is the title.
 */
function ct_list_topics() {
	global $db,$session;

	$topics = ct_get_topics();
	$ctopics = array();
	while (list(,$v) = each($topics)) {
		$ctopics[] = array($v['ID'], $v['title']);
	}
	return $ctopics;
}


# Return array with all access states...
function ct_list_accessstates() {
	$states = array();
	$states[] = array("1",  ct('S_ADMIN_PAPERS_RESULTS_STATUS_P1_SHORT'));
	$states[] = array("0",  ct('S_ADMIN_PAPERS_RESULTS_STATUS_0_SHORT'));
	$states[] = array("-1", ct('S_ADMIN_PAPERS_RESULTS_STATUS_N1_SHORT'));
	$states[] = array("-2", ct('S_ADMIN_PAPERS_RESULTS_STATUS_N2_SHORT'));
	$states[] = array("-3", ct('S_ADMIN_PAPERS_RESULTS_STATUS_N3_SHORT'));
	return $states;
}

/**
 * @desc returns if any users did already register for participation.
 */
function ct_papers_exist($condition="") {
	global $db;
	if ($condition=="")
		$r = $db->query("select * from papers where withdrawn=0");
	else
		$r = $db->query("select * from papers where withdrawn=0 && $condition");
	return ($db->num_rows($r) >0) ? true : false;
}

/**
 * Replace HTML entities by their character code.
 *
 * @param string $string HTML string
 * @return string with removed HTML entities
 */
function ct_unhtmlentities($string) {
   // Replace &nbsp; with a normal space. ct_html_entity_decode replaces it with ASCII Code 160
   $string = str_replace('&nbsp;',' ',$string);
   // First replace numeric entities
   $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
   $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
   // replace literal entities
   $trans_tbl = get_html_translation_table(HTML_ENTITIES);
   $trans_tbl = array_flip($trans_tbl);
   return strtr($string, $trans_tbl);
}


?>