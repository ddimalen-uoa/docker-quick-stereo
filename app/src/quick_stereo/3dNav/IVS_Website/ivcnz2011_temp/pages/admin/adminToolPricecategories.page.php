<?php
//
// PAGE:		adminToolPricecategories
// DESC:		Define price categories for different conference events / products
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminTool'));

ct_load_class('CTForm');
ct_load_lib('participation.lib');

// get current number of pricecategories
$count=0;
$r = $db->query("select count(*) as count from pricecategories");
if ($r && ($db->num_rows($r) == 1)) {
	$row = $db->fetch($r); $count = $row['count'];
}

if (isset($http['cmd_pricecategories_save']) && $http['form_title']!="") {
	$db->replace_into('pricecategories', array('ID'=>$http['form_id'], 'title'=>$http['form_title'], 'title2'=>$http['form_title2'], 'title3'=>$http['form_title3'], 'title4'=>$http['form_title4']));
	$session->put_infobox(ct('S_INFO_SAVE'), ct('S_INFO_SAVE_SUCCESS'));
	ct_redirect(ct_pageurl($http['page']));

} elseif (isset($http['cmd_pricecategories_create']) && $http['form_title']!="") {
	$db->insert_into('pricecategories', array('ID'=>0, 'title'=>$http['form_title'], 'title2'=>$http['form_title2'], 'title3'=>$http['form_title3'], 'title4'=>$http['form_title4']));
	$session->put_infobox(ct('S_INFO_SAVE'), ct('S_INFO_SAVE_SUCCESS'));
	ct_redirect(ct_pageurl($http['page']));

} elseif (isset($http['cmd_pricecategories_delete']) && $http['form_id']!="") {
	$r = $db->select('events','ID','pricecategory="'.$http['form_id'].'"');
	if ($r && ($db->num_rows($r) > 0))
		ct_errorbox(ct('S_ERROR_EDITPARTICIPATIONDATA'),ct('S_ERROR_EDITPARTICIPATIONDATA_NODELETE'));
    else {
	    $db->delete("pricecategories","id='".$http['form_id']."'");
		$session->put_warningbox(ct('S_INFO_DELETE'), ct('S_INFO_DELETE_SUCCESS'));
		ct_redirect(ct_pageurl($http['page']));
    }

} elseif (isset($http['cmd_pricecategories_cancel'])) {
	ct_redirect(ct_pageurl($http['page']));
}

ct_http_trim(array('title','title2','title3','title4'));

// Show create/edit box?
if ( ( ( in_http('cmd_pricecategories_edit') || in_http('cmd_pricecategories_dblclick','true') || in_http('cmd_pricecategories_copy')) &&
		isset($http['form_id']) && $http['form_id'] > 0 ) ||
		 in_http('cmd_pricecategories_new') ||
		 count($form_errors)>0 ) {

	// Error...
	if (count($form_errors)>0) {
		$pricecategory=ct_form_array_convert($http);
		$pricecategory['ID'] = $http['form_id']; // Edit mode...

	// Edit entry
	} elseif ((in_http('cmd_pricecategories_edit') || in_http('cmd_pricecategories_dblclick','true')) && isset($http['form_id']) && $http['form_id'] != "") {
		$r = $db->query("select * from pricecategories where id='".$http['form_id']."'");
		if ($r && ($db->num_rows($r) > 0)) {
			$pricecategory = $db->fetch($r);
		}
	// new entry
	} elseif (isset($http['cmd_pricecategories_new'])) {
		// Init values...
		$pricecategory['ID'] = 0;
		$pricecategory['title']=$pricecategory['title2']=$pricecategory['title3']=$pricecategory['title4']="";
	}

	$form = new CTForm(ct_pageurl('adminToolPricecategories'), 'post', $form_errors);
	$form->width='85%';
	$form->align='center';
	$form->formname='pricecategories';
	$form->warningmessage=true;
	if (ctconf_get('demomode')) $form->demomode=true;

	if (isset($pricecategory['ID']) && $pricecategory['ID'] > 0) {
		echo "<h2>".ct("S_ADMIN_TOOL_PRICECATEGORIES_EDITCMD")."</h2>\n";
		echo "<p class=\"fontnormal font10\">".ct('S_ADMIN_TOOL_PRICECATEGORIES_EDITINTRO')."</p>\n";

		$form->add_label(ct('S_ID'),$http['form_id']);
		$form->add_hidden(array(array('form_id', $http['form_id'])));

		$form->add_submit('cmd_pricecategories_save', ct('S_ADMIN_TOOL_PRICECATEGORIES_SAVECMD'));
		$form->add_submit('cmd_pricecategories_cancel', ct('S_BUTTON_CANCEL'));
	// new entry
	} else {

		echo "<h2>".ct("S_ADMIN_TOOL_PRICECATEGORIES_CREATECMD")."</h2>\n";
		echo "<p class=\"fontnormal font10\">".ct('S_ADMIN_TOOL_PRICECATEGORIES_NEWINTRO')."</p>\n";

		$form->add_submit('cmd_pricecategories_create', ct('S_BUTTON_CREATE'));
		$form->add_submit('cmd_pricecategories_cancel', ct('S_BUTTON_CANCEL'));
	}

	$form->add_text(ct('S_ADMIN_TOOL_PRICECATEGORIES_FORM_PRICECATEGORY'),'form_title', $pricecategory['title'], 50, 255);
	$form->show();


// Show select box
} else {

	echo "<h1>".ct('S_ADMIN_TOOL_PRICECATEGORIES_TITLE')."</h1>\n";
	echo "<p class=\"fontnormal font10\">".ct('S_ADMIN_TOOL_PRICECATEGORIES_INTRO')."</p>\n";

	if ($count==0) {
		echo "<H4>".ct('S_ADMIN_TOOL_PRICECATEGORIES_MISSING')."</h4>";
	} else {
		$form1 = new CTform(ct_pageurl($http['page']), 'get');
		$form1->width='70%';
		$form1->align='center';
		$form1->formname='pricecategories';
		$form1->add_select(ct('S_PARTICIPATION_FORM_PRICECATEGORIES'), 'form_id',  min(18, max(5,$count+1)),  ct_list_pricecategories(), array(), false);
		$form1->add_submit('cmd_pricecategories_edit', ct('S_ADMIN_TOOL_PRICECATEGORIES_EDITCMD'));
		$form1->add_submit('cmd_pricecategories_delete', ct('S_ADMIN_TOOL_PRICECATEGORIES_DELETECMD'));
		$form1->show();
	}

	$form2 = new CTform(ct_pageurl($http['page']), 'get');
	$form2->width='70%';
	$form2->align='center';
	$form2->add_spacer();
	#$form2->add_submit('cmd_pricecategories_sort', ct('S_BUTTON_SORT'));
	$form2->add_submit('cmd_pricecategories_new', ct('S_ADMIN_TOOL_PRICECATEGORIES_CREATECMD'));
	$form2->show();


}

?>
