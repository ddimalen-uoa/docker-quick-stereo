<?php
#
# PAGE:		adminToolEventorder
# DESC:		Set the display order of the events/products within their categories.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminTool'));

if (isset($http['cmd_eventorder_save'])) {
	$events = ct_show_events('');
	foreach ($events as $e){
		$db->update('events','ID='.$e['ID'],array('ID'=>$e['ID'],'title'=>$e['title'],'seqorder'=>$http['form_'.$e['ID']], 'info'=>$e['info'], 'short'=> $e['short'], 'eventdate'=> $e['eventdate'], 'eventlocation'=>$e['eventlocation'], 'minnumber'=>$e['minnumber'],'maxnumber'=> $e['maxnumber'], 'defaultnumber'=>$e['defaultnumber'], 'pricecategory'=>$e['pricecategory'], 'eventtype'=>$e['eventtype'], 'vat'=>$e['vat'], 'disable'=>$e['disable']));
	}
	ct_infobox(ct('S_INFO_SAVE'),ct('S_INFO_SAVE_SUCCESS'));
}

$form = new CTform(ct_pageurl('adminToolEventorder'), 'post');
$form->width='80%';
$form->align='center';
if ($ctconf['demomode']===true) $form->demomode=true;

$etypes = ct_show_eventtypes();
foreach ($etypes as $etype){
	$form->add_separator($etype['title']);
	$events = ct_show_events($etype['ID']);
		foreach ($events as $e){
		$form->add_text($e['title'],'form_'.$e['ID'],$e['seqorder'], 2, 2);
		}
}

$form->add_submit('cmd_eventorder_save', ct('S_ADMIN_TOOL_EVENTORDER_SAVECMD'));
echo "<h1>".ct('S_ADMIN_TOOL_EVENTORDER_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_EVENTORDER_INTRO')."</p>\n";

$form->show();

?>

