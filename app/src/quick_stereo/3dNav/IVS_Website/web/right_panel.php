<?
function returnRightPanel(){
?>
	 <tr bgcolor="#5D743C">       
        <td width="170" class="navText" style=" padding:15px;">  
          
                <!--
        <hr/> 
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
          <div align="center">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="hidden" name="hosted_button_id" value="3V7R2SWEGGJVY">
  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">            
          </div>
        </form>
        --> 
        <hr/>    
        <?
		if(isset($_COOKIE['user_name'])){
			?>
            <h4>Welcome back <? echo $_COOKIE['user_name']; ?><br />
          Click <a href="../php_script/password_protect.php?logout=true" style=" color:#FFFFFF;">here to logout</a>
          </h4>
            <?
		}
		else{
		?>     
          <form method="post" style="text-align:left" action="http://www.ivs.auckland.ac.nz/web/login.php">            
            </p><input type="submit" name="Submit" value="User Login" />
          </form>         
          <? } ?>
          <hr/>
          <p align="justify"><span  class="navText" onclick="document.location='http://www.ivs.auckland.ac.nz';" onmouseover="this.style.cursor='pointer'"><a href="javascript:document.location='http://www.ivs.auckland.ac.nz';" class="navText">Our IVS lab </a></span></p>
          
          <p align="left">          
          <strong>Intelligent Vision System research team - A leader in advanced low cost computational 3D vision solutions.</strong></p>
          
          <hr align="JUSTIFY">
          
          <p align="justify"><span  class="navText" onclick="document.location='http://www.ivs.auckland.ac.nz';" onmouseover="this.style.cursor='pointer'">Conferences</span></p>
          <p align="justify">The twenty-first conference of the International Association for Pattern Recognition (IAPR2012), November 11-15, 2012, Tsukuba International Congress Center, Tsukuba, Japan, <a href="http://www.icpr2012.org/" target="_blank">click here</a>.</p>
          
          <hr align="JUSTIFY">
          
          <p align="justify">27th International Conference of Image and Vision Computing New Zealand (IVCNZ2012), November 26 - 28, 2012, Dunedin, <a href="http://www.cs.otago.ac.nz/ivcnz2012/" target="_blank">click here</a>.</p>
          
          <hr align="JUSTIFY"> </p></p> 
          <!--
          <p align="justify"><span  class="navText" onclick="document.location='../773/index.php';" onmouseover="this.style.cursor='pointer'">THE FACE DATABASE</span><br />
              This database is collected for months using the <a href="http://www.fujifilm.com/products/3d/camera/finepix_real3dw1/" target="_blank">Fujifilm FinePix Real 3D W1</a>. A large number of them are contributed by the students from <a href="http://www.cs.auckland.ac.nz/compsci773s1c" target="_blank">Computer Science 773 class</a>, members of <a href="http://www.ivs.auckland.ac.nz" target="_blank">Vision Lab</a> and other students, collegues from the <a href="http://www.cs.auckland.ac.nz/" target="_blank">University of Auckland</a>.<hr align="JUSTIFY"></p>
          -->       </td>
       <td width="10">&nbsp;</td>
      </tr>     
       <?
} 
?>
   
