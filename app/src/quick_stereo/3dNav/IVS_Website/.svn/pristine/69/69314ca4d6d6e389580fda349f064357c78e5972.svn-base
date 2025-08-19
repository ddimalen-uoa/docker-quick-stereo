<?php
#
# PAGE:		frontdesk
# The frontdesk is to be used at the conference registration desk. This is the main page for
# the registration staff...
#

if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requirefrontdesk();
ct_load_lib('participation.lib');
ct_pagepath(array('index'));
$session->set_besturl(ct('S_FRONTDESK_CMD'));

echo "<H1><A HREF=\"".ct_pageurl('frontdesk')."\">".ct('S_FRONTDESK_CMD')."</A></H1>\n";

if ((isset($http['listLetter']) || isset($http['form_query']) || isset($http['isarrived']) ) && !isset($http['form_nonregistered'])) {
	$query="select participants.personID from participants, persons";

	$where=" where participants.personID=persons.ID";

	if ($http['form_deleted']!="yes") {
		$where .= " and participants.deleted=0";
	}

	if (isset($http['listLetter']) && $http['listLetter']!="all" ) {
		$where .= " and UPPER(persons.name) LIKE '".$http['listLetter']."%'";
		echo "<H3>".ct('S_FRONTDESK_PARTICIPANTS').": ".$http['listLetter']."...</H3>";
    }

	if (isset($http['form_query'])) {
	    if (preg_match("/^[0-9]+$/", $http['form_query']) ) {
	        $where .= " AND (persons.ID = ".$http['form_query'].")";
			echo "<H3>".ct('S_FRONTDESK_QUERY').": ".ct('S_USER_ID')." ".$http['form_query']."</H3>";
	    } else {
    	    $where .= " AND ((UPPER(persons.name) LIKE \"%".ct_strtoupper($http['form_query'])."%\") OR ";
    	    $where .= " (UPPER(persons.firstname) LIKE \"%".ct_strtoupper($http['form_query'])."%\") OR ";
    	    $where .= " (UPPER(persons.organisation) LIKE \"%".ct_strtoupper($http['form_query'])."%\"))";
			echo "<H3>".ct('S_FRONTDESK_QUERY').": ...".$http['form_query']."...</H3>";
	    }
    }

	if (isset($http['isarrived'])) {
		if ($http['isarrived'] == "yes") {
			$where .= " AND participants.frontdesk like '%arrived%'"; # FIND_IN_SET('arrived',frontdesk)>0; also works
			echo "<H3>".ct('S_FRONTDESK_LIST_ARRIVED').": ".$http['form_query']."</H3>";
		} else {
			$where .= " AND !(participants.frontdesk like '%arrived%')";
			echo "<H3>".ct('S_FRONTDESK_LIST_MISSING').": ".$http['form_query']."</H3>";
		}
	}

	$order = ' order by persons.name asc, persons.firstname asc';

	$query.=$where;
	$query.=$order;
  # echo $query;

	$r = $db->query($query);

	if (($r >= 0) && ($db->num_rows($r) > 0)) {
		ctadm_listfrontdesk($r, 'frontdesk');  // -> admin.lib
	} else {
		echo "<H3>".ct('S_ADMIN_PARTICIPANTS_BROWSE_NONE')."</H3>\n";
	}
} else if (isset($http['form_query']) && isset($http['form_nonregistered'])) {
# show all users
	$query="select * from persons ";

	$where="WHERE deleted=0 ";

	if (isset($http['form_query'])) {
	    if (preg_match("/^[0-9]+$/", $http['form_query']) ) {
	        $where .= " AND (persons.ID = ".$http['form_query'].")";
	    } else {
    	    $where .= " AND ((UPPER(persons.name) LIKE \"%".ct_strtoupper($http['form_query'])."%\") OR ";
    	    $where .= " (UPPER(persons.firstname) LIKE \"%".ct_strtoupper($http['form_query'])."%\") OR ";
    	    $where .= " (UPPER(persons.organisation) LIKE \"%".ct_strtoupper($http['form_query'])."%\"))";
	    }
		echo "<H3>".ct('S_FRONTDESK_QUERY2').": ...".$http['form_query']."...</H3>";
    }

	$order = ' order by persons.name asc, persons.firstname asc';

	$query.=$where;
	$query.=$order;
    #echo $query;

	$r = $db->query($query);

	if (($r >= 0) && ($db->num_rows($r) > 0)) {
		ctadm_listusers($r, 'frontdesk'); // -> admin.lib
	} else {
		echo "<H3>".ct('S_ADMIN_USERS_BROWSE_NONE')."</H3>\n";
	}
} else {
    echo "<p class=\"standard\">".ct('S_FRONTDESK_INTRO')."</p>\n";
}

?>

<?php
ct_frontdesk_searchmask();
?>
