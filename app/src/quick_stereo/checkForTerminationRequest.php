<?php
if(isset($_GET["terminate"])) {
	copy("upload_stereo/old_server_life.txt", "upload_stereo/server_life.txt");
	copy("upload_stereo/old_server_life.txt", "upload_stereo/restartServer.txt");
	unlink("upload/upload.txt");
	unlink("upload/left.jpg");
	unlink("upload/right.jpg");
	?>
    <meta http-equiv="refresh" content="10; url=index.php">
    <center><h1>
    All tasks are forced to be terminated, you will be redirected back to homepage in 10 seconds.<br>
    If server is not yet back online, please refresh the page.<br>
	If the problem still remains, our server is probably disconnected from the network.
</h1></center>
    <?php
	return;
}
//Stop reported Spam IP Address to access this page.
if(strpos($spamIP, $ipaddress) > 0)
{
	echo "spam stopper";
	return;
}

if($_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'],'ivs.auckland.ac.nz') < 0){
	echo "spam stopper";
	return;
}
?>