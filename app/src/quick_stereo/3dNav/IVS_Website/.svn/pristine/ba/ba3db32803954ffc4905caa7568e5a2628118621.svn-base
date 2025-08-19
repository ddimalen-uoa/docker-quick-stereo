<?php
#
# PAGE:		adminParticipantsBrowse
# DESC:		Browse all participants of conference
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminUsers'));
global $http, $session;

$session->set_besturl(ct('S_ADMIN_PARTICIPANTS_BROWSE_QUICK'));

echo "<H1>".ct('S_ADMIN_PARTICIPANTS_BROWSE_CMD')."</H1>\n";

$participation = new CTParticipation;

$page=$http['page'];

$groups=array_merge (array(array(0,ct('S_ADMIN_TOOL_ALLPARTICIPANTS'))), ct_list_groups());
$events=array_merge (array(array(0,ct('S_ADMIN_TOOL_ALLPARTICIPANTS'))), ct_list_events());
$discounts=array_merge (array(array(0,ct('S_ADMIN_TOOL_ALLPARTICIPANTS'))), ct_list_timediscounts());


$query="select participants.personID from participants, persons";


// Search options...
if (isset($http['form_event']) && $http['form_event']){
	$query.=", participants2events";
}

if (isset($http['form_discounts']) && $http['form_discounts']){
	$query.=", timediscounts";
}

$where=" where participants.personID=persons.ID";
if (isset($http['form_event']) && $http['form_event']){
	$where.=" and participants2events.eventID= '".$http['form_event']."' and participants.personID=participants2events.personID and participants2events.number>0";
}
if (isset($http['form_discounts']) && $http['form_discounts']){
	$where.=" and timediscounts.ID='".$http['form_discounts']."' and participants.regdate>=timediscounts.effectivefrom and participants.regdate<=timediscounts.effectiveuntil ";
}

if (isset($http['form_group']) && $http['form_group']!=0){
	$where.=" and participants.status='".$http['form_group']."'";
}
if (isset($http['form_payment']) && $http['form_payment']){
	$where.=" and participants.paymethod='".$http['form_payment']."'";
}
if (isset($http['form_notpaid']) && $http['form_notpaid']!="-"){
		if ($http['form_notpaid']>=0) {
			$where.=" and participants.regdate<'".ct_timestamp_2_datetime(ct_time()-($http['form_notpaid']*60*60*24))."' and total>payamount and total>0";
		} elseif ($http['form_notpaid']==-1) {
			$where.=" AND total<=payamount AND total>0";
		} elseif ($http['form_notpaid']==-2) {
			$where.=" AND total<payamount";
		}
}

if (!isset($http['form_deleted']) || $http['form_deleted']=='' || (isset($http['form_deleted']) && $http['form_deleted']=="no")) {
	$where .= " AND participants.deleted=0";
} elseif (isset($http['form_deleted']) && $http['form_deleted']=="only") {
	$where .= " AND participants.deleted>0";
}


// Order of query...
$order = ' order by persons.name asc, persons.firstname asc';

if (isset($http['listorder'])) {
	switch ($http['listorder']) {
	 case 'org':
		$order = ' order by persons.organisation asc, persons.name asc';
		break;
	 case 'id':
		$order = ' order by persons.ID asc';
		break;
	 case 'name':
		$order = ' order by persons.name asc, persons.firstname asc';
		break;
	case 'paymethod':
		$order = ' order by participants.paymethod asc';
		break;
	case 'payamount':
		$order = ' order by participants.payamount asc';
			break;
	    case 'country':
		$order = ' order by persons.country asc, persons.name asc';
		break;
	    case 'regdate':
		$order = ' order by participants.regdate, persons.name asc';
		break;
	    case 'total':
		$order = ' order by participants.total, persons.name asc';
		break;
	    case 'invoicedate':
		$order = ' order by participants.invoicedate, persons.name asc';
		break;
	    case 'paydate':
		$order = ' order by participants.paydate, persons.name asc';
		break;
	    case 'email':
		$order = ' order by persons.email asc';
		break;
	}

}

$query.=$where;
$query.=$order;

echo $query;

$r = $db->query($query);

echo "<p class=\"standard\">".ct('S_ADMIN_PARTICIPANTS_BROWSE_INTRO')."<br>\n";
echo "<B>".ct('S_ADMIN_PARTICIPANTS_BROWSE_SUM1')." ".$db->num_rows($r)." ".ct('S_ADMIN_PARTICIPANTS_BROWSE_SUM2')."</b></p>\n";


