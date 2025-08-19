<?
if(isset($_GET["number"])){
	$numberGenerated = $_GET["number"];
	$fp = fopen('upload/upload.txt', 'w');
	fwrite($fp, $numberGenerated);									
	fclose($fp);
	$fp = fopen('upload/upload_.txt', 'w');
	fwrite($fp, $numberGenerated);									
	fclose($fp);
	echo "Success generate number ".$numberGenerated;	
}
else{
	$file_handle = fopen("upload/upload_.txt", "r");
	while (!feof($file_handle)) {
	   $line = fgets($file_handle);
	   echo $line;
	}
	fclose($file_handle);
}
?>
<?
$days = 20;
$dir = './';
    if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file[0] == '.' || is_dir($dir.'/'.$file)) {
            continue;
        }
        if ((time() - filemtime($dir.'/'.$file)) > ($days *86400)) 
		{
            //echo "delete: ".$file."<br/>";
			unlink($dir.'/'.$file);
        }
    }
    closedir($handle);
}
?>