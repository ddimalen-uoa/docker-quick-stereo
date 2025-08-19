<?php
session_start();
$LOGIN_INFORMATION = array("1984", "12345", "9999", "20131415");

// User will be redirected to this page after logout
define('LOGOUT_URL', 'index.php');

// time out after NN minutes of inactivity. Set to 0 to not timeout
define('TIMEOUT_MINUTES', 30);

// define how many time user can try
define('TOTAL_TRY', 7);

// This parameter is only useful when TIMEOUT_MINUTES is not zero
// true - timeout time from last activity, false - timeout time from login
define('TIMEOUT_CHECK_ACTIVITY', true);

// Set initial counter;
if (!isset($_SESSION['counter'])) $_SESSION['counter'] = TOTAL_TRY; 

##################################################################
#  SETTINGS END
##################################################################


///////////////////////////////////////////////////////
// do not change code below
///////////////////////////////////////////////////////

// show usage example
if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}

// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// logout?
if(isset($_GET['logout'])) {
  unset($_SESSION['counter']); // clear counter;
  setcookie("access_code", '', $timeout, '/'); // clear username;
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

// show login form
function showLoginPasswordProtect($error_msg) {
?>
<html>
<head>
  <title>Please enter password to access this page</title>
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">  
  <div style="width:100%; margin-left:auto; margin-right:auto; text-align:center">
  <? if($_SESSION['counter'] > 0) { ?>
  <form method="post">
    <h3>Please enter a code to access this page, maximum <? echo $_SESSION['counter']; ?> tries.</h3>
    <font color="red"><?php echo $error_msg; ?></font><br />
    <input type="password" name="access_password" /><p></p><input type="submit" name="Submit" value="Submit" />
  </form>
  <? } ?>
  <p>
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
 
  <tr>
    <td width="800" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      
      <tr>
        <td width="100%" height="29" ><div align="center"><span id="clock" class="dateTxt"></span></div></td>
      </tr>
      <tr>
        <td valign="top"><div align="center">
          <br>
            We're offline for awhile to the public.<br> <br>Access is available to those requesting it. <br><br>Please visit our <a href="http://www.facebook.com/2Review">Facebook</a><br>
            <br>
            or <a href="http://www.twitter.com/2ReviewCo">Twitter pages</a>.<br>
            <br>Thank You.
        </div></td>
      </tr>
            <tr>
        		<td valign="top"><div align="center"><img src="http://www.2review.net/images/new-website-coming-soon-21.png"></div></td>

      </tr>
            <tr>
        <td valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        </tr>
      
    </table></td>
    
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
</table>
  </p>
</div>
</body>
</html>

<?php
  // stop at this point
  die();
}
}

// user provided password
if (isset($_POST['access_password'])) {

  $login = 'access_code';
  $pass = $_POST['access_password'];
  if (!in_array($pass, $LOGIN_INFORMATION))
  {
  	if($_SESSION['counter'] > 0) $_SESSION['counter']--;
    showLoginPasswordProtect("Sorry, those attempts were incorrect.");
	
  }
  else {
    // set cookie if password was validated
    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');
	setcookie("access_code", $login, $timeout, '/');

    // Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
    // So need to clear password protector variables    
    unset($_POST['access_password']);
    unset($_POST['Submit']);
	unset($_SESSION['counter']);
  }
}
else {
  // check if password cookie is set
  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }
  // check if cookie is good
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = 'access_code'.'%'.$val;
    if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;
      // prolong timeout
      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");
  }
}

?>