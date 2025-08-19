<?php
#
# PAGE:		adminToolEvents
# DESC:		Define events and products of the conference
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminTool'));

if (isset($http['cmd_events_save']) && in_http('form_event') && $http['form_event']) {
	$db->replace_into('events', array('ID'=>$http['form_id'], 'title'=>$http['form_event'], 'info'=>$http['form_info'], 'short'=>$http['form_short'], 'eventdate'=>$http['form_date'], 'eventlocation'=>$http['form_location'], 'minnumber'=>$http['form_min'], 'maxnumber'=>$http['form_max'], 'defaultnumber'=>$http['form_default'], 'pricecategory'=>$http['form_pricecategory'], 'eventtype'=>$http['form_type'], 'vat'=>$http['form_vat'], 'disable'=>$http['form_disable'], 'style'=>$http['form_style'], 'seqorder'=>$http['form_seqorder']));
}
if (isset($http['cmd_events_create']) && in_http('form_event') && $http['form_event']) {
	$db->insert_into('events', array('ID'=>0, 'title'=>$http['form_event'], 'info'=>$http['form_info'], 'short'=>$http['form_short'], 'eventdate'=>$http['form_date'], 'eventlocation'=>$http['form_location'], 'minnumber'=>$http['form_min'], 'maxnumber'=>$http['form_max'], 'defaultnumber'=>$http['form_default'], 'pricecategory'=>$http['form_pricecategory'], 'eventtype'=>$http['form_type'], 'vat'=>$http['form_vat'], 'disable'=>$http['form_disable'], 'style'=>$http['form_style']));
}
// Delete event.
if (isset($http['cmd_events_delete']) && isset($http['form_event']) && $http['form_event']!='') {
	if (ct_participants_exist($http['form_event']))
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NODELETE'));
    else {
    	$db->delete("events","ID='".$http['form_event']."'");
    	$db->delete("participants2events","deleted=1 and eventID='".$http['form_event']."'");
    }
}

$number = array();
for ($i=0;$i < 11; $i++) {
	$number[] = array ($i,$i);
}

echo "<h1>".ct('S_ADMIN_TOOL_EVENTS_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_EVENTS_INTRO')."</p>\n";

if (count(ct_list_events(true))>0) {
	$form1 = new CTform(ct_pageurl('adminToolEvents'), 'post');
	$form1->width='90%';
	$form1->align='center';
	$form1->add_select(ct('S_ADMIN_TOOL_EVENTS_CMD'), 'form_event', 6, ct_list_events(true), array(), false);
	$form1->add_submit('cmd_events_edit', ct('S_ADMIN_TOOL_EVENTS_EDITCMD'));
	$form1->add_submit('cmd_events_delete', ct('S_ADMIN_TOOL_EVENTS_DELETECMD'));
	if ($ctconf['demomode']===true) $form1->demomode=true;
	$form1->show();
} else {
	echo "<H4 class='yellowbg'>".ct('S_ADMIN_TOOL_EVENTS_MISSING')."</h4>";
}

$form2 = new CTform(ct_pageurl('adminToolEvents'), 'post');
$form2->width='100%';
$form2->align='center';
if ($ctconf['demomode']===true) $form2->demomode=true;

