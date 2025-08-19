<?php
#
# HTML Form class
#

if (!defined('CONFTOOL')) die('Hacking attempt!');
class CTForm {

	var $action;
	var $method = 'post';
	var $width = '100%';
	var $align = 'left';
	var $cellspacing = '0';
	var $cellpadding = '2';
	var $hidden = array();
	var $fields = array();
	var $buttons = array();
	var $enctype = '';
	var $waitmessage = false; // Show Please wait...
	var $warningmessage = false; // Show warning message if user leaves the page
	var $warningmessage_always = false; // Always show warning message if user leaves the page without submit
	var $upload		 = false; // is this an upload form?
	var $has_datefield = false; // is there at least one date field?
	var $demomode 	 = false; // Disable submit bottom
	var $formname    = "";

	// Create a new form
	// $action is the page name (OR the full target URL) for post queries BUT
	// 			  the page name for GET querys.
	// $method is POST or GET
	// $errors is used to highlight fields that were not filled in correctly.
	function CTForm($_action, $_method='post', $_errors=array()) {
		$this->action = $_action;
		$this->method = $_method;
		$this->errors = $_errors;
		$this->formname = 'ct'.md5(time());
	}

	function add_hidden($hidval) {
		$this->hidden = array_merge($this->hidden, $hidval);
	}

	function add_text($label, $name, $value, $size, $len, $readonly=false, $hint='') {
		$field = array();
		$field['type'] = 'TEXT';
		$field['label'] = $label;
		$field['name'] = $name;
		$field['value'] = $value;
		$field['size'] = $size;
		$field['length'] = $len;
		$field['readonly'] = $readonly;
		$field['hint'] = $hint;
		$this->fields[] = $field;
	}


	/**
	 * Add a text element that is not displayed (in normal graphical browsers)
	 *
	 * @param string $label
	 * @param string $name
	 * @param string $value
	 * @param int $size
	 * @param int $len
	 * @param boolean $readonly
	 * @param string $hint
	 */
	function add_hiddentext($label, $name, $value, $size, $len, $readonly=false, $hint='' ) {
		$field = array();
		$field['type'] = 'TEXT_HIDDEN';
		$field['label'] = $label;
		$field['name'] = $name;
		$field['value'] = $value;
		$field['size'] = $size;
		$field['length'] = $len;
		$field['readonly'] = $readonly;
		$field['hint'] = $hint;
		$this->fields[] = $field;
	}

	function add_pass($label, $name, $value, $size, $len, $readonly=false, $hint='') {
		$field = array();
		$field['type'] = 'PASS';
		$field['label'] = $label;
		$field['name'] = $name;
		$field['value'] = $value;
		$field['size'] = $size;
		$field['length'] = $len;
		$field['readonly'] = $readonly;
		$field['hint'] = $hint;
		$this->fields[] = $field;
	}

	function add_label($label, $value, $name='') {
		$field = array();
		$field['type'] = 'LABEL';
		$field['label'] = $label;
		$field['value'] = $value;
		$field['name'] = $name;  // optional name of this field to allow errors as well...
		$this->fields[] = $field;
	}

	function add_label_3col($label, $value, $hint='', $thirdcol='', $name='') {
		$field = array();
		$field['type'] = 'LABEL_3COL';
		$field['label'] = $label;
		$field['value'] = $value;
		$field['hint'] = $hint;
		$field['thirdcol'] = $thirdcol;
		$field['name'] = $name;  // optional name of this field to allow errors as well...
		$this->fields[] = $field;
	}

	function add_textarea($label, $name, $value, $cols, $rows, $hint_above='', $hint_below='', $counter=0, $extra_attribute='', $readonly=false ) {
		$field = array();
		$field['type'] = 'TEXTAREA';
		$field['label'] = $label;
		$field['name']  = $name;
		$field['value'] = $value;
		$field['cols']  = $cols;
		$field['rows']  = $rows;
		$field['hint']  = $hint_above;
		$field['hint2'] = $hint_below;
		$field['counter']  = $counter;
		$field['extra']    = $extra_attribute;
		$field['readonly'] = $readonly;
		$this->fields[] = $field;
		if ($counter!=0) $this->fieldcounter = true;
	}

	/**
	 * Add HTML Form Checkbox Fields -------------------------------------
	 * $label = Text in the left area of this form field
	 * $boxes = array with the checkboxes, each represented by and 5 field array, e.g.
	 *   $form->add_check(ct('S_TEXT_ON_LEFT'), array(
	 *			array( 'form_fieldname_1', 'form_fieldvalue_1', "Label of Field 1", <boolean - is it selected or not? >, <boolean - readonly box? >),
	 *			array( 'form_fieldname_2', 'form_fieldvalue_2', "Label of Field 2", true, false ) ));
	*/
	function add_check($label, $boxes, $hint='', $hint2='') {
		$field = array();
		$field['type'] = 'CHECK';
		$field['label'] = $label;
		$field['boxes'] = $boxes;
		$field['hint'] = $hint;
		$field['hint2'] = $hint2;
		$this->fields[] = $field;
	}

