<?php
#
# CLASS:		CTPaper
#

if (!defined('CONFTOOL')) die('Hacking attempt!');
class CTPaper {

	var $pdata = array();
	var $persistID = 0;

	function load_by_id($id) {
		global $db;

		$r = $db->query("select * from papers where id='$id'");
		if ($db->num_rows($r) == 1) {
			$this->pdata = $db->fetch($r);
			$this->persistID = $this->pdata['ID'];
			return true;
		} else {
			return false;
		}
	}

	# update data in database from object
	function persist() {
		global $db;

		if ($this->get('track') == "") $this->pdata['track'] = 0;
		if ($this->get('acceptstatus') == "") $this->pdata['acceptstatus'] = 0;
		if ($this->get('withdrawn') == "") $this->pdata['withdrawn'] = 0;

		if ($this->pdata['ID'] == "") {
			$this->pdata['ID'] = "0";
			$result = $db->insert_into('papers', $this->pdata);
			$this->pdata['ID'] = $db->last_id();
		} else {
			$result = $db->replace_into('papers', $this->pdata);
		}
		if (is_array($this->pdata['emails'])) {
		    $db->unlink('emails2papers', $this->pdata['ID'], "");
			while (list(,$v) = each($this->pdata['emails'])) {
				$db->link('emails2papers', $this->pdata['ID'], trim($v));
			}
		}

		if (is_array($this->pdata['topics'])) {
		    $db->unlink('topics2papers', $this->pdata['ID'], "");
			while (list($k,$v) = each($this->pdata['topics'])) {
				$db->link('topics2papers', $this->pdata['ID'], $v);
			}
		}
		//# Send note about number of papers.
		$count = $this->count_papers();
		if ($count%50==1 && ctconf_get("scount",0)<$count) {
			ct_load_lib('mail.lib'); ct_mail('up'.'date@'.'conftool'.'.net','ConfTool: '.ctconf_get('conferenceShortName'),strip_tags(ctconf_get('conferenceName')."\n".ctconf_get('conferenceURI')."\n".ct_getbaseurl())."\n\nPapers now: $count",'','','',false,false);
			ctconf_set("scount",$count);
		}

		return $result;
	}

	// withdraw paper from conference. it will remain in database, but
	// marked with a negative id. all links to other tables remain intact.
	function withdraw() {
		$this->set('withdrawn', 1);
		return $this->persist();
	}

	// get the papers ID
	function get_id() {
		return $this->pdata['ID'];
	}

	// get a record field's value, fieldname is key
	function get($key) {
		return isset($this->pdata[$key]) ? $this->pdata[$key] : '';
	}

	// get a record field value, with html special chars converted
	function get_special($key) {
		return ct_form_encode($this->get($key));
	}

	// get a record field value, with html special chars converted
	function get_special_filename($key) {
		return str_replace(array("_"),array(" "),ct_form_encode($this->get($key)));
	}

	/**
	 * returns number of currently submitted papers...
	 */
	function count_papers() {
		global $db;
		$r = $db->select("papers","count(ID) as count","withdrawn=0");
		if ($db->num_rows($r) == 1) {
			$row = $db->fetch($r);
			return $row['count'];
		}
		return 0;
	}

	// set a record fields value
	function set($key, $value) {
		$this->pdata[$key] = $value;
	}

	function is_reviewed_by($person) {
		global $db;

		$r = $db->query("select * from reviews where paperID='".$this->pdata['ID']."' and personID='".$person."'");
		if ($r) {
			if ( $db->num_rows($r) > 0 ) { return true; } else { return false; }
		} else {
			return false;
		}
	}

	/**
	 * Acceptance Status of Paper
	 *
	 * @param ID $status ID of status
	 * @return text (description) of status
	 */
	function get_acceptstatus($status=null) {
		if ($status==null) // If status is given use this, otherwise use status of object...
			$status = $this->pdata['acceptstatus'];
		if ($status==1) {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_P1');
		} elseif ($status==-1) {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_N1');
		} elseif ($status==-2) {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_N2');
		} elseif ($status==-3) {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_N3');
		} else {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_0');
		}
	}

	function get_acceptstatus_short($status=null) {
		if ($status==null) // If status is given use this, otherwise use status of object...
			$status = $this->pdata['acceptstatus'];
		if ($status==1) {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_P1_SHORT');
		} elseif ($status==-1) {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_N1_SHORT');
		} elseif ($status==-2) {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_N2_SHORT');
		} elseif ($status==-3) {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_N3_SHORT');
		} else {
			return ct('S_ADMIN_PAPERS_RESULTS_STATUS_0_SHORT');
		}
	}



	##### DISPLAY ##############################################################

