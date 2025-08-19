<?php
#
# PAGE:		adminToolVats
# DESC: 	Show, enter and edit VAT rates (Mehrwehrtsteuer)
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_load_lib('participation.lib');
ct_pagepath(array('index','adminTool'));

if (isset($http[cmd_vats_save])) {
	$r = $db->select('events','ID','vat="'.$http['form_ID'].'"');
	if ($r && ($db->num_rows($r) > 0)) {
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NOEDIT'));
	} else {
		$db->replace_into('vats', array('ID'=>$http['form_ID'], 'percentage'=>ct_number_unformat($http[form_percentage])));
	}
}
if (isset($http['cmd_vats_create']) && $http['form_percentage']!='') {
	$db->insert_into('vats', array('ID'=>0, 'percentage'=>ct_number_unformat($http['form_percentage'])));
}
if (isset($http['cmd_vats_delete']) && isset($http['form_ID']) && $http['form_ID']>0) {
	$r = $db->select('events','ID','vat="'.$http['form_ID'].'"');
	if ($http['form_ID']==1) {
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_DELETE_PERMANENTDATA_REQUIRED'));
	} elseif ($r && ($db->num_rows($r) > 0)) {
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NODELETE'));
	} else {
    	$db->delete("vats","id='".$http['form_ID']."'");
		ct_warningbox(ct('S_INFO_DELETE'), ct('S_INFO_DELETE_SUCCESS'));
    }
}

if (isset($http['cmd_vats_edit'])) {
	$r = $db->select('events','ID','vat="'.$http['form_ID'].'"');
	if ($r && ($db->num_rows($r) > 0))
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NOEDIT'));
	elseif (isset($http['form_ID']) && ($http['form_ID'] == 1))
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NOEDIT'));
}

echo "<h1>".ct('S_ADMIN_TOOL_VATS_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_VATS_INTRO')."</p>\n";

$form1 = new CTform(ct_pageurl('adminToolVats'), 'post');
$form1->width='400';
$form1->align='center';
$form1->add_select(ct('S_PARTICIPATION_FORM_VATS'), 'form_ID', 6, ct_list_vats(), array(), false);
$form1->add_submit('cmd_vats_edit', ct('S_ADMIN_TOOL_VATS_EDITCMD'));
$form1->add_submit('cmd_vats_delete', ct('S_ADMIN_TOOL_VATS_DELETECMD'));
$form1->show();

$form2 = new CTForm(ct_pageurl('adminToolVats'), 'post');
$form2->width='60%';
$form2->align='center';
if ($ctconf['demomode']===true) $form2->demomode=true;

ct_vspacer('10');

if (isset($http[cmd_vats_edit]) && isset($http['form_ID']) && ($http['form_ID'] > 1)) {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_VATS_EDITINTRO')."</p>\n";
	$form2->add_hidden(array(array('form_ID', $http['form_ID'])));
	$r = $db->query('select percentage from vats where id='.$http['form_ID']);
	if ($r && ($db->num_rows($r) > 0)) {
		$vat = $db->fetch($r);
	}
	$form2->add_text(ct('S_ADMIN_TOOL_VATS_FORM_VAT'),'form_percentage', ct_number_format($vat[percentage]), 5, 5);
	$form2->add_submit('cmd_vats_save', ct('S_ADMIN_TOOL_VATS_SAVECMD'));
} else {
	echo "<p class=\"standard\">".ct('S_ADMIN_TOOL_VATS_NEWINTRO')."</p>\n";
	$form2->add_text(ct('S_ADMIN_TOOL_VATS_FORM_VAT'),'form_percentage', '', 5, 5);
	$form2->add_submit('cmd_vats_create', ct('S_ADMIN_TOOL_VATS_CREATECMD'));
}
$form2->show();

ct_vspacer();

?>