	function add_check_3col($label, $boxes, $thirdcol, $hint='') {
		$field = array();
		$field['type'] = 'CHECK_3COL';
		$field['label'] = $label;
		$field['boxes'] = $boxes;
		$field['thirdcol'] = $thirdcol;
		$field['hint'] = $hint;
		$this->fields[] = $field;
	}

	function add_radio($label, $name, $boxes, $checked, $hint='') {
		$field = array();
		$field['type'] = 'RADIO';
		$field['label'] = $label;
		$field['name'] = $name;
		$field['boxes'] = $boxes;
		$field['checked'] = $checked;
		$field['hint'] = $hint;
		$this->fields[] = $field;
	}

	function add_radio_3col($label, $name, $boxes, $checked, $thirdcol, $hint='') {
		$field = array();
		$field['type'] = 'RADIO_3COL';
		$field['label'] = $label;
		$field['name'] = $name;
		$field['boxes'] = $boxes;
		$field['checked'] = $checked;
		$field['thirdcol'] = $thirdcol;
		$field['hint'] = $hint;
		$this->fields[] = $field;
	}

	# -- Create a HTML Form Select Box ----------------------------------------------
	# $label = Text in the left area of this form field
	# $name  = form name of this field (in the HTML code)
	# $size  = number of lines of this select box. If >1, it is not a popup box but a scroll field.
	# $values   = an array of 2 field arrays with the values for the select box.
	#             e.g. array(array("code_1", "displayed name for code 1"), array("code_2", "displayed name for code 2"), ...)
	# $selected = an array of the (pre-)selected values of this select box, e.g. array("code_1")
	# $multi    = true or false - is the user allowed to select several items in this box?
	#             Does only work if $size > 1
	# $hint     = a little hint shown right of the select box. Add "<br>" before this text to show it below the box.
	function add_select($label, $name, $size, $values, $selected=array(), $multi=false, $hint='') {
		$field = array();
		$field['type'] = 'SELECT';
		$field['name'] = $name;
		$field['label'] = $label;
		$field['size'] = $size;
		$field['values'] = $values;
		$field['selected'] = $selected;
		$field['multi'] = $multi;
		$field['hint'] = $hint;
		$this->fields[] = $field;
	}

	/**
	* Create a HTML Form Select Box (3 columns, left side)
	* The box will be shown on the left side of the form.
	* $label = Text in the left area of this form field
	* $name  = form name of this field (in the HTML code)
	* $size  = number of lines of this select box. If >1, it is not a popup box but a scroll field.
	* $values   = an array of 2 field arrays with the values for the select box.
	*             e.g. array(array("code_1", "displayed name for code 1"), array("code_2", "displayed name for code 2"), ...)
	* $selected = an array of the (pre-)selected values of this select box, e.g. array("code_1")
	* $multi    = true or false - is the user allowed to select several items in this box?
	*             Does only work if $size > 1
	* $hint     = a little hint shown right of the select box. Add "<br>" before this text to show it below the box.
	* $thirdcol = text for third column (usually the price)
	* $unit     = a unit text to show before the selectbox.
	*/
	function add_select_3col($label, $name, $size, $values, $selected=array(), $multi=false, $hint='', $thirdcol='', $unit='') {
		$field = array();
		$field['type']   = 'SELECT_3COL';
		$field['name']   = $name;
		$field['label']  = $label;
		$field['size']   = $size;
		$field['values'] = $values;
		$field['selected'] = $selected;
		$field['multi']  = $multi;
		$field['hint']   = $hint;
		$field['thirdcol'] = $thirdcol;
		$field['unit']   = $unit;
		$this->fields[]  = $field;
	}

	function add_upload($label, $name, $size, $maxfilesize, $hint="") {
		$field = array();
		$field['type'] = 'UPLOAD';
		$field['label'] = $label;
		$field['name'] = $name;
		$field['size'] = $size;
		$field['maxfilesize'] = $maxfilesize;
		$field['hint'] = $hint;
		$this->fields[] = $field;
		$this->enctype = 'multipart/form-data';
		$this->method = 'post';
		$this->upload = true;
	}

	function add_separator($label) {
		$field = array();
		$field['type'] = 'SEPARATOR';
		$field['label'] = $label;
		$this->fields[] = $field;
	}

