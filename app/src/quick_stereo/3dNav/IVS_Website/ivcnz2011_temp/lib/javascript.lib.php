<?php
//
// special javascript functions for Conftool
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

function get_js_waitmessage() {
	$ret =  "\n<script type=\"text/javascript\" language=\"javascript\">\n";
	$ret .= "<!--\n";
	$ret .= "function disableForm(theform) {\n";
	$ret .= "	if (document.all || document.getElementById) {\n";
	$ret .= "		for (i = 0; i < theform.length; i++) {\n";
	$ret .= "			var tempobj = theform.elements[i];\n";
	$ret .= "			if (tempobj.type.toLowerCase() == \"submit\" || tempobj.type.toLowerCase() == \"reset\") {\n";
	// echo "				tempobj.disabled = true;";  // Causes problems...\n";
	$ret .= "				tempobj.value = \"    ".ct('S_WAIT')."    \";\n";
	$ret .= "				tempobj.className=\"waitbutton\";\n";
	// echo "				theform.submit();";
	$ret .= "			}\n";
	$ret .= "		}\n";
	$ret .= "	}\n";
	$ret .= "	return true;\n";
	$ret .= "}\n";
	$ret .= "// End -->\n";
	$ret .= "</script>	\n";
	return $ret;
}

/**
 * show a window "please wait..."
 *
 * @return unknown
 */
function get_js_waitwindow() {
	$ret =  "\n<script type=\"text/javascript\" language=\"javascript\">\n";
	$ret .= "<!--\n";
	$ret .= "document.write(\"<div id='ct_wait_message' style='visibility: hidden; position:absolute; top:150px; margin-top:0px; left:50%; width:300px; margin-left:-150px; background:#EEE; text-align:center; padding: 10px; border:1px solid #000;'><br><span class='fontbold font12'>".ct('S_WAIT')."<\/span><br><br><img id='ct_wait_image' src='images/waitbar.gif'><br><br><span class='fontbold font11'>".ct('S_PAPER_UPLOAD_WAITMINUTES')."<\/span><br><br><\/div>\");\n";
	$ret .= "function show_wait_message() {\n";
	$ret .= "	var strBrwsr = navigator.userAgent.toLowerCase();\n";
	$ret .= "	if ((strBrwsr.indexOf('msie') > -1 && parseInt(strBrwsr.charAt(strBrwsr.indexOf('msie')+5))>=7) || ";
	$ret .= "  		(strBrwsr.indexOf('mozilla') > -1 && parseInt(strBrwsr.charAt(strBrwsr.indexOf('mozilla')+8))>=5) || "; // Test for IE7 and Mozilla 5 (Firefox etc.)
	$ret .= "  		(strBrwsr.indexOf('opera') > -1 && parseInt(strBrwsr.charAt(strBrwsr.indexOf('opera')+6))>=6) ) {\n"; // Test for Opera 6+
	$ret .= "		document.getElementById('ct_wait_message').style.position='fixed';\n";	// fixed positions for new browsers
	$ret .= "		document.getElementById('ct_wait_message').style.top='150px';\n";	// set position
	$ret .= "		document.getElementById('ct_wait_message').style.visibility='visible';\n";	// make visible
	#$ret .= "		alert(strBrwsr);\n";
	$ret .= "	} else if (strBrwsr.indexOf('msie') > -1 && parseInt(strBrwsr.charAt(strBrwsr.indexOf('msie')+5))>=5) {\n"; // Test for IE 5, 5.5 and 6
	$ret .= "		document.getElementById('ct_wait_message').style.setExpression('top','Math.round((document.documentElement.scrollTop || document.body.scrollTop)+150) + \"px\"');\n"; // Fake position: fixed...
	$ret .= "		document.getElementById('ct_wait_message').style.visibility='visible';\n";	// make visible
	$ret .= "	} else {"; // For all other browsers use absolute position...
	$ret .= "		document.getElementById('ct_wait_message').style.top=(150+(document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop)+'px');\n";	// set new position
	$ret .= "		document.getElementById('ct_wait_message').style.visibility='visible';\n";	// make visible
	$ret .= "	}";
	$ret .= "	setTimeout('refresh_wait_image()', 100);\n";	// reload image, so the animation shows
	$ret .= "	return true;\n";
	$ret .= "}\n";
	$ret .= "function refresh_wait_image() {\n";
	$ret .= "	document.getElementById('ct_wait_image').src='images/waitbar.gif';\n";  // reload image...
	$ret .= "}\n";
	$ret .= "// End -->\n";
	$ret .= "</script>\n";
	return $ret;
}



