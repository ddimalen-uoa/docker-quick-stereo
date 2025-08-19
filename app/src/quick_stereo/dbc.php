<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$spamIP="'178.162.191.58','78.159.112.128','212.95.32.92','188.165.212.59','31.214.201.54','89.149.223.180','188.143.232.84','188.143.232.31'";

//if($_SERVER["HTTPS"] == "on")
//{
//   header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
//    exit();
//}

//$hostname = 'home.sit.auckland.ac.nz';
$hostname = 'mysql';
$username = 'cs_ivs';
$dbname = 'cs_ivs';
$password='sdjh86637';

//old database connection
$link = mysql_connect($hostname, $username, $password) or die("Couldn't make connection.");
$db = mysql_select_db($dbname, $link) or die("Couldn't select database");

//new database connection with PDO for more security
try {
	$dbc = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
	$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//return $dbc;
} catch(PDOException $e) {
	echo $e->getMessage();
}

//Detect special conditions devices
$iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android= stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS= stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

//do something with this information
if( $iPod || $iPhone ){
        //were an iPhone/iPod touch -- do something here
	$_SESSION["smartPhoneDevice"] = "iPod";
}else if($iPad){
        //were an iPad -- do something here
	$_SESSION["smartPhoneDevice"] = "iPad";
}else if($Android){
        //were an Android device -- do something here
	$_SESSION["smartPhoneDevice"] = "Android";
}else if($webOS){
        //were a webOS device -- do something here
	$_SESSION["smartPhoneDevice"] = "webOS";
}
else{
	$_SESSION["smartPhoneDevice"] = "desktop";
}

$_SESSION["smartPhoneDevice"] = "desktop";

//var_dump($_SESSION["smartPhoneDevice"]);
//return;


//ADMIN SECTION PASSWORD
/****** SET A STRONG PASSWORD WITH ATLEAST 6-8 CHARS***************/
$admin_user = 'admin';
$admin_pass = 'admin';

function page_protect() {
//check for cookies
if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_name'])){
      $_SESSION['user_id'] = $_COOKIE['user_id'];
      $_SESSION['user_name'] = $_COOKIE['user_name'];
	  $_SESSION['username'] = $_COOKIE['username'];
	  $_SESSION['role'] = $_COOKIE['role'];
   }


if (!isset($_SESSION['user_id']))
{
//header("Location: logout.php");
echo "<script language=javascript>alert('Please login again.');document.location='logout.php';</script>";
}
/*******************END********************************/
}


function makecomma($input)
{
    // This function is written by some anonymous person - I got it from Google
    if(strlen($input)<=2)
    { return $input; }
    $length=substr($input,0,strlen($input)-2);
    $formatted_input = makecomma($length).",".substr($input,-2);
    return $formatted_input;
}

function india($num){
    // This is my function
    $pos = strpos((string)$num, ".");
    if ($pos === false) { $decimalpart="00";}
    else { $decimalpart= substr($num, $pos+1, 2); $num = substr($num,0,$pos); }

    if(strlen($num)>3 & strlen($num) <= 12){
                $last3digits = substr($num, -3 );
                $numexceptlastdigits = substr($num, 0, -3 );
                $formatted = makecomma($numexceptlastdigits);
                $stringtoreturn = $formatted.",".$last3digits ;
    }elseif(strlen($num)<=3){
                $stringtoreturn = $num;
    }elseif(strlen($num)>12){
                $stringtoreturn = number_format($num, 2);
    }

    if(substr($stringtoreturn,0,2)=="-,"){$stringtoreturn = "-".substr($stringtoreturn,2 );}

    return $stringtoreturn;
}



function EncodeURL($url)
{
$new = strtolower(ereg_replace(' ','_',$url));
return($new);
}

function DecodeURL($url)
{
$new = ucwords(ereg_replace('_',' ',$url));
return($new);
}

function ChopStr($str, $len)
{
    if (strlen($str) < $len)
        return $str;

    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);

    return $str . "...";
}
function countryCityFromIP($ipAddr)
{
//return;
//function to find country and city from IP address
//Developed by Roshan Bhattarai http://roshanbh.com.np
//verify the IP address for the
ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
$ipDetail=array(); //initialize a blank array
//get the XML result from hostip.info
$xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);
//get the city name inside the node <gml:name> and </gml:name>
preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);
//assing the city name to the array
$ipDetail['city']=$match[2];
//get the country name inside the node <countryName> and </countryName>
preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);
//assign the country name to the $ipDetail array
$ipDetail['country']=$matches[1];
//get the country name inside the node <countryName> and </countryName>
preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
$ipDetail['country_code']=$cc_match[1]; //assing the country code to array
//return the array containing city, country and country code
return $ipDetail;
}