	function add_separator3($label1, $info="&nbsp;", $label2="&nbsp;") {
		$field = array();
		$field['type'] = 'SEPARATOR3';
		$field['label1'] = $label1;
		$field['info'] = $info;
		$field['label2'] = $label2;
		$this->fields[] = $field;
	}

	function add_subseparator($label, $info="&nbsp;") {
		$field = array();
		$field['type'] = 'SUBSEPARATOR';
		$field['label'] = $label;
		$field['info'] = $info;
		$this->fields[] = $field;
	}

	function add_subseparator3($label1, $info="&nbsp;", $label2="&nbsp;") {
		$field = array();
		$field['type'] = 'SUBSEPARATOR3';
		$field['label1'] = $label1;
		$field['info'] = $info;
		$field['label2'] = $label2;
		$this->fields[] = $field;
	}

	function add_date($label,$name,$show_day,$selected=array(),$hint=''){
		$field[] = array();
		$field['type'] = 'DATE';
		$field['name'] = $name;
		$field['label'] = $label;
		$field['show_day'] = $show_day;
		$field['selected'] = $selected;
		$field['hint'] = $hint;
		$this->fields[] = $field;
		$this->has_datefield=true;
	}

	function add_datetime($label,$name,$show_day,$selected=array(),$hint=''){
		$field[] = array();
		$field['type'] = 'DATETIME';
		$field['name'] = $name;
		$field['label'] = $label;
		$field['show_day'] = $show_day;
		$field['selected'] = $selected;
		$field['hint'] = $hint;
		$this->fields[] = $field;
		$this->has_datefield=true;
	}

	/**
	 * Crosstable is e.g. used for input of prices
	 *
	 * @param array $tables there can be several crosstables, they are displayed below each other
	 * @param array $row titles of rows
	 * @param array $column titles of columns
	 * @param cells_values $cells values of cells
	 * @param cells_readonly $readonly are the cells readonly?
	 */
	function add_crosstable($tables,$row,$column,$cells,$readonly=array()){
		$field[]=array();
		$field['type'] ='CROSSTABLE';
		$field['tables'] 	= $tables;
		$field['row'] 		= $row;
		$field['column'] 	= $column;
		$field['cells'] 	= $cells;
		$field['readonly'] 	= $readonly;
		$this->fields[] = $field;
	}

	function add_spacer(){
		$field[]=array();
		$field['type'] ='SPACER';
		$this->fields[] = $field;
	}

	function add_submit_not_bottom($name, $label, $highlight=false) {
		$field[] = array();
		$field['type'] = 'SUBMIT_NOT_BOTTOM';
		$field['name'] = $name;
		$field['label'] = $label;
		$field['highlight'] = $highlight;
		$this->fields[] = $field;
	}

	function add_submit($name, $label) {
		$field[] = array();
		$field['type'] = 'SUBMIT';
		$field['name'] = $name;
		$field['label'] = $label;
		$this->buttons[] = $field;
	}

	function add_reset($label) {
		$field[] = array();
		$field['type'] = 'RESET';
		$field['label'] = $label;
		$this->buttons[] = $field;
	}


