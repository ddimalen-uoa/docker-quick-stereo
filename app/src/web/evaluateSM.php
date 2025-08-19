<?
$colour = array();
$colour[0] = "blue";
$colour[1] = "white";
$counter = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="http://vision.middlebury.edu/stereo/submit/php/style.css">
<title>Evaluate Stereo Matching</title>
</head>

<body>

<table border="2" class="gr" bordercolorlight="gray" bordercolordark="gray" rules="groups">
	<colgroup></colgroup><colgroup></colgroup><colgroup span="3">
</colgroup><colgroup span="3">
</colgroup><colgroup span="3">
</colgroup><colgroup span="3">
</colgroup><colgroup>
</colgroup><thead>
<tr><td colspan="2"><b>Error Threshold = 1</b>
</td>
<td colspan="4">Sort by nonocc</td><td colspan="4">Sort by all</td><td colspan="4">Sort by disc</td><td>&nbsp;</td>
</tr>
<tr><td colspan="2"><form action="results.php" method="post" name="selForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<select onchange="submit(); changeWindow(2, 0);" name="thresh">
		<option value="-100">Error Threshold...</option>
<option value="0">0.5</option>
<option value="1">0.75</option>
<option value="2">1</option>
<option value="3">1.5</option>
<option value="4">2</option>
</select></form>
</td>
<td colspan="4"><form action="results.php" method="post" name="myForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<input type="hidden" value="2" name="threshInd">
		<input type="hidden" value="4" name="ReSort">
		<input type="image" src="arrow.gif" title="Click to sort by nonocc"></form></td><td colspan="4"><form action="results.php" method="post" name="myForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<input type="hidden" value="2" name="threshInd">
		<input type="hidden" value="5" name="ReSort">
		<input type="image" src="arrow.gif" title="Click to sort by all"></form>
</td><td colspan="4"><form action="results.php" method="post" name="myForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<input type="hidden" value="2" name="threshInd">
		<input type="hidden" value="6" name="ReSort">
		<input type="image" src="arrow.gif" title="Click to sort by disc"></form>
</td><td>&nbsp;</td>
</tr>
<tr>
		<td class="bt"><b>Algorithm</b></td>
		<td class="bt"><b>Avg.</b></td><td colspan="3" class="bt">
<a onclick="openWindow('../tsukuba/imagePair.html', 'picwin'); return false;" title="Click to see Tsukuba Image Pair" href="../tsukuba/imagePair.html"><b>Tsukuba</b></a>
<br><a onclick="openWindow('../tsukuba/groundtruth.html', 'picwin'); return false;" title="Click to see Tsukuba Ground Truth" href="../tsukuba/groundtruth.html"><span class="smfont">ground truth</span></a>
</td>
<td colspan="3" class="bt">
<a onclick="openWindow('../venus/imagePair.html', 'picwin'); return false;" title="Click to see Venus Image Pair" href="../venus/imagePair.html"><b>Venus</b></a>
<br><a onclick="openWindow('../venus/groundtruth.html', 'picwin'); return false;" title="Click to see Venus Ground Truth" href="../venus/groundtruth.html"><span class="smfont">ground truth</span></a></td>
<td colspan="3" class="bt">
<a onclick="openWindow('../teddy/imagePair.html', 'picwin'); return false;" title="Click to see Teddy Image Pair" href="../teddy/imagePair.html"><b>Teddy</b></a>
<br><a onclick="openWindow('../teddy/groundtruth.html', 'picwin'); return false;" title="Click to see Teddy Ground Truth" href="../teddy/groundtruth.html"><span class="smfont">ground truth</span></a></td>
<td colspan="3" class="bt">
<a onclick="openWindow('../cones/imagePair.html', 'picwin'); return false;" title="Click to see Cones Image Pair" href="../cones/imagePair.html"><b>Cones</b></a>
<br><a onclick="openWindow('../cones/groundtruth.html', 'picwin'); return false;" title="Click to see Cones Ground Truth" href="../cones/groundtruth.html"><span class="smfont">ground truth</span></a></td>
<td class="bt"> Average Percent <br> Bad Pixels <br> &nbsp;  </td>
</tr><tr>
      		<td>&nbsp;</td>
		<td><b>Rank</b></td><td>
