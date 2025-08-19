<?php
#
# PAGE:     adminMailerPersons
# DESC:     send bulk mails to different user groups. Could be improved...
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminMailer'));
ct_load_lib('mail.lib');
if( !ini_get('safe_mode') ){
	// Does _not_ work in SAFE MODE
	set_time_limit(1800);
}

//if (in_http('cmd_sendmail','cmdcheckmail') && in_http('form_reviewer')) {
//  echo "Check mail gedrückt!";
//}

if (in_http('cmd_checkmail')) {
	if ($http['form_mailfrom']=="" || $http['form_mailsubject']=="" || $http['form_mailbody']=="" || $http['form_mailselect']=="") {
		ct_errorbox(ct('S_ERROR_MAILER'), ct('S_ERROR_MAILER_INCOMPLETE'));
		mailer_header();
		show_form();
	} else {
		mailer_header();
		echo "<H3>".ct('S_ADMIN_MAILER_SENDNOW')."</H3>\n\n";
		echo "<p class=\"negativebold10\">".ct('S_ADMIN_MAILER_HINTS')."</p>\n";

		echo "<form action=\"".ct_pageurl('adminMailerPersons')."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"form_mailselect\" value=\"".ct_form_encode($http['form_mailselect'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_mailfrom\" value=\"".ct_form_encode($http['form_mailfrom'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_mailreplyto\" value=\"".ct_form_encode($http['form_mailreplyto'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_mailsubject\" value=\"".ct_form_encode($http['form_mailsubject'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_mailbody\" value=\"".ct_form_encode($http['form_mailbody'])."\">\n";

		echo '<table align="center" width="90%" class="form_table" cellspacing=1 cellpadding=2 border=0>';

		echo '<tr><td colspan="3" valign=top align=left class="form_td_separator">';
		echo "<span class=\"form_separator_label\">".ct('S_ADMIN_MAILER_MAIL_HEAD')."</span></td></tr>\n";

		echo '<tr><td width="25%" valign=top align=right class="form_td_label">';
		echo '<span class="form_label">'.ct('S_ADMIN_MAILER_FROM').'</span></td>';
		echo "<td width=\"75%\" align=left valign=top class=\"form_td_field\">".$http['form_mailfrom']."</td></tr>\n";

		if (!$http['form_mailreplyto']=="") {
			echo '<tr><td width="25%" valign=top align=right class="form_td_label">';
			echo '<span class="form_label">'.ct('S_ADMIN_MAILER_REPLYTO').'</span></td>';
			echo "<td width=\"75%\" align=left valign=top class=\"form_td_field\">".$http['form_mailreplyto']."</td></tr>\n";
		}

		echo '<tr><td width="25%" valign=top align=right class="form_td_label">';
		echo '<span class="form_label">'.ct('S_ADMIN_MAILER_SUBJECT').'</span></td>';
		echo "<td width=\"75%\" align=left valign=top class=\"form_td_field\">".stripslashes($http['form_mailsubject'])."</td></tr>\n";

		echo '<tr><td valign=top align=left colspan=2 class="listheader">';
		echo '<span class="bold10">'.ct('S_ADMIN_MAILER_BODY').'</span></td></tr>';

		$pattern = "/({\w*})/i";
		$format  = "<i><b>\$1</b></i>";
		$body = preg_replace($pattern, $format, ct_form_encode($http['form_mailbody'])); // underline presenting author
		echo "<tr><td align=left valign=top colspan=2 class=\"whitebg\"><div class=\"fontmonospaced9\">".nl2br($body)."\n</div><br></td></tr>\n";
		echo "</table><BR>\n";

		$person = new CTPerson();
		$r = get_query();

		echo "<div align=\"center\">\n";
		echo "<table width=\"90%\">\n";

		echo '<tr><td colspan="5" valign=top align=left class="form_td_separator">';
		echo "<span class=form_separator_label>".ct('S_ADMIN_MAILER_RECEIVER_HEAD').": ".ct('S_ADMIN_USERS_BROWSE_SUM1')." ".$db->num_rows($r)." ".ct('S_ADMIN_USERS_BROWSE_SUM2')."</span></td></tr>\n";

		if ($r && ($db->num_rows($r) > 0)) {
			echo "<tr><td colspan=3 align=left class=\"mediumbg\">".ct('S_ADMIN_USERS_BROWSE_SUM1')." $i ".ct('S_ADMIN_USERS_BROWSE_SUM2')."</td><td colspan=2 align=right class=\"mediumbg\">";
			echo "<input type=submit name=\"cmd_back\" class=\"button\" value=\"".ct('S_BUTTON_BACK')."\">";
            #if (ctconf_get('demomode'))
			#	echo "<input disabled style=\"color: #000\"; type=\"submit\" name=\"cancel\" value=\"".ct('S_ADMIN_MAILER_SEND_SUBMIT')." ".ct('S_BUTTON_DEMO')."\">\n";
    		#else
    		#	echo "<input type=submit name=\"cmd_sendmail\" class=\"button\" value=\"".ct('S_ADMIN_MAILER_SEND_SUBMIT')."\">";

			echo "</td></tr>\n";
			for ($i = 0; $i < $db->num_rows($r); $i++) {
				$t = $db->fetch($r);
				$person->load_by_id($t['ID']);
				if ($i % 2) {
					$person->show_simple_row("oddrow");
				} else {
					$person->show_simple_row("evenrow");
				}
			}
			echo "<tr><td colspan=3 align=left class=\"mediumbg\">".ct('S_ADMIN_USERS_BROWSE_SUM1')." $i ".ct('S_ADMIN_USERS_BROWSE_SUM2')."</td><td colspan=2 align=right class=\"mediumbg\">";
			echo "<input type=submit name=\"cmd_back\" class=\"button\" value=\"".ct('S_BUTTON_BACK')."\">";
            if (ctconf_get('demomode'))
				echo "<input disabled style=\"color: #000\"; type=\"submit\" name=\"cancel\" value=\"".ct('S_ADMIN_MAILER_SEND_SUBMIT')." ".ct('S_BUTTON_DEMO')."\">\n";
    		else
    			echo "<input type=submit name=\"cmd_sendmail\" class=\"button\" value=\"".ct('S_ADMIN_MAILER_SEND_SUBMIT')."\">";

			echo "</td></tr>\n";
		}
		echo "</table>\n</div>\n</form>\n";
	}
} else if (in_http('cmd_sendmail')) {
	mailer_header();

	$person = new CTPerson();
	$r = get_query();
	if ($r && ($db->num_rows($r) > 0)) {
		echo "<div align=\"center\">\n";
		echo "<table width=\"90%\">\n";

		for ($i = 0; $i < $db->num_rows($r); $i++) {
			$t = $db->fetch($r);
			$person->load_by_id($t['ID']);
			$mail_content = replace_mail_patterns_persons(stripslashes($http['form_mailbody']),$person);

			$s = ct_mail($person->get('email'),stripslashes($http['form_mailsubject']),$mail_content,stripslashes($http['form_mailfrom']),'',stripslashes($http['form_mailreplyto']));
			echo "<tr class=\"";
			if ($i % 2) {
				echo "oddrow";
			} else {
				echo "evenrow";
			}
			if ($s==0) echo "_del";
			echo "\">\n";
			echo "<td align=right valign=top> <span class=\"bold10\">".$person->get('ID')."</span></td>\n";
			echo "<td align=left valign=top> <span class=\"normal10\">".$person->get_special('name').", ".$person->get_special('firstname')."</span></td>\n";
			echo "<td align=left valign=top><span class=\"bold10\"><a href=\"mailto:".$person->get_special('email')."\">".$person->get_special('email')."</a>\n</span></td>\n";
			if ($s!=0) echo "<td align=right valign=top><span class=\"normal10\">".ct('S_ADMIN_EMAIL_SENT_SUCCESS')."</span></td>\n";
			else    echo "<td align=right valign=top><span class=\"normal10\">".ct('S_ADMIN_EMAIL_SENT_FAILED')."</span></td>\n";
			echo "</tr>\n";
			if (($i % 5) == 4) {
				ob_flush();
				flush();
				sleep(1);  // wait a bit to allow the mail server sending the mails...
			}
			//echo ($person->get_special('email')."<BR>".$http['form_mailsubject']."<BR>".$http['form_mailbody']."<BR>".$http['form_mailfrom']."<BR>".$http['form_mailreplyto'])."<P>";
		}
		echo "</table></div><h3>\n".ct('S_INFO_MAILER_SUCCESS')."</h3>";
	}
//  $session->put_infobox(ct('S_INFO_MAILER'), ct('S_INFO_MAILER_SUCCESS'));
//  ct_redirect(ct_pageurl('index'));
} else {
	mailer_header();
	show_form();
}

// Mailer functions
// ----------------
function show_form(){
	GLOBAL $ctconf,$http;
	if ($http['form_mailfrom']=="") $http['form_mailfrom'] = ctconf_get('conferenceSenderEmail');
	if ($http['form_mailreplyto']=="") $http['form_mailreplyto'] = ctconf_get('conferenceReplytoEmail');
	if ($http['form_mailsubject']=="") $http['form_mailsubject'] = ctconf_get('conferenceShortName').": ";
	if ($http['form_mailbody']=="") {
		$mail_content = "{dear_fullname}, \n\n";
		$mail_content.= "\n\n-- \n";
		$mail_content.=strip_tags(ctconf_get('conferenceName'))."\n";
		$mail_content.=ct_get_loginurl();
	} else {
		$mail_content = stripslashes($http['form_mailbody']);
	}

	echo "<p class=\"standard\">".ct('S_ADMIN_MAILER_INTRO')."</p>\n";
	$form = new CTform(ct_pageurl('adminMailerPersons'), 'post');
	$form->width='100%';
	$form->align='center';
	$form->add_separator(ct('S_ADMIN_MAILER_SELECT_HEAD'));
	$form->add_select(ct('S_USER_STATUS_ONLY'), 'form_mailselect', 1, array(
                    	array('',ct('S_ADMIN_TOOL_NOPERSONS')),
                    	array('0',ct('S_ADMIN_TOOL_ALLPERSONS')),
                    	array('status_author',ct('S_USER_STATUS_AUTHOR')),
                    	array('author_1',ct('S_ADMIN_MAILER_SELECT_TOAUTHORS').": ".ct('S_ADMIN_PAPERS_RESULTS_STATUS_P1_SHORT')),
                    	array('author_0',ct('S_ADMIN_MAILER_SELECT_TOAUTHORS').": ".ct('S_ADMIN_PAPERS_RESULTS_STATUS_0_SHORT')),
                    	array('author_-1',ct('S_ADMIN_MAILER_SELECT_TOAUTHORS').": ".ct('S_ADMIN_PAPERS_RESULTS_STATUS_N1_SHORT')),
                    	array('author_-2',ct('S_ADMIN_MAILER_SELECT_TOAUTHORS').": ".ct('S_ADMIN_PAPERS_RESULTS_STATUS_N2_SHORT')),
                    	array('author_-3',ct('S_ADMIN_MAILER_SELECT_TOAUTHORS').": ".ct('S_ADMIN_PAPERS_RESULTS_STATUS_N3_SHORT')),
                    	array('status_participant',ct('S_USER_STATUS_PARTICIPANT')),
                    	array('notparticipant',ct('S_USER_STATUS_NOTPARTICIPANT')),
                    	array('status_pc',ct('S_USER_STATUS_PC')),
                    	array('status_chair',ct('S_USER_STATUS_CHAIR')),
                    	array('status_admin',ct('S_USER_STATUS_ADMIN')),
                    	array('status_frontdesk',ct('S_USER_STATUS_FRONTDESK')),
                    	array('status_assistant',ct('S_USER_STATUS_ASSISTANT'))),
                    	array($http['form_mailselect']), false);
	$form->add_separator(ct('S_ADMIN_MAILER_MESSAGE_HEAD'));
	$form->add_text(ct('S_ADMIN_MAILER_FROM'),'form_mailfrom',$http['form_mailfrom'], 50, 100);
	$form->add_text(ct('S_ADMIN_MAILER_REPLYTO'),'form_mailreplyto',$http['form_mailreplyto'], 50, 100);
	$form->add_text(ct('S_ADMIN_MAILER_SUBJECT'),'form_mailsubject',stripslashes($http['form_mailsubject']), 50, 200);
	$pattern = "/({\w*})/i";
	$format  = "<code><b>\$1</b></code>";
	$subject_hint = preg_replace($pattern, $format, ct('S_ADMIN_MAILER_BODY_HINT').'<br>'.'{person_password} = '.ct('S_USER_PASSWORD'));
	$form->add_textarea(ct('S_ADMIN_MAILER_BODY'),'form_mailbody',$mail_content, 78,10,'',$subject_hint,0,'class="fontmonospaced9"');
	$form->add_submit('cmd_checkmail', ct('S_ADMIN_MAILER_PREVIEW_SUBMIT'));
	$form->show();
}

function mailer_header(){
	echo "<h1>".ct('S_ADMIN_MAILER')."</h1>\n";
}

function get_query(){
	GLOBAL $http, $db;
	$query = "select ID from persons where deleted=0 order by name, firstname";
	$where = "";
	if ( STRPOS($http[form_mailselect],"status_")===0 ) {
		$where = " && FIND_IN_SET('".ct_substr($http[form_mailselect],7)."',status)>0";
		$query = "select ID from persons where deleted=0 ".$where." order by name, firstname";
	}
	if ( STRPOS($http[form_mailselect],"author_")===0 ) {
		$where = " && papers.acceptstatus='".ct_substr($http[form_mailselect],7)."' ";
		$query = "select persons.ID as ID from persons, papers where persons.ID=papers.personID && persons.deleted=0 && papers.withdrawn=0 ".$where." order by name, firstname";
	}
	if ( $http[form_mailselect] == "notparticipant" ) {
		$where = " && FIND_IN_SET('participant',status)=0";
		$query = "select ID from persons where deleted=0 ".$where." order by name, firstname";
	}
	// echo $query;
	return $db->query($query);

}

?>
