	
	<?php 
$result_file="../quick_stereo/upload_stereo/tsaiCalibResult.txt";
$number = $_GET["number"];
//echo $needToFind;
if (file_exists($result_file)) { 	
	echo "<strong><span id = 'tsaiCalibResult'>";
	$file_handle = fopen($result_file, "r");
	?>
    <table width="60%" border="1" cellspacing="1" cellpadding="1" bgcolor="#CCCCCC">
      <tr>
        <td> <strong>
    <?
	while (!feof($file_handle)) {
	   $line = fgets($file_handle);
	   echo $line."<br/>";
	}
	?>
    	</strong>
        </td>
      </tr>
    </table>
    <?
	fclose($file_handle);	
	echo "</span></strong>";
}
else
{	
	echo '<img class="intermediatedResult" src="../images/waiting.gif">';
}
closedir($handle);
?>