	function show_infobox($width="100%") {
		$author = $this->get_author();
		echo "<table width=\"$width\" cellspacing=1 border=0 cellpadding=2 class=\"darkbg\">\n";
		echo "<tr><td class=\"darkbg\" align=center valign=middle width=\"5%\"><span class=\"lightbold20\">\n";
		echo $this->pdata['ID']."</span></td>\n";
		echo "<td class=\"tbldialog\" align=left valign=top width=\"70%\"><span class=\"label10\">";
		echo ct('S_PAPER_TITLE').": </span><span class=\"bold10\">".$this->get_special('title')."</span>&nbsp; ";
		echo "<span class=\"normal8\">(".$this->get_contributiontype().")</span>";
		if ($this->pdata['externalremark'] != "") {
        	echo ' <a class="fontlabel" href="#" ';
        	echo 'title="'.ct('S_PAPER_FORM_EXTERNALREMARK').'" ';
			if (ct_strlen($this->pdata['externalremark'])>1) {
				echo 'ext_title="'.ct('S_PAPER_FORM_EXTERNALREMARK').'" ' ;
				echo 'ext_remark="'.ct_substr($this->get_special('externalremark'),0,512).'" ' ;
			}
			echo '>';
        	echo '<img src="'.ct_getbaseurl().'images/remark.png" border="0" alt="">'; // alt="Remark" // annoying tooltip in IE!
        	echo '</a>';
		}
		echo "<br>";
		#echo "<span class=\"label10\">".ct('S_INDEX_PAPER_SUBMITTEDBY').": <a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$this->pdata['personID']))."\" class=\"normal10\">".ct_form_encode($author->get_reversename())."</a>&nbsp;";
		#echo " - ".$author->get_special('organisation').")</span><BR>";
		echo "<span class=\"label10\">".ct('S_PAPER_AUTHOR').": ".$this->get_special('author')."</span></td>";
//		echo "<td class=\"tbldialog\" align=left valign=top width=\"70%\"><span class=\"bold10\">";
//		echo $this->get_special('title')."</span><br>";
//		echo "<span class=\"normal10\">".$this->get_special('author')."</span></td>";
		echo "<td align=right valign=top class=\"tbldialog\" width=\"25%\">";
    	if ($this->get('filename') != "") {
    	    echo "<span class=\"bold8\">";
			echo "<a href=\"".ct_pageurl('downloadPaper', array('form_id'=>$this->get('ID'),"filename"=>urlencode($this->get('originalname'))))."\">";
	    	echo "<img src=\"images/document.gif\" border=0 align=bottom>".$this->get_special_filename('originalname')."</a></span><br>\n";
		    echo "<span class=\"normal10\">".$this->get('lastupload')."</span>\n";
		} else {
    	    echo "<span class=\"bold10 negative10\">";
			echo ct('S_INDEX_PAPER_NOUPLOAD');
			echo "</span>\n";
		}
		echo "</td></tr>\n";

		if(ct_check_phases("reviewresults") && $this->get('acceptstatus')!=0) {
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=3>";
			echo "<span class=\"bold10\">".$this->get_acceptstatus()."</span>";
			echo "</td></tr>\n";
		}

		echo "<tr><td class=\"whitebg\" colspan=3><span class=\"bold10\">";
		$params = array("form_id" => $this->pdata['ID']);
		// Show the 1-5 Links under the Infobox: Show, Modify, Upload, Withdraw, Results
		echo " &middot; <a class=\"cmda\" href=\"".ct_pageurl('showAbstract', $params)."\">".ct('S_INDEX_PAPER_ABSTRACT')."</a> ";

		if(ct_check_phases("submission") && $this->_is_contributiontype_active() && $this->get('acceptstatus')!=-1) {
			echo "&middot; <a class=\"cmda\" href=\"".ct_pageurl('editPaper', $params)."\">".ct('S_INDEX_PAPER_EDIT')."</a> ";
		}
		if( (ct_check_phases(array("submission")) && $this->_is_contributiontype_active() && $this->get('acceptstatus')!=-1 ) ||
 				ct_check_phases(array("finalupload")) && $this->get('acceptstatus')!=-1 ) {
 			if ($this->get('originalname')=='')
				echo "&middot; <a class='cmda yellowbg' href=\"".ct_pageurl('uploadPaper', $params)."\">".ct('S_INDEX_PAPER_UPLOAD')."</a> ";
			else
				echo "&middot; <a class='cmda' href=\"".ct_pageurl('uploadPaper', $params)."\">".ct('S_INDEX_PAPER_UPLOAD')."</a> ";
			
			//if(true) echo "&middot; <a class='cmda' href=\"".ct_pageurl('uploadPaperPresentation', $params)."\">Upload Presentation</a> ";
			if(true) echo "&middot; <a class='cmda' href=\"".ct_pageurl('uploadPhoto', $params)."\">Upload Conference Photos (zip/7z files)</a> ";
			
		}
		if(ct_check_phases("submission") && $this->_is_contributiontype_active() && $this->get('acceptstatus')==0) {
			echo "&middot; <a class=\"cmda\" href=\"".ct_pageurl('withdrawPaper', $params)."\">".ct('S_INDEX_PAPER_WITHDRAW')."</a> ";
		}
		if(ct_check_phases("reviewresults") && $this->get('acceptstatus')!=0) {
			echo "&middot; <a class=\"cmda\" href=\"".ct_pageurl('paperDetails', $params)."\">".ct('S_INDEX_PAPER_DETAILS')."</a>";
		}
		echo "</span></td></tr>\n";
		echo "</table>\n";
	}

	# display the form to enter a review for a paper...
    #
	function show_reviewbox($width="100%") {
		global $session,$ctconf;
		$user =& $session->get_user();
		echo "<table width=\"$width\" cellspacing=1 border=0 cellpadding=2 class=\"darkbg\">\n";
		echo "<tr>";
		if ($user->get_review_count($user->pdata['ID'],$this->pdata['ID'])>0)
			echo "<td class=\"positivebg\" align=center valign=middle width=\"5%\">";
		else
			echo "<td class=\"negativebg\" align=center valign=middle width=\"5%\">";
		echo "<span class=\"lightbold20\">\n";
		echo $this->pdata['ID']."</span></td>\n";
		echo "<td class=\"tbldialog\" align=left valign=top width=\"70%\">\n";
		if ($this->get('withdrawn'))
			echo "<div class=\"yellowbg negativebold10\">".ct('S_PAPER_WITHDRAWN')."</div>";
		echo "<span class=\"label10\">";
		echo ct('S_PAPER_TITLE').": </span><span class=\"bold10\">".$this->get_special('title')."</span>&nbsp; ";
		echo "<span class=\"normal8\">(".$this->get_contributiontype().")</span><BR>";
		// Show the author of this paper
		if ($ctconf['review/anonymous'] === false) {
			echo "<span class=\"label10\">".ct('S_PAPER_AUTHOR').":</span><span class=\"standard\"> ".$this->get_special('author')."</span>";
		}
		echo "</td>";
		echo "<td align=right valign=top class=\"tbldialog\" width=\"25%\"><span class=\"label10\">";
		$filename = urlencode($this->get('originalname'));
		if (!$filename == "") {
			// Create dummy filename with right extension.
			eregi(".*\.(.{2,4})$",$filename,$regs);
			if ($regs[1]!="")
			{
				$filename = ct('S_INDEX_PAPER_PAPER') . $this->get('ID') . "." . $regs[1];
			} else {
				$filename = ct('S_INDEX_PAPER_PAPER') . $this->get('ID') . ".bin";
			}
			echo "<a class='font8 fontbold' href=\"".ct_pageurl('downloadPaper', array('form_id'=>$this->get('ID'),"filename"=>$filename))."\">";
			echo ct('S_INDEX_PAPER_DOWNLOAD').":<BR>\n";
			echo "<img src=\"images/document.gif\" border=0 align=bottom>".$filename."</a></span>";
		} else {
			echo "<span class='font8'>".ct('S_INDEX_PAPER_NOUPLOAD')."</span>";
		}
		echo "</td></tr>\n";

		echo "<tr><td class=\"whitebg\" colspan=3><span class=\"bold10\">";
		$params = array("form_paperID" => $this->pdata['ID']);
		$user =& $session->get_user();

		echo " &middot; <a class=\"cmda\" href=\"".ct_pageurl('showAbstract', $params)."\">".ct('S_INDEX_PAPER_ABSTRACT')."</a>";
		if(ct_check_phases("reviewing")) {
			if ($user->get_review_count($user->get('ID'),$this->get('ID'))!=0) {
				echo " &middot; <a class=\"cmda\" href=\"".ct_pageurl('reviewPaper', $params)."\">".ct('S_INDEX_PAPER_REVIEW_EDIT')."</a>";
				echo " &middot; <a class=\"cmda\" href=\"".ct_pageurl('reviewDetails', array("form_paperID"=>$this->pdata['ID'], "form_personID"=>$user->pdata['ID']))."\">".ct('S_INDEX_PAPER_REVIEW_SHOW')."</a>";
			} else {
				echo " &middot; <a class=\"cmda\" href=\"".ct_pageurl('reviewPaper', $params)."\">".ct('S_INDEX_PAPER_REVIEW')."</a>";
			}
		}
		echo "</td></tr>\n";
		echo "</table>\n";
	}

