<?
if(isset($_GET["process_images"])){
	$_SESSION["filename"] = $_GET["process_images"];
	//echo "http://www.ivs.auckland.ac.nz/stereo/images/".$_SESSION["filename"]."";
}
?>
<HTML>
<HEAD>
   <TITLE>Applet HTML Page</TITLE>
</HEAD>
<BODY>

<!--
*** GENERATED applet HTML launcher - DO NOT EDIT IN 'BUILD' FOLDER ***

If you need to modify this HTML launcher file (e.g., to add applet parameters),
copy it to where your applet class is found in the SRC folder. If you do this,
the IDE will use it when you run or debug the applet.

Tip: To exclude an HTML launcher from the JAR file, use exclusion filters in
the Packaging page in the Project Properties dialog.

For more information see the online help.
-->

<table width="500" border="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr>
    <td><strong>Good day visitor!</strong></td>
    <td>|</td>

    <td><a href="../../index.php">Change images </a></td>
    <td>|</td>
    <td><a href="../../folder.php">Your image folder </a></td>
    <td>|</td>
    <td><a href="../../logout.php">Log out </a></td>
  </tr>

</table>
<br/>
<APPLET codebase="." code="mainApplet.class" width=1280 height=900>
	<param name="image_url" value="http://www.ivs.auckland.ac.nz/stereo/images/<? echo $_SESSION["filename"]; ?>">  	
</APPLET>


<HR WIDTH="100%">
<FONT SIZE=-1><I><a href="www.cs.auckland.ac.nz/~mngu012">Minh Nguyen</a> - last update 30/0708 </I></FONT>
</BODY>
</HTML>