    # generate and show the form object
	function show() {
		$browser=ct_detect_browser();

		if ($this->waitmessage) {  // JavaScript code for "please wait" message on buttons...
			ct_load_lib('javascript.lib');
			echo get_js_waitmessage();
			if ($this->upload) echo get_js_waitwindow();
			$onSubmit[]="disableForm(this)";
			if ($this->upload) $onSubmit[]="show_wait_message()";
		}

		if ($this->has_datefield) {  // JavaScript-Code to verify the entered date (day of month).
			ct_load_lib('javascript.lib');
			echo get_js_checkdate();
		}

		if ($this->warningmessage_always) {  // Show warning if a user leaves a page without "submit"
			ct_load_lib('javascript.lib');
			echo get_js_checkSubmit($this->formname);
			$onSubmit[]="noNeedToConfirm()";	// If user selects sumbit, no confirm message is required.
		} elseif ($this->warningmessage) {  // Show warning if a user leaves a page, has changed fields and did not submitted the data...
			ct_load_lib('javascript.lib');
			echo get_js_checkDataSaved($this->formname);
			$onSubmit[]="noNeedToConfirm()";	// If user selects sumbit, no confirm message is required.
		}


		// form element
		if (strtolower($this->method)=="get") {
			if (strpos($this->action,"page=") === FALSE)
				$this->add_hidden(array(array("page",$this->action)));
			else {// remove URL and any further parameters from page parameter!
				$matches=array();
				preg_match('/^.*page=([a-zA-Z0-9_\-]*).*$/',$this->action,$matches);
				$this->add_hidden(array(array("page",$matches[1])));
			}
			echo "<form action=\"".ct_pageurl()."\" method=\"get\"";
			if ($this->enctype != "") {
				echo " enctype=\"".$this->enctype."\"";
			}
			echo " name=\"".$this->formname."\"";
			echo ">\n";
		} else { // post
			if (strpos($this->action,"page=") === FALSE)
				echo "<form action=\"".ct_pageurl($this->action)."\"";
			else
				echo "<form action=\"".$this->action."\"";
				echo " method=\"post\"";
			if ($this->enctype != "") {
				echo " enctype=\"".$this->enctype."\"";
			}
			if (count($onSubmit)>0)
				echo " onsubmit=\"return (".implode(' && ',$onSubmit).");\" ";
			#if (count($onKeyPress)>0)
			#	echo " onkeypress=\"(".implode(' && ',$onKeyPress).");\" ";
			echo " name=\"".$this->formname."\"";
			echo ">\n";
		}

		// Check if session ID has to be added:
		if (!ct_detect_robot() && !ini_get("session.use_only_cookies")) { // Don't add it for robots!
			if ($_COOKIE[session_name()]=="") {
				$this->add_hidden(array(array(session_name(),session_id())));
			}
		}

		// Add hidden fields
		if (sizeof($this->hidden) > 0) {
			while (list(,$h) = each($this->hidden)) {
				echo "<input type=\"hidden\" name=\"".$h[0]."\" value=\"".ct_form_encode($h[1])."\">\n";
			}
		}

		// Small change for netscape...
		if ($this->cellspacing==0 && $browser['name']=="netscape")
		 	$this->cellspacing=1;

		// table header
		echo "\n<table align=\"".$this->align."\" ";
		if ($this->width!="") echo "width=\"".$this->width."\" ";
		echo "class=\"form_table\" cellspacing=1 cellpadding=\"$this->cellpadding\" border=0>\n";

        # output all other fields
		reset($this->fields);
		while (list(,$field) = each($this->fields)) {
			if (isset($field['name']) && is_array($this->errors) && !empty($this->errors) &&
						(in_array($field['name'], $this->errors) || in_array($field['label'], $this->errors))) {
				$css_label = "form_td_label_error";
				$css_field = "form_td_field_error";
				$erroricon = '<img src="'.ct_getbaseurl().'images/warning.gif" align="top" alt="Warning">';
			} else {
				$css_label = "form_td_label";
				$css_field = "form_td_field";
				$erroricon = '';
			}
			// Check boxes and radio buttons
			if (isset($field['boxes']) && is_array($this->errors) && !empty($this->errors)) {
				while (list($k,$v) = each($field['boxes'])) {
					if (in_array($v[0], $this->errors)) {
						$css_label = "form_td_label_error";
						$css_field = "form_td_field_error";
						$erroricon = '<img src="'.ct_getbaseurl().'images/warning.gif" align="top" alt="Warning">';
						break;
					}
				}
				reset ($field['boxes']);
			}

			switch ($field['type']) {
			case 'TEXT':
 			case 'TEXT_HIDDEN':
				if ($field['type']=='TEXT_HIDDEN')
					$style_hidden = 'style="display:none;"';
				else
					$style_hidden = '';
				echo "<tr $style_hidden >\n";
				echo "<td $style_hidden width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				echo "<span class=\"form_label\">".$field['label']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td $style_hidden width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				echo "<input type=\"text\" name=\"".$field['name']."\" ";
				echo "value=\"".ct_form_encode($field['value'])."\" size=\"".$field['size']."\" ";
				if ($field['readonly']) {
					echo "readonly class=\"disabled\" ";
				}
				echo "maxlength=\"".$field['length']."\">\n";
				if ($field['hint'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint']."</span>\n";
				}
				echo "</td></tr>\n";
				break;
			 case 'PASS':
				echo "<tr><td width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				echo "<span class=\"form_label\">".$field['label']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				echo "<input type=\"password\" name=\"".$field['name']."\" ";
				echo "value=\"".ct_form_encode($field['value'])."\" size=\"".$field['size']."\" ";
				if ($field['readonly']) {
					echo "readonly ";
				}
				echo "maxlength=\"".$field['length']."\" autocomplete=\"off\">\n";
				if ($field['hint'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint']."</span>\n";
				}
				echo "</td></tr>\n";
				break;

			 case 'LABEL':
				echo "<tr><td width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				if (isset($field['label']) && !empty($field['label']))
					echo "<span class=\"form_label\">".$field['label']."</span>";
			 	echo "&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				echo "<span class=standard>".$field['value']."</span>\n";
				echo "</td></tr>\n";
				break;

			 case 'TEXTAREA':
				echo "<tr><td width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				echo "<span class=\"form_label\">".$field['label']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				if ($field['hint'] != '') {
					echo "<span class=\"form_hint\">".$field['hint']."</span><br>\n";
				}
				echo "<textarea name=\"".$field['name']."\" cols=\"".$field['cols']."\"";
				echo "rows=\"".$field['rows']."\" ";
				if (isset($field['extra']) and $field['extra']!='') {
					echo ' '.$field['extra'];
				}
				if (isset($field['readonly']) and $field['readonly']) {
					echo " readonly class=\"disabled\" ";
				}
				echo ">\n";
				echo ct_form_encode($field['value']);
				echo "</textarea>\n";
				if ($field['hint2'] != '') {
					echo "<div class=\"form_hint\">".$field['hint2']."</div>\n";
				}
				echo "\n</td></tr>\n";
				break;

			 case 'CHECK':
				echo "<tr><td width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				echo "<span class=\"form_label\">".$field['label']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				if ($field['hint'] != '') {
					echo "<span class=\"form_hint\">".$field['hint']."</span><br>\n";
				}
				while (list($k,$v) = each($field['boxes'])) {
					if ($v[0]=="" && $v[1]=="") {						// Only text, no checkbox
						echo "<span class=\"form_checkbox\">".$v[2]."&nbsp;</span><br>\n";
					} elseif (!isset($v[5]) || !$v[5]) {				// Active Checkbox
						$id = preg_replace('|\[\]|','',$v[0]."_".$v[1]); // create id, remove '[]'
						echo "<input type=\"checkbox\" class=\"checkboxradio\" id=\"".$id."\" name=\"".$v[0]."\" value=\"".$v[1]."\"";
						if ($v[3]) {
							echo " checked";
						}
						echo ">\n";
						if ($v[2]!="")
							echo " <label for=\"$id\"><span class=\"form_checkbox\">".$v[2]."&nbsp;</span></label><br>\n";
					} else {										 // read-only
						if ($v[3]) {
							echo "<input type=\"hidden\" name=\"".$v[0]."\" value=\"".$v[1]."\">\n";
							echo "<span class=\"form_checkbox\">";
							echo "<img src=\"".ct_getbaseurl()."images/checkboxOn.gif\" align=top> ";
						} else {
							echo "<span class=\"form_checkbox\">";
							echo "<img src=\"".ct_getbaseurl()."images/checkboxOff.gif\" align=top> ";
						}
						echo trim($v[2])."</span><br>\n";
					}
					if (isset($v[4]) && $v[4] != '') {
						echo "<span class=\"form_hint\">".$v[4]."</span><br>\n";
					}
				}
				if ($field['hint2'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint2']."</span>\n";
				}
				echo "</td></tr>\n";
				break;

			 case 'RADIO':
				echo "<tr><td width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				echo "<span class=\"form_label\">".$field['label']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				if ($field['hint'] != '') {
					echo "<span class=\"form_hint\">".$field['hint']."</span><br><br>\n";
				}
				while (list($k,$v) = each($field['boxes'])) {
					$id = preg_replace('|\[\]|','',$field['name'].'_'.$v[0]);
					echo "<input type=\"radio\" class=\"checkboxradio\" id=\"".$id."\"  name=\"".$field['name']."\" value=\"".ct_form_encode($v[0])."\"";
					if ($field['checked'] == $v[0]) {
						echo " checked";
					}
					if (isset($v[3]) && $v[3] != '') { // Extra attributes / javascript, used in CTParticipation only
						echo ' '.$v[3];
					}
					echo ">&nbsp;";
					echo "<label for=\"$id\"><span class=\"form_radio\">".$v[1]."</span></label><br>\n";
					if (isset($v[2]) && $v[2] != '') {
						echo "<span class=\"form_hint\">".$v[2]."</span><br>\n";
					}
				}
				echo "</td></tr>\n";
				break;
			 case 'SELECT':
				echo "<tr><td width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				echo "<span class=\"form_label\">".$field['label']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				echo "<select name=\"".$field['name']."\" size=\"".$field['size']."\"";
				if ($field['multi']) {
					echo " multiple";
				}
				echo ">\n";
				while (list($k,$v) = each($field['values'])) {
					echo "<option value=\"".ct_form_encode($v[0])."\"";
					if (in_array($v[0], $field['selected'])) {
						echo " selected";
					}
					if (ct_strlen($v[1])>82) $v[1]=ct_substr($v[1],0,80)."...";
					echo ">".ct_form_encode($v[1])." </option>\n";
				}
				echo "</select>\n";
				if ($field['hint'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint']."</span>\n";
				}
				echo"</td></tr>\n";
				break;

			// 3 - Column form elements for participant registration...

			case 'LABEL_3COL':
				echo "<tr><td width=\"30%\" valign=top align=right class=\"form_td_entry\">\n";
				echo "<span class=\"fontbold font10\">".$field['value']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"58%\" align=left valign=top class=\"$css_field\">\n";
				echo "<span class=\"fontbold font10\">".$field['label']."</span>\n";
				if ($field['hint'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint']."</span>\n";
				}
				echo "</td><td width=\"12%\" align=right valign=top class=\"$css_field\">&nbsp;\n";
				echo $field['thirdcol'];
				echo "</td></tr>\n";
				break; // ------------------------

			 case 'CHECK_3COL':
				echo "<tr>";
				echo "<td width=\"30%\" align=right valign=top class=\"form_td_entry\">\n";
				while (list($k,$v) = each($field['boxes'])) {
					echo "<input type=\"checkbox\" class=\"checkboxradio\" name=\"".$v[0]."\" value=\"".$v[1]."\"";
					if ($v[3]) {
						echo " checked";
					}
				echo "> <span class=\"form_checkbox\">".$v[2]."</span>\n";
				}
				echo "&nbsp;&nbsp;<BR><BR>";
				echo $erroricon;
				echo "</td>";
				echo "<td width=\"58%\" align=left valign=top class=\"$css_field\">\n";
				echo "<b>".$field['label']."</b>&nbsp;\n";
					if ($v[4] != '') {
					echo "<span class=\"form_hint\">".$v[4]."</span><br>\n";
					}
				if ($field['hint'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint']."</span>\n";
				}
				echo "</td><td width=\"12%\" align=right valign=top class=\"$css_field\">\n";
				echo $field['thirdcol'];
				echo "</td></tr>\n";
				break;

			 case 'RADIO_3COL':
				echo "<tr>";
				echo "<td width=\"30%\" align=right valign=top class=\"form_td_entry\">\n";
				foreach ($field['boxes'] as $v) {
					echo "<input type=\"radio\" class=\"checkboxradio\" id=\"".$this->formname.'_'.$field['name'].'_'.$v[0]."\" name=\"".$field['name']."\" value=\"".$v[0]."\"";
					if ($field['checked'] == $v[0]) {
						echo " checked";
					}
					echo "> <span class=\"form_radio\">".$v[1]."</span>\n";

				}
				echo "&nbsp;&nbsp;<BR><BR>";
				echo $erroricon;
				echo "</td>";
				echo "<td width=\"58%\" align=left valign=top class=\"$css_field\">\n";
				if (is_array($v) && $v[1]=='') echo "<label for=\"".$this->formname.'_'.$field['name'].'_'.$v[0]."\">";
				echo "<b>".$field['label']."</b>&nbsp;\n";
				if (is_array($v) && $v[1]=='') echo "</label>";
				if ($field['hint'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint']."</span>\n";
				}
				echo "</td>";
				echo "</td><td width=\"12%\" align=right valign=top class=\"$css_field\">\n";
				echo $field['thirdcol'];
				echo "</td></tr>\n";
				break;
			 case 'SELECT_3COL':
				echo "<tr>";
				echo "<td width=\"30%\" align=right valign=top class=\"form_td_entry\">\n";
				echo "<select name=\"".$field['name']."\" size=\"".$field['size']."\"";
				if ($field['multi']) {
					echo " multiple";
				}
				echo ">\n";
				while (list($k,$v) = each($field['values'])) {
					echo "<option value=\"".$v[0]."\"";
					if (in_array($v[0], $field['selected'])) {
						echo " selected";
					}
					if (ct_strlen($v[1])>70) $v[1]=ct_substr($v[1],0,65)."...";
					echo ">".$v[1]."</option>\n";
				}
				echo "</select>\n";
				echo "&nbsp;<BR><BR>";
				echo $erroricon;
				echo "</td>";
				echo "<td width=\"58%\" valign=top align=left class=\"$css_field\">\n";
				echo "<b>".$field['label']."</b>&nbsp;\n";
				if ($field['hint'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint']."</span>\n";
				}
				echo "</td>";
				echo "<td width=\"12%\" align=right valign=top class=\"$css_field\">\n";
				echo $field['thirdcol'];
				echo"</td></tr>\n";
				break;
			 case 'UPLOAD':
				echo "<tr><td width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				echo "<span class=\"form_label\">".$field['label']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".$field['maxfilesize']."\">\n";
				echo "<input type=\"file\" size=\"40\" maxlength=\"".$field['maxfilesize']."\" name=\"".$field['name']."\">\n";
				if ($field['hint'] != '') {
					echo "<br><span class=\"form_hint\">".$field['hint']."</span>\n";
				}
				echo "</td></tr>\n";
				break; // ------------------------
			 case 'SEPARATOR':
				echo "<tr><td colspan=\"3\" valign=top align=left class=\"form_td_separator\">\n";
				echo "<span class=\"form_separator_label\">".$field['label']."</span>\n";
				echo "</td></tr>\n";
				break;
			 case 'SEPARATOR3':
				echo "<tr><td width=\"30%\"  valign=top align=right class=\"form_td_separator\">\n";
				echo "<span class=\"form_separator_label\">".$field['label1']."</span>\n";
				echo "</td><td width=\"58%\"  class=\"form_td_separator\"  align=\"center\"><span class=\"form_separator_label\">".$field['info']."</span>\n";
				echo "</td><td width=\"12%\"  class=\"form_td_separator\"  align=\"center\"><span class=\"form_separator_label\">".$field['label2']."</span>\n";
				echo "</td></tr>\n";
				break;
			case 'SUBSEPARATOR':
				echo "<tr><td valign=top align=left class=\"form_td_subseparator_left\">\n";
				echo "<span class=\"form_subseparator_label\">".$field['label']."</span>\n";
				echo "</td><td class=\"form_td_subseparator_right\" colspan=\"2\" align=\"right\"><span class=\"standard\">".$field['info']."</span>\n";
				echo "</td></tr>\n";
				break;
			case 'SUBSEPARATOR3':
				echo "<tr><td align=left class=\"form_td_subseparator_left\">\n";
				echo "<span class=\"form_subseparator_label\">".$field['label1']."</span>\n";
				echo "</td><td class=\"form_td_subseparator_right\" align=\"center\"><span class=\"standard\">".$field['info']."</span>\n";
				echo "</td><td class=\"form_td_subseparator_right\" align=\"right\"><span class=\"standard\">".$field['label2']."</span>\n";
				echo "</td></tr>\n";
				break;
			case 'SPACER':
				echo '<tr>';
				echo '<td colspan="3" valign=top align=left class="form_td_separator">';
				echo "<img src=\"".ct_getbaseurl()."images/spacer.gif\" width=1 height=1>";
				echo "</td></tr>\n";
				break; // ------------------------
			case 'DATE':
			case 'DATETIME':
			 	echo "<tr><td width=\"30%\" valign=top align=right class=\"$css_label\">\n";
				echo "<span class=\"form_label\">".$field['label']."</span>&nbsp;\n";
				echo $erroricon;
				echo "</td><td width=\"70%\" align=left valign=top class=\"$css_field\" colspan=\"2\">\n";
				if ($field['show_day']==true){
				echo "<select name=\"".$field['name']."_day\" size=\"1\" ";
				echo " onChange=\"checkdate(this.form.".$field['name']."_day,".$field['name']."_month,".$field['name']."_year);\">\n";
					for ($i=1;$i<=31;$i++){
						echo "<option value=\"".sprintf("%02d",$i)."\"";

						if ($i==$field['selected'][2]) {
							echo " selected";
						}
						echo ">".$i."</option>\n";
					}
				echo "</select>";
				}
				echo "<select name=\"".$field['name']."_month\" size=\"1\" ";
				echo " onChange=\"checkdate(this.form.".$field['name']."_day,".$field['name']."_month,".$field['name']."_year);\">\n";
					for ($j=1;$j<=12;$j++){
						echo "<option value=\"".sprintf("%02d",$j)."\"";
						if ($j== $field['selected'][1]) {
							echo " selected";
						}
						echo ">".$j."</option>\n";
					}
				echo "</select>";
				echo "<select name=\"".$field['name']."_year\" size=\"1\" ";
				echo " onChange=\"checkdate(this.form.".$field['name']."_day,".$field['name']."_month,".$field['name']."_year);\">\n";
				$yearfrom=2010;
				if ($field['selected'][0]<2010 && $field['selected'][0]>1900)
					$yearfrom=$field['selected'][0];
				for ($k=$yearfrom;$k<=2020;$k++){
					echo "<option value=\"".sprintf("%04d",$k)."\"";
					if ($k==$field['selected'][0]) {
						echo " selected";
					}
					echo ">".$k."</option>\n";
				}
				echo "</select>\n";
				# also display time?
				if ($field['type']=="DATETIME") {
					echo "&nbsp;&nbsp;";
					echo "<select name=\"".$field['name']."_hour\" size=\"1\">\n";
					for ($k=0;$k<=23;$k++){
						echo "<option value=\"".sprintf("%02d",$k)."\"";
						if ($k==$field['selected'][3]) {
							echo " selected";
						}
						echo ">".$k."</option>\n";
					}
					echo "</select><B>:</B>";
					echo "<select name=\"".$field['name']."_minute\" size=\"1\">\n";
					for ($k=0;$k<60;$k++){
						if ($k%5==0 || $k==59 || $k==$field['selected'][4]) {
							echo "<option value=\"".sprintf("%02d",$k)."\"";
							if ($k==$field['selected'][4]) echo " selected";
							echo ">".$k."</option>\n";
						}
					}
					echo "</select><B>:</B>";
					echo "<select name=\"".$field['name']."_second\" size=\"1\">\n";
					for ($k=0;$k<60;$k++){
						if ($k==0 || $k==59 || $k==$field['selected'][5]) {
							echo "<option value=\"".sprintf("%02d",$k)."\"";
							if ($field['selected'][5]==$k) echo " selected";
							echo ">".$k."</option>\n";
						}
					}
					echo "</select>";
				}
				if ($field['hint'] != '') {
					echo "<BR><span class=\"form_hint\">".$field['hint']."</span>";
				}
				echo "</td></tr>\n";
				break;

			case 'SUBMIT_NOT_BOTTOM':
				echo "<tr><td width=\"30%\" class=\"form_td_label".($field['highlight']?"_yellow":"")."\">&nbsp;\n";
				echo "</td><td width=\"70%\" align=right valign=top class=\"form_td_field".($field['highlight']?"_yellow":"")."\" colspan=\"2\">\n";
				echo "<input class='button' type='submit' name=\"".$field['name']."\" ";
				echo "value=\"".$field['label']."\">\n";
				echo "</td></tr>\n";
				break;

				case 'CROSSTABLE':
				foreach ($field['tables'] as $tkey =>$tvalue){
					echo "<tr><td width=\"12%\" valign=top align=right class=\"$css_label\">\n";
					echo"<span class=\"form_label\">".stripslashes($tvalue)."</span>&nbsp;\n";
					echo "</td><td width=\"85%\" align=left colspan=\"2\">\n";
					echo "<table><tr><td>&nbsp;</td>";

					foreach ($field['row'] as $rkey =>$rvalue){
						echo "<td align=\"center\"><span class=\"fontbold font8\">".stripslashes($rvalue)."</span></td>";
					}
					echo "</tr>";

					foreach ($field['column'] as $ckey =>$cvalue){
						echo"<tr><td align=\"right\"><span class=\"fontbold font8\">".stripslashes($cvalue)."</span></td>";
						foreach ($field['row'] as $rkey =>$rvalue){
							echo "<td align=\"center\"><input type='text' style='text-align:right' name='".$tkey."_".$rkey."_".$ckey."' ";
							echo "value=\"".preg_replace("(\r\n|\n|\r)", ' ', $field['cells'][$tkey][$rkey][$ckey])."\" size=\"6\" maxlength=\"11\" ";
							if (isset($field['readonly'][$tkey][$rkey][$ckey]) && $field['readonly'][$tkey][$rkey][$ckey]==true)
								echo "readonly class=\"disabled\" ";
							echo ">";
							echo "</td>";
						}
						echo"</tr>";
					}
					echo "</table>";
					echo "</td></tr>\n";
				}
				break;

			}
		}

		reset($this->buttons);
		if (count($this->buttons) > 0) {
			echo "<tr><td valign=top align=right colspan=3 class=\"form_td_buttons\">\n";
			while (list(,$button) = each($this->buttons)) {
				if ($button['type'] == 'SUBMIT') {
					if ($this->demomode && !(strtolower($button['name'])=="cancel" || $button['label']==ct('S_BUTTON_CANCEL') )) {
    						echo "<input disabled style=\"color: #000; background:#cde;\"; type=\"submit\" name=\"cancel\" ";
	    					echo "value=\"".$button['label']." ".ct('S_BUTTON_DEMO')."\">\n";
					} else {
    					echo "<input class='button' type='submit' name=\"".$button['name']."\" ";
	    				echo "value=\"".ct_form_encode_noentities($button['label'])."\" ";
						// Hack for Internet Explorer: Set width if much text exists, as the buttons get to wide otherwise
						if ($browser['name']=='msie' && $browser['version']>=5 && ct_strlen($button['label'])>20) {
							$with=min(560,ct_strlen($button['label'])*7);
							echo "style='width:".$with."px' ";
						}
						if ($button['extra_attributes']!="")
							echo $button['extra_attributes']." ";
						echo ">\n";
					}
				} else if ($button['type'] == 'RESET') {
					echo "<input class='button' type=\"reset\" value=\"".$button['label']."\">\n";
				}
			}
			echo "</td></tr>\n";
		}
		echo "</table>\n";
		echo "</form>\n";
	}
}
?>
