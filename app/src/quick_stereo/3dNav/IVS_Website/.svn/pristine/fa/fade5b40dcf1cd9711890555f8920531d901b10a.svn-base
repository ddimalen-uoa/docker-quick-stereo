<?php 
$result_file = 'images/test.jpg';
$result_txt_file = 'images/test.txt';
$flag_file = 'images/startRecoginised.txt';
if (file_exists($result_file) && file_exists($result_txt_file)) { 	
	if (file_exists($flag_file)) { 
		echo '<p><h1>Please wait...</h1></p><p><img src="http://www.ivs.auckland.ac.nz/images/waiting.gif"></p>';
	}	
	else{
		echo "<h1 id=\"done\">Recognition is finished, result will be displayed in 5s</h1>";
	}
}
else
{	
	echo '<h1>Please wait...</h1></p><p><img src="http://www.ivs.auckland.ac.nz/images/waiting.gif">';
}
closedir($handle);
?>