if (isset($http['filter']) && $http['filter'] == "show") {
	echo "<div width=100% class=\"whitebg\">\n";
	echo "&lt;&lt;&lt; <a class=\"bold10\" href=\"".ct_pageurl('adminParticipantsBrowse', array('filter'=>'hide', 'listorder'=>$http['listorder'], 'form_deleted'=> $http['form_deleted']))."\">".ct('S_ADMIN_FILTER_HIDE')."</a>\n";
	echo "</div>\n";
	$form1 = new CTform(ct_pageurl('adminParticipantsBrowse'), 'post');
	$form1->width='100%';
	$form1->align='center';
	$form1->add_hidden(array(array('filter','show'), array('listorder',$http['listorder']), array('form_deleted', $http['form_deleted'])));
    $form1->add_select(ct('S_ADMIN_TOOL_EVENTS_CMD'), 'form_event', 1, $events, array($http['form_event']), false);
    $form1->add_select(ct('S_PARTICIPATION_FORM_DISCOUNTS'), 'form_discounts', 1, $discounts, array($http['form_discounts']), false);
    $form1->add_select(ct('S_PARTICIPATION_FORM_GROUPS'), 'form_group', 1, $groups, array($http['form_group']), false);
    $form1->add_select(ct('S_ADMIN_TOOL_PAYMENT_CMD'), 'form_payment', 1, array(array('0',ct('S_ADMIN_TOOL_ALLPARTICIPANTS')),
                        array('transfer',ct('S_PARTICIPATE_PAYMENT_TRANSFER')),
                        array('cash',ct('S_PARTICIPATE_PAYMENT_CASH')),
                        array('cc',ct('S_PARTICIPATE_PAYMENT_CC')),
                        array('free',ct('S_PARTICIPATE_PAYMENT_FREE'))),
                        array($http['form_payment']), false);
	$form1->add_select(ct('S_ADMIN_PARTICIPANTS_PAYMENT'), 'form_notpaid', 1,
		array(array('-',ct('S_ADMIN_TOOL_ALLPARTICIPANTS')),
		array('0',ct('S_ADMIN_PARTICIPANTS_PAYMENT_OPEN')),
		array('7',ct('S_ADMIN_PARTICIPANTS_PAYMENT_OPENFOR',array('7',ct_timestamp_2_datetime(ct_time()-7*24*60*60)))),
		array('14',ct('S_ADMIN_PARTICIPANTS_PAYMENT_OPENFOR',array('14',ct_timestamp_2_datetime(ct_time()-14*24*60*60)))),
		array('21',ct('S_ADMIN_PARTICIPANTS_PAYMENT_OPENFOR',array('21',ct_timestamp_2_datetime(ct_time()-21*24*60*60)))),
		array('30',ct('S_ADMIN_PARTICIPANTS_PAYMENT_OPENFOR',array('30',ct_timestamp_2_datetime(ct_time()-30*24*60*60)))),
		array('60',ct('S_ADMIN_PARTICIPANTS_PAYMENT_OPENFOR',array('60',ct_timestamp_2_datetime(ct_time()-60*24*60*60)))),
		array('-1',ct('S_ADMIN_PARTICIPANTS_PAYMENT_RECEIVED')),
		array('-2',ct('S_ADMIN_PARTICIPANTS_PAYMENT_OVERPAID'))),
		array($http['form_notpaid']), false);

	$form1->add_select(ct('S_ADMIN_PARTICIPANTS_BROWSE_DELETED'), 'form_deleted', 1,
		array(
			array('no',ct('S_ADMIN_PARTICIPANTS_BROWSE_HIDEDELETED')),
			array('yes',ct('S_ADMIN_PARTICIPANTS_BROWSE_SHOWDELETED')),
			array('only',ct('S_ADMIN_PARTICIPANTS_BROWSE_ONLYDELETED'))),
			array($http['form_deleted']), false);

    $form1->add_submit('cmd_search', ct('S_ADMIN_TOOL_SUBMIT'));
	$form1->show();
} else {
	echo "<div width=100% class=\"whitebg\">\n";
	echo "&gt;&gt;&gt; <a class=\"bold10\" href=\"".ct_pageurl('adminParticipantsBrowse', array('filter'=>'show', 'listorder'=>$http['listorder'], 'form_deleted'=> $http['form_deleted'] ))."\">".ct('S_ADMIN_FILTER_SHOW')."</a><p>\n";
	echo "</div>\n";
}