<a onclick="openWindow('../tsukuba/nonocc.html', 'picwin'); return false;" title="Click to see Image of non-occluded regions" href="../tsukuba/nonocc.html">nonocc</a>
</td>
<td>
<a onclick="openWindow('../tsukuba/all.html', 'picwin'); return false;" title="Click to see Image of all regions" href="../tsukuba/all.html">all</a></td>
<td>
<a onclick="openWindow('../tsukuba/disc.html', 'picwin'); return false;" title="Click to see Image of regions near discontinuties" href="../tsukuba/disc.html">disc</a></td>
<td>
<a onclick="openWindow('../venus/nonocc.html', 'picwin'); return false;" title="Click to see Image of non-occluded regions" href="../venus/nonocc.html">nonocc</a></td>
<td>
<a onclick="openWindow('../venus/all.html', 'picwin'); return false;" title="Click to see Image of all regions" href="../venus/all.html">all</a></td>
<td>
<a onclick="openWindow('../venus/disc.html', 'picwin'); return false;" title="Click to see Image of regions near discontinuties" href="../venus/disc.html">disc</a></td>
<td>
<a onclick="openWindow('../teddy/nonocc.html', 'picwin'); return false;" title="Click to see Image of non-occluded regions" href="../teddy/nonocc.html">nonocc</a></td>
<td>
<a onclick="openWindow('../teddy/all.html', 'picwin'); return false;" title="Click to see Image of all regions" href="../teddy/all.html">all</a></td>
<td>
<a onclick="openWindow('../teddy/disc.html', 'picwin'); return false;" title="Click to see Image of regions near discontinuties" href="../teddy/disc.html">disc</a></td>
<td>
<a onclick="openWindow('../cones/nonocc.html', 'picwin'); return false;" title="Click to see Image of non-occluded regions" href="../cones/nonocc.html">nonocc</a></td>
<td>
<a onclick="openWindow('../cones/all.html', 'picwin'); return false;" title="Click to see Image of all regions" href="../cones/all.html">all</a></td>
<td>
<a onclick="openWindow('../cones/disc.html', 'picwin'); return false;" title="Click to see Image of regions near discontinuties" href="../cones/disc.html">disc</a></td>
<td>&nbsp;</td></tr>
<tr>
		<td>&nbsp;</td>
		<td><form action="results.php" method="post" name="myForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<input type="hidden" value="2" name="threshInd">
		<input type="hidden" value="-1" name="ReSort">
		<input type="image" src="arrowB.gif" title="Click to sort by Average Rank"></form></td><td colspan="3"><form action="results.php" method="post" name="myForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<input type="hidden" value="2" name="threshInd">
		<input type="hidden" value="0" name="ReSort">
		<input type="image" src="arrow.gif" title="Click to sort by tsukuba"></form>
</td><td colspan="3"><form action="results.php" method="post" name="myForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<input type="hidden" value="2" name="threshInd">
		<input type="hidden" value="1" name="ReSort">
		<input type="image" src="arrow.gif" title="Click to sort by venus"></form>
</td><td colspan="3"><form action="results.php" method="post" name="myForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<input type="hidden" value="2" name="threshInd">
		<input type="hidden" value="2" name="ReSort">
		<input type="image" src="arrow.gif" title="Click to sort by teddy"></form>
</td><td colspan="3"><form action="results.php" method="post" name="myForm">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22" name="newAlgDir">
<input type="hidden" value="72e1pqi2gc4dqr1nepij6g3u22" name="sid">
<input type="hidden" value="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/outputTEMP" name="datafile">
<input type="hidden" value="its set" name="allImagesGood">
<input type="hidden" value="" name="manyWindows">
<input type="hidden" value="2" name="threshInd">
		<input type="hidden" value="3" name="ReSort">
		<input type="image" src="arrow.gif" title="Click to sort by cones"></form>
</td><td>&nbsp;
</td>
</tr></thead><tbody>

