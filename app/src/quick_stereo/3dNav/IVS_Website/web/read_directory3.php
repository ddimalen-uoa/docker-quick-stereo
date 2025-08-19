<?php 

// open the current directory by opendir
$result_file="shadow/autostereogram.jpg";
$upload_file = "shadow/stereogram3.txt";
$number = $_GET["number"];

//echo $needToFind;
$questionList=array();
$questionList[0]="autoStereogram";
$questionList[1]="edge";
$questionList[2]="texture";
$questionList[3]="crossParallel";
$questionList[4]="mask";

$questionLists=array();
$questionLists[$questionList[0]] = "Does it work? Can you see the 3D affect of this autostereogram?";
$questionLists[$questionList[1]] = "Do you find the extra edges useful to view autostereogram?";
$questionLists[$questionList[2]] = "Do you find using its own image texture better than random dots?";
$questionLists[$questionList[3]] = "Do you find using cross-eyed view easier than parallel-eyed view?";
$questionLists[$questionList[4]] = "Do you find having a mask image helpful?";

$questionChoosen = $questionList[rand()%5];

if (file_exists($result_file)) { 	
	if (file_exists($upload_file)) { 
		echo '<p><h1>Auto-stereogram is reconstructing...&nbsp;</h1></p><p><img src="http://www.ivs.auckland.ac.nz/images/waiting.gif"></p>';
	}
	else{
		echo '
		<hr>
		<strong>		
		<img id = "resultImage" src="shadow/autostereogram.jpg?'.$number.'" >
		<hr>
		<a href="saveStereogram.php">Save this to Public Gallery?</a>
		<hr>
		If you can not see the 3D effect, <a href="#bottom" onclick="if(document.getElementById(\'imageExample\').style.display==\'none\') document.getElementById(\'imageExample\').style.display=\'\'; else document.getElementById(\'imageExample\').style.display=\'none\';">please hold a pen 25 cm away from your eyes and look at the pen</a>. Be patient!<br />
		'.$questionLists[$questionChoosen].'<br />
		<input name="" type="button" value="Yes" onclick="window.open( \'answer.php?question='.$questionChoosen.'&answer=yes\', \'Feedback\', \'status = 0, height = 200, width = 600, resizable = 0\' );" /> 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="" type="button" value="No" onclick="window.open( \'answer.php?question='.$questionChoosen.'&answer=no\', \'Feedback\', \'status = 0, height = 200, width = 600, resizable = 0\' );" />
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
