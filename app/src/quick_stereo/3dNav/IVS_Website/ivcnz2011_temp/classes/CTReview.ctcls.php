<?php
#
# CLASS:	CTReview
#
if (!defined('CONFTOOL')) die('Hacking attempt!');

class CTReview {

	var $data = array();
	var $errors = array();

	var $styles = array();

	function CTReview() {
		$this->_init();
	}

	function _init() {
				global $db,$session;

		$this->styles = array(''=>''		  ,
						'--'=>''	  ,
						'0' =>'review0',
						'1' =>'review1', '2' =>'review2',
						'3' =>'review3', '4' =>'review4',
						'5' =>'review5', '6' =>'review6',
						'7' =>'review7', '8' =>'review8',
						'9' =>'review9', '10'=>'review10');
	}

	##### database methods #####################################################

	function load($paper, $person) {
		global $db;

		$r = $db->query("select * from reviews where paperID='$paper' and personID='$person'");
		if ($r && ($db->num_rows($r) == 1)) {
			$this->data = $db->fetch($r);
			if ($this->get('creationdate')==0) { // review not submitted, set values to "--"
				$this->data['familiarity']    = "--";
				$this->data['evaluation']    = "--";
				$this->data['significance']  = "--";
				$this->data['originality']   = "--";
				$this->data['relevance']     = "--";
				$this->data['readability']   = "--";
				$this->data['overall']       = "--";
			}
			return true;
		} else {
			$this->data = array();
			return false;
		}
	}

	function persist() {
		global $db;
		$result = $db->replace_into('reviews', $this->data);
	}


	##### data access ##########################################################

        // get a record field's value, fieldname is key
	function get($key) {
		return isset($this->data[$key]) ? $this->data[$key] : '';
	}

	// get a record field value, with html special chars converted
	function get_special($key) {
		return ct_form_encode($this->get($key));
	}

	// set a record fields value
	function set($key, $value) {
		$this->data[$key] = $value;
	}


	function get_reviewer() {

		$person = new CTPerson();
		$person->load_by_id($this->data['personID']);
		return $person;
	}

	function get_paper() {
		$paper = new CTPaper();
		$paper->load_by_id($this->data['paperID']);
		return $paper;
	}


	function get_total() {
		if ($this->get('creationdate')==0) return "--";
		$total = intval(
			($this->data['evaluation'] *10 +
			$this->data['significance'] *10 +
			$this->data['originality'] *10 +
			$this->data['relevance'] *10 +
			$this->data['readability'] *10 +
			$this->data['overall'] * 50 )/10);
		return $total;
	}

		// Return the style sheet for this ranking (usually changes the background color..)
	function get_style($value) {
		$style = "";
		if ($this->get('creationdate')!=0 && $value<>"") $style = $this->styles[$value];
		return $style;
	}

	// Return the style sheet for this ranking (usually changes the background color..)
	function get_totalstyle() {
		$style = "";
		$total = $this->get_total();
		if ($this->get('creationdate')!=0 && $total!=="--") $style = $this->styles[intval($total/10)];
		return $style;
	}

	##### display ##############################################################

	function show_row() {
		echo "<tr>\n";
		echo "<td width=\"14%\" align=center class=\"".$this->styles[$this->data['evaluation']]."\">";
		echo "<span class=\"label10\">".ct('S_REVIEW_SHORT_EVALUATION')."</span><br><span class=\"normal8\">(10%)</span><br>\n";
		echo "<span class=\"bold10\">".$this->data['evaluation']."</span></td>\n";
		echo "<td width=\"14%\" align=center class=\"".$this->styles[$this->data['significance']]."\">";
		echo "<span class=\"label10\">".ct('S_REVIEW_SHORT_SIGNIFICANCE')."</span><br>\n<span class=\"normal8\">(10%)</span><br>\n";
		echo "<span class=\"bold10\">".$this->data['significance']."</span></td>\n";
		echo "<td width=\"14%\" align=center class=\"".$this->styles[$this->data['originality']]."\">";
		echo "<span class=\"label10\">".ct('S_REVIEW_SHORT_ORIGINALITY')."</span><br>\n<span class=\"normal8\">(10%)</span><br>\n";
		echo "<span class=\"bold10\">".$this->data['originality']."</span></td>\n";
		echo "<td width=\"14%\" align=center class=\"".$this->styles[$this->data['relevance']]."\">";
		echo "<span class=\"label10\">".ct('S_REVIEW_SHORT_RELEVANCE')."</span><br>\n<span class=\"normal8\">(10%)</span><br>\n";
		echo "<span class=\"bold10\">".$this->data['relevance']."</span></td>\n";
		echo "<td width=\"14%\" align=center class=\"".$this->styles[$this->data['readability']]."\">";
		echo "<span class=\"label10\">".ct('S_REVIEW_SHORT_READABILITY')."</span><br>\n<span class=\"normal8\">(10%)</span><br>\n";
		echo "<span class=\"bold10\">".$this->data['readability']."</span></td>\n";
		echo "<td width=\"14%\" align=center class=\"".$this->styles[$this->data['overall']]."\">";
		echo "<span class=\"label10\">".ct('S_REVIEW_SHORT_OVERALL')."</span><br>\n<span class=\"normal8\">(50%)</span><br>\n";
		echo "<span class=\"bold10\">".$this->data['overall']."</span></td>\n";
		$total = $this->get_total();
		echo "<td width=\"16%\" align=center class=\"".$this->styles[intval(round($total/10))]."\">";
		echo "<span class=\"label10\">".ct('S_REVIEW_SHORT_TOTAL',array('100'))."</span><br>\n";
		echo "<span class=\"bold12\">".$total."</span></td></tr>\n";
	}

