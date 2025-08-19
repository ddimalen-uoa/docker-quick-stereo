<?php
#
# PAGE:		adminPapersBrowse
# DESC:		Browse all papers/contributions
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirechair();
ct_pagepath(array('index','adminPapers'));
$session->set_besturl(ct('S_ADMIN_PAPERS_BROWSE_QUICK'));

ct_load_lib('papers.lib');
$session->del('db_contributiontypes');


echo "<h1>".ct('S_ADMIN_PAPERS_BROWSE_CMD')."</h1>\n";
echo "<p class=\"standard\">".ct('S_ADMIN_PAPERS_BROWSE_INTRO')."</p>\n";

$tables = 'papers ';
$where = ' where persons.id = papers.personID';

// Show withdrawn papers?
if (in_http('form_withdrawn', 'yes')) {
	# Nothing, show all
} elseif (in_http('form_withdrawn', 'only')) {
	$where .= " AND papers.withdrawn=1 ";
} else {
	$where .= " AND papers.withdrawn=0 ";
}

// From Filter to Query...
if (isset($http['form_tracks']) && $http['form_tracks']!=0){
	$where.=" AND papers.contributiontypeID='".$http['form_tracks']."'";
}
#if (isset($http['form_status']) && $http['form_status']=='a' ){
#	$where.=" AND (papers.acceptstatus>0 || papers.acceptstatus<-1) ";
#} elseif (isset($http['form_status']) && $http['form_status']!="-"){
#	$where.=" AND papers.acceptstatus='".$http['form_status']."'";
#}
if (isset($http['form_topic']) && $http['form_topic']!="-"){
	$tables.=", topics2papers ";
	$where.=" AND topics2papers.paperID=papers.ID and topics2papers.topicID='".$http['form_topic']."'";
}


$order = ' ORDER BY papers.id asc';
if (isset($http['listorder'])) {
	switch ($http['listorder']) {
	 case 'author':
		$order = ' ORDER BY persons.name, persons.firstname, papers.author asc';
		break;
	 case 'title':
		$order = ' ORDER BY title asc';
		break;
	 case 'id':
		$order = ' ORDER BY id asc';
		break;
	 case 'lastupload':
		$order = ' ORDER BY lastupload desc';
		break;
	}
}


$form1 = new CTform('adminPapersBrowse', 'get');
$form1->width='100%';
$form1->align='center';
$form1->add_hidden(hash2aa(ct_http_array(array('version'=>'','form_tracks'=>'','form_topics'=>'','form_withdrawn'=>''))));
$tracks = ct_list_contributiontypes();
if (count($tracks)>1) {
	$tracks=array_merge (array(array(0,ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_ALLTYPES'))), $tracks);
	$form1->add_select(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_CONTRIBUTIONTYPE'), 'form_tracks', 1, $tracks, array($http['form_tracks']));
}
$topics=ct_list_topics();
if (count($topics)>1) {
	$selecttopics=array_merge (array(array("-",ct('S_ADMIN_FILTER_ALLTOPICS'))), $topics);
	$form1->add_select(ct('S_ADMIN_FILTER_TOPICS'), 'form_topic', 1, $selecttopics, array($http['form_topic']));
}
$form1->add_select(ct('S_ADMIN_PAPERS_BROWSE_WITHDRAWN'), 'form_withdrawn', 1,
					array(array('no',ct('S_ADMIN_PAPERS_BROWSE_HIDEWITHDRAWN')),
					array('yes',ct('S_ADMIN_PAPERS_BROWSE_SHOWWITHDRAWN')),
					array('only',ct('S_ADMIN_PAPERS_BROWSE_ONLYWITHDRAWN'))),
			array($http['form_withdrawn']));
$form1->add_submit('cmd_search', ct('S_ADMIN_TOOL_SUBMIT'));

$form1->show();


$query="select papers.ID as paperID from $tables, persons $where $order";
#echo $query;
$r = $db->query($query);
if (($r >= 0) && ($db->num_rows($r) > 0)) {
	ctadm_listpapers($r, 'adminPapersBrowse');  // -> admin.lib
}

?>