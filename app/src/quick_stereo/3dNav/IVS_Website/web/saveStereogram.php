<?
$randomNumber = rand()%1000;
if (copy("shadow/autostereogram.jpg", "shadow/stereograms/".$randomNumber.".jpg")) {
    echo "Saved depth map <br>";
}
?>
<meta http-equiv="refresh" content="0; url=autostereogram.php">