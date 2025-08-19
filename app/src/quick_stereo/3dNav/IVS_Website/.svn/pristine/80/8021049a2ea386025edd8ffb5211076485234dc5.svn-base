<?php
#
# PAGE:		login
# this page offers an alternative login page.
# it has the style of amazon and other, however we experienced problems
# users had in user tests with this kind of unified login screen
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

# has user entered name and password and pressed submit?
if (isset($http['ctusername']) && isset($http['ctpassword']) && ($http['cthaspasswd'] != "no")) {
	# try login
	if ($session->login($http['ctusername'], $http['ctpassword'])) {
		# login successful
		# check if participation registration has already started
		$user=$session->get_user();
  		if (!$user->is_participant()){
		 	if(ct_check_phases("participation")) {
				# yes, so show info message
				$session->put_infobox(ct('S_INFO_PARTICIPATE'), ct('S_INFO_PARTICIPATE_REMINDER'));
			}
    		echo "<p class=\"standard\">".ct('S_INDEX_YOUAREPARTICIPANT')."</p>\n";
  		}
		# login successfull: redirect to index page
		ct_redirect(ct_pageurl("index"));
	} else {	# failed
		$session->put_errorbox(ct('S_ERROR_LOGIN'), ct('S_ERROR_LOGIN_FAILED'));
		ct_redirect(ct_pageurl("login", array("ctusername"=>stripslashes($http['ctusername']))));
	}
}
elseif (isset($http['ctusername']) && isset($http['cthaspasswd']) && ($http['cthaspasswd'] == "no")) {
	# user wants to create account

	# is the username already known?
	if (isset($http['ctusername'])) {
	    global $db;

	    $query="select count(*) as count from persons where (username='".$http['ctusername']."' or email='".$http['ctusername']."') and deleted=0";
        $r = $db->query($query);
		if ($r && ($db->num_rows($r) > 0)) {
			$t = $db->fetch($r);
			if ($t[count]>0) {
 			# username already known - display error message and return to login screen
				$session->put_errorbox(ct('S_ERROR_REGISTER'), ct('S_ERROR_REGISTER_USERNAMEEXISTS'));
				ct_redirect(ct_pageurl("login", array("ctusername"=>stripslashes($http['ctusername']))));
			}
		}

    }
    # redirect him to register page
	ct_redirect(ct_pageurl("register", array("ctusername"=>stripslashes($http['ctusername']))));
}
else {
	echo "<p><form name=\"loginform\" method=\"post\" action=\"".ct_pageurl("login")."\" onSubmit=\"if (document.loginform.ctpassword.value!='' && document.loginform.cthaspasswd[1].checked!=true) {document.loginform.cthaspasswd[1].checked=true; return false;}\">\n";
	echo "<table width=\"75%\" align=\"center\" class=\"tbldialog\" cellspacing=1 cellpadding=3 border=0>\n";
	echo "<tr><td colspan=2 class=\"td_dlg_text\">\n";
	echo "<h2 align=center>".ct('S_LOGIN_TITLE')."</h2>\n";
	echo ct('S_LOGIN_WELCOME')."<br><br>\n";
	echo "</td></tr>\n";

	echo "<tr><td colspan=2 class=\"td_dlg_title\">\n";
	echo "<H3>".ct('S_LOGIN_FORMTITLE')."</H3>\n";
	echo "</td></tr>\n";

	echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

	echo "<tr><td colspan=2 class=\"td_dlg_label\">".ct('S_LOGIN_USERNAME')."</td></tr>\n";
	echo "<td class=\"td_dlg_input\" width=33% align=right><span class=\"label10\">".ct('S_LOGIN_USERNAME_LABEL').":</span></td>\n";
	echo "<td class=\"td_dlg_input\" width=66% align=left><input type=text name=\"ctusername\" value=\"".ct_form_encode($http['ctusername'])."\" size=35></td></tr>\n";

 	if(ct_check_phases("userregistration")) {	#registration allowed?
		echo "<tr><td colspan=2 class=\"td_dlg_label\">".ct('S_LOGIN_PASSWORD')."</td></tr>\n";
		echo "<td class=\"td_dlg_input\" width=\"33%\">&nbsp;</td>\n";
		echo "<td class=\"td_dlg_label\" width=\"67%\">\n";
		echo "<input type=radio name=\"cthaspasswd\" value=\"no\" checked> <span class=\"label10\">".ct('S_LOGIN_HAVENOPASSWD')."</span><br>\n";
		echo "<input type=radio name=\"cthaspasswd\" value=\"yes\"> <span class=\"label10\">".ct('S_LOGIN_HAVEPASSWD').": </span>\n";
		echo "<input type=password name=\"ctpassword\" onFocus=\"document.loginform.cthaspasswd[1].checked=true; return false;\" onBlur=\"if(document.loginform.ctpassword.value=='') { document.loginform.cthaspasswd[0].checked=true; } return false;\" size=14></td></tr>\n";
	} else {
		echo "<td class=\"td_dlg_input\" width=\"33%\" align=right><span class=\"label10\">".ct('S_USER_PASSWORD')."</span></td>\n";
		echo "<td class=\"td_dlg_label\" width=\"67%\">\n";
		echo "<input type=hidden name=\"cthaspasswd\" value=\"yes\">\n";
		echo "<input type=password name=\"ctpassword\" size=14></td></tr>\n";
	}
	echo "<tr><td colspan=2>&nbsp;</td></tr>\n";

	echo "<td class=\"td_dlg_buttons\">&nbsp;</td>\n";
	echo "<td class=\"td_dlg_buttons\"><input type=submit name=\"cmd_login\" value=\"".ct('S_BUTTON_LOGIN')."\"></dd>\n";
	echo "</td></tr>\n";

	echo "<tr><td colspan=2 class=\"td_dlg_label\" align=center><BR><span class=\"bold10\">";
	echo "<a href=\"".ct_pageurl('sendPassword')."\">".ct('S_LOGIN_SENDPASSWORD')."</a>";
	echo "</span><BR><BR></td></tr>\n";
	echo "</table>\n</form>\n";

}

?>