function save_image($img,$fullpath){
	if($fullpath=='basename'){
		$fullpath = basename($img);
	}
	$ch = curl_init ($img);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	$rawdata=curl_exec($ch);
	curl_close ($ch);
	if(file_exists($fullpath)){
		unlink($fullpath);
	}
	$fp = fopen($fullpath,'x');
	fwrite($fp, $rawdata);
	fclose($fp);
}

function returnItem($enterid, $count, $action){
	$sql = "SELECT * FROM `stereo_gallery_category` where id = $enterid";
	$result1 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
	while($row1 = mysql_fetch_assoc($result1)){
		?>
			<?php if($action=="move") { ?>
            	<option value="<?php echo $row1["id"]; ?>" style="font-size:<?php echo (21-2*$count); ?>px"><?php for($i=0; $i<$count;$i++) echo '&nbsp;&nbsp;&nbsp;'; ?><?php echo $row1["categoryName"]; ?></option>
            <?php } else { ?>
            	<option value="<?php echo $row1["id"]; ?>" <?php if($row1["id"]==$_SESSION["selectedCategory"]) echo " selected"; ?> style="font-size:<?php echo (21-2*$count); ?>px"><?php for($i=0; $i<$count;$i++) echo '&nbsp;&nbsp;&nbsp;'; ?><?php echo $row1["categoryName"]; ?></option>
            <?php } ?>
		<?php
		$sql = "SELECT * FROM `stereo_gallery_category` where parentCategory = $enterid";
		$result2 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
		while($row2 = mysql_fetch_assoc($result2)){
			echo returnItem($row2['id'], $count+1, $action);
		}
	}
}


function getExtension($str) {
	 $i = strrpos($str,".");
	 if (!$i) { return ""; }

	 $l = strlen($str) - $i;
	 $ext = substr($str,$i+1,$l);
	 return strtolower($ext);
}

function returnFullResults($enterCategoryId){
	$LIMITEACHCATEGORY = 100;
	$sql = "SELECT * FROM `stereo_gallery_ivs` where category = $enterCategoryId order by uploadedTime desc limit ".$LIMITEACHCATEGORY;
	$result[] = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());

	$sql = "SELECT * FROM `stereo_gallery_category` where parentCategory = $enterCategoryId";
	$result1 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());

	while($row1 = mysql_fetch_assoc($result1)){
		$sql = "SELECT * FROM `stereo_gallery_ivs` where category = '".$row1["id"]."' order by uploadedTime desc limit ".$LIMITEACHCATEGORY;
		$result[] = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());

		$sql = "SELECT * FROM `stereo_gallery_category` where parentCategory = ".$row1["id"]."";
		$result2 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());

		while($row2 = mysql_fetch_assoc($result2)){
			$sql = "SELECT * FROM `stereo_gallery_ivs` where category = '".$row2["id"]."' order by uploadedTime desc limit ".$LIMITEACHCATEGORY;
			$result[] = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());

			$sql = "SELECT * FROM `stereo_gallery_category` where parentCategory = ".$row2["id"]."";
			$result3 = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());

			while($row3 = mysql_fetch_assoc($result3)){
				$sql = "SELECT * FROM `stereo_gallery_ivs` where category = '".$row3["id"]."' order by uploadedTime desc limit ".$LIMITEACHCATEGORY;
				$result[] = mysql_query($sql) or die('Error, query'.$sql.'failed'.mysql_error());
			}
		}
	}
	return $result;
}

function deleteOldFiles($path, $howManyDays){
	  //$path = 'ftmp/';
	  if ($handle = opendir($path)) {
		 while (false !== ($file = readdir($handle))) {
			if ((time()-filectime($path.$file)) >= $howManyDays*86400) {
			   if (preg_match('/\.*$/i', $file)) {
				  if($file != "." && $file != ".."){
				   //echo "<br/>";
				   //echo "$file";
				   //echo "$path.$file";

				  	unlink($path.$file);
				  }
			   }
			}
		 }
	   }
}