if (($r >= 0) && ($db->num_rows($r) > 0)) {
	$participation= new CTParticipation;
	$feetotal = 0;
	$paidtotal = 0;
	$eventqty = 0;

	echo "<table width=\"100%\" cellspacing=1 cellpadding=2 border=0>\n";
	echo "<tr class=\"listheader\">\n";
	echo "<td width=\"3%\" align=\"right\"><span class=\"listheader_label\">".ct('S_USER_COUNT')."</span></td>\n";
	echo "<td width=\"3%\" align=\"right\"><a href=\"".ct_pageurl($page,array('listorder' => 'id', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_ID')."</a></td>\n";
	echo "<td width=\"32%\"><a href=\"".ct_pageurl($page,array('listorder' => 'name', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_FULLNAME')."</a><br>\n";
	echo "<a class=\"normal8\" href=\"".ct_pageurl($page,array('listorder' => 'email', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_EMAIL')."</a></td>\n";
	echo "<td width=\"27%\"><a href=\"".ct_pageurl($page,array('listorder' => 'org', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_ORGANISATION')."</a><br>";
	echo "<a class=\"normal8\" href=\"".ct_pageurl($page,array('listorder' => 'country', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_USER_COUNTRY')."</a></td>\n";
	echo "<td align=middle width=\"5%\"><a href=\"".ct_pageurl($page,array('listorder' => 'paymethod', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_PARTICIPATE_PAYMENT_METHOD')."</a><BR>\n";
	echo "<a class=\"normal8\" href=\"".ct_pageurl($page,array('listorder' => 'regdate', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_PARTICIPATE_REGDATE_SHORT')."</a></td>\n";
	echo "<td width=\"6%\" align=\"right\"><a href=\"".ct_pageurl($page,array('listorder' => 'total', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_PARTICIPATE_TOTALFEE')."</a><BR>\n";
	echo "<a class=\"normal8\" href=\"".ct_pageurl($page,array('listorder' => 'invoicedate', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_PARTICIPATE_INVOICEDATE_SHORT')."</a></td>\n";
	echo "<td width=\"6%\" align=\"right\"><a href=\"".ct_pageurl($page,array('listorder' => 'payamount', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_PARTICIPATE_PAYAMOUNT')."</a><BR>\n";
	echo "<a class=\"normal8\" href=\"".ct_pageurl($page,array('listorder' => 'paydate', 'form_deleted'=>$http['form_deleted'], 'form_group'=>$http['form_group'],'form_event'=>$http['form_event'],'form_discounts'=>$http['form_discounts'],'form_payment'=>$http['form_payment'],'filter'=>$http['filter']))."\" class=\"listheader_label\">".ct('S_PARTICIPATE_PAYDATE_SHORT')."</a></td>\n";
	# is the list filtered by event type? If yes, sum events...
	if ($http['form_event']=="0" || $http['form_event']=="" ) {
		echo "<td width=\"5%\" align=\"right\"><span class=\"listheader_label\">".ct('S_PARTICIPATE_PRINT')."</span></td>\n";
	} else {
		echo "<td width=\"5%\" align=\"right\"><span class=\"listheader_label\">".ct('S_PARTICIPATE_EVENTNUMBER')."</span><BR><span class=\"normal8\">".ct('S_PARTICIPATE_EVENTFEE')."</span></td>\n";
	}
    echo "<td width=\"3%\" align=\"right\"><span class=\"listheader_label\">".ct('S_ADMIN_USERS_ACTIONS')."</span></td>\n";
	echo "</tr>\n";
	for ($i=0; $i < $db->num_rows($r); $i++) {
		$row= $db->fetch($r);
		$participation->load_by_id($row['personID']);
		$participation->show_row(($i%2?"oddrow":"evenrow"),$row['personID'],$i,$http['form_event'],$page);
		$feetotal+=$participation->pdata['total'];
		$paidtotal+=$participation->pdata['payamount'];
		if (isset($http['form_event']) && $http['form_event'])
			$eventqty+=$participation->eventdata[$http['form_event']];
	}
	echo "<tr class=\"darkbg\">\n";
	echo "<td colspan=\"4\"><span class=\"form_label\">".ct('S_ADMIN_PARTICIPANTS_BROWSE_SUM1')." $i ".ct('S_ADMIN_PARTICIPANTS_BROWSE_SUM2')."</span></td>\n";
	echo "<td align=right colspan=\"2\"><span class=\"form_label\">".ct_currency_format($feetotal, false, false)."</span></td>\n";
	# is the list filtered by event type? If yes, sum events...
	if ($http['form_event']=="0" || $http['form_event']=="" ) {
		echo "<td align=left colspan=\"3\"><span class=\"form_label\">&nbsp;".ct_currency_format($paidtotal, false, false)."</span></td>\n";
	} else {
		echo "<td align=left colspan=\"1\"><span class=\"form_label\">&nbsp;".ct_currency_format($paidtotal, false, false)."</span></td>\n";
		echo "<td align=\"right\"><span class=\"form_label\">".$eventqty."</span></td>\n";
		echo "<td><span class=\"form_label\">&nbsp;</span></td>\n";
	}
	echo "</tr>\n";
	echo "</table>\n";
}

?>