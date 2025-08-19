<?php
#
# PAGE:		adminToolPrices
# DESC:		Enter prices for conference participation
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminTool'));

// First check if all categories exist!
$rp = $db->query('select ID from pricecategories');
for ($i=0; $i < $db->num_rows($rp); $i++) {
	$p = $db->fetch($rp);
	$rt = $db->query('select ID from timediscounts');
	for ($j=0; $j < $db->num_rows($rt); $j++) {
		$t = $db->fetch($rt);
		$rg = $db->query('select ID from groups');
		for ($k=0; $k < $db->num_rows($rg); $k++) {
			$g = $db->fetch($rg);
			$r = $db->query("select * from prices where groupID='".$g['ID']."' and timediscountID='".$t['ID']."' and pricecategoryID='".$p['ID']."'");
			if ($r && ($db->num_rows($r) > 0)) {
				// Entry exists! OK :-)
			} else {
				$db->insert_into('prices', array('ID'=>0,'groupID'=>$g['ID'],'timediscountID'=>$t['ID'],'pricecategoryID'=>$p['ID'],'gross'=>0)); // Insert new entry!
			}
		}
	}
}

// If submit was pressed, do update.
if (isset($http['cmd_prices_update'])) {
	$r = $db->query('select * from prices');
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$p = $db->fetch($r);
			if (isset($http[$p['pricecategoryID'].'_'.$p['timediscountID'].'_'.$p['groupID']])) {
				// update data!
				$newprice = $http[$p['pricecategoryID'].'_'.$p['timediscountID'].'_'.$p['groupID']];
			    $db->update('prices',"ID='".$p['ID']."'", array('ID'=>$p['ID'],'groupID'=>$p['groupID'],'timediscountID'=>$p['timediscountID'],'pricecategoryID'=>$p['pricecategoryID'],'gross'=>ct_number_unformat($newprice)));
			} else {
				// delete price entry as some master data was removed!
				$db->delete("prices","ID='".$p['ID']."'");
			}
		}
	}
	ct_infobox(ct('S_INFO_SAVE'), ct('S_INFO_SAVE_SUCCESS'));
} else {
	if (ct_participants_exist()) ct_warningbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_PARTICIPATS_EXISTS'));
}

// -------------------------------------------------------------

// creates an array with all pricecategories
$pricecategory=array();
$rp = $db->query('select * from pricecategories order by title');
if ($rp && ($db->num_rows($rp) > 0)) {
	for ($i=0; $i < $db->num_rows($rp); $i++) {
		$p = $db->fetch($rp);
		$pricecategory[$p['ID']]=$p['title'];
	}
}
// creates an array with all timediscounts
$timediscount=array();
$rt = $db->query('select * from timediscounts order by effectivefrom');
if ($rt && ($db->num_rows($rt) > 0)) {
	for ($i=0; $i < $db->num_rows($rt); $i++) {
		$t = $db->fetch($rt);
		$timediscount[$t['ID']]=$t['title'];
	}
}
// creates an array with all groups
$group=array();
$rg = $db->query('select * from groups order by title');
if ($rg && ($db->num_rows($rg) > 0)) {
	for ($i=0; $i < $db->num_rows($rg); $i++) {
		$g = $db->fetch($rg);
		$group[$g['ID']]=$g['title'];
	}
}

// selects all from table prices and creates an array
$price=array();
// If persons are already registered, set as readonly...
$readonly=array();
$r = $db->query('select pricecategoryID, timediscountID, groupID, gross from prices');
if ($r && ($db->num_rows($r) > 0)) {
	for ($i=0; $i < $db->num_rows($r); $i++) {
		$pr = $db->fetch($r);
		$price[$pr['pricecategoryID']][$pr['timediscountID']][$pr['groupID']]=ct_number_format($pr['gross']);
		$readonly[$pr['pricecategoryID']][$pr['timediscountID']][$pr['groupID']]=false;

		if (!in_http('force')) {
			$q = "SELECT sum(p2e.number) AS summe ";
			$q.=" FROM (participants as p, participants2events as p2e, events as e, timediscounts as t) ";
			$q.=" WHERE p.regdate != '0000-00-00 00:00:00' AND p2e.number>0 AND p.deleted=0 AND p2e.deleted=0 AND ";
			$q.=" p.personID=p2e.personID AND p2e.eventID=e.ID AND";
			$q.=" p.regdate>=t.effectivefrom and p.regdate<=t.effectiveuntil AND ";
			$q.=" t.ID='".$pr['timediscountID']."' AND ";
			$q.=" p.status='".$pr['groupID']."' AND ";
			$q.=" e.pricecategory='".$pr['pricecategoryID']."' ";
			$r2 = $db->query($q);
			#echo $q;
			if ($r2 AND $db->num_rows($r2)>0) {
				$pr2 = $db->fetch($r2);
				if ($pr2['summe']>0)
					$readonly[$pr['pricecategoryID']][$pr['timediscountID']][$pr['groupID']]=true;
			}
		}
	}
}

// Output ---------------

// Title of page
echo "<h1>".ct('S_ADMIN_TOOL_PRICES_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_PRICES_INTRO')."</p>\n";

// Create form
$form = new CTform(ct_pageurl('adminToolPrices'), 'post');
$form->width='100%';
$form->align='center';
$form->formname='prices';
$form->return2tab=true; // allow return key...
$form->warningmessage=true;

if (ctconf_get('demomode')) $form->demomode=true;

$form->add_crosstable($pricecategory,$timediscount,$group,$price,$readonly);
$form->add_submit('cmd_prices_update', ct('S_ADMIN_TOOL_PRICES_SAVECMD'));
$form->show();

?>