<tr class="<? echo $colour[$counter++%2]; ?>">
<td ><a title="Rank=81" href=""><b>GC+DP</b></a></td>
<td><span class="avgRank">76.8</span></td><td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/tsukuba/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD tsukuba images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/tsukuba/newAlgt100.html">1.56</a>
<span class="rank">57</span></td>
<td>2.20
<span class="rank">62</span></td>
<td>7.22
<span class="rank">55</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/venus/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD venus images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/venus/newAlgt100.html">1.74</a>
<span class="rank">116</span></td>
<td>1.94
<span class="rank">103</span></td>
<td>4.33
<span class="rank">73</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/teddy/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD teddy images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/teddy/newAlgt100.html">6.47</a>
<span class="rank">49</span></td>
<td>9.12
<span class="rank">21</span></td>
<td>16.6
<span class="rank">60</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/cones/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD cones images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/cones/newAlgt100.html">5.86</a>
<span class="rank">111</span></td>
<td>12.1
<span class="rank">98</span></td>
<td>15.3
<span class="rank">117</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">7.04</div><div class="innerBar" style="width: 33.953718595206px;">&nbsp;</div></div></td>
</tr>

<tr class="<? echo $colour[$counter++%2]; ?>">
<td><a title="Rank=114" href=""><b>BM+DP (2 iterations)</b></a></td>
<td><span class="avgRank">105.5</span></td><td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/tsukuba/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD tsukuba images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/tsukuba/newAlgt100.html">3.77</a>
<span class="rank">117</span></td>
<td>4.98
<span class="rank">115</span></td>
<td>14.4
<span class="rank">114</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/venus/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD venus images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/venus/newAlgt100.html">2.73</a>
<span class="rank">123</span></td>
<td>3.37
<span class="rank">122</span></td>
<td>20.4
<span class="rank">128</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/teddy/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD teddy images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/teddy/newAlgt100.html">8.00</a>
<span class="rank">86</span></td>
<td>11.3
<span class="rank">39</span></td>
<td>18.8
<span class="rank">92</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/cones/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD cones images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/cones/newAlgt100.html">5.71</a>
<span class="rank">110</span></td>
<td>12.0
<span class="rank">98</span></td>
<td>16.5
<span class="rank">122</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">10.2</div><div class="innerBar" style="width: 49.006726018696px;">&nbsp;</div></div></td>
</tr>

<tr class="<? echo $colour[$counter++%2]; ?>">
<td><a title="Rank=115" href=""><b>BM+DP (1 iteration)</b></a></td>
<td><span class="avgRank">110.4</span></td><td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/tsukuba/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD tsukuba images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/tsukuba/newAlgt100.html">4.01</a>
<span class="rank">121</span></td>
<td>5.47
<span class="rank">119</span></td>
<td>15.0
<span class="rank">119</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/venus/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD venus images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/venus/newAlgt100.html">3.07</a>
<span class="rank">124</span></td>
<td>3.90
<span class="rank">125</span></td>
<td>23.3
<span class="rank">133</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/teddy/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD teddy images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/teddy/newAlgt100.html">8.51</a>
<span class="rank">95</span></td>
<td>12.1
<span class="rank">60</span></td>
<td>20.1
<span class="rank">99</span></td>
<td><a onclick="openWindow('tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/cones/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD cones images" href="tempAlgs/SIDhbj5jeo1c9v8fstc7elo6idr94/cones/newAlgt100.html">5.73</a>
<span class="rank">110</span></td>
<td>12.1
<span class="rank">98</span></td>
<td>16.6
<span class="rank">122</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">10.8</div><div class="innerBar" style="width: 52.19468451272px;">&nbsp;</div></div></td>
</tr>

<tr class="<? echo $colour[$counter++%2]; ?>">
<td><a title="Rank=127" href=""><b>GC</b></a>S</td>
<td><span class="avgRank">113.2</span></td><td><a onclick="openWindow('../tsukuba/alg03t100.html', 'picwin'); return false;" title="Click to see GC [1d]
 tsukuba images" href="../tsukuba/alg03t100.html">1.94</a>
<span class="rank">75</span></td>
<td>4.12
<span class="rank">100</span></td>
<td>9.39
<span class="rank">84</span></td>
<td><a onclick="openWindow('../venus/alg03t100.html', 'picwin'); return false;" title="Click to see GC [1d]
 venus images" href="../venus/alg03t100.html">1.79</a>
