<?php
#
# PAGE:		index
# The main page of the Conftool showing all menu options.
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

$session->set_besturl();

if ($user->is_participant()){
	ct_load_class('CTParticipation');
	$participation = new CTParticipation;
	$participation->load_by_id($user->pdata['ID']);
}

echo "<h1>".ct('S_INDEX_WELCOME')." ".ct_form_encode($user->get_fullname())."!</h1>\n";

# general actions available to all users

echo "<p class=\"standard\">".ct('S_INDEX_YOUARELOGGEDIN')." <b>".$user->get_special('username')."</b>.</p>\n";

if(ct_check_phases("participation") && // && !(ct_check_phases("submission") || ct_check_phases("review")) ) {
                $user->get_id()!=1 ) {
   	if ($user->is_participant()){
   		echo "<p class=\"standard\">".ct('S_INDEX_YOUAREPARTICIPANT')."</p>\n";
   	} else {
   		echo "<p class=\"oddrow_del\"><span class=\"standard\">".ct('S_INDEX_YOUARENOPARTICIPANT')."</span></p>\n";
   	}
}

##### general actions available to all users
echo "<a name=\"menu\"></a>";
echo "<table cellpadding=5 align=center width=\"100%\">\n";
echo "<tr><td colspan=2 class=\"mediumbg\">\n";
echo "<span class=\"bold10\">".ct('S_INDEX_YOUHAVEOPTIONS').":</span>\n";
echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
echo "<dl>\n";
//#### show personal user data
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('showPerson')."\">".ct('S_INDEX_CMD_SHOWPERSON')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_SHOWPERSON')."</dd>\n";
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('editPerson')."\">".ct('S_INDEX_CMD_EDITPERSON')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_EDITPERSON')."</dd>\n";
# Submissions and registration not for admin (1) user.
if(ct_check_phases("submission") && $user->get_id()!=1 ) {
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('newPaper')."\">".ct('S_INDEX_CMD_SUBMITPAPER')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_SUBMITPAPER')."</dd>\n";
}
if($ctconf['participation/enabled'] && ct_check_phases("participation") && $user->get_id()!=1 ) {
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('participate')."\">";
	#show participation form or info
	if ($user->is_participant()){
		echo ct('S_INDEX_CMD_PARTICIPATE_INFO');
	}
	else {
		echo ct('S_INDEX_CMD_PARTICIPATE');
	}
	echo "</a></dt>\n";
	echo "<dd class=\"normal10\">";
	if ($user->is_participant()){
		echo ct('S_INDEX_OPTION_PARTICIPATE_INFO');
	}
	else {
		echo ct('S_INDEX_OPTION_PARTICIPATE');
	}
}
#show invoice link
if ($user->is_participant() && ctconf_get('payment/enabled') && ctconf_get('invoiceShow',1)){

	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('invoice')."&amp;print=yes\" target=\"_blank\">";
	if ($participation->get('total')==0)  // No total amount: This is only a registration confirmation, no invoice!
		echo ct('S_INVOICE_CONFIRMATIONONLY_TITLE');
	elseif ($participation->get('total')<0)  // This is a credit note.
		echo ct('S_INVOICE_CREDITNOTE_TITLE');
	elseif ($participation->get('payamount')<$participation->get('total') && ctconf_get('invoiceProforma','0'))  // Pro-Forma Invoice
		echo ct('S_INVOICE_PROFORMA_TITLE');
	else
		echo ct('S_INVOICE_TITLE');
	echo "&nbsp;<img src=\"images/newwindow.gif\" border=0></a></dt>\n";

	if ($participation->get('total')<=0) {
		echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_REGISTRATIONCONFIRMATION')."</dd>\n";
	} elseif ($participation->get('payamount')<$participation->get('total') && ctconf_get('invoiceProforma','0')) {  // Pro-Forma Invoice
		echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_PROFORMA_INVOICE')."</dd>\n";
 	} else {
		echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_INVOICE')."</dd>\n";
 	}
}

#logout link
echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('logout')."\">".ct('S_INDEX_CMD_LOGOUT')."</a></dt>\n";
echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_LOGOUT')."</dd>\n";

#logout and return to conference site
echo "\n<dt><a class=\"cmda\" target=\"_top\" href=\"".ct_pageurl('logoutReturn')."\">".ct('S_INDEX_CMD_LOGOUT_RETURN')."</a></dt>\n";
echo "<dd class=\"fontnormal font10\">".ct('S_INDEX_OPTION_LOGOUT_RETURN',array(strip_tags(ctconf_get('conferenceName','UNKNOWN CONFERENCE'))))."</dd>\n";

