<?php 

// open the current directory by opendir
$result_file="shadow/stereogram.jpg";
$upload_file = "shadow/stereogram.txt";
$number = $_GET["number"];
//echo $needToFind;

if (file_exists($result_file)) { 
	//echo "here";
	if (file_exists($upload_file)) { 
		echo '<p><h1>Stereogram is reconstructing...&nbsp;</h1></p><p><img src="http://www.ivs.auckland.ac.nz/images/waiting.gif"></p>';
	}
	else{
		echo '
		<hr>
		<strong>		
		<img id = "resultImage" src="shadow/stereogram.jpg?'.$number.'" >
		<hr>
		</strong>'; //"$file";
		
	}
}
else
{
	//echo "here else ";
	echo '<img src="http://www.ivs.auckland.ac.nz/images/waiting.gif" width="400" height="400">';
}
closedir($handle);
?>
