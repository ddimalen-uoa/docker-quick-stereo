<?
$fp = fopen('../web/shadow/stereogram.txt', 'w');
fwrite($fp, "//webshare.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/quick_stereo/upload_stereo/result_segmented.jpg");
fwrite($fp, "\n");
fwrite($fp, "//webshare.fos.auckland.ac.nz/web/www.ivs.auckland.ac.nz/web/shadow/".$row["url"]);
fwrite($fp, "\n");
fclose($fp);
?>