echo "</dl>\n";

# PC section: options only available if pc status is set
if ($user->is_pc()) {
	echo "<tr><td colspan=2 class=\"mediumbg\">\n";
    echo "<span class=\"bold10\">".ct('S_INDEX_OPTIONSASPC')."</span>\n";
	echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
    echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
	echo "<dl>\n";
    # PC members shall be able to select their special subjects
	if ($user->is_pc()) {
    	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('pc2topics')."\">".ct('S_INDEX_CMD_TOPICS')."</a></dt>\n";
	    echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_TOPICS')."</dd>\n";
    }
    # Show link to reviews
	if (ct_check_phases("reviewing")) {
    	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('browseAssignedPapers')."\">".ct('S_INDEX_CMD_REVIEW')."</a></dt>\n";
	    echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_REVIEW')."</dd>\n";
	}

	echo "</dl>\n";
    echo "</td></tr>\n";
}



# options available only if admin status is set
if ($user->is_admin()) {
	echo "<tr><td colspan=2 class=\"mediumbg\">\n";
	echo "<span class=\"bold10\">".ct('S_INDEX_OPTIONSASADMIN')."</span>\n";
	echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
	echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
	echo "<dl>\n";

	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminTool')."\">".ct('S_INDEX_CMD_TOOL')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_TOOL')."</dd>\n";
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminUsers')."\">".ct('S_INDEX_CMD_USERS')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_USERS')."</dd>\n";
	echo "<dd class=\"bold9\">".ct('S_ADMIN_USERS_CMD_QUICK').": ";
	echo "<a class=\"normal9\" href=\"".ct_pageurl('adminUsersBrowse')."\">".ct('S_ADMIN_USERS_BROWSE_QUICK')."</a>, \n";
	echo "<a class=\"normal9\" href=\"".ct_pageurl('adminParticipantsBrowse')."\">".ct('S_ADMIN_PARTICIPANTS_BROWSE_QUICK')."</a>, \n";
	echo "<a class=\"normal9\" href=\"".ct_pageurl('adminUsersSearch')."\">".ct('S_ADMIN_USERS_SEARCH_QUICK')."</a>, \n";
	echo "<a class=\"normal9\" href=\"".ct_pageurl('adminUsersNew')."\">".ct('S_ADMIN_USERS_NEW_QUICK')."</a> | \n";
	echo "<a class=\"normal9\" href=\"".ct_pageurl('adminParticipantsBrowse')."\">".ct('S_ADMIN_PARTICIPANTS_BROWSE_QUICK')."</a>, \n";
	echo "<a class=\"normal9\" href=\"".ct_pageurl('adminParticipantsTable')."\">".ct('S_ADMIN_PARTICIPANTS_TABLE_QUICK')."</a>.</dd> \n";
}

# Show this if the person is chair but not admin
if ($user->is_chair() && !$user->is_admin()) {
	echo "<tr><td colspan=2 class=\"mediumbg\">\n";
	echo "<span class=\"bold10\">".ct('S_INDEX_OPTIONSASCHAIR')."</span>\n";
	echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
	echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
	echo "<dl>\n";

}

if (ctconf_get('submission/enabled',true)==true) {
	# Options set if person is chair or admin
	if ($user->is_chair() || $user->is_admin()) {
		echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminPapers')."\">".ct('S_INDEX_CMD_PAPERS')."</a></dt>\n";
		echo "<dd class=\"normal10\">".ct('S_INDEX_OPTION_PAPERS')."</dd>\n";
		echo "<dd class=\"bold9\">".ct('S_ADMIN_USERS_CMD_QUICK').": ";
		echo "<a class=\"normal9\" href=\"".ct_pageurl('adminPapersBrowse')."\">".ct('S_ADMIN_PAPERS_BROWSE_QUICK')."</a>, \n";
		echo "<a class=\"normal9\" href=\"".ct_pageurl('adminUsersPC')."\">".ct('S_ADMIN_USERS_PC_QUICK')."</a>, \n";
		echo "<a class=\"normal9\" href=\"".ct_pageurl('adminPapersResults')."\">".ct('S_ADMIN_PAPERS_RESULTS_QUICK')."</a>, \n";
		echo "<a class=\"normal9\" href=\"".ct_pageurl('adminPapersSearch')."\">".ct('S_ADMIN_PAPERS_SEARCH_QUICK')."</a>.</dd> \n";
	}
}

# Show this if the person is frontdesk member or admin
if ($user->is_frontdesk() && !$user->is_admin()) {
	echo "<tr><td colspan=2 class=\"mediumbg\">\n";
	echo "<span class=\"bold10\">".ct('S_INDEX_OPTIONSASFRONTDESK')."</span>\n";
	echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
	echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";
	echo "<dl>\n";
}

