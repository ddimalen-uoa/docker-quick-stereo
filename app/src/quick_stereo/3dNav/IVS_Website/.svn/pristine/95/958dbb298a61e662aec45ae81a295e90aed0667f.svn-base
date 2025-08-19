<?php
#
# PAGE:		adminToolDiscounts
# DESC:		Define different time discount rates (early bird, on site etc.)
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminTool'));

// date format
if($http['cmd_timediscount_save'] || $http['cmd_timediscount_create']){
	$http['form_effectivefrom'] = $http['form_effrom_year']."-".$http['form_effrom_month']."-".$http['form_effrom_day']." 00:00:00";
	$http['form_effectiveuntil'] =$http['form_efuntil_year']."-".$http['form_efuntil_month']."-".$http['form_efuntil_day']." 23:59:59";
}

if (isset($http['cmd_timediscount_save'])) {
	$db->replace_into('timediscounts', array('ID'=>$http['form_id'], 'title'=>$http['form_title'],'title2'=>$http['form_title2'],'title3'=>$http['form_title3'],'title4'=>$http['form_title4'],'effectivefrom'=>$http['form_effectivefrom'], 'effectiveuntil'=>$http['form_effectiveuntil']));
	ct_infobox(ct('S_INFO_SAVE'), ct('S_INFO_SAVE_SUCCESS'));
} elseif (isset($http['cmd_timediscount_create']) && $http['form_title']!="") {
	$db->insert_into('timediscounts', array('ID'=>0, 'title'=>$http['form_title'],'title2'=>$http['form_title2'],'title3'=>$http['form_title3'],'title4'=>$http['form_title4'],'effectivefrom'=>$http['form_effectivefrom'], 'effectiveuntil'=>$http['form_effectiveuntil']));
	ct_infobox(ct('S_INFO_SAVE'), ct('S_INFO_SAVE_SUCCESS'));
} elseif (isset($http['cmd_timediscount_delete'])) {
   	// Check if at least one entry is left
	$r = $db->select('timediscounts','*');
	if ($r && ($db->num_rows($r) > 1)) {
    	// Check if Time discount is already running...
    	$r = $db->select("timediscounts",'*',"id='".$http['form_timediscount']."'");
		if ($r && $db->num_rows($r)==1) {
			$row=$db->fetch($r);
			// Delete only if no participants are registered yet or timediscount starts in the future.
			if (!ct_participants_exist() || ct_datetime_2_timestamp($row['effectivefrom'])>ct_time()) {
		    	$db->delete("timediscounts","id='".$http['form_timediscount']."'");
				ct_infobox(ct('S_INFO_DELETE'), ct('S_INFO_DELETE_SUCCESS'));
			} else
				ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NODELETE'));
		}
	} else
		ct_errorbox(ct('S_ERROR_DELETE_PERMANENTDATA'),ct('S_ERROR_DELETE_PERMANENTDATA_KEEPONE'));
} elseif (ct_participants_exist()) {
	ct_warningbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_PARTICIPATS_EXISTS'));
}

echo "<h1>".ct('S_ADMIN_TOOL_DISCOUNTS_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_DISCOUNTS_INTRO')."</p>\n";

echo "<table align=center cellspacing=0 cellpadding=4 border=1>";
echo "<tr>";
$r = $db->query('select * from timediscounts order by effectivefrom');
$lastUntil = "";
$errorfirstfrom = false;
$errororder = false;
$errorlap = false;
$errorlastuntil = false;
for ($i = 0; $db->num_rows($r) > $i; $i++) {
	$errorfrom=false;
	$erroruntil=false;
	$row = $db->fetch($r);
	// check for consistency
	if ($i==0 && ct_datetime_2_timestamp($row['effectivefrom'])>time()) {$errorfirstfrom=true; $errorfrom=true;}
	if (ct_datetime_2_timestamp($row['effectiveuntil']) < ct_datetime_2_timestamp($row['effectivefrom'])) {$errororder=true; $erroruntil=true;}
	if ($i>0  && (ct_datetime_2_timestamp($lastUntil))+1!= ct_datetime_2_timestamp($row['effectivefrom'])) {$errorlap=true; $errorfrom=true;}
	if ($i==($db->num_rows($r)-1) && ct_datetime_2_timestamp($row['effectiveuntil'])<=time()) {$errorlastuntil=true; $erroruntil=true;}
	echo "<td align=center class=\"lightbg\">";
	echo "<span class=\"bold8\">".$row['title']."</span><br>";
	if ($errorfrom) { echo "<span class=\"negativebold10\">"; } else { echo "<span class=\"normal10\">"; };
	echo ct_date_format($row['effectivefrom'])."</span> - ";
	if ($erroruntil) { echo "<span class=\"negativebold10\">"; } else { echo "<span class=\"normal10\">"; };
	echo ct_date_format($row['effectiveuntil'])."</span>";
	echo "</td>\n";
	$lastUntil = $row['effectiveuntil'];
}
echo "</tr>";
echo "<tr>";
echo "<td align=center class=\"mediumbg\" colspan=\"".($i)."\">";
if ($errorfirstfrom) echo "<span class=\"negativebold10\">".ct('S_ADMIN_TOOL_DISCOUNTS_ERROR_FIRSTFROM')."</span><br>";
elseif ($errororder) echo "<span class=\"negativebold10\">".ct('S_ADMIN_TOOL_DISCOUNTS_ERROR_ORDER')."</span><br>";
elseif ($errorlap) echo "<span class=\"negativebold10\">".ct('S_ADMIN_TOOL_DISCOUNTS_ERROR_LAP')."</span><br>";
elseif ($errorlastuntil) echo "<span class=\"negativebold10\">".ct('S_ADMIN_TOOL_DISCOUNTS_ERROR_LASTUNTIL')."</span><br>";
else echo "<span class=\"positivebold10\">".ct('S_ADMIN_TOOL_DISCOUNTS_OK')."</span><br>";
echo "</td>";
echo "</tr>";
echo "</table><br>";


