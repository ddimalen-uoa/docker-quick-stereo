<?php
//
// PAGE:        adminMailerAuthors
// DESC:        send bulk mails to all authors of specific contributions.
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index','adminMailer'));
ct_load_lib('mail.lib');
ct_load_lib('papers.lib');

$paper   = new CTPaper();
$author  = new CTPerson();
$coauthor= new CTPerson();

if( !ini_get('safe_mode') ){
	// Does _not_ work in SAFE MODE
	set_time_limit(1800);
}

$form_errors=array();

if (in_http('cmd_checkmail')) {
	// Check the selected settings before sending the mails...
	if (ct_validate_email($http['form_mailfrom'])===false) $form_errors[]="form_mailfrom";
	if ($http['form_mailreplyto']!="" && ct_validate_email($http['form_mailreplyto'])===false) $form_errors[]="form_mailreplyto";
	if ($http['form_mailsubject']=="") $form_errors[]="form_mailsubject";
	if ($http['form_mailbody']=="") $form_errors[]="form_mailbody";

	if ( count($form_errors)!=0 ) {
		ct_errorbox(ct('S_ERROR_MAILER'), ct('S_ERROR_MAILER_INCOMPLETE'));
		mailer_header();
		show_form($form_errors);
	} else {

		// Show confirmation page before sending mails...
		mailer_header();
		echo "<p class=\"bold10\">".ct('S_ADMIN_MAILER_SENDNOW')."</p>\n\n";
		echo "<p class=\"negativebold10\">".ct('S_ADMIN_MAILER_HINTS')."</p>\n";

		echo "<form action=\"".ct_pageurl('adminMailerAuthors')."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"form_status\" value=\"".ct_form_encode($http['form_status'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_track\" value=\"".ct_form_encode($http['form_track'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_mailfrom\" value=\"".ct_form_encode($http['form_mailfrom'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_mailreplyto\" value=\"".ct_form_encode($http['form_mailreplyto'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_mailsubject\" value=\"".ct_form_encode($http['form_mailsubject'])."\">\n";
		echo "<input type=\"hidden\" name=\"form_mailbody\" value=\"".ct_form_encode($http['form_mailbody'])."\">\n";

		// Write Mail Info
		echo '<table align="center" width="700" class="form_table" cellspacing=0 cellpadding=1 border=0>';

		echo '<tr><td colspan="3" valign=top align=left class="form_td_separator">';
		echo "<span class=\"form_separator_label\">".ct('S_ADMIN_MAILER_MAIL_HEAD')."</span></td></tr>\n";

		echo '<tr><td width="25%" valign=top align=right class="form_td_label">';
		echo '<span class="form_label">'.ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_CONTRIBUTIONTYPE').'</span></td>';
		echo "<td width=\"75%\" align=left valign=top class=\"form_td_field\">". show_mailer_status($http['form_track'], ct_list_contributiontypes())."</td></tr>\n";

		echo '<tr><td width="25%" valign=top align=right class="form_td_label">';
		echo '<span class="form_label">'.ct('S_ADMIN_PAPERS_RESULTS_STATUS').'</span></td>';
		echo "<td width=\"75%\" align=left valign=top class=\"form_td_field\">". show_mailer_status($http['form_status'], ct_list_accessstates())."</td></tr>\n";

		$pattern = "/({\w*})/i";
		$format  = "<i><b>\$1</b></i>";
		$body = preg_replace($pattern, $format, ct_form_encode($http['form_mailbody'])); // underline presenting author

		show_preview($body);

		echo "</table><BR>\n";

		$r = get_query();

		// List of receivers: Create header
		echo "<div align=\"center\">\n";
		echo "<table width=\"100%\" cellspacing=1 cellpadding=2 border=0>\n";

		// Submit button...
		echo "<tr><td colspan=7 align=right class=\"mediumbg\">";
			echo "<input type=submit name=\"cmd_back\" class=\"button\" value=\"".ct('S_BUTTON_BACK')."\">";
		echo "&nbsp;";
        #if (ctconf_get('demomode')) {
		#	echo "<input disabled style=\"color: #000\"; type=\"submit\" name=\"cancel\" value=\"".ct('S_ADMIN_MAILER_SEND_SUBMIT')." ".ct('S_BUTTON_DEMO')."\">\n";
        #} else {
		#	echo "<input type=submit name=\"cmd_sendmail\" class=\"button\" value=\"".ct('S_ADMIN_MAILER_SEND_SUBMIT')."\">";
        #}
		echo "</td></tr>\n";

		// Main header
		echo '<tr><td colspan="7" valign=top align=left class="form_td_separator">';
		echo "<span class=form_separator_label>".ct('S_ADMIN_MAILER_AUTHORS_RECEIVER_HEAD', array($db->num_rows($r)))."</span></td></tr>\n";

		// Column header
		echo "<tr class=\"listheader\">\n";
		echo "<td align=right valign=top><span class=\"bold8\">".ct('S_ID')."</span></td>\n";
		echo "<td align=left valign=top>";
		echo "<span class=\"bold8\">".ct('S_PAPER_STATUS')."</span>";
		echo "</td>";
		echo "<td align=left valign=top>";
		echo "<span class=\"bold8\">".ct('S_PAPER_AUTHOR')."</span><br>";
		echo "<img src=\"".ct_getbaseurl()."images/darkblue.gif\" height=1 width=220><br>";
		echo "<span class=\"normal8\">".ct('S_USER_FULLNAME')."</span>";
		echo "</td>";
		echo "<td align=left valign=top>";
		echo "<span class=\"bold8\">".ct('S_PAPER_TITLE')."</span><br>";
		echo "<img src=\"".ct_getbaseurl()."images/darkblue.gif\" height=1 width=220><br>";
		echo "<span class=\"normal8\">".ct('S_USER_EMAIL')."</span>";
		echo "</td>";
		#echo "<td align=left valign=top><span class=\"normal8\">".ct('S_USER_ORGANISATION')."</span></td>";
		echo "<td align=left valign=top>";
		echo "<span class=\"bold8\">".ct('S_PAPER_TYPE')."</span><br>";
		echo "<img src=\"".ct_getbaseurl()."images/darkblue.gif\" height=1 width=40><br>";
		echo "<span class=\"normal8\">".ct('S_USER_COUNTRY')."</span>";
		echo "</td>";
		echo "</tr>\n";

		// Create List
		if ($r && ($db->num_rows($r) > 0)) {
			$row = 0;
			for ($i = 0; $i < $db->num_rows($r); $i++) {
				$t = $db->fetch($r);
				$paper->load_by_id($t['ID']);
				$author=$paper->get_author();
				show_paper_row('oddrow',$paper);
				$class = "oddrow2";
				show_person_row(ct('S_USER_STATUS_AUTHOR_SHORT'),$class,$author->get_id(),$author->get_special('email'),ct_form_encode($author->get_reversename()),$author->get_special('organisation'),$author->get('country'));
				$coauthors=$paper->get_emails();
				foreach ($coauthors as $c) {
					$class = "evenrow2";
					if ($coauthor->load_by_email($c))
						show_person_row(ct('S_USER_STATUS_COAUTHOR_SHORT'),$class,$coauthor->get_id(),$coauthor->get_special('email'),ct_form_encode($coauthor->get_reversename()),$coauthor->get_special('organisation'),$coauthor->get('country'));
					else
						show_person_row(ct('S_USER_STATUS_COAUTHOR_SHORT'),$class,'?',$c,'','',ct_strtoupper(ct_substr(strrchr($c, "."),1)));
				}
			}
			echo "</table>\n\n";

			// Submit button...
			echo "<table width=\"100%\" cellspacing=1 cellpadding=2 border=0>\n";
			echo "<tr><td colspan=3 align=left class=\"form_td_separator\"><span class=form_separator_label>".ct('S_ADMIN_PAPERS_BROWSE_SUM1').ct('S_ADMIN_MAILER_AUTHORS_RECEIVER_HEAD', array($i))."</span></td><td colspan=3 align=right class=\"mediumbg\">";
   			echo "<input type=submit name=\"cmd_back\" class=\"button\" value=\"".ct('S_BUTTON_BACK')."\">";
			echo "&nbsp;";
            if (ctconf_get('demomode')) {
				echo "<input disabled style=\"color: #000\"; type=\"submit\" name=\"cancel\" value=\"".ct('S_ADMIN_MAILER_SEND_SUBMIT')." ".ct('S_BUTTON_DEMO')."\">\n";
            } else {
    			echo "<input type=submit name=\"cmd_sendmail\" class=\"button\" value=\"".ct('S_ADMIN_MAILER_SEND_SUBMIT')."\">";
            }
			echo "</td></tr>\n";

		}
		echo "</table>\n</div>\n</form>\n";
	}
} else if (in_http('cmd_sendmail')) {

	// remove all slashes
	$http = ct_deepunslash($http);
	// Now send mails and show protocol...
	mailer_header();
	echo "<H3>".ct('S_ADMIN_MAILER_PROTOCOL')."</H3>\n\n";

	if (in_http('cmd_sendmail') && is_array($http['form_sendmail_paper'])) {
		echo "<div align=\"center\">\n";
		echo "<table width=\"90%\">\n";

		// List of receivers: Create header
		echo "<tr class=\"listheader\">\n";
		echo "<td align=right valign=top><span class=\"normal10\">".ct('S_USER_ID')."</span></td>\n";
		echo "<td align=left valign=top><span class=\"bold10\">".ct('S_USER_FULLNAME')."</span>";
		echo "<td align=left valign=top><span class=\"bold10\">".ct('S_USER_EMAIL')."</span>";
		echo "<td align=left valign=top><span class=\"bold10\">".ct('S_USER_STATUS')."</span>";
		echo "</tr>\n";

		$i=0;
 		foreach($http['form_sendmail_paper'] as $paperID) {
 			$i++;
			$paper->load_by_id($paperID);
			$author=$paper->get_author();
			$coauthors=array();
			$coauthors=$paper->get_emails();

			$mail_content = $http['form_mailbody'];
			$mail_content = replace_mail_patterns_papers($mail_content,$paper);
			$mail_content = replace_mail_patterns_persons($mail_content,$author);

			$s = ct_mail($author->get('email'),$http['form_mailsubject'],$mail_content,$http['form_mailfrom'],'',$http['form_mailreplyto'],$coauthors);
			echo "<tr class=\"";
			if ($i % 2) {
				echo "oddrow";
			} else {
				echo "evenrow";
			}
			if ($s==0) echo "_del";
			echo "\">\n";
			echo "<td align=right valign=top> <span class=\"bold10\">".$author->get('ID')."</span></td>\n";
			echo "<td align=left valign=top> <span class=\"normal10\">".$author->get_special('name').", ".$author->get_special('firstname')."</span> </td>\n";
			echo "<td align=left valign=top><span class=\"bold10\"><a href=\"mailto:".$author->get_special('email')."\">".$author->get_special('email')."</a>\n</span></td>\n";
			if ($s!=0) echo "<td align=right valign=top><span class=\"normal10\">".ct('S_ADMIN_EMAIL_SENT_SUCCESS')."</span></td>\n";
			else    echo "<td align=right valign=top><span class=\"normal10\">".ct('S_ADMIN_EMAIL_SENT_FAILED')."</span></td>\n";
			echo "</tr>\n";

			foreach ($coauthors as $c) {
				echo "<tr class=\"oddrow2\">\n";
				echo "<td align=right valign=top> <span class=\"bold10\">&nbsp</span></td>\n";
				echo "<td align=left valign=top> <span class=\"normal10\">cc</span> </td>\n";
				echo "<td align=left valign=top><span class=\"bold10\"><a href=\"mailto:".$c."\">".$c."</a>\n</span></td>\n";
				if ($s!=0) echo "<td align=right valign=top><span class=\"normal10\">".ct('S_ADMIN_EMAIL_SENT_SUCCESS')."</span></td>\n";
				else    echo "<td align=right valign=top><span class=\"normal10\">".ct('S_ADMIN_EMAIL_SENT_FAILED')."</span></td>\n";
				echo "</tr>\n";
			}


			if (($i % 5) == 4) {
				ob_flush();
				flush();
				sleep(1);  // wait a bit to allow the mail server sending the mails...
			}
			//echo ($author->get('email')."<BR>".$http['form_mailsubject']."<BR>".$http['form_mailbody']."<BR>".$http['form_mailfrom']."<BR>".$http['form_mailreplyto'])."<P>";
		}
		echo "</table></div>\n";

		echo "<h3>\n".ct('S_INFO_MAILER_SUCCESS')."</h3>";

	} else {
		echo "<p class=negativebold10>".ct('S_ADMIN_MAILER_NOMAILS')."</p>";
	}

} else {
	mailer_header();
	show_form($form_errors);
}

// Mailer functions
// ----------------
function show_form($form_errors){
	GLOBAL $http,$session;
	$user=$session->get_user();

	// remove all "magic" slashes.
	if ($http['form_mailfrom']=="") $http['form_mailfrom'] = ctconf_get('conferenceSenderEmail');
	if ($http['form_mailreplyto']=="") $http['form_mailreplyto'] = ctconf_get('conferenceReplytoEmail');
	if ($http['form_mailsubject']=="") $http['form_mailsubject'] = ctconf_get('conferenceShortName').": ";
	if ($http['form_mailbody']=="") {
		$mail_content = "{dear_fullname}, \n\n";
		$mail_content.= "we would like to inform you that your {contribution_type} {contribution_id}\n'{contribution_title}'\nhas....\n\n";
		$mail_content.= "\n\n-- \n";
		$mail_content.=strip_tags(ctconf_get('conferenceName'))."\n";
		$mail_content.=ct_get_loginurl();
	} else {
		$mail_content = $http['form_mailbody'];
	}

	echo "<p class=\"standard\">".ct('S_ADMIN_MAILER_AUTHORS_INTRO')."</p>\n";

	$form = new CTform(ct_pageurl('adminMailerAuthors'), 'post',$form_errors);
	$form->width='99%';
	$form->align='center';
	$form->add_separator(ct('S_ADMIN_MAILER_AUTHORS_SELECT_HEAD'));
	$tracks=array_merge (array(array(0,ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_ALLTYPES'))), ct_list_contributiontypes());
	$form->add_select(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_CONTRIBUTIONTYPE'), 'form_track', 1, $tracks, array($http['form_track']));
	$fstates=array_merge (array(array("-",ct('S_ADMIN_PAPERS_RESULTS_ALLSTATES'))), ct_list_accessstates());
	$form->add_select(ct('S_ADMIN_PAPERS_RESULTS_STATUS'), 'form_status', 1, $fstates, array($http['form_status']));
	$form->add_separator(ct('S_ADMIN_MAILER_MESSAGE_HEAD'));
	$form->add_text(ct('S_ADMIN_MAILER_FROM'),'form_mailfrom',$http['form_mailfrom'], 50, 100);
	$form->add_text(ct('S_ADMIN_MAILER_REPLYTO'),'form_mailreplyto',$http['form_mailreplyto'], 50, 100);
	$form->add_text(ct('S_ADMIN_MAILER_SUBJECT'),'form_mailsubject',$http['form_mailsubject'], 50, 200);
	$pattern = "/({\w*})/i";
	$format  = "<code><b>\$1</b></code>";
	$subject_hint = preg_replace($pattern, $format, ct('S_ADMIN_MAILER_BODY_HINT').'<br>{person_password} = '.ct('S_USER_PASSWORD').'<br>'.ct('S_ADMIN_MAILER_AUTHORS_BODY_HINT_STANDARD')); // mark commands in bold.

	$form->add_textarea(ct('S_ADMIN_MAILER_BODY'),'form_mailbody',$mail_content, 78,10,'',$subject_hint,0,'class="fontmonospaced9"');


	$form->add_submit('cmd_checkmail', ct('S_ADMIN_MAILER_PREVIEW_SUBMIT'));
	$form->show();
}

function mailer_header(){
	echo "<h1>".ct('S_ADMIN_MAILER_AUTHORS')."</h1>\n";
}


function get_query(){
	GLOBAL $http, $db;
	$query = "SELECT ID FROM papers ";
	$where = "WHERE withdrawn=0 ";
	$order = "ORDER by title,ID";
	if ( $http['form_status']!='-' ) {
		$where .= " && acceptstatus='".$http['form_status']."' ";
	}
	if ( $http['form_track']!='0' ) {
		$where .= " && contributiontypeID='".$http['form_track']."' ";
	}
	$query = "$query $where $order";
	#echo $query;
	return $db->query($query);
}

function show_mailer_status($needle,$mail_status) {
	reset ($mail_status);
	while (list ($key, $val) = each ($mail_status)) {
		if ($val[0]==$needle)
	   		return $val[1];
	}
  	return "---";
}

// show	basic user information in one row
function show_paper_row($class,&$paper) {
	echo "<tr class=\"$class\">\n";
	echo "<td align=center valign=top>";
	echo "<input type=\"hidden\" class=\"\" name=\"form_sendmail_paper[]\" value=\"".$paper->get_id()."\">";
	echo "<span class=\"bold8\">".$paper->get_id()."</span>";
	echo "</td>\n";

	echo "<td align=left valign=top>";
	if ($paper->get('acceptstatus')==1)
		echo "<span	class=\"positivebold8\">".$paper->get_acceptstatus_short()."</span>";
	elseif ($paper->get('acceptstatus')==-1)
		echo "<span	class=\"negativebold8\">".$paper->get_acceptstatus_short()."</span>";
	else
		echo "<span	class=\"bold8\">".$paper->get_acceptstatus_short()."</span>";
	echo "</td>\n";

	echo "<td align=left valign=top>";
	echo "<span	class=\"bold8\">".$paper->get_special('author')."</span>";
	echo "</td>\n";

	#echo "<td align=left valign=top colspan=2>";
	echo "<td align=left valign=top>";
	echo "<span	class=\"bold8\">".$paper->get_special('title')."</span>";
	echo "</td>\n";

	echo "<td align=left valign=top colspan=1>";
	echo "<span	class=\"bold8\">".$paper->_get_contributiontype_title()."</span>";
	echo "</td>\n";

	echo "</tr>\n";
}

// show	basic user information in one row
function show_person_row($type,$class,$id,$email,$name,$organisation,$country) {
	echo "<tr class=\"$class\">\n";
	echo "<td align=right valign=top>&nbsp;</td>\n";
	echo "<td align=right valign=top><span class=\"normal8\">".$id."</span></td>\n";
	echo "<td align=left valign=top><span class=\"normal8\">".$type."</span></td>\n";

	echo "<td align=left valign=top>";
	echo "<span class=\"normal8\">".$name."</span>";
	echo "</td>\n";

	echo "<td align=left valign=top>";
	echo "<span class=\"normal8\">";
	echo "<a href=\"mailto:".$email."\">".$email."</a>\n";
	echo "</span>";
	echo "</td>\n";

	echo "</tr>\n";
}

// output mail preview on screen...
function show_preview($body) {
	global $http;

	echo "<tr><td colspan=\"3\" valign=top align=left class=\"form_td_separator\"><img src=\"".ct_getbaseurl()."images/spacer.gif\" width=1 height=1 border=0></td></tr>\n";

	echo '<tr><td width="25%" valign=top align=right class="form_td_label">';
	echo '<span class="form_label">'.ct('S_ADMIN_MAILER_FROM').'</span></td>';
	echo "<td width=\"75%\" align=left valign=top class=\"form_td_field\">".ct_form_encode($http['form_mailfrom'])."</td></tr>\n";

	if (!$http['form_mailreplyto']=="") {
		echo '<tr><td width="25%" valign=top align=right class="form_td_label">';
		echo '<span class="form_label">'.ct('S_ADMIN_MAILER_REPLYTO').'</span></td>';
		echo "<td width=\"75%\" align=left valign=top class=\"form_td_field\">".ct_form_encode($http['form_mailreplyto'])."</td></tr>\n";
	}

	echo '<tr><td width="25%" valign=top align=right class="form_td_label">';
	echo '<span class="form_label">'.ct('S_ADMIN_MAILER_SUBJECT').'</span></td>';
	echo "<td width=\"75%\" align=left valign=top class=\"form_td_field\">".ct_form_encode($http['form_mailsubject'])."</td></tr>\n";

	echo '<tr><td valign=top align=left colspan=2 class="listheader">';
	echo '<span class="bold10">'.ct('S_ADMIN_MAILER_BODY').'</span></td></tr>';

	echo "<tr><td align=left valign=top colspan=2 class=\"whitebg\"><div class=\"fontmonospaced9\">".nl2br($body)."\n</div><br></td></tr>\n";

}


?>