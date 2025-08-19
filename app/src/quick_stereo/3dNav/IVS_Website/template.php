<? 	
	session_start();	
	$db_host     = '207.210.192.92';
	
	//check if the computer has coookie set before
	if (!isset($_COOKIE['w2g_cookie'])) {	
		$newID = '';
		for($i = 0; $i < 30; $i++){
			$newID.=rand(0,9);
		}
		setcookie('w2g_cookie', $newID, time() + 71536000, '/', '.2review.net');//set this cookie to last for 100 years
		setcookie('w2g_cookie', $newID, time() + 71536000, '/', '.mkiq.net');//set this cookie to last for 100 years
	}
	
	$conn = mysql_pconnect($db_host, $db_username, $db_password);
	mysql_select_db($db_name);
	
	$sql = "select Rule.rule_detail from Rule where Rule.rule_description = 'Cookie_Length_Mins_Always_On'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$_SESSION["Cookie_Length_Mins_Always_On"] = 60*$row["rule_detail"];
	
	$sql = "select Rule.rule_detail from Rule where Rule.rule_description = 'Cookie_Length_Mins_Standard'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$_SESSION["Cookie_Length_Mins_Standard"] = 60*$row["rule_detail"];
	
	$sql = "select * from Site_Status where Site_Status.Current_Status = 'y' and Site_Status = 'Offline'";
	$result = mysql_query($sql);
	if($row = mysql_fetch_assoc($result)){
		if(strtolower($_SERVER['PHP_SELF']) != '/offline.php')
		 	echo "<script language=javascript>document.location='offline.php';</script>";
	}
	
	$sql = "SELECT * FROM Available_Languages where `status` = 'completed'";
	$result = mysql_query($sql);
	$languageRow = '';
	while($row = mysql_fetch_assoc($result)){
		$languageRow .= '<option value="'.$row["Language"].'">'.$row["Language"].'</option>';
	}
	
	if(isset($_SESSION['w2g_email']) || isset($_SESSION["w2g_association_email"])) $sayOUT = "SIGN OUT"; else{ 
		$sayOUT = "SIGN IN";
		$registering = '';
		$registering_ = '<a href="register_respondent.php">
						<font color="#FFFFFF">( SIGN ME UP )-</font>
						</a>';
	}
	
	$domainName = str_replace("mkiq.net","MKIQ",str_replace("g","G",str_replace("www.","",$_SERVER['HTTP_HOST'])));
	if($domainName == "Where2g0.com")
		$imageLogo = "w2glogo.gif";
	else if($domainName == "MKIQ")
		$imageLogo = "mkiqt.gif";
	else if($domainName == "2review.net")
		$imageLogo = "2reviewlogo.gif";
	else
		$imageLogo = "w2glogo.gif";
	//mkiqtm.gif
	//small_logo.jpg
	
	//Get the Banner Right
	$change_Banner_Frequency = "refresh";
	if($_SERVER['HTTP_HOST']=="www.where2g0.com"){
		$directory = "/home/where2g0/public_html/images/Main_Banner/Public/";
		$sql = "SELECT Curdate() as todayDate, Image_Control.Change_Main_Banner_Public FROM Image_Control limit 1";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$change_Banner_Frequency = $row["Change_Main_Banner_Public"];
			$todayDate =  $row["todayDate"];
		}
		$sql = "select UserRoles.Slogan from UserRoles where UserRoles.User_Role = 'Public'";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$slogan = $row["Slogan"];
		}
		
	}	
	else if($_SESSION["w2g_association_email"] != "" && $_SERVER['HTTP_HOST']=="www.mkiq.net"){
		$directory = "/home/where2g0/public_html/images/Main_Banner/Associations/";
		$sql = "SELECT Curdate() as todayDate, Image_Control.Change_Main_Banner_Associations FROM Image_Control limit 1";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$change_Banner_Frequency = $row["Change_Main_Banner_Associations"];
			$todayDate =  $row["todayDate"];
		}
		$sql = "select UserRoles.Slogan from UserRoles where UserRoles.User_Role = 'Association'";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$slogan = $row["Slogan"];
		}
	}
	else if($_SESSION["w2g_user_role"] != "" || $_SERVER['HTTP_HOST']=="www.mkiq.net"){
		$directory = "/home/where2g0/public_html/images/Main_Banner/Businesses/";
		$sql = "SELECT Curdate() as todayDate, Image_Control.Change_Main_Banner_Businesses FROM Image_Control limit 1";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$change_Banner_Frequency = $row["Change_Main_Banner_Businesses"];
			$todayDate =  $row["todayDate"];
		}
		$sql = "select UserRoles.Slogan from UserRoles where UserRoles.User_Role = 'Business'";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$slogan = $row["Slogan"];
		}
	}
	else{
		$directory = "/home/where2g0/public_html/images/Main_Banner/Respondents/";
		$sql = "SELECT Curdate() as todayDate, Image_Control.Change_Main_Banner_Respondents FROM Image_Control limit 1";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$change_Banner_Frequency = $row["Change_Main_Banner_Respondents"];
			$todayDate =  $row["todayDate"];
		}
		$sql = "select UserRoles.Slogan from UserRoles where UserRoles.User_Role = 'Reviewer'";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$slogan = $row["Slogan"];
		}
	}
	//$mainBanner = "http://www.where2g0.com/Main_Banner/Businesses/1.jpg";	
	$fileCount = count(glob("" . $directory . "*.jpg"));
	if($change_Banner_Frequency == "refresh"){
		$randomNumber = rand() % $fileCount + 1;
		$mainBanner = str_replace("/home/where2g0/public_html/","http://www.where2g0.com/",$directory).$randomNumber.".jpg";
	} else if($change_Banner_Frequency == "daily"){
		$todayDate =  str_replace("-","",$todayDate);
		$todayDate =  str_replace("-","",$todayDate);
		$randomNumber = intval($todayDate) % $fileCount + 1;
		$mainBanner = str_replace("/home/where2g0/public_html/","http://www.where2g0.com/",$directory).$randomNumber.".jpg";
	} else if($change_Banner_Frequency == "logon"){
		if(!isset($_SESSION["session_banner"])){
			$_SESSION["session_banner"] = rand() % $fileCount + 1;
		}
		$mainBanner = str_replace("/home/where2g0/public_html/","http://www.where2g0.com/",$directory).$_SESSION["session_banner"].".jpg";
	}
	
	
	//side panel
	
	//Get the Side panel Right
	$change_Side_Panel_Frequency = "refresh";
	if($_SESSION["w2g_email"] == ""){
		$directory = "/home/where2g0/public_html/images/Side_Panel/Public/";
		$sql = "SELECT Curdate() as todayDate, Image_Control.Change_Side_Panel_Public FROM Image_Control limit 1";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$change_Side_Panel_Frequency = $row["Change_Side_Panel_Public"];
			$todayDate =  $row["todayDate"];
		}
	}
	else if($_SESSION["w2g_user_role"] != ""){
		$directory = "/home/where2g0/public_html/images/Side_Panel/Businesses/";
		$sql = "SELECT Curdate() as todayDate, Image_Control.Change_Side_Panel_Businesses FROM Image_Control limit 1";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$change_Side_Panel_Frequency = $row["Change_Side_Panel_Businesses"];
			$todayDate =  $row["todayDate"];
		}
	}
	else if($_SESSION["w2g_association_email"] != ""){
		$directory = "/home/where2g0/public_html/images/Side_Panel/Associations/";
		$sql = "SELECT Curdate() as todayDate, Image_Control.Change_Side_Panel_Associations FROM Image_Control limit 1";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$change_Side_Panel_Frequency = $row["Change_Side_Panel_Associations"];
			$todayDate =  $row["todayDate"];
		}
	}
	else{
		$directory = "/home/where2g0/public_html/images/Side_Panel/Respondents/";
		$sql = "SELECT Curdate() as todayDate, Image_Control.Change_Side_Panel_Respondents FROM Image_Control limit 1";
		$result = mysql_query($sql);
		if($row = mysql_fetch_assoc($result)){
			$change_Side_Panel_Frequency = $row["Change_Side_Panel_Respondents"];
			$todayDate =  $row["todayDate"];
		}
	}
		
	$fileCount = count(glob("" . $directory . "*.jpg"));
	if($change_Side_Panel_Frequency == "refresh"){
		$randomNumber = rand() % $fileCount + 1;
		$side_panel = str_replace("/home/where2g0/public_html/","http://www.where2g0.com/",$directory).$randomNumber.".jpg";
	} else if($change_Side_Panel_Frequency == "daily"){
		$todayDate =  str_replace("-","",$todayDate);
		$todayDate =  str_replace("-","",$todayDate);
		$randomNumber = intval($todayDate) % $fileCount + 1;
		$side_panel = str_replace("/home/where2g0/public_html/","http://www.where2g0.com/",$directory).$randomNumber.".jpg";
	} else if($change_Side_Panel_Frequency == "logon"){
		if(!isset($_SESSION["session_side_panel"])){
			$_SESSION["session_side_panel"] = rand() % $fileCount + 1;
		}
		$side_panel = str_replace("/home/where2g0/public_html/","http://www.where2g0.com/",$directory).$_SESSION["session_side_panel"].".jpg";
	}
	//echo 	$side_panel;
	//images/Side_Panel/Associations
	
	if($_SESSION["w2g_user_code"] == "where2g0bizUser"){
		$point1 = '';
		$point2 = '';
		$point3 = '';
		$point4 = '';
		if($_SESSION["w2g_user_role"] == "BizAdmin"){
			
			$point4 = '<input type="button" name="Button" value="My account" style="height:30px; width:130px" onclick="document.location=\'biz_user_profile.php\';" />';
		}
		else if($_SESSION["w2g_user_role"] == "BizBranchManager"){
			$point2 = '<input type="button" name="Button" value="My account" style="height:30px; width:130px" onclick="document.location=\'biz_user_profile.php\';" />';
			$point3 = '<input type="button" name="Button" value="Reviews" style="height:30px; width:130px" onclick="document.location=\'biz_reply_review.php\';" />';
			$point4 = '<input type="button" name="Button" value="Reports" style="height:30px; width:130px" onclick="document.location=\'Marketing_Report.php\';" />';
			//$point4 = '';
		}
		else if($_SESSION["w2g_user_role"] == "BizHeadOfficeManager"){
			$point2 = '<input type="button" name="Button" value="My account" style="height:30px; width:130px" onclick="document.location=\'biz_user_profile.php\';" />';
			$point3 = '<input type="button" name="Button" value="Reviews" style="height:30px; width:130px" onclick="document.location=\'biz_reply_review.php\';" />';
			$point4 = '<input type="button" name="Button" value="Reports" style="height:30px; width:130px" onclick="document.location=\'Marketing_Report.php\';" />';
			//$point4 = '';
		}
		else if($_SESSION["w2g_user_role"] == "BizAdminBizBranchManager"){
			//$point1 = '<td style="width:113px"><div align="center"><div align="center"><a href="biz_user_profile.php">My Profile</a></div></td>';
			$point2 = '<input type="button" name="Button" value="My account" style="height:30px; width:130px" onclick="document.location=\'biz_user_profile.php\';" />';
			$point3 = '<input type="button" name="Button" value="Reviews" style="height:30px; width:130px" onclick="document.location=\'biz_reply_review.php\';" />';
			$point4 = '<input type="button" name="Button" value="Reports" style="height:30px; width:130px" onclick="document.location=\'Marketing_Report.php\';" />';
			//$point4 = '';
		}
	}
	
	if($_SESSION["w2g_user_code"] == "") 
	{ 
		$common_header = '
	
	
 <tr><td height="46" colspan="2">
<table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="bottom" background="'.$mainBanner.'"><table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="21%" rowspan="4"><div align="center">
          <input type="image" name="imageField" src="images/'.$imageLogo.'" />
        </div></td>
        <td width="2%" rowspan="3"><div align="center" class="maintitle"></div></td>
        <td width="77%"><div align="right" class="signoutstyle">
          <table width="15%" border="0" align="right">
              <tr>
                <td bgcolor="#333333"><div align="right" class="signoutstyle"> <a href="signout.php"> <font color="#FFFFFF">-( '.$sayOUT.'</font></a><a href="signout.php"><font color="#FFFFFF"> )-</font> </a>'.$registering.'</div></td>
              </tr>
            </table>
        </div></td>
      </tr>
      <tr>
        <td><form id="form1" name="form1" method="post" action="">
          <label for="select"></label>
          <div align="center">
            <div align="right"></div>
          </div>
        </form>        </td>
      </tr>
      <tr>
        <td><span class="style1111"><strong>'.$slogan.'</strong></span></td>
      </tr>
      
        <td>&nbsp;</td>
        <td colspan="2"><table width="500" height="31" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width=""><div align="center"><a href="#"></a></div></td>
			<td width=""><div align="center"><a href="#"></a></div></td>
			<td width=""><div align="center"><a href="#"></a></div></td>
			<td style="width:113px"><div align="center"><input type="button" name="Button" value="Home page" style="height:30px; width:130px" onclick="document.location=\'index.php\';" /></div></td>           
          </tr>
        </table></td>
      </tr>
      
      
    </table></td>
  </tr>
</table>
</td>
</tr>';	

$common_header2 = '
		
	
	
 <tr><td height="46" colspan="2">
<table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="bottom" background="'.$mainBanner.'"><table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="21%" rowspan="4"><div align="center">
          <input type="image" name="imageField" src="images/'.$imageLogo.'" />
        </div></td>
        <td width="2%" rowspan="3"><div align="center" class="maintitle"></div></td>
        <td width="77%"><div align="right" class="signoutstyle">
          <table width="15%" border="0" align="right">
              <tr>
                <td bgcolor="#333333"><div align="right" class="signoutstyle"> <a href="signout.php"> <font color="#FFFFFF">-( '.$sayOUT.'</font></a><a href="signout.php"><font color="#FFFFFF"> )-</font> </a>'.$registering.'</div></td>
              </tr>
            </table>
        </div></td>
      </tr>
      <tr>
        <td><form id="form1" name="form1" method="post" action="">
          <label for="select"></label>
          <div align="center">
            <div align="right"></div>
          </div>
        </form>        </td>
      </tr>
      <tr>
        <td><span class="style1111"><strong>'.$slogan.'</strong></span></td>
      </tr>
      
        <td>&nbsp;</td>
        <td colspan="2"><table width="500" height="31" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width=""><div align="center"><a href="#"></a></div></td>
			<td width=""><div align="center"><a href="#"></a></div></td>
			<td width=""><div align="center"><a href="#"></a></div></td>
			<td style="width:113px"><div align="center"><input type="button" name="Button" value="Home page" style="height:30px; width:130px" onclick="document.location=\'index.php\';" /></div></td>           
          </tr>
        </table></td>
      </tr>
      
      
    </table></td>
  </tr>
</table>
</td>
</tr>';	
	}
	else if($_SESSION["w2g_user_code"] == "responde") 
	{
		$common_header = '
		
		<tr><td height="46" colspan="2">
<table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="bottom" background="images/bannerw2g.jpg"><table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="21%" rowspan="3"><div align="center">
          <input type="image" name="imageField" src="images/'.$imageLogo.'" />
        </div></td>
        <td width="2%" rowspan="2"><div align="center" class="maintitle"></div></td>
        <td width="77%">
        <table width="15%" border="0" align="right">
              <tr>
                <td bgcolor="#333333"><div align="right" class="signoutstyle">
        <a href="signout.php">
<font color="#FFFFFF">-( '.$sayOUT.'</font></a><a href="signout.php"><font color="#FFFFFF"> )-</font>
        </a>'.$registering.'</div></td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td><table width="100%" border="0">
          <tr>
            <td width="80%"><span class="style1111"><strong>'.$slogan.'</strong></span></td>
            <td width="20%"><img src="../images/Review_now_button.png" width="150" height="50" onclick="document.location=\'biz_search.php\';" /></td>
          </tr>
        </table></td>
      </tr>
      
      
        <td>&nbsp;</td>
        <td colspan="2"><table width="500" height="31" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width=""><div align="center"><a href="#"></a></div></td>
			<td style="width:113px"><div align="center"><input type="button" name="Button" value="Home page" style="height:30px; width:130px" onclick="document.location=\'index.php\';" /></div></td>
            <td style="width:113px"><div align="center"><input type="button" name="Button" value="My Profile" style="height:30px; width:130px" onclick="document.location=\'respondentprofile.php\';" /></div></td>
            
            <td style="width:113px"><div align="center"><input type="button" name="Button" value="My Reviews" style="height:30px; width:130px" onclick="document.location=\'view_my_review.php\';" /></div></td>   
			<td style="width:113px"><div align="center"><input type="button" name="Button" value="My Friends" style="height:30px; width:130px" onclick="document.location=\'addmates.php\';" /></div></td>       
          </tr>
        </table></td>
      </tr>
      
      
    </table></td>
  </tr>
</table>
</td>
</tr>';	
		
		$common_header2 = '
		
		<tr><td height="46" colspan="2">
<table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="bottom" background="images/bannerw2g.jpg"><table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="21%" rowspan="3"><div align="center">
          <input type="image" name="imageField" src="images/'.$imageLogo.'" />
        </div></td>
        <td width="2%" rowspan="2"><div align="center" class="maintitle"></div></td>
        <td width="77%"><div align="right" class="signoutstyle">
          <table width="15%" border="0" align="right">
              <tr>
                <td bgcolor="#333333"><div align="right" class="signoutstyle"> <a href="signout.php"> <font color="#FFFFFF">-( '.$sayOUT.'</font></a><a href="signout.php"><font color="#FFFFFF"> )-</font> </a>'.$registering.'</div></td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr>
        <td><table width="100%" border="0">
          <tr>
            <td width="80%"><span class="style1111"><strong>'.$slogan.'</strong></span></td>
            <td width="20%"><img src="../images/Review_now_button.png" width="150" height="50" onclick="document.location=\'biz_search.php\';" /></td>
          </tr>
        </table></td>
      </tr>
      
      
        <td>&nbsp;</td>
        <td colspan="2"><table width="500" height="31" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width=""><div align="center"><a href="#"></a></div></td>
			<td style="width:113px"><div align="center"><input type="button" name="Button" value="Home page" style="height:30px; width:130px" onclick="document.location=\'index.php\';" /></div></td>
            <td style="width:113px"><div align="center"><input type="button" name="Button" value="My Profile" style="height:30px; width:130px" onclick="document.location=\'respondentprofile.php\';" /></div></td>            
            <td style="width:113px"><div align="center"><input type="button" name="Button" value="My Reviews" style="height:30px; width:130px" onclick="document.location=\'view_my_review.php\';" /></div></td> 
			<td style="width:113px"><div align="center"><input type="button" name="Button" value="My Friends" style="height:30px; width:130px" onclick="document.location=\'addmates.php\';" /></div></td>         
          </tr>
        </table></td>
      </tr>
      
      
    </table></td>
  </tr>
</table>
</td>
</tr>';	
						 
						 
		$rightBar_button = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								  <td><img src="images/sidebtn_01.gif" width="260" height="27" alt=""></td>
								</tr>
								<tr>
								  <td><a href="biz_search.php"><img src="images/sidebtn_02.gif" alt="" width="260" height="24" border="0"></a></td>
								</tr>
								<tr>
								  <td><img src="images/sidebtn_03.gif" width="260" height="25" alt=""></td>
								</tr>
								<tr>
								  <td><img src="images/sidebtn_04.gif" width="260" height="24" alt=""></td>
								</tr>
								<tr>
								  <td><img src="images/sidebtn_05.gif" width="260" height="25" alt=""></td>
								</tr>
								<tr>
								  <td><img src="images/sidebtn_06.gif" width="260" height="23" alt=""></td>
								</tr>
								<tr>
								  <td><img src="images/sidebtn_07.gif" width="260" height="26" alt=""></td>
								</tr>
								<tr>
								  <td><img src="images/pendingDum_32.gif" width="266" height="183" alt=""></td>
								</tr>
							</table>';
	}
	else if($_SESSION["w2g_user_code"] == "where2g0bizUser") 
	{
		$common_header = '
		
	<tr><td height="46" colspan="2">
<table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="bottom" background="images/bannerw2g.jpg"><table width="100%" height="115" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="21%" rowspan="4"><div align="center">
          <input type="image" name="imageField" src="images/'.$imageLogo.'" />
        </div></td>
        <td width="2%" rowspan="3"><div align="center" class="maintitle"></div></td>
        <td width="77%"><div align="right" class="signoutstyle"><a href="signout.php">
<font color="#FFFFFF">-( '.$sayOUT.' )-</font>
</a>'.$registering.'</div></td>
      </tr>
      <tr>
        <td><form id="form1" name="form1" method="post" action="">
          <label for="select"></label>
          <div align="center">
            <div align="right">&nbsp;</div>
          </div>
        </form>        </td>
      </tr>
      <tr>
        <td><span class="style1111"><strong>'.$slogan.'</strong></span></td>
      </tr>
      
        <td>&nbsp;</td>
          <td colspan="2"><table width="500" height="31" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td style="width:113px"><div align="center"><input type="button" name="Button" value="Home page" style="height:30px; width:130px" onclick="document.location=\'index.php\';" /></div></td>
			<td style="width:113px"><div align="center">'.$point1.'</div></td>
            <td style="width:113px"><div align="center">'.$point2.'</div></td>
            <td style="width:113px"><div align="center">'.$point3.'</div></td>
            <td style="width:113px"><div align="center">'.$point4.'</div></td>   
			       
          </tr>
        </table></td>
        </tr>
      
      
    </table></td>
  </tr>
</table>
</td>
</tr>';	
	}
	
	if($_SESSION["w2g_user_code"] == "retailer") 
	{
		$bizsignin_part = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td background="images/bg_signin.gif" width="266" height="201" valign="top"><span class="signinTitle">Sign in to Where2Go!</span><br><span class="signinText">Email Address </span><input type="text" name="username" class="signininputbox" id="username"><br><span class="signinText2">Password</span> <input type="password" name="password" class="signininputbox"><br><span class="signindotline">......................................................................</span><br><span class="signinRem">&nbsp; <input type="checkbox" id="checker" name="checker" value="1"> 	Remember me on this computer</span><br><span class="signindotline"......................................................................</span><br><span><div align="right"><input type="button" name="button" value="Sign In" class="signinbutton" onClick="javascript:checkform(this)"></div></span><br><span><a href="forgot_pwd.php" class="signinBottomLink">Forget your  password?</a><span class="split_line"> I </span> <a href="register_respondent.php" class="normalWhiteLink">Sign Me Up </a></span><br></td></tr></table>';
	}
	else
	{
		$bizsignin_part = '<form name="form" method="post" action="chk_signin.php"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td background="images/bg_bizsignin.gif" width="260" height="199" valign="top"><span class="signinTitle">Sign in to Where2Go!</span><br><span class="signinText">Email Address </span><input type="text" name="username" class="signininputbox" id="username"><br><span class="signinText2">Password</span> <input type="password" name="password" class="signininputbox"><br><span class="signindotline">......................................................................</span><br><span class="signinRem">&nbsp; <input type="checkbox" id="checker" name="checker" value="1"> 	Remember me on this computer</span><br><span class="signindotline"......................................................................</span><br><span><div align="right"><input type="button" name="button" value="Sign In" class="signinbutton" onClick="javascript:checkform(this)"></div></span><br><span><a href="forgot_pwd.php" class="signinBottomLink">Forget your  password?</a><span class="split_line"> I </span> <a href="register_retailer.php" class="normalWhiteLink">Sign Me Up </a></span><br></td></tr></table></form>';
		
	}
	
	
	//$common_header.=('---code: '.$_SESSION["w2g_user_code"].'--role:'.$_SESSION["w2g_user_role"]);
	
	$page = str_replace("/","",strtolower($_SERVER['PHP_SELF']));
	$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	$arrayDot = explode(".", $ipaddress);
	if(intval($arrayDot[0]) == 0 || $arrayDot[0] == ''){
			$sql = "INSERT INTO `Monitor_Pages_Data` (`Monitor_pages_ID`,`IP`,`username`,`page_name`,`Date`,`Time`,`Browser`,`Domain`,`Denied`,`Referal_Page`) VALUES (NULL,'$ipaddress','".$_SESSION['w2g_email']."','".$page."',curdate(),now(),'".$browser.' '.$browser_version."','".$_SERVER['HTTP_HOST']."','D','$referalPage')";
			mysql_query($sql);
			echo "<script language=javascript>alert('we aren\'t available in your area yet and not all functions are available outside our coverage areas!'); document.location = 'page_unavailable_in_area.php';</script>";
			return;
	}
	
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
		$browser_version=$matched[1];
		$browser = 'IE';
	} elseif (preg_match( '|Opera ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
		$browser_version=$matched[1];
		$browser = 'Opera';
	} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'Firefox';
	} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'Safari';
	} else {
			// browser not recognized!
		$browser_version = 0;
		$browser= 'other';
	}	
	
	$newSQL = "SELECT * from IP_Range 