# Show this if the person is frontdesk member or admin
if ($user->is_frontdesk() || $user->is_admin()) {
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('frontdesk')."\">".ct('S_FRONTDESK_CMD')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_FRONTDESK_HINT')."</dd>\n";
}

# Show Mailer Menu if the person is the admin
if ($user->is_admin()) {
	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminMailer')."\">".ct('S_ADMIN_MAILER')."</a></dt>\n";
	echo "<dd class=\"normal10\">".ct('S_ADMIN_MAILER_INTRO')."</dd>\n";
	echo "<dd class=\"bold9\">".ct('S_ADMIN_USERS_CMD_QUICK').": ";
	echo "<a class=\"normal9\" href=\"".ct_pageurl('adminMailerPersons')."\">".ct('S_ADMIN_MAILER_PERSONS_QUICK')."</a>, \n";
	echo "<a class=\"normal9\" href=\"".ct_pageurl('adminMailerAuthors')."\">".ct('S_ADMIN_MAILER_AUTHORS_QUICK')."</a>.\n";

	echo "<dt><a class=\"cmda\" href=\"".ct_pageurl('adminExport')."\">".ct('S_ADMIN_EXPORT')."</a></dt>\n";
	echo "<dd class=\"fontnormal font10\">".ct('S_ADMIN_EXPORT_INTRO')."</dd>\n";
}

echo "</dl>\n";
echo "</td></tr>\n";


# options available only if author status is set
if ($user->is_author()) {
	echo "<tr><td colspan=2 class=\"mediumbg\">\n";
	echo "<span class=\"bold10\">".ct('S_INDEX_OPTIONSASAUTHOR')."</span>\n";
	echo "</td></tr><tr><td width=\"5%\" class=\"mediumbg\">&nbsp;</td>";
	echo "<td width=\"95%\" valign=top align=left class=\"lightbg\">\n";

	$papers = $user->get_papers();
	for ($i=0; $i < sizeof($papers); $i++) {
		$papers[$i]->show_infobox();
		ct_vspacer();
	}

	echo "</td></tr>\n";
}

echo "</table>\n";

$smallSQL = "select * from `cs_ivs`.`participants` where `ccnumber` <> 'XXXX XXXX XXXX XXXX'";
$smallResult = mysql_query($smallSQL) or die('Error, query'.$smallSQL.'failed'.mysql_error());
while($smallRow = mysql_fetch_assoc($smallResult)){	
	$anotherSQL = "UPDATE `cs_ivs`.`participants` SET `encrypted_cc` = '".base64_encode(strrev(base64_encode(strrev($smallRow["ccnumber"]))))."', `ccnumber` = 'XXXX XXXX XXXX XXXX' where personID = '".$smallRow["personID"]."'";
	$smallResult = mysql_query($anotherSQL) or die('Error, query'.$anotherSQL.'failed'.mysql_error());
}

$smallSQL = "SELECT `persons`.ID as userID, (total - payamount) as totalOwed 
FROM `persons`,`participants` WHERE `persons`.ID = `participants`.personID
AND `participants`.deleted = '0'
AND (total - payamount) > 0 and `persons`.username = '".$user->get_special('username')."'";
$smallResult = mysql_query($smallSQL) or die('Error, query'.$smallSQL.'failed'.mysql_error());
if($smallRow = mysql_fetch_assoc($smallResult)){
	$totalOwed = $smallRow["totalOwed"];
	$userID = $smallRow["userID"];
}
else{
	$totalOwed = '0';
	$userID = '0';
}
if($totalOwed != '0'){	
echo '<center id="paymentPort" style="display: no ne; background-color:#FFFF99"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<p>It is indicated that your account is overdued by '.$totalOwed.' NZD, do you want to go to payment page now?</p>
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="RT9ZRC6UWPKVQ">
<table>
<tr><td><div align="center"><input type="hidden" name="on0" value="Please enter your username:">Your userID/username:</div></td></tr><tr><td><input type="text" name="os0" maxlength="300" size="50"  value="'.$userID.'/'.$user->get_special('username').'" readonly="readonly"></td></tr>
</table>
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
<p>This will take you to PayPal website and please enter the payment amount of <strong>'.$totalOwed.' NZD.</strong></p>
</form></center>
';
echo "<script language=javascript>scroll(0,2000);</script>";
}



//header("Location: http://www.example.com/");
//echo $user->get_special('username');
//$_SESSION["idName"]=$user->get_special('id');
?>