if (isset($http['cmd_events_edit']) && isset($http['form_event']) && ($http['form_event'] != "")) {
        echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_EVENTS_EDITINTRO')."</p>\n";
        $form2->add_hidden(array(array('form_id', $http['form_event'])));
		$r = $db->query("select * from events where id='".$http['form_event']."'");
		if ($r && ($db->num_rows($r) > 0)) {
			$event = $db->fetch($r);
		}
	$form2->add_hidden(array(array('form_seqorder',$event['seqorder'])));
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_TYPE'), 'form_type', 1, ct_list_eventtypes(), array($event['eventtype']), false);
	$form2->add_spacer();
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTS_NAME'),'form_event', $event['title'], 50, 255);
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTS_SHORT'),'form_short',$event['short'], 10, 32);
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTS_DATE'),'form_date', $event['eventdate'], 50, 255);
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTS_LOCATION'),'form_location', $event['eventlocation'], 50, 255);
	$form2->add_textarea(ct('S_ADMIN_TOOL_EVENTS_INFO'),'form_info', $event['info'], 40,5);
	$form2->add_spacer();
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_MIN'),'form_min', 1,$number, array($event['minnumber']), false);
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_MAX'),'form_max', 1,$number, array($event['maxnumber']), false);
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_DEFAULT'),'form_default', 1,$number,array($event['defaultnumber']),false);
	$form2->add_spacer();
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_PRICECAT'),'form_pricecategory', 1, array_merge(array(array('',ct('S_PARTICIPATE_EVENT_PLEASESELECT'))),ct_list_pricecategories()), array($event['pricecategory']), false);
	if (isset($ctconf['participation/vat']) && $ctconf['participation/vat'])
		$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_VAT'), 'form_vat', 1, ct_list_vats(), array($event['vat']), false);
	else
		$form2->add_hidden(array(array('form_vat','1')));
	$form2->add_spacer();
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_DEACTIVATE'),'form_disable', 1, array(array('true', ct('S_YES')), array('false', ct('S_NO'))), array($event['disable']), false, ct('S_YES').' = '.ct('S_ADMIN_TOOL_EVENTS_DEACTIVATE_DESC'));
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_STYLE'), 'form_style', 1, array(array('normal',ct('S_ADMIN_TOOL_EVENTS_STYLE_NORMAL')), array('hidden',ct('S_ADMIN_TOOL_EVENTS_STYLE_HIDDEN'))), array($event['style']));
	$form2->add_submit('cmd_events_save', ct('S_ADMIN_TOOL_EVENTS_SAVECMD'));
}

// new event:

else {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_EVENTS_NEWINTRO')."</p>\n";
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_TYPE'), 'form_type', 1, ct_list_eventtypes(), array(), false);
	$form2->add_spacer();
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTS_NAME'),'form_event','', 50, 255);
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTS_SHORT'),'form_short','', 10, 32);
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTS_DATE'),'form_date','', 50, 255);
	$form2->add_text(ct('S_ADMIN_TOOL_EVENTS_LOCATION'),'form_location','', 50, 255);
	$form2->add_textarea(ct('S_ADMIN_TOOL_EVENTS_INFO'),'form_info','', 50,5);
	$form2->add_spacer();
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_MIN'),'form_min', 1,$number, array('0'), false);
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_MAX'),'form_max', 1,$number, array('1'), false);
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_DEFAULT'),'form_default', 1,$number,array('0'),false);
	$form2->add_spacer();
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_PRICECAT'),'form_pricecategory', 1, array_merge(array(array('',ct('S_PARTICIPATE_EVENT_PLEASESELECT'))),ct_list_pricecategories()), array(), false);
	if (isset($ctconf['participation/vat']) && $ctconf['participation/vat'])
		$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_VAT'), 'form_vat', 1, ct_list_vats(), array(), false);
	else
		$form2->add_hidden(array(array('form_vat','1')));
	$form2->add_spacer();
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_DEACTIVATE'),'form_disable', 1, array(array('true', ct('S_YES')), array('false', ct('S_NO'))), array('false'), false, ct('S_YES').' = '.ct('S_ADMIN_TOOL_EVENTS_DEACTIVATE_DESC'));
	$form2->add_select(ct('S_ADMIN_TOOL_EVENTS_STYLE'), 'form_style', 1, array(array('normal',ct('S_ADMIN_TOOL_EVENTS_STYLE_NORMAL')), array('hidden',ct('S_ADMIN_TOOL_EVENTS_STYLE_HIDDEN'))), array('normal'));
	$form2->add_submit('cmd_events_create', ct('S_ADMIN_TOOL_EVENTS_CREATECMD'));
}

$form2->show();

?>