function returnDownloadMaterials(){
?>

<!-- <a style="align:center" target="_blank" href="./Oculus/Oculus_obj.php">Oculus</a> -->

<br/>
	<strong>Downloads:</strong>
	<br/>
	<br/>
		<strong>Processed inputs:</strong>
		<a href="download.php?request=left_raw_resized" >Resized left image</a>,
		<a href="download.php?request=right_raw_resized">Resized right image</a>,
		<a href="download.php?request=left_rect_resized">Left rectified image</a>,
		<a href="download.php?request=right_rect_resized">Right rectified image</a>,
	<br/>
	<br/>
		<strong>Disparity-map/Depth-map:</strong>
		<a href="download.php?request=result">Rectified Left Disparity Map</a>,
		<a href="download.php?request=depthReserved">Left Image Disparity Map</a>,
		<a href="download.php?request=depthmap">Grey Depth Map</a>,
		<a href="download.php?request=colourdepthmap">Colour Depth Map</a>,
		<a href="download.php?request=shareResults">Shared Combined Results</a>
	<br/>
	<br/>
		<strong>Optical views:</strong> 
		<a href="download.php?request=anaglyph">Anaglyph</a>,
		<a href="download.php?request=crossEye">Cross Eyed Side-by-side</a>,
		<a href="download.php?request=parallelEye">Parallel Eyed Side-by-side</a>,
		<a href="download.php?request=p_stereogram">AutoStereogram</a>
		<!-- <a href="download.php?request=autostereogram">Cross Eyed AutoStereogram</a> -->
	<br/>
	<br/>
		<strong>Alias Wavefront&rsquo;s OBJ files: </strong>
		<a href="download.php?request=modelObj">Model.obj</a>,
		<a href="download.php?request=modelMtl">Model.mtl</a>,
		<a href="download.php?request=modelJpg">Model.jpg</a>
		<br />
		(Notice: you need to download all these three files to have a 3D model with texture, use
		<a href="http://www.glc-player.net/">GLC Player to view</a>)
	<br/>
	<br/>
		<strong>Other Parameters: </strong>
		<a href="download.php?request=fundamental_matrix">Fundamental Matrix</a>,
		<a href="download.php?request=returnParameters">Disparity Range Parameters</a>,
		<a href="download.php?request=correspondences">Left/Right Correspondences</a>,
	<br/>
	<br/>
	<!--br/>
	<strong>Downloads:</strong>
	<br/>
	<br/>
		<strong>Processed inputs:</strong>
		<a href="#" onClick="show_warning('download.php?request=left_raw_resized')" >Resized left image</a>,
		<a href="#" onClick="show_warning('download.php?request=right_raw_resized')">Resized right image</a>,
		<a href="#" onClick="show_warning('download.php?request=left_rect_resized')">Left rectified image</a>,
		<a href="#" onClick="show_warning('download.php?request=right_rect_resized')">Right rectified image</a>,
	<br/>
	<br/>
		<strong>Disparity-map/Depth-map:</strong>
		<a href="#" onClick="show_warning('download.php?request=result')">Rectified Left Disparity Map</a>,
		<a href="#" onClick="show_warning('download.php?request=depthReserved')">Left Image Disparity Map</a>,
		<a href="#" onClick="show_warning('download.php?request=depthmap')">>Grey Depth Map</a>,
		<a href="#" onClick="show_warning('download.php?request=colourdepthmap')">Colour Depth Map</a>,
		<a href="#" onClick="show_warning('download.php?request=shareResults')">Shared Combined Results</a>
	<br/>
	<br/>
		<strong>Optical views:</strong> 
		<a href="#" onClick="show_warning('download.php?request=anaglyph')">Anaglyph</a>,
		<a href="#" onClick="show_warning('download.php?request=crossEye')">Cross Eyed Side-by-side</a>,
		<a href="#" onClick="show_warning('download.php?request=parallelEye')">Parallel Eyed Side-by-side</a>,
		<a href="#" onClick="show_warning('download.php?request=p_stereogram')">AutoStereogram</a>
	<br/>
	<br/>
		<strong>Alias Wavefront&rsquo;s OBJ files: </strong>
		<a href="#" onClick="show_warning('download.php?request=modelObj')">Model.obj</a>,
		<a href="#" onClick="show_warning('download.php?request=modelMtl')">Model.mtl</a>,
		<a href="#" onClick="show_warning('download.php?request=modelJpg')">>Model.jpg</a>
		<br />
		(Notice: you need to download all these three files to have a 3D model with texture, use
		<a href="#" onClick="show_warning('http://www.glc-player.net/')">GLC Player to view</a>)
	<br/>
	<br/>
		<strong>Other Parameters: </strong>
		<a href="#" onClick="show_warning('download.php?request=fundamental_matrix')">Fundamental Matrix</a>,
		<a href="#" onClick="show_warning('download.php?request=returnParameters')">Disparity Range Parameters</a>,
		<a href="#" onClick="show_warning('download.php?request=correspondences')">Left/Right Correspondences</a>,
	<br/>
	<br/ -->
<?php
}
?>