$form1 = new CTform(ct_pageurl('adminToolDiscounts'), 'post');
$form1->width='450';
$form1->align='center';
$form1->add_select(ct('S_PARTICIPATION_FORM_DISCOUNTS'), 'form_timediscount',6, ct_list_timediscounts(), array(), false);
$form1->add_submit('cmd_timediscount_edit', ct('S_ADMIN_TOOL_DISCOUNTS_EDITCMD'));
$form1->add_submit('cmd_timediscount_delete', ct('S_ADMIN_TOOL_DISCOUNTS_DELETECMD'));
if ($ctconf['demomode']===true) $form1->demomode=true;
$form1->show();
$form2 = new CTForm(ct_pageurl('adminToolDiscounts'), 'post');
$form2->width='450';
$form2->align='center';
if ($ctconf['demomode']===true) $form2->demomode=true;


if (isset($http['cmd_timediscount_edit']) && isset($http['form_timediscount']) && ($http['form_timediscount'] != "")) {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_DISCOUNTS_EDITINTRO')."</p>$errormsg\n";
	$form2->add_hidden(array(array('form_id', $http['form_timediscount'])));
	$r = $db->query("select title,effectivefrom,effectiveuntil from timediscounts where id='".$http['form_timediscount']."'");
	if ($r && ($db->num_rows($r) > 0)) {
		$timediscount = $db->fetch($r);
	}

	$effectivefrom=array();
	$effectivefrom = explode(" ", $timediscount['effectivefrom']);
	$selected_from=array();
	$selected_from = explode("-", $effectivefrom[0]);
	$effectiveuntil=array();
	$effectiveuntil = explode(" ", $timediscount['effectiveuntil']);
	$selected_until=array();
	$selected_until = explode("-", $effectiveuntil[0]);


	$form2->add_text(ct('S_ADMIN_TOOL_DISCOUNTS_FORM_DISCOUNT'),'form_title', $timediscount['title'], 50, 255);
	$form2->add_date(ct('S_ADMIN_TOOL_DISCOUNTS_FORM_EFFECTIVE_FROM'),'form_effrom','true',$selected_from);
	$form2->add_date(ct('S_ADMIN_TOOL_DISCOUNTS_FORM_EFFECTIVE_UNTIL'),'form_efuntil','true',$selected_until);
	$form2->add_submit('cmd_timediscount_save', ct('S_ADMIN_TOOL_DISCOUNTS_SAVECMD'));

}

else {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_DISCOUNTS_NEWINTRO')."</p>$errormsg\n";
	$form2->add_text(ct('S_ADMIN_TOOL_DISCOUNTS_FORM_TITLE'),'form_title', '', 50, 255);
	$form2->add_date(ct('S_ADMIN_TOOL_DISCOUNTS_FORM_EFFECTIVE_FROM'),'form_effrom','true',$selected_from);
	$form2->add_date(ct('S_ADMIN_TOOL_DISCOUNTS_FORM_EFFECTIVE_UNTIL'),'form_efuntil','true',$selected_until);
	$form2->add_submit('cmd_timediscount_create', ct('S_ADMIN_TOOL_DISCOUNTS_CREATECMD'));
}

$form2->show();
?>