<span class="rank">116</span></td>
<td>3.44
<span class="rank">122</span></td>
<td>8.75
<span class="rank">98</span></td>
<td><a onclick="openWindow('../teddy/alg03t100.html', 'picwin'); return false;" title="Click to see GC [1d]
 teddy images" href="../teddy/alg03t100.html">16.5</a>
<span class="rank">134</span></td>
<td>25.0
<span class="rank">138</span></td>
<td>24.9
<span class="rank">123</span></td>
<td><a onclick="openWindow('../cones/alg03t100.html', 'picwin'); return false;" title="Click to see GC [1d]
 cones images" href="../cones/alg03t100.html">7.70</a>
<span class="rank">123</span></td>
<td>18.2
<span class="rank">129</span></td>
<td>15.3
<span class="rank">116</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">11.4</div><div class="innerBar" style="width: 55.028362898861px;">&nbsp;</div></div></td>
</tr>

<tr class="<? echo $colour[$counter++%2]; ?>">
<td><a title="Rank=127" href=""><b>DP</b></a><a title="Rank=128" href="#references">S</a></td>
<td><span class="avgRank">125.9</span></td><td><a onclick="openWindow('../tsukuba/alg02t100.html', 'picwin'); return false;" title="Click to see DP [1b]
 tsukuba images" href="../tsukuba/alg02t100.html">4.12</a>
<span class="rank">123</span></td>
<td>5.04
<span class="rank">115</span></td>
<td>12.0
<span class="rank">103</span></td>
<td><a onclick="openWindow('../venus/alg02t100.html', 'picwin'); return false;" title="Click to see DP [1b]
 venus images" href="../venus/alg02t100.html">10.1</a>
<span class="rank">142</span></td>
<td>11.0
<span class="rank">142</span></td>
<td>21.0
<span class="rank">129</span></td>
<td><a onclick="openWindow('../teddy/alg02t100.html', 'picwin'); return false;" title="Click to see DP [1b]
 teddy images" href="../teddy/alg02t100.html">14.0</a>
<span class="rank">128</span></td>
<td>21.6
<span class="rank">129</span></td>
<td>20.6
<span class="rank">105</span></td>
<td><a onclick="openWindow('../cones/alg02t100.html', 'picwin'); return false;" title="Click to see DP [1b]
 cones images" href="../cones/alg02t100.html">10.5</a>