where IP_Range.Starting_First_Block <= ".$arrayDot[0]." and IP_Range.Starting_Second_Block <=".$arrayDot[1]."  and IP_Range.Starting_Third_Block <=".$arrayDot[2]."  and IP_Range.Starting_Fourth_Block <=".$arrayDot[3]."
and IP_Range.Ending_First_Block >= ".$arrayDot[0]." and IP_Range.Ending_Second_Block >=".$arrayDot[1]."  and IP_Range.Ending_Third_Block >=".$arrayDot[2]."  and IP_Range.Ending_Fourth_Block >=".$arrayDot[3]." order by IP_Range.Status desc limit 1";
	$newResult = mysql_query($newSQL);
	if($newRow = mysql_fetch_assoc($newResult)) {
		$_SESSION["ipcountry"] = $newRow["Country"]; 
	}
	else {
		$_SESSION["ipcountry"] = 'New Zealand';
	}
		
	$newSQL = "SELECT * from IP_Range 
where IP_Range.Starting_First_Block <= ".$arrayDot[0]." and IP_Range.Starting_Second_Block <=".$arrayDot[1]."  and IP_Range.Starting_Third_Block <=".$arrayDot[2]."  and IP_Range.Starting_Fourth_Block <=".$arrayDot[3]."
and IP_Range.Ending_First_Block >= ".$arrayDot[0]." and IP_Range.Ending_Second_Block >=".$arrayDot[1]."  and IP_Range.Ending_Third_Block >=".$arrayDot[2]."  and IP_Range.Ending_Fourth_Block >=".$arrayDot[3]." and Status = 'D' order by IP_Range.Status desc limit 1";
	
	$newResult = mysql_query($newSQL);
	
	if(mysql_num_rows($newResult) == 1){
		
		$newSQL2 = "SELECT * from IP_Range 
where IP_Range.Starting_First_Block = ".$arrayDot[0]." and IP_Range.Starting_Second_Block =".$arrayDot[1]."  and IP_Range.Starting_Third_Block =".$arrayDot[2]."  and IP_Range.Starting_Fourth_Block =".$arrayDot[3]."
and IP_Range.Ending_First_Block = ".$arrayDot[0]." and IP_Range.Ending_Second_Block =".$arrayDot[1]."  and IP_Range.Ending_Third_Block =".$arrayDot[2]."  and IP_Range.Ending_Fourth_Block =".$arrayDot[3]." and Status is null order by IP_Range.Status desc limit 1";
		//echo $newSQL2;
		$newResult2 = mysql_query($newSQL2);
		
		if(mysql_num_rows($newResult2) == 0){			
		
			$newRow = mysql_fetch_assoc($newResult);
			$sql = "SELECT Permit_unavailable_area FROM Monitor_Pages WHERE Monitor_Page = '".$page."' and Permit_unavailable_area = 'y'";
			$resultT = mysql_query($sql);
			if(mysql_num_rows($resultT) == 0){
				$sql = "INSERT INTO `Monitor_Pages_Data` (`Monitor_pages_ID`,`IP`,`username`,`page_name`,`Date`,`Time`,`Browser`,`Domain`,`Country`,`Special_Description`,`Denied`,`Referal_Page`) VALUES (NULL,'$ipaddress','".$_SESSION['w2g_email']."','".$page."',curdate(),now(),'".$browser.' '.$browser_version."','".$_SERVER['HTTP_HOST']."','".$newRow["Country"]."','".$newRow["Special_Description"]."','D','$referalPage')";
				mysql_query($sql);
				
				$sql = "SELECT * FROM Unavailable_user_access where Unavailable_user_access.Text_field = '$ipaddress' and Unavailable_user_access.Allowed_until > now()";
				$result3 = mysql_query($sql);
				if(mysql_num_rows($result3) == 0 && $_SESSION["w2g_email"] == ""){
					if(isset($_GET["biz"])){
						$nnSQL = "INSERT INTO `IP_Range` (`IP_range_ID`,`Country`,`Country_Abbreviation`,`Starting_First_Block`,`Starting_Second_Block`,`Starting_Third_Block`,`Starting_Fourth_Block`,`Ending_First_Block`,`Ending_Second_Block`,`Ending_Third_Block`,`Ending_Fourth_Block`) VALUES (NULL,'Reserved','Re','$arrayDot[0]','$arrayDot[1]','$arrayDot[2]','$arrayDot[3]','$arrayDot[0]','$arrayDot[1]','$arrayDot[2]','$arrayDot[3]')";
						mysql_query($nnSQL);
					}
					else{
						echo "<script language=javascript>alert('we aren\'t available in your area yet and not all functions are available outside our coverage areas!'); document.location = 'page_unavailable_in_area.php';</script>";
						return;
					}
				}
			}
		}
	}
	
	$sql = "SELECT * FROM Monitor_Pages WHERE Monitor_Page = '".$page."'";

	$result = mysql_query($sql);
	if(mysql_num_rows($result) == 1){	
	
		$newSQL = "SELECT * from IP_Range 
where IP_Range.Starting_First_Block <= ".$arrayDot[0]." and IP_Range.Starting_Second_Block <=".$arrayDot[1]."  and IP_Range.Starting_Third_Block <=".$arrayDot[2]."  and IP_Range.Starting_Fourth_Block <=".$arrayDot[3]."
and IP_Range.Ending_First_Block >= ".$arrayDot[0]." and IP_Range.Ending_Second_Block >=".$arrayDot[1]."  and IP_Range.Ending_Third_Block >=".$arrayDot[2]."  and IP_Range.Ending_Fourth_Block >=".$arrayDot[3]." order by IP_Range.Special_Description desc, Status desc limit 1";
		$newResult = mysql_query($newSQL);
		if($newRow = mysql_fetch_assoc($newResult)){			
			$sql = "INSERT INTO `Monitor_Pages_Data` (`Monitor_pages_ID`,`IP`,`username`,`page_name`,`Date`,`Time`,`Browser`,`Domain`,`Country`,`Special_Description`,`Referal_Page`) VALUES (NULL,'$ipaddress','".$_SESSION['w2g_email']."','".$page."',curdate(),now(),'".$browser.' '.$browser_version."','".$_SERVER['HTTP_HOST']."','".$newRow["Country"]."','".$newRow["Special_Description"]."','$referalPage')";
			mysql_query($sql);
		}
		else{
			$sql = "INSERT INTO `Monitor_Pages_Data` (`Monitor_pages_ID`,`IP`,`username`,`page_name`,`Date`,`Time`,`Browser`,`Domain`,`Denied`,`Referal_Page`) VALUES (NULL,'$ipaddress','".$_SESSION['w2g_email']."','".$page."',curdate(),now(),'".$browser.' '.$browser_version."','".$_SERVER['HTTP_HOST']."','D','$referalPage')";
			mysql_query($sql);
			echo "<script language=javascript>alert('we aren\'t available in your area yet and not all functions are available outside our coverage areas!'); document.location = 'page_unavailable_in_area.php';</script>";
			return;
		}
		
		
	}
	

?>