<?php
// Conference Header
// Add any code for your conference here if necessary :-)
if (!defined('CONFTOOL')) die('Hacking attempt!');
?>

<table border=0 width="100%" bgcolor="#ffffff" cellpadding=0 cellspacing=0><tr>
<td align=left><img src="http://www.ivs.auckland.ac.nz/ivcnz2011/images/new_logo.jpg" width="156" border=0 alt="ConfTool Logo"></td>
<td align=center>
<h1 style='margin-bottom:1px;'><a target="_self" href="<?php echo ctconf_get('conferenceURI',''); ?>" style="font: 16pt Arial, Helvetica, sans-serif; font-weight:bold; color:navy;">
<div style="text-align:center; padding-top:1em;">
						  <h3 style="margin-top:0px;"><img src="http://www.ivs.auckland.ac.nz/ivcnz2011/images/ivcnz2010.png" width="100%" /></h3>
	  </div></a></h3>
<h2 style="font: 12pt Arial, Helvetica, sans-serif; font-weight:bold; color:#666666; margin:0pt; padding:1pt;"><?php echo ctconf_get('conferenceSubtitle'); ?></h4>
<h2 style="font: 12pt Arial, Helvetica, sans-serif; font-weight:bold; color:#000000; margin:0pt; padding:1pt;"><?php echo ctconf_get('conferenceSubtitle2'); ?></h4>
</td>

  <td align=right>
  <img src="http://www.ivs.auckland.ac.nz/ivcnz2011/images/ua.gif" width="156" border=0 alt="ConfTool Logo">
  </td>
</tr></table>