<span class="rank">133</span></td>
<td>19.1
<span class="rank">132</span></td>
<td>21.1
<span class="rank">130</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">14.2</div><div class="innerBar" style="width: 68.367623667583px;">&nbsp;</div></div></td>
</tr>
<tr class="<? echo $colour[$counter++%2]; ?>">
<td class=""><a title="Rank=127" href=""><b>SDPS</b></a></td>
<td class=""><span class="avgRank">125.3</span></td><td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD tsukuba images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html">5.62</a>
<span class="rank">133</span></td>
<td>6.90
<span class="rank">128</span></td>
<td>16.8
<span class="rank">120</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD venus images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html">10.2</a>
<span class="rank">138</span></td>
<td>11.2
<span class="rank">138</span></td>
<td>32.5
<span class="rank">134</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD teddy images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html">11.7</a>
<span class="rank">120</span></td>
<td>19.4
<span class="rank">120</span></td>
<td>21.2
<span class="rank">107</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD cones images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html">8.57</a>
<span class="rank">121</span></td>
<td>15.8
<span class="rank">119</span></td>
<td>20.1
<span class="rank">125</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">15.0</div><div class="innerBar" style="width: 72.232442785048px;">&nbsp;</div></div></td>
</tr>
<tr class="<? echo $colour[$counter++%2]; ?>">
<td><a title="Rank=133" href=""><b>BPS (1D)</b></a></td>
<td><span class="avgRank">129.6</span></td><td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD tsukuba images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html">5.13</a>
<span class="rank">130</span></td>
<td>7.23
<span class="rank">133</span></td>
<td>14.4
<span class="rank">109</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD venus images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html">11.5</a>
<span class="rank">139</span></td>
<td>13.0
<span class="rank">139</span></td>
<td>33.2
<span class="rank">135</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD teddy images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html">14.5</a>
<span class="rank">126</span></td>
<td>23.3
<span class="rank">129</span></td>
<td>24.3
<span class="rank">118</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD cones images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html">11.4</a>
<span class="rank">131</span></td>
<td>21.4
<span class="rank">134</span></td>
<td>23.2
<span class="rank">132</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">16.9</div><div class="innerBar" style="width: 81.37839791305px;">&nbsp;</div></div></td>
</tr>
<tr class="<? echo $colour[$counter++%2]; ?>">
<td><a href="" title="Rank=140"><b>SAD</b></a></td>
<td><span class="avgRank">138.8</span></td><td><a href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html" title="Click to see YOUR METHOD tsukuba images" onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html', 'picwin'); return false;">8.31</a>
<span class="rank">140</span></td>
<td>10.4
<span class="rank">140</span></td>
<td>26.4
<span class="rank">137</span></td>
<td><a href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html" title="Click to see YOUR METHOD venus images" onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html', 'picwin'); return false;">13.6</a>
<span class="rank">139</span></td>
<td>15.1
<span class="rank">139</span></td>
<td>42.5
<span class="rank">140</span></td>
<td><a href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html" title="Click to see YOUR METHOD teddy images" onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html', 'picwin'); return false;">21.0</a>
<span class="rank">140</span></td>
<td>29.1
<span class="rank">140</span></td>
<td>38.6
<span class="rank">139</span></td>
<td><a href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html" title="Click to see YOUR METHOD cones images" onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html', 'picwin'); return false;">14.7</a>
<span class="rank">136</span></td>
<td>24.3
<span class="rank">138</span></td>
<td>29.3
<span class="rank">138</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">22.8</div><div style="width: 100px;" class="innerBar">&nbsp;</div></div></td>
</tr>
<tr class="<? echo $colour[$counter++%2]; ?>">
<td><a title="Rank=140" href=""><b>SSD</b></a></td>
<td><span class="avgRank">139.0</span></td><td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD tsukuba images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html">9.74</a>
<span class="rank">140</span></td>
<td>11.8
<span class="rank">140</span></td>
<td>31.9
<span class="rank">140</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD venus images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html">14.4</a>
<span class="rank">139</span></td>
<td>15.9
<span class="rank">140</span></td>
<td>45.6
<span class="rank">140</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD teddy images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html">21.2</a>
<span class="rank">140</span></td>
<td>29.3
<span class="rank">140</span></td>
<td>42.5
<span class="rank">139</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD cones images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html">13.9</a>
<span class="rank">134</span></td>
<td>23.7
<span class="rank">138</span></td>
<td>33.8
<span class="rank">138</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">24.5</div><div class="innerBar" style="width: 100px;">&nbsp;</div></div></td>
</tr>

<tr class="<? echo $colour[$counter++%2]; ?>">
<td class=""><a title="Rank=140" href=""><b>BMS</b></a></td>
<td class=""><span class="avgRank">138.6</span></td><td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD tsukuba images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/tsukuba/newAlgt100.html">15.0</a>
<span class="rank">140</span></td>
<td>16.9
<span class="rank">140</span></td>
<td>34.8
<span class="rank">140</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD venus images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/venus/newAlgt100.html">16.0</a>
<span class="rank">140</span></td>
<td>17.4
<span class="rank">140</span></td>
<td>38.5
<span class="rank">139</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD teddy images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/teddy/newAlgt100.html">21.8</a>
<span class="rank">140</span></td>
<td>29.8
<span class="rank">140</span></td>
<td>41.5
<span class="rank">139</span></td>
<td><a onclick="openWindow('tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html', 'picwin'); return false;" title="Click to see YOUR METHOD cones images" href="tempAlgs/SID72e1pqi2gc4dqr1nepij6g3u22/cones/newAlgt100.html">12.8</a>
<span class="rank">132</span></td>
<td>22.5
<span class="rank">135</span></td>
<td>30.4
<span class="rank">138</span></td>
<td>
<div class="outerBar">&nbsp;<div class="innerText">24.8</div><div class="innerBar" style="width: 100px;">&nbsp;</div></div></td>
</tr>
</tbody>
</table>

</body>
</html>