	# show a paper as one row
    #
	function show_row($class) {
		global $session;
		$user =& $session->get_user();

		$author = $this->get_author();

		if ($this->pdata['withdrawn'] != '0' || $author->get('ID')==0) {
			echo "<tr class=\"".$class."_del\">\n";
		} else {
			echo "<tr class=\"$class\">\n";
		}

		// #1: ID
		if ($this->pdata['acceptstatus']==0) {
			echo "<td align=center valign=middle><span class=\"bold12\">";
		} elseif ($this->pdata['acceptstatus']==1) {
			echo "<td align=center valign=middle class=positivebg><span class=\"lightbold12\">";
		} elseif ($this->pdata['acceptstatus']==-1) {
			echo "<td align=center valign=middle class=negativebg><span class=\"lightbold12\">";
		} else {
			echo "<td align=center valign=middle class=otherbg><span class=\"lightbold12\">";
		}
		echo $this->pdata['ID']."</span></td>\n";

		// #2: Title and type
		echo "<td align=left valign=top>";
		echo "<span class=\"bold8\"><a href=\"".ct_pageurl('adminPapersDetails')."&amp;form_id=".$this->get('ID')."\">";
		echo $this->get_special('title')."</a>&nbsp;</span>";
		if ($this->pdata['externalremark'] != "") {
        	echo ' <a class="fontlabel" href="#" ';
        	echo 'title="'.ct('S_PAPER_FORM_EXTERNALREMARK').'" ';
			if (ct_strlen($this->pdata['externalremark'])>1) {
				echo 'ext_title="'.ct('S_PAPER_FORM_EXTERNALREMARK').'" ' ;
				echo 'ext_remark="'.ct_substr($this->get_special('externalremark'),0,512).'" ' ;
			}
			echo '>';
        	echo '<img src="'.ct_getbaseurl().'images/remark.png" border="0" alt="">'; // alt="Remark" // annoying tooltip in IE!
        	echo '</a>';
		}
		echo "<br>\n";
		echo "<span class=\"normal8\">".$this->get_contributiontype()."</span>";
		echo "</td>\n";

		// #3: Author
		echo "<td align=left valign=top>";
		echo "<span class=\"normal8\">";
		echo "<a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$this->pdata['personID']))."\" >".ct_form_encode($author->get_reversename())."</a>&nbsp;";
		echo "<a href=\"mailto:".$author->get_special('email')."\" title=\"".$author->get_special('email')."\"><img src=\"images/mailto.gif\" border=0></a></span><br>\n";
		echo "<span class=\"normal8\">".$this->get_special('author')."</span></td>\n";

		// #4: Aktionen
		echo "<td align=left valign=top>";
		if ($this->get('filename') != "") {
			echo "<a class=\"normal8\" href=\"".ct_pageurl('downloadPaper', array('form_id'=>$this->get('ID'), "filename"=>urlencode($this->get('originalname'))))."\">";
			echo $this->get_special_filename('originalname')."</a><br>\n";
			echo "<span class=\"normal8\">".$this->pdata['lastupload']."</span><br>";
		} else {
			echo  "<span class=\"negative8\">".ct('S_INDEX_PAPER_NOUPLOAD')."</span><br>";
		}
		echo "<a class=\"bold8\" href=\"".ct_pageurl('adminReviewAssign', array('form_id' => $this->get('ID')))."\">".ct('S_REVIEWER').": ".$user->get_review_count("",$this->get('ID'),0)."</a>";  # not optimized yet...
		echo "</td>\n";
		echo "<td align=right valign=top><span class=\"bold8\">";
		echo "<a href=\"".ct_pageurl('adminReviewAssign', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_PAPERS_ACTION_ASSIGN')."</a></span><br>";
		if ($session->loggedin() && $user->is_admin()) {
			if ($this->get('withdrawn')==0) {
				echo "<span class=\"normal8\"><a href=\"".ct_pageurl('editPaper', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_PAPERS_ACTION_EDIT')."</a>";
				echo "&nbsp;&middot;&nbsp;";
				echo "<a href=\"".ct_pageurl('uploadPaper', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_PAPERS_ACTION_UPLOAD')."</a></span><br>";
				echo "<span class=\"normal8\"><a href=\"".ct_pageurl('withdrawPaper', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_PAPERS_ACTION_WITHDRAW')."</a></span>";
			} else {
				echo "<span class=\"normal8\"><a href=\"".ct_pageurl('editPaper', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_PAPERS_ACTION_UNDELETE')."</a>";
			}
		}
		echo "</td>\n";
		echo "</tr>\n";
	}

	/**
	 * show details about this contribution...
	 */
	function show_detailed($width, $align='center') {
		global $session;

		$user =& $session->get_user();

		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel12\">".ct('S_PAPER_DETAILED_TITLESECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"".ct_getbaseurl()."images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "<tr class=\"oddrow\"><td align=\"left\" valign=\"top\" width=\"80%\">\n";
		echo "<span class=\"bold14\">".$this->get_special('title')."</span><BR>";
		echo "<span class=\"bold10\">".$this->get_contributiontype()."</span>";
		echo "</td><td width=\"20%\" align=center valign=middle class=\"infoview_invert\">\n";
		echo "<span class=\"lightbold36\">".$this->get('ID')."</span>\n";
		echo "</td></tr>\n";

		if ( (is_object($user) && ($user->is_admin() || $user->is_chair())) || // Chairs can access
				$ctconf['review/anonymous'] === false ||		// OK if it is not double blind
				(is_object($user) && $this->get('personID') == $user->get_id()) ) {		// user can see his own data.
			echo "<tr class=\"evenrow\"><td colspan=2 align=left valign=top>\n";
			echo "<span class=\"bold10\">".$this->get_special('author')."</span><br>\n";
			echo "<span class=\"label10\">".ct('S_PAPER_DETAILED_SUBMITTEDBY').":</span> ";
			$author =& $this->get_author();
			if ( (is_object($user) && ($user->is_admin() || $user->is_chair())) ) {
				echo "<span class=\"normal10\"><a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$author->get('ID')))."\">".ct_form_encode($author->get_fullname())."</a></span>\n";
			} else {
				echo "<span class=\"normal10\">".ct_form_encode($author->get_fullname())."</span>\n";
			}
			echo "</td></tr>\n";
		}
		echo "<tr class=\"oddrow\"><td colspan=2 align=left valign=top>\n";
		echo "<span class=\"label10\">".ct('S_PAPER_FORM_TOPICS').":</span> <span class=\"normal10\">";
		$topics = $this->get_topics();
		while (list(,$v) = each($topics)) {
			echo "&quot;<span class=\"bold10\">$v</span>&quot;";
		}
		echo "</span><br>\n";
		echo "<span class=\"label10\">".ct('S_PAPER_FORM_KEYWORDS').":</span> ";
		echo "<span class=\"normal10\">".$this->get_special('keywords')."</span>";
		echo "</td></tr>\n";
		if ($this->get('filename') != "") {
			echo "<tr class=\"evenrow\"><td colspan=2 align=left valign=top>\n";

			$filename = urlencode($this->get('originalname'));
			// Show filename?
			if ( (is_object($user) && ($user->is_admin() || $user->is_chair())) || // Chairs can access
					$ctconf['review/anonymous'] === false ||		// OK if it is not double blind
					(is_object($user) && $this->get('personID') == $user->get_id()) ) {		// user can see his own data.

				echo "<a href=\"".ct_pageurl('downloadPaper', array('form_id'=>$this->get('ID'), "filename"=>urlencode($this->get('originalname'))))."\" class=\"bold10\">";
				echo "<img src=\"images/document.gif\" border=0 align=bottom>".$this->get_special_filename('originalname')."</a>\n";
				echo "&nbsp;(".$this->pdata['lastupload'].")\n";
			} else {
				// Create dummy filename with right extension.
				eregi(".*\.(.{2,4})$",$filename,$regs);
				if ($regs[1]!="") {
					$filename = ct('S_INDEX_PAPER_PAPER') . $this->get('ID') . "." . $regs[1];
				} else {
					$filename = ct('S_INDEX_PAPER_PAPER') . $this->get('ID') . ".bin";
				}
				echo "<a class='font10 fontbold' href=\"".ct_pageurl('downloadPaper', array('form_id'=>$this->get('ID'),"filename"=>$filename))."\">";
				echo "<img src=\"images/document.gif\" border=0 align=bottom>".$filename."</a></span>";
			}
			echo "</td></tr>\n";
		}

		echo "</table>\n";
	}

	// Show abstract of a contribution...
	function show_abstract($width, $align='center') {
		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_PAPER_FORM_ABSTRACT')."</span>\n";
		echo "</td></tr>\n";

		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"".ct_getbaseurl()."images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";

		echo "<tr class=\"evenrow\"><td colspan=2 align=left valign=top>\n";
		echo $this->_format_abstract($this->get_special('abstract'));
		echo "</td></tr>\n";
		echo "</table>";
	}


	// Show a nicely formmated abstract
	function _format_abstract($abstract) {
		$abstract_formatted = "<p class=\"normal8\"> ".$abstract." </p>";

		// remove double CRs
		$pattern = "/(\s*[\n|\r]+\s*)/s";
		$format = "\n";
		$abstract_formatted = preg_replace($pattern, $format, $abstract_formatted);

		// format paragraphs
		$pattern = "/\n([^<])/s";
		$format = "</p>\n<p class=\"normal8\">\$1";
		$abstract_formatted = preg_replace($pattern, $format, $abstract_formatted);

		return $abstract_formatted;
	}


	// Show an overview of all review results to this contribution
	function show_review_results($showAllReviews=true, $width, $align='center') {
		global $session;
		$user =& $session->get_user();
		ct_load_class('CTReview');
		$review = new CTReview();

		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";

		$reviews = $this->get_reviews();
		$rcount = count($reviews);

		# Show acceptance status
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_PAPER_DETAILED_REVIEWSRESULT')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"".ct_getbaseurl()."images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "<tr class=\"yellowbg\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<span class=\"bold14\">".$this->get_acceptstatus()."</span>";
		echo "</td></tr>\n";
		echo "</table>";

		ct_vspacer('10');

		$reviews = $this->get_reviews($showAllReviews);
		$rcount = count($reviews);
		if ($rcount > 0) {
			echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
			echo "<span class=\"boldlabel10\">".ct('S_PAPER_DETAILED_REVIEWSSECTION')."</span>\n";
			echo "</td></tr>\n";
			echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
			echo "<img src=\"".ct_getbaseurl()."images/spacer.gif\" width=1 height=1 border=0>";
			echo "</td></tr>\n";

			echo "<tr><td align=\"left\" valign=\"top\" colspan=2>\n";
			echo "<table width=\"100%\" cellspacing=1 cellpadding=2 border=0>\n";

			# heading for review table
			echo "<tr class=\"listheader\"><td colspan=2 width=\"".(100-($rcount*15))."%\">\n<span class=\"bold11\">".ct('S_PAPER_DETAILED_QUESTIONHEADER')."</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				$p = $reviews[$i]->get_reviewer();
				echo "<td width=\"15%\" align=\"center\"><a class=\"bold11\" ";
				echo "href=\"".ct_pageurl("reviewDetails",array("form_paperID"=>$reviews[$i]->data['paperID'], "form_personID"=>$reviews[$i]->data['personID']))."\"";
		        if ($session->loggedin() && $user->is_admin())
					echo "title=\"".ct_form_encode($p->get_fullname())."\"";
				echo ">";
				echo "<u>".ct('S_PAPER_DETAILED_REVIEWHEADER')."&nbsp;".($i+1)."</u></a><BR>";
		        if ($session->loggedin() && $user->is_admin())
		        	echo "<span class=\"normal8\">".$p->get_shortname()."</span>";
				echo "</td>\n";
			}
			echo "</tr>\n";

			# reviewers familiarity with subject
			echo "<tr class=\"oddrow\"><td colspan=2 width=\"".(100-($rcount*15))."%\">\n<span class=\"bold10\">".ct('S_REVIEW_FORM_FAMILIARITY_LABEL')."</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				echo "<td width=\"15%\" class=\"".$reviews[$i]->styles[$reviews[$i]->data['familiarity']]."\" align=center>";
				echo "<span class=\"bold12\">".$reviews[$i]->data['familiarity']."</span></td>\n";
			}
			echo "</tr>\n";

			# evaluation
			echo "<tr class=\"evenrow\"><td width=\"".(100-($rcount*15)-7)."%\">\n<span class=\"bold10\">".ct('S_REVIEW_SHORT_EVALUATION')."</span></td>\n";
			echo "<td width=\"7%\" align=center><span class=\"normal10\">10%</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				echo "<td width=\"15%\" class=\"".$reviews[$i]->styles[$reviews[$i]->data['evaluation']]."\" align=center>";
				echo "<span class=\"bold12\">".$reviews[$i]->data['evaluation']."</span></td>\n";
			}
			echo "</tr>\n";

			# significance
			echo "<tr class=\"oddrow\"><td width=\"".(100-($rcount*15)-7)."%\">\n<span class=\"bold10\">".ct('S_REVIEW_SHORT_SIGNIFICANCE')."</span></td>\n";
			echo "<td width=\"7%\" align=center><span class=\"normal10\">10%</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				echo "<td width=\"15%\" class=\"".$reviews[$i]->styles[$reviews[$i]->data['significance']]."\" align=center>";
				echo "<span class=\"bold12\">".$reviews[$i]->data['significance']."</span></td>\n";
			}
			echo "</tr>\n";

			# originality
			echo "<tr class=\"evenrow\"><td width=\"".(100-($rcount*15)-7)."%\">\n<span class=\"bold10\">".ct('S_REVIEW_SHORT_ORIGINALITY')."</span></td>\n";
			echo "<td width=\"7%\" align=center><span class=\"normal10\">10%</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				echo "<td width=\"15%\" class=\"".$reviews[$i]->styles[$reviews[$i]->data['originality']]."\" align=center>";
				echo "<span class=\"bold12\">".$reviews[$i]->data['originality']."</span></td>\n";
			}
			echo "</tr>\n";

			# relevance
			echo "<tr class=\"oddrow\"><td width=\"".(100-($rcount*15)-7)."%\">\n<span class=\"bold10\">".ct('S_REVIEW_SHORT_RELEVANCE')."</span></td>\n";
			echo "<td width=\"7%\" align=center><span class=\"normal10\">10%</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				echo "<td width=\"15%\" class=\"".$reviews[$i]->styles[$reviews[$i]->data['relevance']]."\" align=center>";
				echo "<span class=\"bold12\">".$reviews[$i]->data['relevance']."</span></td>\n";
			}
			echo "</tr>\n";

			# readability
			echo "<tr class=\"evenrow\"><td width=\"".(100-($rcount*15)-7)."%\">\n<span class=\"bold10\">".ct('S_REVIEW_SHORT_READABILITY')."</span></td>\n";
			echo "<td width=\"7%\" align=center><span class=\"normal10\">10%</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				echo "<td width=\"15%\" class=\"".$reviews[$i]->styles[$reviews[$i]->data['readability']]."\" align=center>";
				echo "<span class=\"bold12\">".$reviews[$i]->data['readability']."</span></td>\n";
			}
			echo "</tr>\n";

			# overall
			echo "<tr class=\"oddrow\"><td width=\"".(100-($rcount*15)-7)."%\">\n<span class=\"bold10\">".ct('S_REVIEW_SHORT_OVERALL')."</span></td>\n";
			echo "<td width=\"7%\" align=center><span class=\"normal10\">50%</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				echo "<td width=\"15%\" class=\"".$reviews[$i]->styles[$reviews[$i]->data['overall']]."\" align=center>";
				echo "<span class=\"bold12\">".$reviews[$i]->data['overall']."</span></td>\n";
			}
			echo "</tr>\n";

