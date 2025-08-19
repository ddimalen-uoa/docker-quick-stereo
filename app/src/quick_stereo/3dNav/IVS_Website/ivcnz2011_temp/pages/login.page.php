<?php
#
# PAGE:		login
# Show login screen.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

if ($session->loggedin() && $_SERVER["REQUEST_METHOD"] == "GET") {
	$session->put_infobox(ct('S_INFO_USERS_LOGOUT'), ct('S_INFO_USERS_LOGOUT_SUCCESS',array(ct_form_encode(trim($user->get_fullname())))));
	$session->logout();
}

$session->set_besturl();

// Test if cookies are enabled!
if (isset($http['cmd_login']) && ini_get("session.use_only_cookies")==1 && count($_COOKIE)==0) {
	ct_errorbox(ct('S_ERROR_LOGIN'),ct('S_ERROR_LOGIN_ENABLECOOKIES',array(ctconf_get('conferenceContactEmail'))));
	unset($http['cmd_login']); // Don't try to login, it won't work...
}


// has user entered name and password and pressed submit? ------------------
if (isset($http['ctusername']) && isset($http['ctpassword']) && $http['ctpassword']<>"" && isset($http['cmd_login']) ) {
	// Wait a little bit to make attacks
	sleep(0.2);
	// data was sent: try to login
	if ($session->login($http['ctusername'], $http['ctpassword'])) {
		# login successful
		# check if participation registration has already started
		$user =& $session->get_user();
  		if (!$user->is_participant() && $user->get('ID')!=1){
		 	if(ct_check_phases("participation")) {
				# yes, so show info message
				$session->put_infobox(ct('S_INFO_PARTICIPATE'), ct('S_INFO_PARTICIPATE_REMINDER'));
			}
  		}
		# login successfull: redirect to index page
		ct_redirect(ct_pageurl("index"));
	} else {	# failed
		sleep(0.5);
		$session->put_errorbox(ct('S_ERROR_LOGIN'), ct('S_ERROR_LOGIN_FAILED'));
		ct_redirect(ct_pageurl("login", array("ctusername"=>stripslashes($http['ctusername']))));
		#die(ct_pageurl("login", array("ctusername"=>$http['ctusername'])));
	}
}
elseif (isset($http['ctusername']) && isset($http['try_login']) ) {
	# user wants to create account
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
    # redirect him to register page
	ct_redirect(ct_pageurl("register", array("ctusername"=>stripslashes($http['ctusername']))));
}
else {
	# No data was sent: show login screen...
	ct_vspacer('6');
	echo "<form name=\"loginform\" method=\"post\" action=\"".ct_pageurl("login")."\">\n";
	echo "<table width=\"90%\" align=\"center\" class=\"tbldialog\" cellspacing=1 cellpadding=3 border=0>\n";
	echo "<tr><td colspan=2 class=\"td_dlg_text\">\n";
	echo "<h2 align=center>".ct('S_LOGIN_TITLE')."</h2>\n";
	echo "</td></tr>\n";
	echo "<tr><td colspan=2 class=\"td_dlg_text\">\n";

	// Show welcome message. Depends on setup in conftool.conf and system phases
	if (!ctconf_get('participation/enabled') || (!ct_check_phases("participation") && ctconf_get('submission/enabled')))
		echo ct('S_LOGIN_WELCOME_SUBMIT');
	elseif (!ctconf_get('submission/enabled'))
		echo ct('S_LOGIN_WELCOME_PARTICIPATE');
	else
		echo ct('S_LOGIN_WELCOME');
	echo "<br><br>\n";

	echo "</td></tr>\n";

	echo "<tr><td class=\"td_dlg_title\">&nbsp;</td>\n";
	echo "<td class=\"td_dlg_title\" align=left><H3>".ct('S_LOGIN_FORMTITLE')."</H3>\n";
	echo "</td></tr>\n";

 	if(ct_check_phases("userregistration")) {	#registration allowed?
		echo "<tr><td class=\"mediumbg\" align=\"right\">\n";
		echo "<span class=\"bold10\">".ct('S_LOGIN_NEWUSER')."</span>\n";
		echo "</td><td class=\"mediumbg\">&nbsp;</td></tr>";
		echo "<tr><td class=\"td_dlg_label\" width=\"33%\" align=right>&nbsp;</td>\n";
		echo "<td class=\"td_dlg_label\" width=\"67%\" align=left>";
		ct_vspacer();
		echo "<a class=\"fontbold font10\" href=\"".ct_pageurl('register')."\" tabindex=1>";
		if (ct_check_phases("submission") && !ct_check_phases("participation"))
			echo ct('S_LOGIN_CREATEACCOUNT_SUBMIT');
		elseif (ct_check_phases("participation") && !ct_check_phases("submission"))
			echo ct('S_LOGIN_CREATEACCOUNT_PARTICIPATE');
		else
			echo ct('S_LOGIN_CREATEACCOUNT');

		echo "</a><br>";
		ct_vspacer();
		echo "</td></tr>";
	} else {
		echo "<tr><td class=\"mediumbg\" align=\"right\">\n";
		echo "<span class=\"light10\">".ct('S_LOGIN_NEWUSER')."</span>\n";
		echo "</td><td class=\"mediumbg\">&nbsp;</td></tr>";
		echo "<tr><td class=\"td_dlg_label\" width=\"33%\" align=right>&nbsp;</td>\n";
		echo "<td class=\"td_dlg_label\" width=\"67%\" align=left>";
		ct_vspacer();
		echo "<span class=\"negative10\">".ct('S_LOGIN_CREATEACCOUNT_CLOSED')."</span><br>";
		ct_vspacer();
		echo "</td></tr>";

	}

	echo "<tr><td class=\"mediumbg\" align=\"right\">\n";
	echo "<span class=\"bold10\">".ct('S_LOGIN_OLDUSER')."</span>\n";
	echo "</td><td class=\"mediumbg\">&nbsp;</td></tr>\n";

	echo "<td class=\"td_dlg_input\" width=33% align=right><span class=\"label10\">".ct('S_LOGIN_USERNAME_LABEL').":</span></td>\n";
	echo "<td class=\"td_dlg_input\" width=67% align=left><input type=text name='ctusername' tabindex=2 ";
	if (isset($http['ctusername']))
		echo "value=\"".ct_form_encode($http['ctusername'])."\" ";
	echo "size=35></td></tr>\n";

	echo "<td class=\"td_dlg_input\" width=\"33%\" align=right><span class=\"label10\">".ct('S_LOGIN_PASSWORD').":</span></td>\n";
	echo "<td class=\"td_dlg_label\" width=\"67%\">\n";
	echo "<input type='password' name='ctpassword' size='14' tabindex='3'>&nbsp;&nbsp;&nbsp;&middot;&nbsp;&nbsp;&nbsp;";
   	echo "<a href='".ct_pageurl('sendPassword')."' tabindex='5'>".ct('S_LOGIN_SENDPASSWORD')."</a>";
	echo "</td></tr>\n";
	echo "<input type='hidden' name='try_login' value='yes'>\n";

	echo "<td class=\"td_dlg_label\">&nbsp;</td>\n";
	echo "<td class=\"td_dlg_label\"><input type='submit' class='button' name='cmd_login' value='".ct('S_BUTTON_LOGIN')."' tabindex='4'></dd>\n";
    echo "<br><br>";
	echo "</td></tr>\n";

	echo "</table>\n</form>\n";
	ct_vspacer();
}

?>