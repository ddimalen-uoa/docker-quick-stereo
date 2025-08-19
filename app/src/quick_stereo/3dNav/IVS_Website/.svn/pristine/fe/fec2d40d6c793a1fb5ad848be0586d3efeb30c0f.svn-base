<?php 

// open the current directory by opendir
$file1="../upload/temp";
$file2="../upload/certificate.txt";
$file3="../upload/certificate_returned.txt";
//echo $needToFind;
if (file_exists($file2) && file_exists($file3)) { 
	?>    
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
      <tr>
        <td bgcolor="#FFFFCC"><div align="center" id="tableKey"><?
$f = fopen($file3, "r");
if ( $line = fgets($f, 10000) ) {
print $line;
}
?></div></td>
      </tr>
    </table>
<?
}
else
{	
	echo '<img src="http://www.ivs.auckland.ac.nz/images/waiting.gif" width="50" height="50">';
}
?>

