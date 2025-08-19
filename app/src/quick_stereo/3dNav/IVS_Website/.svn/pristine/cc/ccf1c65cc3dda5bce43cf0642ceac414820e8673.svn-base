<?php
//
// PAGE:		offline
// Message that ConfTool is offline (as for database errors...)
//
if (!defined('CONFTOOL')) die('Hacking attempt!');
?>
    <br><br>
    <table width="80%" align="center" class="tbldialog" cellspacing=25 cellpadding=2 border=0>
    <tr><td class="td_dlg_title"><h2 align=center><?php echo ctconf_get('name') ?></h2>
    </td></tr>
    <tr><td class="form_td_field_error" align=center>
<?php
if (empty($ctconf['offline/message'])) {
    echo "<H3>... is momentary closed for maintenance!</H3>\n";
    echo "<H4>Please come back again in a few minutes.</H4>\n";
} else {
	echo $ctconf['offline/message'];
}
?>
    </td></tr>
    <tr><td align="center"><H4>Please report any persistent problems to</h4>
      <H4><?php echo ct_encodeMail(ctconf_get('conferenceContactEmail',ctconf_get('mail/contact','info@conftool.net'))) ?></h4></td></tr>
    </table>
	<br><br>