			# total
			echo "<tr class=\"evenrow\"><td width=\"".(100-($rcount*15))."%\" colspan=2>\n<span class=\"bold12\">".ct('S_REVIEW_SHORT_TOTAL',array('100'))."</span></td>\n";
			for ($i=0; $i < $rcount; $i++) {
				echo "<td width=\"15%\" align=center class=\"".$reviews[$i]->get_totalstyle()."\">";
				echo "<span class=\"bold14\">".$reviews[$i]->get_total()."</span></td>\n";
			}
			echo "</tr>\n";
			echo "</table>\n";



/*			while (list(,$rev) = each($reviews)) {
				$p = $rev->get_reviewer();
				$p->show_shortinfo($width, "oddrow", "center");
				if ($rev->get_total() != 0) {
					echo "<table width=\"100%\">\n";
					$rev->show_row();
					echo "</table>\n";
					# echo "<div align=right class=\"bold10\">[ <a href=\"";
					# echo ct_pageurl('adminReviewDetail',array('form_paperID'=>$this->get('ID'),'form_personID'=>$p->get('ID')));
					# echo "\">".ct('S_PAPER_REVIEWDETAILS')."</a> ']</div>\n";
				}
				echo "<hr size=1 noshade>\n";
			}
*/

			echo "</td></tr>\n";
			echo "</table>";
		}
	}


	// Show options to admin and pc chairs...
	function show_admin_options($width, $align='center') {
		global $session;
		$user =& $session->get_user();

		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td colspan=2><img src=\"images/spacer.gif\" width=1 height=1 border=0></td></tr>\n";

		if ($session->loggedin() && $user->is_admin()) {
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
			echo "<span class=\"boldlabel10\">".ct('S_PAPER_DETAILED_COMMANDSSECTION')."</span>\n";
			echo "</td></tr>\n";
			echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
			echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
			echo "</td></tr>\n";
			echo "<tr><td class=\"mediumbg\" colspan=2><span class=\"bold10\">";
			$params = array("form_id" => $this->pdata['ID']);
			echo "<a class=\"cmda\" href=\"".ct_pageurl('editPaper', $params)."\">".ct('S_INDEX_PAPER_EDIT')."</a> &middot; ";
			echo "<a class=\"cmda\" href=\"".ct_pageurl('uploadPaper', $params)."\">".ct('S_INDEX_PAPER_UPLOAD')."</a> &middot; ";
			echo "<a class=\"cmda\" href=\"".ct_pageurl('withdrawPaper', $params)."\">".ct('S_INDEX_PAPER_WITHDRAW')."</a> &middot; ";
			echo "<a class=\"cmda\" href=\"".ct_pageurl('adminReviewAssign', $params)."\">".ct('S_REVIEW_ASSIGN')."</a>";
			echo "</td></tr>\n";
		}
		echo "</table>\n";
	}


	# returns a reference to a CTPerson-object that represents the paper
	# "author" (the user who submitted it to the conference)
	function &get_author() {
		global $db;

		$author = new CTPerson;
		$author->load_by_id($this->get('personID'));
		return $author;
	}

	# returns an array with all reviews assigned to the current paper
	function get_reviews($showAllReviews=true) {
		global $db;

		if ($showAllReviews)
			$q = "select paperID, personID from reviews where paperID='".$this->pdata['ID']."' ORDER BY personID";
		else
			$q = "select paperID, personID from reviews where paperID='".$this->pdata['ID']."' AND creationdate>0 ORDER BY personID";
		$r = $db->query($q);
		$result = array();
		if ($r && ($db->num_rows($r) > 0)) {
			for ($i = 0; $i < $db->num_rows($r); $i++) {
				$row = $db->fetch($r);
				$review = new CTReview();
				$review->load($row['paperID'],$row['personID']);
				$results[] = $review;
			}
		}
		return $results;
	}

	/**
	* returns an array that contains all IDs of topics assigned to this paper
	*/
	function get_topicIDs() {
		global $db;

		if (is_array($this->pdata['topics'])) return $this->pdata['topics'];

		$topics = array();
		if ($this->get_id()) {
			$rows = $db->get_links('topics2papers', $this->get('ID'), '');
			for ($i = 0; $i < sizeof($rows); $i++) {
				array_push($topics, $rows[$i]['topicID']);
			}
		} elseif (is_array($this->get('topics'))) {
			$topics = $this->get('topics');
		}
		return $topics;
	}

	# returns an array that contains all titles of topics assigned to this paper
	#
    function get_topics() {
	global $db;

		$topics = array();
		if ($this->get_id()) {
			$r = $db->query("select	topics.* from topics, topics2papers where topics.id=topics2papers.topicID and topics2papers.paperID='".$this->get('ID')."'");
			for	($i	= 0; $i	< $db->num_rows($r); $i++) {
				$row = $db->fetch($r);
				array_push($topics,	$row['title']);
			}
		} elseif (is_array($this->get('topics'))) {
			$t = $this->get('topics');
			while (list(,$v) = each($t)) {
				$r = $db->select('topics','title','ID="'.$v.'"');
				if ($r) {
					$row = $db->fetch($r);
					array_push($topics,	$row['title']);
				}
			}
		}
		return $topics;
   	}

    #
	function get_emails() {
		global $db;

		if (is_array($this->pdata['emails'])) return $this->pdata['emails'];

		$emails = array();
		$r = $db->query("select email from emails2papers e2p, papers p where e2p.paperID=p.ID and e2p.paperID='".$this->get('ID')."'");
		if ($r) {
			for ($i = 0; $i < $db->num_rows($r); $i++) {
				$row = $db->fetch($r);
				array_push($emails, $row['email']);
			}
		}
		return $emails;
	}

	# returns an array that contains all available topics. Each element is an array with two
	# elements, first is topic ID, second is topic title - also available in CTPerson
	function _list_topics() {
		global $db;

		$topics = array();
		$r = $db->query('select * from topics order by title asc');
		if ($r && ($db->num_rows($r) > 0)) {
			for ($i=0; $i < $db->num_rows($r); $i++) {
				$t = $db->fetch($r);
				$topics[] = array($t['ID'], $t['title']);
			}
		}
		return $topics;
	}

	# returns an array that contains all available contribution types.
	# Each element is an array with two elements, first is ID, second is title
	function _list_types($filter=0) {
		global $db;

		$types = array();
		if ($filter)
			$query = "select * from contributiontypes WHERE active='true' order by ID asc";
		else
			$query = "select * from contributiontypes order by ID asc";
		$r = $db->query($query);
		if ($r && ($db->num_rows($r) > 0)) {
			for ($i=0; $i < $db->num_rows($r); $i++) {
				$t = $db->fetch($r);
				$types[] = array($t['ID'], $t['title']);
			}
		}
		return $types;
	}

	# returns the name of the contribution type
	#
	function &get_contributiontype($typeID="") {
		global $db;
	    if ($typeID=="") $typeID=$this->pdata['contributiontypeID'];
		if ($typeID=="") return "";
		$r = $db->query("select * from contributiontypes where id='".$typeID."'");
		if ($db->num_rows($r) == 1) {
			$row = $db->fetch($r);
			return $row['title'];
		} else {
			return ""; // Contribution type not defined... (or 0)
		}
	}

	/**
	 * returns the name of the contribution type, already encoded for HTML output.
	 */
	function _get_contributiontype_title($typeID="") {
		if ($typeID=="") $typeID=$this->pdata['contributiontypeID'];
		if ($typeID=="") return "";
		ct_load_lib('papers.lib');
		$t = ct_get_contributiontypes($typeID);
		$type = $t['title'];
		if ($type!="") return ct_form_encode($type);
		else return "---"; // Contribution type not defined... (or 0)
	}


	# returns if contribution type is currently set active
	#
	function _is_contributiontype_active($typeID="") {
		global $db;
	    if ($typeID=="") $typeID=$this->pdata['contributiontypeID'];
		if ($typeID=="") return 0;
		$r = $db->query("select * from contributiontypes where id='".$typeID."'");
		if ($db->num_rows($r) == 1) {
			$row = $db->fetch($r);
			return $row['active']=="true" ? 1 : 0;
		} else {
			return 1; // Contribution type not defined... (or 0)
		}
	}


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

	# process a request that results from a form generated by CTPaper-object
	function process_infoform() {
		global $http, $session;

		$this->errors = array();
		$okay = true;

		ct_http_trim(array('title','author'));
		$this->set('ID', $http['form_id']);
		$okay &= $this->_require('contributiontypeID', 'form_contributiontypeID');
		$okay &= $this->_require('title', 'form_title');
		$okay &= $this->_require('author', 'form_author');
		$okay &= $this->_require('abstract', 'form_abstract');
		$okay &= $this->_require('keywords', 'form_keywords');

		if (isset($http['form_topics']) && is_array($http['form_topics']) && (sizeof($http['form_topics']) > 0)) {
			$this->pdata['topics'] = $http['form_topics'];
		} else {
		    if (sizeof($this->_list_topics())) {
			    $okay = false;
				$this->errors[] = 'form_topics[]';
			}
		}

		if (isset($http['form_emails']) && ($http['form_emails'] != "")) {
			$this->pdata['emails'] = ct_csv_explode($http['form_emails']);
		} else {
		    $this->pdata['emails'] = array();
		}

		$this->set('externalremark', $http['form_externalremark']);
		# Reset withdrawn status...
		$this->set('withdrawn', 0);

		if ($okay) {
			return false;
		} else {
			return $this->errors;
		}
	}

	function show_infoform($action, $errors=array()) {
		global $session;
		$user =& $session->get_user();
		$form = new CTForm($action, 'post', $errors);
		$form->width='99%';
		$form->align='center';
		$form->warningmessage=true;

		$form->add_hidden(array(array("form_id",$this->get('ID'))));

		$form->add_separator(ct('S_PAPER_FORM_INFOSECTION'));

		if ($this->get_id()>0) {
			$form->add_label(ct('S_PAPER_FORM_ID'),$this->get_id());
			$form->add_spacer();
		}

		if ($this->get('personID')>0) {
			$author = $this->get_author();
			$form->add_label(ct('S_INDEX_PAPER_SUBMITTEDBY'),ct_form_encode($author->get_reversename()));
			$form->add_label('',$author->get_special('organisation').', '.$author->get_special('country'));
			$form->add_hidden(array(array("form_personID",$this->get('personID'))));
			$form->add_spacer();
		}

		if ($session->loggedin() && $user->is_admin()) { // show all types to admin
			$form->add_select(ct('S_PAPER_FORM_CONTRIBUTIONTYPE'), 'form_contributiontypeID', 1, $this->_list_types(0), array($this->get('contributiontypeID')), false);
		} else {
			if ($this->_list_types(1)==array()) {  // No Types defined
				$form->add_hidden(array(array("form_contributiontypeID",0)));
			} else {
				$form->add_select(ct('S_PAPER_FORM_CONTRIBUTIONTYPE'), 'form_contributiontypeID', 1, $this->_list_types(1), array($this->get('contributiontypeID')), false);
			}
		}

		// Header for section with author information
		$form->add_separator(ct('S_PAPER_SECTION_AUTHORS'));

		$form->add_textarea("* ".ct('S_PAPER_FORM_AUTHOR'), 'form_author', $this->get('author'), 70, 3, '', ct('S_PAPER_FORM_AUTHOR_HINT'));
		$form->add_text(ct('S_PAPER_FORM_EMAILS'), 'form_emails', ct_csv_implode($this->get_emails()), 70, 255, false, ct('S_PAPER_FORM_EMAILHINT'));


		// Header for section with title and further information
		$form->add_separator(ct('S_PAPER_SECTION_DETAILS'));

		$form->add_text("* ".ct('S_PAPER_FORM_TITLE'), 'form_title', $this->get('title'), 70, 255);
		$form->add_textarea("* ".ct('S_PAPER_FORM_ABSTRACT'), 'form_abstract', $this->get('abstract'), 70,10);

		$form->add_spacer();
		$form->add_text("* ".ct('S_PAPER_FORM_KEYWORDS'), 'form_keywords', $this->get('keywords'), 60, 255, false, ct('S_PAPER_FORM_KEYWORDSHINT'));

		$topicboxes = array();	// Create array for topics...
		$topics=$this->_list_topics();
		$selectedtopics=$this->get_topicIDs();
		foreach ($topics as $t) {
			$topicboxes[]=array('form_topics[]',$t[0],$t[1],in_array($t[0],$selectedtopics),false);
		}
		if (count($topics)>0) {
			$form->add_check("* ".ct('S_PAPER_FORM_TOPICS'), array_merge(array(array('','',ct('S_PAPER_FORM_TOPICSNEWHINT'))),$topicboxes));
			#$form->add_select("* ".ct('S_PAPER_FORM_TOPICS'), 'form_topics[]', 6, $this->_list_topics(), $this->get_topicIDs(), true, "<br>".ct('S_PAPER_FORM_TOPICSHINT'));
		} else {
			#$form->add_label("* ".ct('S_PAPER_FORM_TOPICS'), ct('S_PC2TOPICS_NOTOPICS'));
		}

		$form->add_spacer();
		$form->add_textarea(ct('S_PAPER_FORM_EXTERNALREMARK'), 'form_externalremark', $this->get('externalremark'), 70, 3);
		$form->add_submit('cmd_paper_saveinfo', ct('S_PAPER_INFOFORM_SUBMIT'));
		# $form->add_reset(ct('S_BUTTON_RESET'));
		$form->show();
	}


	function show_uploadform($action, $errors=array()) {
		ct_load_lib("papers.lib");
		$type = ct_get_contributiontypes($this->get('contributiontypeID'));
		if (!is_array($type)) $type = array();

		$form = new CTForm($action, 'post', array());
		$form->waitmessage=true;// show waitmessage!
		$form->width='99%';
		$form->align='center';
		#$form->warningmessage_always=true;

		$form->add_hidden(array(array("form_id",$this->get('ID'))));
		$form->add_hidden(array(array("form_personID",$this->get('personID'))));

		$form->add_separator(ct('S_PAPER_FORM_INFOSECTION'));
		$form->add_label(ct('S_PAPER_FORM_CONTRIBUTIONTYPE'),$type['title']);
		$form->add_label(ct('S_PAPER_FORM_TITLE'), $this->get_special('title'));
		$form->add_label(ct('S_PAPER_FORM_AUTHOR'), $this->get_special('author'));
		$form->add_separator(ct('S_PAPER_FORM_UPLOADSECTION'));
		if ($type['info']!="")
			$form->add_label(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_INFO'),$type['info']);
		#$form->add_label('',ct('S_PAPER_UPLOAD_DETAILS'));
		$form->add_spacer();

		$form->add_label(ct('S_PAPER_UPLOAD_INFO'),ctconf_get('uploadFileInfo'));

		$form->add_label(ct('S_PAPER_UPLOAD_FILETYPES'),ct('S_PAPER_UPLOAD_FILETYPES_INFO',array(ctconf_get('uploadFileTypes'))));
		$form->add_label('',ct('S_PAPER_UPLOAD_MAX'));
		$form->add_upload(ct('S_PAPER_FORM_FILE'), 'form_filename', '60', $ctconf['paperupload/maxsize']);
		if ($this->get('filename') != "") {
			$form->add_label(ct('S_PAPER_FORM_LASTUPLOAD'),
			"<span class=\"bold8\">".$this->get_special_filename('originalname')."</span><br>".$this->get('lastupload'));
		}
		$form->add_submit('cmd_paper_upload', ct('S_PAPER_UPLOADFORM_SUBMIT'));
		// $form->add_reset(ct('S_BUTTON_RESET'));
		$form->show();
	}
	
	function show_uploadpresentationform($action, $errors=array()) {
		ct_load_lib("papers.lib");
		$type = ct_get_contributiontypes($this->get('contributiontypeID'));
		if (!is_array($type)) $type = array();

		$form = new CTForm($action, 'post', array());
		$form->waitmessage=true;// show waitmessage!
		$form->width='99%';
		$form->align='center';
		#$form->warningmessage_always=true;

		$form->add_hidden(array(array("form_id",$this->get('ID'))));
		$form->add_hidden(array(array("form_personID",$this->get('personID'))));

		$form->add_separator(ct('S_PAPER_FORM_INFOSECTION'));
		//$form->add_label(ct('S_PAPER_FORM_CONTRIBUTIONTYPE'),$type['title']);
		$form->add_label(ct('S_PAPER_FORM_TITLE'), $this->get_special('title'));
		$form->add_label(ct('S_PAPER_FORM_AUTHOR'), $this->get_special('author'));
		$form->add_separator(ct('S_PAPER_FORM_UPLOADSECTION'));
		if ($type['info']!="")
			$form->add_label(ct('S_ADMIN_TOOL_CONTRIBUTIONTYPES_FORM_INFO'),$type['info']);
		#$form->add_label('',ct('S_PAPER_UPLOAD_DETAILS'));
		$form->add_spacer();

		//$form->add_label(ct('S_PAPER_UPLOAD_INFO'),ctconf_get('uploadFileInfo'));

		//$form->add_label(ct('S_PAPER_UPLOAD_FILETYPES'),ct('S_PAPER_UPLOAD_FILETYPES_INFO',array(ctconf_get('uploadFileTypes'))));
		
		$form->add_label('',ct('S_PAPER_UPLOAD_MAX'));
		$form->add_upload(ct('S_PAPER_FORM_FILE'), 'form_filename', '60', $ctconf['paperupload/maxsize']);
		//if ($this->get('filename') != "") {
		//	$form->add_label(ct('S_PAPER_FORM_LASTUPLOAD'),
		//	"<span class=\"bold8\">".$this->get_special_filename('originalname')."</span><br>".$this->get('lastupload'));
		//}
		$form->add_submit('cmd_paper_upload', ct('S_PAPER_UPLOADFORM_SUBMIT'));
		// $form->add_reset(ct('S_BUTTON_RESET'));
		$form->show();
	}

	# Process paper upload and save file or show error messages
	# Prefix is used to separate submissions from final uploads, posters etc.
	function process_uploadform($prefix="") {
		global $ctconf, $http, $session, $_FILES;

		if( !ini_get('safe_mode') ){
			// Does _not_ work in SAFE MODE
			$uploaddir = ct_get_path('uploads').$this->get('personID')."/";
			//check if the directory is writable.
			if (!is_dir($uploaddir)) {
				//	mkdir($uploaddir, 0750); // User access by ftp may be "difficult".
				if (mkdir($uploaddir, 0777)) {
					chmod($uploaddir, 0777);  // Set permissions (The above sometimes fails!)
				} else {
					$uploaddir = ct_get_path('uploads');
				}
			}
		} else {
			$uploaddir = ct_get_path('uploads');
		}
	    if (!is_writeable($uploaddir)){
	   		die ("<H3>ConfTool SETUP ERROR</H3> The directory <b><code>".$uploaddir."</code></b> is NOT writable.");
	    }

		$file = $_FILES['form_filename'];
		$f_name		=str_replace(" ","_",$file['name']);
		$f_tmp_name	=$file['tmp_name'];
	    $f_size		=$file['size'];
	    $fileContents = "";
	    // Check if a file name was entered/selected...
	    if ($f_name != 'none' && $f_name != '' ) {
			// Check if temporary upload file exists...
			if (is_uploaded_file($f_tmp_name)) {
		    	// Test if file type is accepted.
				if ($this->isLegalFileType($f_name)) {
					// Check if file name is maybe too long...
					// Most OS have a limit of 255 characters, but sometimes > 127 already creates problems
					if (strlen(rawurlencode($f_name))>99) {
						eregi("(.*)\.(.{2,4})$",$f_name,$regs);
						if ($regs[1]!="" && $regs[2]!="") {
							$f_name = ct_substr($regs[1],0,70).".".$regs[2];
						} else {
							$f_name = ct_substr($f_name,0,70);  // 255/3
						}
					}
					$tmp_name = ct_get_path('uploads').rawurlencode($f_name);
					// Now move the uploaded file to its designated place
					if (move_uploaded_file($f_tmp_name, $tmp_name)) {
						// Set access rights...
						chmod($tmp_name, 0666);
						$upload_file = fopen($tmp_name,"r");
						if (filesize($tmp_name) >0) $fileContents = fread($upload_file, filesize($tmp_name));
						if (strlen($fileContents) != 0) {
							if ($this->isLegalFileSize($fileContents)) {
								fclose($upload_file);
								// create unique filename to keep old versions...
								$count = -1;
								if ($handle = opendir($uploaddir)) {
							    	while (false !== readdir($handle)) { $count++; }
								}
								if ($count<1)
									$count = time(); // if files could not be counted, use timestamp.

								$savename = rawurlencode($f_name);
								if ($prefix!="") {
									$savename = $prefix."-".$savename;
								}
								$savename = $uploaddir.$count."-".$savename;
								// Move file and set permissions.
								if (rename($tmp_name, $savename)) {
									chmod($savename, 0666); // Allow access also for conftool user (not only for web-server...)

									$savename = str_replace(ct_get_path('uploads'),'',$savename); // remove leading savepath
									$this->set('filename', 	   addslashes($savename));
									$this->set('originalname', addslashes($f_name));
									$this->set('lastupload',   date('Y-m-d H:i:s'));
									return true; // Big success...
								} else {
									ct_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_COPYFILE'));
									@unlink($tmp_name);
								}
							} else {
								ct_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_FILESIZE')." ".strlen($fileContents)." Bytes.");
								fclose($upload_file);
								@unlink($tmp_name);
							}
						} else {
							ct_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_NOT_FOUND'));
							chmod($tmp_name, 0666);
							$upload_file = fopen($tmp_name,"r");
							fclose($upload_file);
							@unlink($tmp_name);
						}
					} else {
						ct_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_NOT_FOUND'));
					}
				} else {
					ct_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_FAILED'));
				}
			} else {
				ct_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_NO_FILE'));
			}
		} else {
			ct_errorbox(ct('S_ERROR_PAPER_UPLOAD'), ct('S_ERROR_PAPER_UPLOAD_FILETYPE')." ".ctconf_get('uploadFileTypes'));
		}
		return false;
	}


    // Check if the file has a legal filename!
    function isLegalFileType($f_name) {
        $type = trim(strtolower($f_name));
        $pos = strrpos($type, ".");
        if ($pos === false)
            return false;
        else {
			$legal_types=ct_csv_explode(ctconf_get('uploadFileTypes'));
            $type = ct_substr($type, $pos+1);
            foreach ($legal_types as $t) {
                if ($t == $type) return true;
            }
        }
        return false;
    }

    // Check if the file has a legal filename!
    // Min 1KByte, max 10MBytes! That should be enough...
    function isLegalFileSize($i) {
        global $ctconf;
        if (strlen($i) > 512 && strlen($i) < $ctconf['paperupload/maxsize']) return true;
        return false;
    }



}

?>