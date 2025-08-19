<select name="algorithm" id="algorithm" onchange="
if(this.value=='CBP_user') alert('For security region, this is temporary disable, sorry for the inconvenience!');
updateProcessingTime(this.value);
">
<option value="BMDPP" selected="selected" <?php if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'BMDPP') echo "selected"; else if(!isset($_REQUEST["algorithm"])) echo "selected"; ?>>BM+DP (2 iterations)</option>
<!--<option value="BMDP" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'BMDP') echo "selected"; ?>>BM+DP (2 iterations)</option>

<option value="GCDP" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'GCDP') echo "selected"; ?>>GC+DP</option>-->
<!--<option value="BMDP1" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'BMDP1') echo "selected"; ?>>BM+DP (1 iteration)</option>-->
<option value="CD" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'CD') echo "selected"; ?>>Composite DepthMap</option>
<!--<option value="CBP" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'CBP') echo "selected"; ?>>Belief Propogation (1D)</option>
<option value="CSDPS" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'CSDPS') echo "selected"; ?>>Coloured SDPS</option>-->
<option value="DP" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'DP') echo "selected"; ?>>Dynamic Programming</option>
<!-- <option value="GC" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'GC') echo "selected"; ?>>Graph Cuts (Slow)</option> -->
<!-- <option value="BM" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'BM') echo "selected"; ?>>Block Matching</option> -->

<option value="SAD" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'SAD') echo "selected"; ?>>SAD</option>
<option value="SSD" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'SSD') echo "selected"; ?>>SSD</option>
<option value="crossBP" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'crossBP') echo "selected"; ?> disabled>Cross BP (coming soon)</option>
<option value="CUDA" <?php  if(isset($_REQUEST["algorithm"]) && $_REQUEST["algorithm"] == 'CUDA') echo "selected"; ?> disabled>BP on CUDA (coming soon)</option>
<!--
<option value="CBP_user">User's implementation...</option>
-->
</select>