/**
 * Return a JavaScript function to check if the selected date is valid (checks only the day of the month)
 *
 * @return string the javascript code
 */
function get_js_checkdate() {
	$ret =  "\n<script type=\"text/javascript\" language=\"javascript\">\n";
	$ret .= "<!--\n";
	$ret .= "function checkdate(d, m, y) {\n";
	$ret .= "	day=d.value;month=m.value;year=y.value;\n";
	$ret .= "	var days=31;\n";
	$ret .= "	if (month==4 || month==6 || month==9 || month==11) days = 30;\n";
	$ret .= "	if (month==2) days = (((year%4 == 0) && ( (!(year%100 == 0)) || (year%400 == 0))) ? 29 : 28 );\n";
	#$ret .= "	alert (day+' '+month+' '+year+' : '+days);\n";
	$ret .= "	if (day>days) d.value=days;\n";
	$ret.= "}\n";
	$ret .= "// End checkdate -->\n";
	$ret .= "</script>\n";
	return $ret;
}


/**
 * JavaScript functions to check if changes were saved?
 *
 * @param string $formname name of form
 */
function get_js_checkDataSaved($formname) {
	$ret =  "\n<script type=\"text/javascript\" language=\"javascript\">\n";
	$ret.=  "<!--\n";
	$ret.=  "formInitValues = '';\n";
	$ret.=  "noNeedToConfirmForm = true;\n";
	$ret.=  "function readFormValues() {\n";
	$ret.=  "	var values = '';\n";
	$ret.=  "	var formElement = document.forms.$formname;\n";
	$ret.=  "	if (formElement) {\n";
	$ret.=  "		for (x=0; x<formElement.length; x++) {\n";
	$ret.=  "			formField=formElement.elements[x];\n";
	$ret.=  "			if (formField.type == 'checkbox' || formField.type == 'radio') {\n";
	$ret.=  "				values += formField.checked;\n";
	$ret.=  "			} else {\n";
	$ret.=  "				values += formField.value;\n";
	$ret.=  "			}\n";
	$ret.=  "		}\n";
	#$ret.=  "		alert(values)\n;";
	$ret.=  "	}\n";
	$ret.=  "	return values;\n";
	$ret.=  "}\n";
	$ret.=  "function noNeedToConfirm() {\n";
	$ret.=  "	noNeedToConfirmForm = true;\n";
	$ret.=  "	return true;\n";
	$ret.=  "}\n";
	$ret.=  "function initValues() {\n";
	$ret.=  "	formInitValues = readFormValues();\n";
	$ret.=  "	noNeedToConfirmForm = false;\n";
	$ret.=  "}\n";
	$ret.=  "function testValues() {\n";
	$ret.=  "	if (noNeedToConfirmForm || formInitValues == readFormValues()) { return; }\n";
	$ret.=  "	else { return '".addslashes(ct('S_FORM_NOTSUBMITTED_WARNINGMESSAGE'))."'; }\n";
	$ret.=  "}\n";
	$ret.=  "addEvent(window, 'load', function(){initValues();} );\n";
	$ret.=  "\n";
	$ret.=  "window.onbeforeunload = testValues;\n";
#	$ret.=  "addEvent(window, 'beforeunload', testValues );\n"; // Does not work here!
	$ret.=  "\n";
	$ret .= "// Show Warningmessage -->\n";
	$ret .= "</script>\n";
	return $ret;
}

/**
 * JavaScript functions to check if the user leaves page with submit button...
 *
 * @param string $formname name of form
 */
function get_js_checkSubmit() {
	$ret =  "\n<script type=\"text/javascript\" language=\"javascript\">\n";
	$ret.=  "<!--\n";
	$ret.=  "noNeedToConfirmForm = false;\n";
	$ret.=  "function noNeedToConfirm() {\n";
	$ret.=  "	noNeedToConfirmForm = true;\n";
	$ret.=  "	return true;\n";
	$ret.=  "}\n";
	$ret.=  "function testSubmit() {\n";
	$ret.=  "	if (noNeedToConfirmForm) { return; }\n";
	$ret.=  "	else { return '".addslashes(ct('S_FORM_NOTSUBMITTED_WARNINGMESSAGE'))."'; }\n";
	$ret.=  "}\n";
	$ret.=  "window.onbeforeunload = testSubmit;\n";
	$ret.=  "\n";
	$ret .= "// Show Warningmessage -->\n";
	$ret .= "</script>\n";
	return $ret;
}

?>