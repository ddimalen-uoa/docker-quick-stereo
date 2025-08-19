<?php
session_start();
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
include 'dbc.php';
require_once('recaptcha/recaptchalib.php');                      
$privatekey = "6LevANMSAAAAAGTzbBt0pN0K4vnXJk2P018-OzZP ";
$resp = recaptcha_check_answer ($privatekey,
							  $_SERVER["REMOTE_ADDR"],
							  $_POST["recaptcha_challenge_field"],
							  $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	  $_SESSION["feedback"] = $_POST["feedback"];
	  echo "<script language=javascript>alert('The reCAPTCHA wasn\'t entered correctly. Go back and try it again.');document.location='index.php';</script>";
} else {
	$sql = "INSERT INTO `quick_stereo_feedback` (
	`id` ,
	`feedback` ,
	`ip` ,
	`timePost`
	)
	VALUES (
	NULL , :feedback, :ipaddress, NOW()
	);";
	$feedback = $_POST["feedback"];
	$stmt = $dbc->prepare($sql);	
	$stmt->execute(array(':feedback' => $feedback, ':ipaddress' => $ipaddress));
	echo "<script language=javascript>alert('Thank you for your feedback, we will look at this to improve for a better version.'); document.location='index.php';</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Feedback</title>
</head>

<body>
</body>
</html>
