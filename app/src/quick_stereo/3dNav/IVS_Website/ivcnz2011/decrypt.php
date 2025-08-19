<?
if($_SERVER['REQUEST_METHOD'] == "POST"){
//echo "<h1>".base64_decode(base64_decode(strrev($_POST["textfield"])))."</h1>";
echo "<h1>".strrev(base64_decode(strrev(base64_decode($_POST["textfield"]))))."</h1>";
//return;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <p>
    <input name="textfield" type="text" id="textfield" size="500" />
  </p>
  <p>
    <input type="submit" name="button" id="button" value="Submit" />
</p>
</form>
</body>
</html>