	function show_infobox($width="100%") {
	}

	function show_resultbox($width="100%") {
	}

	function show_detailed($width="100%", $align="center") {
		global $session, $ctconf;

		$user =& $session->get_user();
		$paper = $this->get_paper();
		$author = $paper->get_author();

		echo "<table width=\"$width\"  align=\"$align\" border=0 class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_REVIEW_DETAILED_PAPERSECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";

		echo "<tr><td class=\"darkbg\" align=center valign=middle width=\"5%\"><span class=\"lightbold20\">\n";
		echo $paper->pdata['ID']."</span></td>\n";
		echo "<td class=\"tbldialog\" align=left valign=top width=\"70%\"><span class=\"label10\">";
		echo ct('S_PAPER_TITLE').": </span><span class=\"bold10\">".$paper->get_special('title')."</span>&nbsp; ";

		echo "<span class=\"normal8\">(".$paper->get_contributiontype().")</span>";
		if ($paper->get('externalremark') != "") {
        	echo ' <a class="fontlabel" href="#" ';
        	echo 'title="'.ct('S_PAPER_FORM_EXTERNALREMARK').'" ';
			if (ct_strlen(trim($paper->get('externalremark')))>0) {
				echo 'ext_title="'.ct('S_PAPER_FORM_EXTERNALREMARK').'" ' ;
				echo 'ext_remark="'.ct_substr($paper->get_special('externalremark'),0,512).'" ' ;
			}
			echo '>';
        	echo '<img src="'.ct_getbaseurl().'images/remark.png" border="0" alt="">'; // alt="Remark" // annoying tooltip in IE!
        	echo '</a>';
		}
		echo "<br>";
        // The following line displays the authors of the contribution unless anonymous reviewing.
		if ($ctconf['review/anonymous'] === false || $user->get('ID') == $author->get('ID')) {
			if ($user->is_admin() || $user->is_chair())
				echo "<span class=\"label10\">".ct('S_INDEX_PAPER_SUBMITTEDBY').": <a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$paper->pdata['personID']))."\" class=\"normal10\">".ct_form_encode($author->get_reversename())."</a>&nbsp;";
			else
				echo "<span class=\"label10\">".ct('S_INDEX_PAPER_SUBMITTEDBY').": ".ct_form_encode($author->get_reversename())."&nbsp;";
			echo " (".$author->get_special('organisation').")</span><BR>";
			echo "<span class=\"label10\">".ct('S_PAPER_AUTHOR').": ".$paper->get_special('author')."</span>\n";
		}
		echo "</td></tr>\n";
		echo "</table>\n";

		if ($user->is_chair() or $user->is_admin()) {
			ct_vspacer();
			echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
			echo "<span class=\"boldlabel10\">".ct('S_REVIEW_DETAILED_REVIEWERSECTION')."</span>\n";
			echo "</td></tr>\n";
			echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
			echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
			echo "</td></tr>\n";
			echo "<tr class=\"oddrow\"><td align=\"left\" valign=\"top\" width=\"80%\" colspan=2>\n";
			$reviewer = $this->get_reviewer();
			echo "<a href=\"".ct_pageurl("adminUsersDetails", array("form_id"=>$reviewer->get('ID')))."\" class=\"bold12\">".ct_form_encode($reviewer->get_fullname())."</a><br>\n";
			echo "<span class=\"bold10\">".$reviewer->get_special('organization')."</span>";
			echo "</td></tr>\n";
			echo "</table>\n";
		}

		ct_vspacer();
		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_REVIEW_DETAILED_RESULTSECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"".ct_getbaseurl()."images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "<tr class=\"oddrow\"><td align=\"left\" valign=\"top\" width=\"80%\" colspan=2>\n";
		echo "<table width=\"100%\">";
		$this->show_row();
		echo "</table>\n";
		echo "</td></tr>\n";

		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_REVIEW_DETAILED_COMMENTSECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"".ct_getbaseurl()."images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "<tr class=\"oddrow\"><td align=\"left\" valign=\"top\" width=\"80%\" colspan=2>\n";
		$reviewer = $this->get_reviewer();
		echo "<span class=\"label10\">".ct('S_REVIEW_FORM_SUMMARY_LABEL').":</span><br>\n";
		echo "<span class=\"normal10\">".ct_nl2br($this->get('summary'))."</span><br><hr size=1 noshade>\n";
		echo "<span class=\"label10\">".ct('S_REVIEW_FORM_AUTHORCOMMENTS_LABEL').":</span><br>\n";
		echo "<span class=\"normal10\">".ct_nl2br($this->get('authorcomments'))."</span><br>\n";

		if ($user->is_chair() or $user->is_admin() || $user->get_id()==$this->get('personID')) {
			echo "<hr size=1 noshade>\n";
			echo "<span class=\"label10\">".ct('S_REVIEW_FORM_PCCOMMENTS_LABEL').":</span><br>\n";
			echo "<span class=\"normal10\">".ct_nl2br($this->get('pccomments'))."</span>\n";
		}
		echo "</td></tr>\n";
		echo "</table>\n";
	}

