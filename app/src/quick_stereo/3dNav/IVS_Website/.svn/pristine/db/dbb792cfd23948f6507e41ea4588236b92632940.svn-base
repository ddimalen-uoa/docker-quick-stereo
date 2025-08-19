<?php
#
# PAGE:		pc2topics
# DESC:		Assign topics to PC members
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

$person = new CTPerson;

if (isset($http['form_id']) && ($user->is_admin() === true)) {
	ct_pagepath(array('index','adminUsersPC'));
	$person->load_by_id($http['form_id']);
} elseif ($user->is_pc() ===true || $user->is_admin() === true) {
	ct_pagepath(array('index'));
	$person->load_by_id($user->get('ID'));
} else {
	ct_redirect($session->get_besturl());
}

# Save data...
if (isset($http['cmd_pc2topics_save'])) {
	if (isset($http['form_topics']) && is_array($http['form_topics']) && (sizeof($http['form_topics']) > 0)) {
		$person->save_topics($http['form_topics']);
		$session->put_infobox(ct('S_INFO_SAVE'), ct('S_INFO_SAVE_SUCCESS'));
		ct_redirect($session->get_besturl());
	} else { # Nothing selected: Try again!
		$person->save_topics(array());
		ct_errorbox(ct('S_ERROR_PC2TOPICS'), ct('S_ERROR_PC2TOPICS_MISSINGINFO'));
		#ct_redirect(ct_pageurl('pc2topics',array('form_id'=>$person->get('ID'))));
	}
}
# User pressed cancel...
if (isset($http['cmd_pc2topics_cancel']))  {
	ct_redirect($session->get_besturl());
}

echo "<h1>".ct('S_PC2TOPICS_TITLE')."</h1>\n";
echo "<p class=\"standard\">".ct('S_PC2TOPICS_INTRO')."</p>\n";


$person->show_shortinfo('85%');

$topics=$person->list_topics();

if (count($topics)==0) {
	echo "<p class=\"fontbold font12\">".ct('S_PC2TOPICS_NOTOPICS')."</p>\n";
} else {
	$selectedtopics=$person->get_topicIDs();
	foreach ($topics as $t) {
		$topicboxes[]=array('form_topics[]',$t[0],$t[1],in_array($t[0],$selectedtopics),false);
	}

	$form = new CTform(ct_pageurl('pc2topics'), 'post');
	$form->width='85%';
	$form->align='center';
	$form->add_hidden(array(array('form_id', $person->get('ID'))));

	// $form->add_select(ct('S_PAPER_FORM_TOPICS'), 'form_topics[]', 10, $person->list_topics(), $person->get_topicIDs(), true, "<br>".ct('S_PAPER_FORM_TOPICSHINT'));
	$form->add_check("* ".ct('S_PAPER_FORM_TOPICS'), array_merge($topicboxes,array(array('','',ct('S_PAPER_FORM_TOPICSNEWHINT')))));

	$form->add_submit('cmd_pc2topics_save', ct('S_PC2TOPICS_FORM_SAVECMD'));
	$form->add_submit('cmd_pc2topics_cancel', ct('S_PC2TOPICS_FORM_CANCELCMD'));
	$form->show();
}

?>
