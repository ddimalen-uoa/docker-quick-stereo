<?php
//
//	ConfTool footer with status and copyright information...
//
//	############################################################################
//	# Do NOT modify this file without to confer with Harald Weinreich first.   #
//	# Especially DO NOT remove our copyright notice and the links to our pages #
//	############################################################################
//
//  If you want to add images, logos or links to the foot of the system, please
//  use "conftool/etc/sitefooter.inc.php"
//

if (!defined('CONFTOOL')) die('Hacking attempt!');

GLOBAL $pagepath;
//
// Navigation area and "Print View"
ct_vspacer();
echo "<table width=\"100%\" cellspacing=0 cellpadding=2 border=0>\n";
echo "<tr>\n";
echo "<td class=\"cthead_td_cmds\" align=\"left\" nowrap><span class=\"cthead_cmds\">\n";
if (isset($pagepath)) {
	while (list(,$p) = each($pagepath)) {
		echo "&nbsp;&gt; <a href=\"".ct_pageurl($p)."\" class=\"cthead_a\">$p</a>\n";
	}
	echo "&nbsp;&gt; ".$http['page'];
} else {
	echo "&nbsp;";
}
echo "</span></td>\n";

echo "<td valign=\"top\" align=\"right\" width=\"15%\" class=\"cthead_td_cmds\" nowrap>";
if ($http['page']!="login" &&
		strlen(ct_pageurl($http['page'],ct_http_array(array('print' => "yes"))))<2048) {
	echo "<a class='ctfoot' target='_blank' href=\"".ct_pageurl($http['page'],ct_http_array(array('print' => "yes")))."\" >".ct('S_CTFOOT_PRINTVIEW')." <img src=\"".ct_getbaseurl()."images/printer.gif\" alt=\"".ct('S_CTFOOT_PRINTVIEW')."\" border=0 align='top'></a>";
}else{
	echo "&nbsp;";
}
echo "</td>";
echo "</tr>\n</table>\n";

//
// Status and copyright information - Please do NOT remove!
echo "<table class='whitebg' cellpadding=\"0\" cellspacing=\"2\" border=\"0\" width=\"100%\">\n";
echo "<tr><td nowrap>\n";
echo "<span class=\"ctfoot\">".ct('S_CTFOOT_EMAIL').": ".ct_encodeMail(ctconf_get('conferenceContactEmail','E-Mail address missing!'))."</span>";
echo "<br><span class=\"ctfoot\">".ct('S_CTFOOT_CONFERENCE').": <a href=\"".ctconf_get('conferenceURI','http://www.conftool.net/')."\">".ctconf_get('conferenceShortName','UNKNOWN CONFERENCE')."</a></span>";
// echo "<br><span class=\"ctfoot\">".ct('S_CTFOOT_MODIFIED').": ".date("d.m.Y - H:i", getLastMod())."</span>";
// echo "<br><span class=\"ctfoot\">".ct('S_CTFOOT_URL').": ".ct_getbaseurl(false).substr($_SERVER['REQUEST_URI'],0,70)."</span>";
echo "</td>\n";

echo "<td align=\"right\" nowrap>";
echo "<span class=\"normal8\">";
echo "<a href='http://www.conftool.net'>";
if (ctconf_get('language')=='german') {
	if (ctconf_get('submission/enabled',true)==false) $ctnames = array("Konferenz-Registrierungssystem","Web-basierte Teilnehmerverwaltung","Web-Anmeldesystem");
	elseif (ctconf_get('participation/enabled',true)==false) $ctnames = array("Online-Einreichungssystem","Abstract Einreichungssystem");
	else $ctnames = array("Konferenzverwaltungs-Software","Konferenzverwaltungssystem","Kongressverwaltungssystem","Kongressverwaltungs-Software","Event-Management-Software","Web-basierte Konferenz-Software","Online-Konferezverwaltung");
} else {
	if (ctconf_get('submission/enabled',true)==false) $ctnames = array("Conference Registration Software","Registration Management Tool","Participant Management Software");
	elseif (ctconf_get('participation/enabled',true)==false) $ctnames = array("Submission Management Software","Abstract Management System","Paper Submission and Reviewing System","Abstract Submission and Reviewing System");
	else $ctnames = array("Conference Software","Conference Management System","Conference Management Software","Conference System","Event Management System","Online Conference Software","Web-based Conference Software");
}
$ctname=$ctnames[hexdec(substr(md5(ct_getbaseurl()),0,8))%count($ctnames)];
echo (strlen($ctname)<5?'Conference Software':$ctname)." - ";
echo "VSIS <b>ConfTool</b></a> Standard ".$ctconf['version']."</span><BR>\n";
echo "<span class=\"normal8\">\n&copy; 2001 - 2011 by";
echo "<a href=\"imprint.php\">";
echo " H. Weinreich, Hamburg, Germany</a>";
echo "</span></td>\n";

echo "</tr></table>";

?>