	##### form processing ######################################################

	function _require($field, $name) {
		global $http;

		if (isset($http[$name]) && ($http[$name] != "")) {
			$this->set($field, $http[$name]);
			return true;
		} else {
			$this->errors[] = $name;
			$this->set($field, "");
			return false;
		}
	}

	function process_form($ignore_errors=false) {
		global $http, $session, $user;

		$this->errors = array();
		$okay = true;

		$this->set('paperID', $http['form_paperID']);
		$this->set('personID', $http['form_personID']);
		$this->set('creationdate', $http['form_creationdate']);
		$okay &= $this->_require('evaluation', 'form_evaluation');
		$okay &= $this->_require('significance', 'form_significance');
		$okay &= $this->_require('originality', 'form_originality');
		$okay &= $this->_require('relevance', 'form_relevance');
		$okay &= $this->_require('readability', 'form_readability');
		$okay &= $this->_require('overall', 'form_overall');
		$okay &= $this->_require('familiarity', 'form_familiarity');
		//$okay &= $this->_require('summary', 'form_summary');
		$this->set('summary', $http['form_summary']);  // non-obligatory field
		$okay &= $this->_require('authorcomments', 'form_authorcomments');
		// $okay &= $this->_require('pccomments', 'form_pccomments');
		$this->set('pccomments', $http['form_pccomments']);

		if ($ignore_errors) {
			return false;
		} else {
			if ($okay) {
				return false;
			} else {
				return $this->errors;
			}
		}
	}

