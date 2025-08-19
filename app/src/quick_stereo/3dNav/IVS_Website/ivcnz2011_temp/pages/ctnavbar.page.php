<?php

if (!defined('CONFTOOL')) die('Hacking attempt!');
	echo "<a name='top' id='top'></a>\n";

	$user =& $session->get_user();
	$thisurl = ct_pageurl($http['page'],ct_http_array());

	echo "<table width=\"100%\" cellspacing=0 cellpadding=2 border=0 bgcolor=\"#ccddff\">\n";
	echo "<tr>";
	echo "<td class=\"cthead_td_cmds\" nowrap width=\"80%\">";

	echo "<span class=\"cthead_cmds\">\n";
	if ($session->loggedin()) {
		#echo "<a href=\"".ct_pageurl('index',array(),'',$user->is_admin()?'menu':'')."\" class=\"cthead_a\">".ct('S_CTHEAD_CMD_INDEX')."</a>";
		echo "<a href=\"".ct_pageurl('index',array(),'',$user->is_admin()?'top':'')."\" class=\"cthead_a\">".ct('S_CTHEAD_CMD_INDEX')."</a>";
		if ($session->get_besturl()!="" && $session->get_besturlinfo()!="" &&
			 	$session->get_besturl()!=$thisurl) {
			echo " &middot; <a href=\"".$session->get_besturl()."\" class=\"cthead_a\"><img src=\"images/back.gif\" alt=\"go back\" border=0>&nbsp;";
			echo $session->get_besturlinfo()."</a>";
		}
	} else {
		if(ct_check_phases("userregistration") && $http['page']=='login') 	//registration allowed?
			echo "<a href=\"".ct_pageurl('register')."\" class=\"cthead_a\">".ct('S_CTHEAD_CMD_REGISTER')."</a> &middot; ";
		echo "<a href=\"".ct_pageurl('logout')."\" class=\"cthead_a\">".ct('S_CTHEAD_CMD_LOGIN')."</a>";  // logout to avoid problems.
	}
	echo "</span>";

	echo "</td>\n";

	echo "<td class=\"cthead_td_cmds\" nowrap align=\"right\" valign=\"middle\">";
	// Show user name
	if ($session->loggedin()) {
		echo "<span class=\"cthead_user\">";
		echo "<img src='images/user.gif' border=0 align='top' title=\"".ct('S_USER_USERNAME').": ".$user->get_special('username').", ".ct('S_ID').": ".$user->get_id()."\"> ";

	  	$name=$user->get_name();
	  	if (ct_strlen($name)>28) $name=$user->get_shortname();
	  	$name=ct_abbreviate($name,28);
	  	echo ct_form_encode($name);
 		echo "</span>\n";
    	unset($name);

		echo "&nbsp;&nbsp;&nbsp;<a href=\"".ct_pageurl('logout')."\" class=\"cthead_a\"><img src='images/logout.gif' border=0 align='top'> ".ct('S_CTHEAD_CMD_LOGOUT')."</a>";  // logout to avoid problems.
	}
	echo "&nbsp;</td></tr>\n</table>\n";

?>