<?
$mouseX = $_REQUEST['mouseX'];
$mouseY = $_REQUEST['mouseY'];
$pack = array( // Default values
		'mouseX'=>'shitX'.$mouseX,
		'mouseY'=>'shitY'.$mouseY,
		'notice'=>'refreshing - '.rand(),
		'result'=>'<img src="http://www.ivs.auckland.ac.nz/web/shadow/result.png?'.rand().'" />',
	);
	
$fp = fopen('shadow/request.txt', 'w');
fwrite($fp, $mouseX);
fwrite($fp, "\n");
fwrite($fp, $mouseY);
fwrite($fp, "\n");
fclose($fp);
			
echo json_encode($pack); 
//--------------------------------------------------------------------------------------------------
?>