	function show_reviewform($width="100%", $errors = array()) {
		global $session,$ctconf;

		$form = new CTForm(ct_pageurl('saveReview'), 'post', $errors);
		$form->align="center";
		$form->width=$width;
		$form->warningmessage=true;

		$form->add_hidden(array(array('form_paperID', $this->data['paperID']),
								array('form_personID', $this->data['personID']),
								array('form_creationdate', $this->data['creationdate'])));

		$paper = $this->get_paper();

		$form->add_separator(ct('S_REVIEW_FORM_INFOSEP'));
		$form->add_label(ct('S_REVIEW_FORM_ID_LABEL'), "<b>".$paper->get('ID')."</b>");
		$form->add_label(ct('S_REVIEW_FORM_TITLE_LABEL'), "<b>".$paper->get_special('title')."</b>");
		// The following line displays the authors of the contribution in the
		// review form unless anonymous reviewing.
		if ($ctconf['review/anonymous'] === false) {
			$form->add_label(ct('S_REVIEW_FORM_AUTHOR_LABEL'), $paper->get_special('author'));
		}
		$form->add_label(ct('S_REVIEW_FORM_TOPICS_LABEL'), implode(", ", $paper->get_topics()));
		$form->add_label(ct('S_REVIEW_FORM_KEYWORDS_LABEL'), $paper->get_special('keywords'));

		$form->add_separator(ct('S_REVIEW_FORM_SUMMARY_LABEL'));
		$form->add_textarea(ct('S_REVIEW_FORM_SUMMARY_LABEL'), 'form_summary', $this->data['summary'], 60, 8, ct('S_REVIEW_FORM_SUMMARY_HINT'));

		// The review scale
		$form->add_separator(ct('S_REVIEW_FORM_VOTESEP'));

		$form->add_radio("* ".ct('S_REVIEW_FORM_EVALUATION_LABEL').'<br>10%','form_evaluation',
		array(
		array('10',ct('S_REVIEW_FORM_EVALUATION_10')),
		// array('9',ct('S_REVIEW_FORM_EVALUATION_9')),
		array('8',ct('S_REVIEW_FORM_EVALUATION_8')),
		// array('7',ct('S_REVIEW_FORM_EVALUATION_7')),
		array('6',ct('S_REVIEW_FORM_EVALUATION_6')),
		// array('5',ct('S_REVIEW_FORM_EVALUATION_5')),
		array('4',ct('S_REVIEW_FORM_EVALUATION_4')),
		// array('3',ct('S_REVIEW_FORM_EVALUATION_3')),
		array('2',ct('S_REVIEW_FORM_EVALUATION_2')),
		// array('1',ct('S_REVIEW_FORM_EVALUATION_1')),
		array('0',ct('S_REVIEW_FORM_EVALUATION_0'))), $this->data['evaluation'],
		ct('S_REVIEW_FORM_EVALUATION_HINT')
		);

		$form->add_radio("* ".ct('S_REVIEW_FORM_SIGNIFICANCE_LABEL').'<br>10%','form_significance',
		array(	array('10',ct('S_REVIEW_FORM_SIGNIFICANCE_10')),
		// array('9',ct('S_REVIEW_FORM_SIGNIFICANCE_9')),
		array('8',ct('S_REVIEW_FORM_SIGNIFICANCE_8')),
		// array('7',ct('S_REVIEW_FORM_SIGNIFICANCE_7')),
		array('6',ct('S_REVIEW_FORM_SIGNIFICANCE_6')),
		// array('5',ct('S_REVIEW_FORM_SIGNIFICANCE_5')),
		array('4',ct('S_REVIEW_FORM_SIGNIFICANCE_4')),
		// array('3',ct('S_REVIEW_FORM_SIGNIFICANCE_3')),
		array('2',ct('S_REVIEW_FORM_SIGNIFICANCE_2')),
		// array('1',ct('S_REVIEW_FORM_SIGNIFICANCE_1')),
		array('0',ct('S_REVIEW_FORM_SIGNIFICANCE_0'))), $this->data['significance'],
		ct('S_REVIEW_FORM_SIGNIFICANCE_HINT')
		);

		$form->add_radio("* ".ct('S_REVIEW_FORM_ORIGINALITY_LABEL').'<br>10%','form_originality',
		array(	array('10',ct('S_REVIEW_FORM_ORIGINALITY_10')),
		// array('9',ct('S_REVIEW_FORM_ORIGINALITY_9')),
		array('8',ct('S_REVIEW_FORM_ORIGINALITY_8')),
		// array('7',ct('S_REVIEW_FORM_ORIGINALITY_7')),
		array('6',ct('S_REVIEW_FORM_ORIGINALITY_6')),
		// array('5',ct('S_REVIEW_FORM_ORIGINALITY_5')),
		array('4',ct('S_REVIEW_FORM_ORIGINALITY_4')),
		// array('3',ct('S_REVIEW_FORM_ORIGINALITY_3')),
		array('2',ct('S_REVIEW_FORM_ORIGINALITY_2')),
		// array('1',ct('S_REVIEW_FORM_ORIGINALITY_1')),
		array('0',ct('S_REVIEW_FORM_ORIGINALITY_0'))), $this->data['originality'],
		ct('S_REVIEW_FORM_ORIGINALITY_HINT')
		);
		$form->add_radio("* ".ct('S_REVIEW_FORM_RELEVANCE_LABEL').'<br>10%','form_relevance',
		array(	array('10',ct('S_REVIEW_FORM_RELEVANCE_10')),
		// array('9',ct('S_REVIEW_FORM_RELEVANCE_9')),
		array('8',ct('S_REVIEW_FORM_RELEVANCE_8')),
		// array('7',ct('S_REVIEW_FORM_RELEVANCE_7')),
		array('6',ct('S_REVIEW_FORM_RELEVANCE_6')),
		// array('5',ct('S_REVIEW_FORM_RELEVANCE_5')),
		array('4',ct('S_REVIEW_FORM_RELEVANCE_4')),
		// array('3',ct('S_REVIEW_FORM_RELEVANCE_3')),
		array('2',ct('S_REVIEW_FORM_RELEVANCE_2')),
		// array('1',ct('S_REVIEW_FORM_RELEVANCE_1')),
		array('0',ct('S_REVIEW_FORM_RELEVANCE_0'))), $this->data['relevance'],
		ct('S_REVIEW_FORM_RELEVANCE_HINT')
		);


		$form->add_radio("* ".ct('S_REVIEW_FORM_READABILITY_LABEL').'<br>10%','form_readability',
		array(	array('10',ct('S_REVIEW_FORM_READABILITY_10')),
		// array('9',ct('S_REVIEW_FORM_READABILITY_9')),
		array('8',ct('S_REVIEW_FORM_READABILITY_8')),
		// array('7',ct('S_REVIEW_FORM_READABILITY_7')),
		array('6',ct('S_REVIEW_FORM_READABILITY_6')),
		// array('5',ct('S_REVIEW_FORM_READABILITY_5')),
		array('4',ct('S_REVIEW_FORM_READABILITY_4')),
		// array('3',ct('S_REVIEW_FORM_READABILITY_3')),
		array('2',ct('S_REVIEW_FORM_READABILITY_2')),
		// array('1',ct('S_REVIEW_FORM_READABILITY_1')),
		array('0',ct('S_REVIEW_FORM_READABILITY_0'))), $this->data['readability'],
		ct('S_REVIEW_FORM_READABILITY_HINT')
		);


		$form->add_separator(ct('S_REVIEW_FORM_OVERALLSEP'));
		$form->add_radio("* ".ct('S_REVIEW_FORM_OVERALL_LABEL').'<br>50%','form_overall',

		array(	array('10',ct('S_REVIEW_FORM_OVERALL_10')),
		array('9',ct('S_REVIEW_FORM_OVERALL_9')),
		array('8',ct('S_REVIEW_FORM_OVERALL_8')),
		array('7',ct('S_REVIEW_FORM_OVERALL_7')),
		array('6',ct('S_REVIEW_FORM_OVERALL_6')),
		array('5',ct('S_REVIEW_FORM_OVERALL_5')),
		array('4',ct('S_REVIEW_FORM_OVERALL_4')),
		array('3',ct('S_REVIEW_FORM_OVERALL_3')),
		array('2',ct('S_REVIEW_FORM_OVERALL_2')),
		array('1',ct('S_REVIEW_FORM_OVERALL_1')),
		array('0',ct('S_REVIEW_FORM_OVERALL_0'))), $this->data['overall'],
		ct('S_REVIEW_FORM_OVERALL_HINT')
		);

		$form->add_separator(ct('S_REVIEW_FORM_COMMENTSSEP'));
		$form->add_textarea("* ".ct('S_REVIEW_FORM_AUTHORCOMMENTS_LABEL'), 'form_authorcomments',$this->data['authorcomments'], 60, 15, ct('S_REVIEW_FORM_AUTHORCOMMENTS_HINT'));


		$form->add_separator(ct('S_REVIEW_FORM_INTERNALSEP'));
		$form->add_radio("* ".ct('S_REVIEW_FORM_FAMILIARITY_LABEL'),'form_familiarity',
		array(	array('10',ct('S_REVIEW_FORM_FAMILIARITY_10')),
		// array('9',ct('S_REVIEW_FORM_FAMILIARITY_9')),
		array('8',ct('S_REVIEW_FORM_FAMILIARITY_8')),
		// array('7',ct('S_REVIEW_FORM_FAMILIARITY_7')),
		array('6',ct('S_REVIEW_FORM_FAMILIARITY_6')),
		// array('5',ct('S_REVIEW_FORM_FAMILIARITY_5')),
		array('4',ct('S_REVIEW_FORM_FAMILIARITY_4')),
		// array('3',ct('S_REVIEW_FORM_FAMILIARITY_3')),
		array('2',ct('S_REVIEW_FORM_FAMILIARITY_2')),
		// array('1',ct('S_REVIEW_FORM_FAMILIARITY_1')),
		array('0',ct('S_REVIEW_FORM_FAMILIARITY_0'))), $this->data['familiarity'],
		ct('S_REVIEW_FORM_FAMILIARITY_HINT')
		);

		$form->add_textarea(ct('S_REVIEW_FORM_PCCOMMENTS_LABEL'), 'form_pccomments', $this->data['pccomments'], 60, 10, ct('S_REVIEW_FORM_PCCOMMENTS_HINT'));

		$form->add_submit('cmd_savereview', ct('S_REVIEW_FORM_SAVECMD'));
		#$form->add_reset(ct('S_REVIEW_FORM_RESETCMD'));

		$form->show();
	}

}

?>