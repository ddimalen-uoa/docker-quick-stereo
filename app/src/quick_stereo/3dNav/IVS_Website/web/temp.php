<?
$file = @fopen('../stereogallery/iPhone/dirlist.txt', "r") ;  


// while there is another line to read in the file
$count = 0;
while (!feof($file))
{
    // Get the current line that the file is reading
	$currentLine = fgets($file) ;
	if(($count)%2==0) echo "autoRectificationOnly.exe ";
    echo "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogallery/iPhone/".$currentLine." ";
	if(($count)%2==1) echo "//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogallery/iPhone/".$count."_l.jpg "."//files.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/stereogallery/iPhone/".$count."_r.jpg<br/>";
	$count++;

}   

fclose($file) ;
?>

