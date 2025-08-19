<?php
#
# CLASS:		CTPerson
#
if (!defined('CONFTOOL')) die('Hacking attempt!');
class CTPerson {

	# holds corresponding row from persons-database
	var $pdata = array();

	# Try to login user with password
	function login($uname, $passwd) {
		global $db;

		// Just to be sure. This is not really necessary as all http parameters do have slashes!
		$uname = ct_mysql_escape_string(stripslashes($uname));
		$passwd = ct_mysql_escape_string(stripslashes($passwd));
		if (preg_match("/^.*@.*\.[a-z]+/i",$uname))	{
			// EMail-Address was entered
			$r = $db->query("select	* from persons where (username='$uname' or email='$uname') and password='$passwd' and deleted=0");
		} else {
			// no email-Address
			$r = $db->query("select	* from persons where username='$uname' and password='$passwd' and deleted=0");
		}
		if ($db->num_rows($r) == 1) {
			// Success!
			$this->pdata = $db->fetch($r);
			$this->pdata['logindate'] =	ct_timestamp_2_datetime(ct_time());  // Save last login...
			$this->pdata['lastactiondate'] = ct_timestamp_2_datetime(ct_time());  // Save last action
			$this->pdata['logoutdate'] = '0000-00-00 00:00:00';
			// Do this double check, to make sure the user will have the right options.
			$this->check_author();
			$this->check_participant();
			$this->persist();
			if (ctconf_get('firstlogin',0)==0) ct_firstlogin($this);
			return true;
		} else {
			return false;
		}
	}

	# Logout user.
	function logout() {
		if ($this->get_id()>0) {
			$this->reload();
			$this->pdata['logoutdate'] = ct_timestamp_2_datetime(ct_time());  // Save logout...
			$this->persist();
			return true;
		} else {
			return false;
		}
	}


	### DATABASE FUNCTIONS ##############################################

	# load person data from database by id
	function load_by_id($id) {
		global $db;

		$r = $db->query("select * from persons where id='$id'");
		if ($db->num_rows($r) == 1) {
			$this->pdata = $db->fetch($r);
			return true;
		} else {
			return false;
		}
	}

	# load person data from database by email
	function load_by_email($email) {
		global $db;
		$r = $db->query("select	* from persons where email='$email'");
		if ($db->num_rows($r) == 1)	{
			$this->pdata = $db->fetch($r);
			return true;
		} else {
			return false;
		}
	}

	# reload person data from database
	function reload() {
		return $this->load_by_id($this->pdata['ID']);
	}

	# update data in database from object
	function persist() {
		global $db;

		if ($this->pdata['ID'] == "") {
			if (!$this->pdata['username']=="") { // the username has to be set - check to avoid errors...
				$this->pdata['ID'] = "0";
				$this->pdata['creationdate'] = date('Y-m-d H-i-s');
				$this->pdata['deleted'] = "0";
				$db->insert_into('persons', $this->pdata);
				$r = $db->query("select	* from persons where username='".$this->pdata['username']."'");
				if ($db->num_rows($r) == 1)	{
					// Get ID of new user
					$row = $db->fetch($r);
					$this->pdata['ID'] = $row['ID'];
					// Check status of new user
					$this->check_author();
					$db->replace_into('persons', $this->pdata);
					return true;
				} else {
					return false;
				}
			}
		} else {
			$db->replace_into('persons', $this->pdata);
		}
	}

	# delete Person
	function delete() {
		global $db;
		if ($this->get_id()!=1 && !$this->is_author() && !$this->is_participant() && $this->get_review_count($this->get_id())==0) {
			$this->pdata['deleted'] = '1';
			$this->persist();
			return true;
		} else
			return false;
	}

	# undelete Person
	function undelete() {
		global $db;
		$this->pdata['deleted'] = '0';
		$this->persist();
		return true;
	}

	### ACCESSOR FUNCTIONS ###############################################

	# get a record field`s value, fieldname is key
	function get($key) {
		return isset($this->pdata[$key]) ? $this->pdata[$key] : '';
	}

	# get a record field`s value, with html special chars converted
	function get_special($key) {
		return ct_form_encode($this->get($key));
	}

	# set a record field`s value
	function set($key, $value) {
		$this->pdata[$key] = $value;
	}

	function _check_status($status)	{
		if (isset($this->pdata['status'])) {
			$starr = explode(",", $this->pdata['status']);
			if (in_array($status, $starr))	{
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	# quick checks for status
	function is_admin() {
		return $this->_check_status("admin");
	}

	function is_pc() {
		return $this->_check_status("pc");
	}

	function is_chair() {
		return $this->_check_status("chair");
	}

	function is_author() {
		return $this->_check_status("author");
	}

	function is_participant() {
		return $this->_check_status("participant");
	}

	function is_frontdesk() {
		return $this->_check_status("frontdesk");
	}

	function is_assistant() {
		return $this->_check_status("assistant");
	}

	/**
	 * Add new Status / Role to user
	 *
	 * @param string $status name of status: admin, chair, conferenceChair, frontdesk, pc, reviewer, author, coauthor, speaker, participant
	 */
	function add_status($status) {
		$starr = ct_csv_explode($this->pdata['status']);
		if (!in_array($status, $starr))	{
			array_push($starr, $status);
		}
		$this->pdata['status'] = implode(",", $starr);
	}

	/**
	 * Revoke a status / role from a user
	 *
	 * @param string $status name of the status: admin, chair, conferenceChair, frontdesk, pc, reviewer, author, coauthor, speaker, participant
	 */
	function remove_status($status)	{
		$starr = explode(",", $this->pdata['status']);
		if (in_array($status, $starr)) {
			reset($starr);
			while (list($k,$v) = each($starr)) {
				if ($v == $status) {
					unset($starr[$k]);
					break;
				}
			}
		}
		$this->pdata['status'] = implode(",", $starr);
	}

	# Check if user is (still) an author.
	function check_author() {
		$papercount = sizeof($this->get_papers());
		if ($this->is_author() && ( $papercount == 0)) {
			$this->remove_status('author');
			return true;
		}
		if (!$this->is_author() && ($papercount > 0)) {
			$this->add_status('author');
			return true;
		}
		return false;
	}

	# Check if user is really (still) an participant
	function check_participant()	{
		global $db;
		$query='select count(p.personID) as count from participants as p where p.deleted=0 and p.personID='.$this->get_id();
		$r = $db->query($query);
		if ($r and $db->num_rows($r)==1) {
			$p = $db->fetch($r);
			if ($p['count']>0) {
				$this->add_status('participant');
			} else {
				$this->remove_status('participant');
			}
		}
		return true;
	}

	/**
	 * how many	reviews	assigned to	this pc	member?
	 */
	function count_assigned_reviews() {
		global $db;

		$reviews = 0;
		if ($this->get_id()>0) {
			$r = $db->query("select	count(*) as	count from reviews where personID=".$this->pdata['ID']);
			if ($r && ($db->num_rows($r) > 0)) {
				$t = $db->fetch($r);
				$reviews = $t['count'];
			}
		}
		return $reviews;
	}


	# Get_ID
	function get_id() {
		return $this->pdata['ID'];
	}

	# Dr. Peter Smith
	function get_fullname() {
		// return $this->pdata['name'].", ".$this->pdata['title']." ".$this->pdata['firstname'];
		return $this->pdata['title']." ".$this->pdata['firstname']." ".$this->pdata['name'];
	}

	# Peter Smith
	function get_name() {
		return $this->pdata['firstname']." ".$this->pdata['name'];
	}

	# P. Smith
	function get_shortname() {
		return ct_substr($this->pdata['firstname'],0,1).". ".$this->pdata['name'];
	}

	# Smith, Dr. Peter
	function get_reversename() {
		return $this->pdata['name'].", ".$this->pdata['title']." ".$this->pdata['firstname'];
	}

	/**
	 * Salutation
	 */
	function get_salutation($gender=0) {
		if ($gender==0) $gender=$this->get('gender');
		switch ($gender) {
			#case 1: return ct('S_USER_GENDER_MALE');
			#case 2: return ct('S_USER_GENDER_FEMALE');
			case 3: return ct('S_USER_GENDER_DR');
			case 4: return ct('S_USER_GENDER_PROF');
		}
		return '';
	}

	function get_papers() {
		global $db;

		$papers = array();
		$r = $db->query("select * from papers where withdrawn=0 and personID='".$this->pdata['ID']."'");
		if ($r) {
			for ($i=0; $i < $db->num_rows($r); $i++) {
				$p = new CTPaper;
				$p->pdata = $db->fetch($r);
				$papers[] = $p;
			}
		}
		return $papers;
	}

	function is_reviewer_for($paper) {
		global $db;

		$r = $db->query("select * from reviews where personID='".$this->pdata['ID']."' and paperID='".$paper."'");
		if ($r) {
			if ( $db->num_rows($r) > 0 ) { return true; } else { return false; }
		} else {
			return false;
		}
	}

	function get_reviews() {
		global $db;

		$reviews = array();
		$r = $db->query("select * from reviews where personID='".$this->pdata['ID']."' order by paperID");
		if ($r) {
			for ($i = 0; $i < $db->num_rows($r); $i++) {
				$rev = new CTReview();
				$rev->data = $db->fetch($r);
				$reviews[] = $rev;
			}
		}
		return $reviews;
	}


    # get number of reviews to a person/paper
	#
    function get_review_count($personID="", $paperID="", $entered=1) {
        global $db;

        $query="select count(*) as count from reviews where 1 ";
        if ($entered==1) {
            $query.="and creationdate>0 ";
		}
        if ($personID!="") {
            $query.="and personID=$personID ";
		}
        if ($paperID!="") {
            $query.="and paperID=$paperID";
        }

        $r = $db->query($query);
		if ($r && ($db->num_rows($r) > 0)) {
			$t = $db->fetch($r);
			return $t['count'];
        } else {
            return 0;
        }

    }


	# returns an array that contains all available topics. Each element is an array with two
	# elements, first is topic ID, second is topic title - also available in CTPaper
	function list_topics() {
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

	# returns an array that contains all IDs of topics assigned to person (as PC-member)
    #
	function get_topicIDs() {
		global $db;

		$topics = array();
		$rows = $db->get_links('pc2topics', $this->get('ID'), '');
		for ($i = 0; $i < sizeof($rows); $i++) {
			array_push($topics, $rows[$i]['topicID']);
		}
		return $topics;
	}

	# returns an array that contains all titles of topics assigned to this paper
	#
    function get_topics() {
		global $db;

		$topics = array();
		$r = $db->query("select title from topics, pc2topics where topics.id=pc2topics.topicID and pc2topics.personID='".$this->get('ID')."'");
		for ($i = 0; $i < $db->num_rows($r); $i++) {
			$row = $db->fetch($r);
			array_push($topics, $row['title']);
		}
		return $topics;
	}

	# Save topics assigned to this person. The topics are contained as array in the parameter if this function.
	#
	function save_topics($topics=array()) {
		global $db;

		$db->unlink('pc2topics', $this->pdata['ID'], "");
		if (is_array($topics)) {
			while (list($k,$v) = each($topics)) {
				$db->link('pc2topics', $this->pdata['ID'], $v);
			}
		}
	}


	### FORM PROCESSING AND GENERATION #######################################

	function _require($field, $name, $minchars=0) {
		global $http;

		if (isset($http[$name])	&& ($http[$name] !=	"")) {
			$this->set($field, $http[$name]);
			// Test if number of different characters is met. (count_chars(x,3) returns a string with all found characters.)
			if ($minchars>0 && ct_strlen(count_chars($http[$name],3))<$minchars) {
				$this->errors[]	= $name;
				return false;
			}
			return true;
		} else {
			if (isset($http[$name])) {
				$this->set($field, '');
			}
			$this->errors[]	= $name;
			return false;
		}
	}

	# process a request that results from a form generated by CTPerson-object
	function process_form() {
		global $http, $session;
		ct_load_lib('password.lib');
		ct_load_lib('address.lib');

		$this->errors = array();

		ct_http_trim(array('username','firstname','lastname','email','password','pwd','url','organisation','organisation2'));
		$this->set('ID', $http['form_id']);
		$this->_require('organisation', 'form_organisation', 2);
		$this->set('organisation2', $http['form_organisation2']);
		$this->_require('gender', 'form_gender');
		$this->set('title', $http['form_title']);
		$this->_require('firstname', 'form_firstname');
		$this->_require('name', 'form_name', 2);
		$this->_require('addr1', 'form_addr1');
		$this->set('addr2', $http['form_addr2']);
		$this->set('zip', $http['form_zip']);
		$this->_require('city', 'form_city');
		$this->set('state', $http['form_state']);
		$this->set('country', $http['form_country']);
		if (isset($http['form_country']) &&	($http['form_country'] != "") && ($http['form_country']	!= "-")) {
			// Check state:	Some countries need	a state!
			if (ctconf_get('web/liststates',true) && in_array($this->get('country'),array('US','CA','AU'))) {
				 if	(isset($http['form_state'])	&& ($http['form_state']	!= "") && ($http['form_state'] != "-"))	{
					$this->set('state',	$http['form_state']);
				} else {
					$this->errors[]	= 'form_state';
				}
			} else {
				$this->set('state',	'');
			}
			// Check postal	code: Some countries don't need	a postal code!
			$countries_without_postal_codes=ct_get_countries_without_postal_code();
			if (!in_array($this->get('country'),$countries_without_postal_codes) &&
					(!isset($http['form_zip']) || $http['form_zip']=="") )  {
				$this->errors[]	= 'form_zip';
			}
		} else {
			$this->errors[]	= 'form_country';
			$this->_require('zip',	'form_zip'); //	see below...
		}
		$this->set('phone', $http['form_phone']);
		$this->set('fax', $http['form_fax']);
		if (isset($http['form_email']) && ($http['form_email'] != "")) {
			$this->set('email', $http['form_email']);
			// Check if email-adress is valid. This function checks the syntax and the existance of the domain.
			ct_load_lib("mail.lib");
			if (!ct_validate_email($this->get('email'))) {
				$this->errors[] = 'form_email';
			}
		} else {
			$this->errors[] = 'form_email';
		}
		$this->set('url', $http['form_url']);
		$this->set('externalremark', $http['form_externalremark']);
		$this->_require('username', 'form_username');
		if ( isset($http['form_username']) && ( ct_strlen($http['form_username'])<2) ) {
			$this->errors[] = 'form_username';
		}
		$this->_require('password', 'form_pwd');
		$pwdcheck = ct_validate_password($http['form_pwd'],$this);
		if ( isset($http['form_pwd']) && (count($pwdcheck)>0) ) {
			$this->errors[] = 'form_pwd';
		} else {
			$this->set('password', $http['form_pwd']);
		}
		// Check for spam robots!
		$this->set('name2', $http['form_name2']);
		if (ct_strlen(trim($this->get('name2')))>0) {
			$this->errors[] = 'form_name2';
		}
		$user =& $session->get_user();
		if ($session->loggedin() && $user->is_admin()) {
			$stat = array();
			if (isset($http['form_status_admin'])) { $stat[] = 'admin'; }
			if (isset($http['form_status_author'])) { $stat[] = 'author'; }
			if (isset($http['form_status_pc'])) { $stat[] = 'pc'; }
			if (isset($http['form_status_chair'])) { $stat[] = 'chair'; }
			if (isset($http['form_status_participant'])) { $stat[] = 'participant'; }
			if (isset($http['form_status_frontdesk'])) { $stat[] = 'frontdesk'; }
			if (isset($http['form_status_assistant'])) { $stat[] = 'assistant'; }
			$this->set('status', implode($stat, ','));
			$this->set('internalremark', $http['form_internalremark']);
			# echo "<p>Status: ".$this->get('status')."</p>";
		}
		if (count($this->errors)==0) {
			return false;
		} else {
			return $this->errors;
		}
	}

	# Creates a HTML form to edit person`s data
	function show_form($action,$errors,$empty=false) {
		global $session, $ctconf;

		$user =& $session->get_user();

		$form = new CTForm($action, "post", $errors);

		$form->width='100%';
		$form->align='center';
		$form->warningmessage=true;

		$form->add_hidden(array(array('form_id', $this->get('ID'))));

		$form->add_separator(ct('S_USER_PERSONALSECTION'));
		$form->add_text("*&nbsp;".ct('S_USER_ORGANISATION'), 'form_organisation', $this->get('organisation'), 50, 255);
		$form->add_text(ct('S_USER_ORGANISATION2'), 'form_organisation2', $this->get('organisation2'), 50, 255);
		$form->add_radio("*	".ct('S_USER_GENDER'), 'form_gender', array(
							array('2',ct('S_USER_GENDER_FEMALE')),
							array('1',ct('S_USER_GENDER_MALE')),
							array('3',ct('S_USER_GENDER_DR')),
							array('4',ct('S_USER_GENDER_PROF'))),	$this->get('gender'));
		$form->add_text(ct('S_USER_TITLE'), 'form_title', $this->get('title'), 20, 255);
		if (($session->loggedin() && ($user->is_admin() || $user->is_frontdesk() || $this->get_id()==0)) ||
					(ct_strlen($this->get('firstname'))<2 || ct_strlen($this->get('name'))<2)  ||
					!($session->loggedin())) {
			$form->add_text("* ".ct('S_USER_FIRSTNAME'), 'form_firstname', $this->get('firstname'), 50, 255);
			$form->add_text("* ".ct('S_USER_NAME'), 'form_name', $this->get('name'), 50, 255);
		} else { # disallow changes to the name of the user!
			$form->add_hidden(array(array('form_firstname', $this->get('firstname'))));
			$form->add_hidden(array(array('form_name', $this->get('name'))));
			$form->add_label("* ".ct('S_USER_FIRSTNAME'), $this->get_special('firstname'));
			$form->add_label("* ".ct('S_USER_NAME'), $this->get_special('name'));
		}
		$form->add_spacer();

		$form->add_text("* ".ct('S_USER_ADDRESS1'), 'form_addr1', $this->get('addr1'), 50, 255);
		$form->add_text(ct('S_USER_ADDRESS2'), 'form_addr2', $this->get('addr2'), 50, 255);
		$form->add_text("(*) ".ct('S_USER_ZIPCODE'), 'form_zip', $this->get('zip'), 10, 10);
		$form->add_text("* ".ct('S_USER_CITY'), 'form_city', $this->get('city'), 50, 255);
		ct_load_lib('address.lib');
		// Select State/Territory
		if (ctconf_get('web/liststates',true)) {
			// $form->add_text(ct('S_USER_STATE'), 'form_state', $this->get('state'), 40, 255);
			$states	= ct_list_states();
			$form->add_select( ct('S_USER_STATE'),'form_state',1, $states, array($this->get('state')));
		}
		//	$form->add_text("* ".ct('S_USER_COUNTRY'), 'form_country', $this->get('country'), 40, 255);
		$countries = ct_list_countries();
		if ($session->loggedin() || $this->get('country')!="")	{
			$defaultcountry = $this->get('country');
		} else { // give default country
			$defaultcountry = ctconf_get('web/defaultcountry');
		}
		$form->add_select('* '.ct('S_USER_COUNTRY'),'form_country',1, $countries, array($defaultcountry), false, (ct_strlen($this->get('country'))>2)?"<br>(".$this->get('country').")":"" );
		$form->add_spacer();

		$form->add_text(ct('S_USER_PHONE'), 'form_phone', $this->get('phone'), 50, 255);
		$form->add_text(ct('S_USER_FAX'), 'form_fax', $this->get('fax'), 50, 255);
		$form->add_text("* ".ct('S_USER_EMAIL'), 'form_email', $this->get('email'), 50, 255);
		$form->add_text(ct('S_USER_HOMEPAGE'), 'form_url', $this->get('url'), 50, 255);
		$form->add_textarea(ct('S_USER_EXTERNALREMARK'), 'form_externalremark', $this->get('externalremark'), 70, 6);
		if ($session->loggedin() && $user->is_admin()) {
			$form->add_separator(ct('S_USER_ADMINSECTION'));
			$form->add_textarea(ct('S_USER_INTERNALREMARK'), 'form_internalremark', $this->get('internalremark'), 70, 6);
			$form->add_check(ct('S_USER_STATUS'), array(
			array( 'form_status_pc', 'pc', "<strong>".ct('S_USER_STATUS_PC')."</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".ct('S_USER_STATUS_PC_HINT'), $this->is_pc()),
			array( 'form_status_author', 'author', ct('S_USER_STATUS_AUTHOR'), $this->is_author(), '', true),
			array( 'form_status_participant', 'participant', ct('S_USER_STATUS_PARTICIPANT').'<br><br><i>'.ct('S_USER_STATUS_ADMINISTRATIVE').'</i>', $this->is_participant(), '', true),
			array( 'form_status_admin',	'admin', "<strong>".ct('S_USER_STATUS_ADMIN')."</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".ct('S_USER_STATUS_ADMIN_HINT'), $this->is_admin(), '', $this->get_ID()==1 || !$user->is_admin()),
			# array( 'form_status_assistant',	'assistant', "<strong>".ct('S_USER_STATUS_ASSISTANT')."</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".ct('S_USER_STATUS_ASSISTANT_HINT'), $this->is_assistant(), '', (!$user->is_admin() && !$user->is_assistant()) || ($user->get_id()==$this->get_id() && !$user->is_admin()) ),
			array( 'form_status_chair',	'chair', "<strong>".ct('S_USER_STATUS_CHAIR')."</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".ct('S_USER_STATUS_CHAIR_HINT'), $this->is_chair()),
			array( 'form_status_frontdesk',	'frontdesk', "<strong>".ct('S_USER_STATUS_FRONTDESK')."</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".ct('S_USER_STATUS_FRONTDESK_HINT'), $this->is_frontdesk()),
			) );
		}
		$form->add_separator(ct('S_USER_LOGINSECTION'));
		if (($session->loggedin() && $user->is_admin()) || !($session->loggedin()) || $this->get_id()==0 ) {
			$form->add_text("* ".ct('S_USER_USERNAME'), 'form_username', $this->get('username'), 40, 255, false, $session->loggedin() ? '' : ct('S_USER_USERNAME_HINT') );
		} else {
			$form->add_hidden(array(array('form_username', $this->get('username'))));
			$form->add_label(ct('S_USER_USERNAME'), $this->get_special('username'));
		}
		//if ($session->loggedin() && $user->is_admin())
    	//	$form->add_text("* ".ct('S_USER_PASSWORD'), 'form_pwd', $this->get('password'), 10, 255);
        //else
    		$form->add_pass("* ".ct('S_USER_PASSWORD'), 'form_pwd', $this->get('password'), 10, 255, false, ct('S_USER_PASSWORD_HINT_STRONG'));

		if ($this->get('ID') == "") {
			if (!($session->loggedin() && $user->is_admin())) {
				$form->add_check("&nbsp;", array(array('form_passwmail','passwmail',ct('S_USER_REG_EMAIL_DESCRIPTION'),0)));
			}
		}

		// Honeypot to protect from Spam-Bots
		$form->add_hiddentext('* '.ct('S_USER_SPAMPROTECT'), 'form_name2', $this->get('name2'), 10, 255, false, ct('S_USER_SPAMPROTECT_HINT'));

		if ((ctconf_get('participation/enabled') && $session->loggedin() && ($user->is_admin() || $user->is_frontdesk()) && $this->get_id()<1) ||
			   (!$session->loggedin() && ct_check_phases("participation") && !ct_check_phases('submission'))) {
			$form->add_submit_not_bottom('cmd_save_person_register', ct('S_USER_SUBMIT_PARTICIPATE'));
			$form->add_submit('cmd_save_person', ct('S_USER_SUBMIT_PARTICIPATE_LATER'));
		} else {
			$form->add_submit('cmd_save_person', ct('S_USER_SUBMIT'));
		}

		#$form->add_reset(ct('S_BUTTON_RESET'));
		if ($session->loggedin() && $user->is_admin()) {
			$form->add_submit('cmd_cancel', ct('S_BUTTON_CANCEL'));
		}

		if (ctconf_get('demomode') && $this->get_id()>0 && $this->get_id()<100) $form->demomode=true;

		$form->show();
	}

	### DISPLAY FUNCTIONS ########################################################

	# show row in userlist or PC-list
	function show_row($class, $page="") {
		global $session;
		$user =& $session->get_user();
		$param = array("form_id" => $this->pdata['ID']);
		if ($this->get('deleted') != '0') {
			echo "<tr class=\"".$class."_del\">\n";
		} else {
			echo "<tr class=\"$class\">\n";
		}
		echo "<td align=right valign=top><span class=\"normal10\">".$this->pdata['ID']."</span></td>\n";
		echo "<td align=left valign=top><span class=\"bold9\">";
		echo "<a href=\"".ct_pageurl('adminUsersDetails')."&form_id=".$this->get('ID')."\">";
		echo $this->get_special('name').", ".$this->get_special('firstname')."</a></span>";
		if ($this->pdata['externalremark'] != "" || $this->pdata['internalremark'] != "") {
        	echo ' <a class="fontlabel" href="'.ct_pageurl('adminUsersDetails', $param).'" ';
        	echo 'title="'.ct('S_USER_DETAILED_REMARKSECTION').'" ';
			if (ct_strlen($this->pdata['externalremark'])>1) {
				echo 'ext_title="'.ct('S_USER_EXTERNALREMARK').'" ' ;
				echo 'ext_remark="'.ct_substr($this->get_special('externalremark'),0,512).'" ' ;
			}
			if (ct_strlen($this->pdata['internalremark'])>1) {
				echo 'int_title="'.ct('S_USER_INTERNALREMARK').'" ' ;
				echo 'int_remark="'.ct_substr($this->get_special('internalremark'),0,512).'" ' ;
			}
	       	echo '>';
        	echo '<img src="'.ct_getbaseurl().'images/remark.png" border="0" alt="">'; // alt="Remark" // annoying tooltip in IE!
        	echo '</a>';
		}
		echo "<br>";
		echo "<span class=\"normal8\"><a href=\"mailto:".$this->get_special('email')."\">";
		echo $this->get_special('email')."</a>\n";
		if ($this->pdata['username']!=$this->pdata['email'])
			echo " (".$this->get_special('username').")\n";
		echo "</span>";
  		echo "</td>\n";
		echo "<td align=left valign=top>";
		echo "<span class=\"bold9\">".$this->get_special('organisation')."</span><br>";
		if (ct_strlen($this->get('organisation2')))
			echo "<span class=\"normal10\">".$this->get_special('organisation2')."</span><br>";
		ct_load_lib("address.lib");
		echo "<span	class=\"normal8\">";
		if (ct_strlen($this->get('country'))==2)	echo $this->get_special('country').",	";
		echo ct_get_country($this->get('country'))."</span>";
		echo "</td>\n";

		// -------------
		echo "<td align=center valign=top>";
		# PC-Overview page with numer of reviews
		if ($page=="adminUsersPC") {
			echo "<a class=\"bold8\" href=\"".ct_pageurl('adminReviewAssignPC', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_REVIEWS')."</a>";
			echo "<BR>";
			$reviewcount=$this->get_review_count($this->pdata['ID'] , "" , 0);
			$reviewsdone=$this->get_review_count($this->pdata['ID']);
			if ($reviewcount!=$reviewsdone) {
				echo "<span class=\"negativebold8\">";
			} else {
				echo "<span class=\"positivebold8\">";
			}
			echo $reviewsdone."</span> / ";
			echo "<span class=\"bold8\">".$reviewcount."</span><BR>";
		} else {
			echo "<span class=\"normal8\">";
			if ($this->is_admin()) {
				echo "<span class=\"negativebold8\">".ct('S_USER_STATUS_ADMIN_SHORT')."</SPAN>";
			} elseif ($this->is_frontdesk() || $this->is_assistant()) {
				if ($this->is_frontdesk()) { echo "<span class=\"positivebold8\">".ct('S_USER_STATUS_FRONTDESK_SHORT')."&nbsp;</SPAN>"; }
				if ($this->is_assistant()) { echo "<span class=\"positivebold8\">".ct('S_USER_STATUS_ASSISTANT_SHORT')."&nbsp;</SPAN>"; }
			} else {
				echo "&nbsp;&nbsp;";
			}
			echo "<br>";
			if ($this->is_pc()) { echo ct('S_USER_STATUS_PC_SHORT'); }
			echo "&nbsp;";
			if ($this->is_chair()) { echo ct('S_USER_STATUS_CHAIR_SHORT'); }
			echo "</span>";
		}
		echo "</td>\n";

		// -------------
		echo "<td align=left valign=top><span class=\"normal8\">";

		if (strstr($this->pdata['status'], 'author')) { echo ct('S_USER_STATUS_AUTHOR_SHORT')."<br>"; } else { echo "&nbsp;&nbsp;<br>"; }
		if (strstr($this->pdata['status'], 'participant')) {
			echo "<b><a href=\"".ct_pageurl('adminParticipantsDetails', array('form_id' => $this->get('ID')))."\">".ct('S_USER_STATUS_PARTICIPANT_SHORT')."</a></b>"; }

		echo "</span></td>\n";

		// -------------
		// echo "<td align=left valign=top><span class=\"normal10\">".$this->get_special('username')."</span></td>\n";

		echo "<td align=right valign=top>";
		if ($page=="adminUsersPC") {
			echo "<a class=\"bold8\" href=\"".ct_pageurl('adminReviewAssignPC', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_PAPERS_ACTION_ASSIGN')."</a> &middot; ";
			echo "<a class=\"bold8\" href=\"".ct_pageurl('pc2topics', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_TOPICS')."</a><br>";
		}
		if ($user->is_admin()) {
			echo "<a class=\"bold8\" href=\"".ct_pageurl('editPerson', array('form_userID' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_EDIT')."</a>";
			if ($this->pdata['deleted'] == '0') {
				echo "<span class=\"normal8\">&nbsp;&middot;&nbsp;<a href=\"".ct_pageurl('editPerson', array('form_delete_ID' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_DELETE')."</a></span><BR>";
			} else {  # allow to reactivate/undelete users
				echo "<span class=\"normal8\">&nbsp;&middot;&nbsp;<a href=\"".ct_pageurl('editPerson', array('form_id' => $this->get('ID'), 'cmd_undelete_person' => 'true'))."\">".ct('S_ADMIN_USERS_ACTION_UNDELETE')."</a></span><BR>";
			}
		    echo "<a class=\"normal8\" href=\"".ct_pageurl('adminUsersLoginAs', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_LOGINAS')."</a>";
		} else {
			echo "&nbsp;";
		}
		if ($page!="adminUsersPC") {
			if (($user->is_admin() || $user->is_frontdesk())&& !strstr($this->pdata['status'], 'participant') && $page!=="adminUsersPC" && ctconf_get('participation/enabled'))
				echo "<br><span class=\"normal8\"><a href=\"".ct_pageurl('adminParticipantsEditDelete', array('form_userID' => $this->get('ID')))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_REGISTER')."</a></span>";
			if ($user->is_admin() && $page!=="adminUsersPC" && ctconf_get('submission/enabled'))
				echo "<br><span class=\"normal8\"></span><a class=\"normal8\" href=\"".ct_pageurl('newPaper', array('form_personID' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_NEWPAPER')."</a></span>";
		}

		echo "</td>\n";
		echo "</tr>\n";
	}

	# show basic user information in one row
	function show_simple_row($class) {
		global $session;
		$user =& $session->get_user();
		if ($this->get(deleted) != '0') {
			echo "<tr class=\"".$class."_del\">\n";
		} else {
			echo "<tr class=\"$class\">\n";
		}
		echo "<td align=right valign=top><span class=\"normal9\">".$this->pdata['ID']."</span></td>\n";
		echo "<td align=left valign=top><span class=\"bold9\">";
		echo $this->get_special('name').", ".$this->get_special('firstname')."</span>";
		echo "</td>\n";
		echo "<td align=left valign=top>";
		echo "<span class=\"normal8\"><a href=\"mailto:".$this->get_special('email')."\">";
		echo $this->get_special('email')."</a>\n";
		echo "</span>";
  		echo "</td>\n";
		echo "<td align=left valign=top>";
		echo "<span class=\"bold9\">".$this->get_special('organisation')."</span>";
  		echo "</td>\n";
		echo "<td align=left valign=top>";
		echo "<span class=\"normal8\">".$this->get_special('country')."</span>";
		echo "</td>\n";
		echo "</tr>\n";
	}


	// Show	short info about user
	function show_shortinfo($width="98%", $align="center") {
		global $session;
		$user =& $session->get_user();

		echo "<table width=\"$width\" align=\"$align\" cellspacing=0 cellpadding=1 border=0>\n";
		echo "<tr><td class=\"blackbg\">\n";

		echo "<table width=\"100%\" align=\"center\" cellspacing=0 cellpadding=2 border=0>\n";
		echo "<tr class=\"lightbg\">\n";
		echo "<td>\n";
		echo "<span	class=\"normal10\">".$this->get_special('title')."</span><br>\n";
		if ($user->is_admin() || $user->is_assistant() ) {
			echo "<a href=\"".ct_pageurl('adminUsersDetails', array('form_id'=>$this->get('ID')))."\" class=\"bold10\">".ct_form_encode($this->get_fullname())."</a><br>\n";
		} else {
			echo "<span	class=\"bold10\">".ct_form_encode($this->get_fullname())."</span><br>\n";
		}
		echo "<span	class=\"label10\">".ct('S_USER_ORGANISATION').":</span>	";
		echo "<span	class=\"normal10\">".$this->get_special('organisation')."</span>\n";
		echo "</td><td width=\"15%\" align=center valign=middle	class=\"infoview_invert\">\n";
		echo "<span	class=\"lightbold20\">".$this->get('ID')."</span>\n";
		echo "</td></tr>\n";
		echo "</table>\n";
		echo "</td></tr>\n";
		echo "</table>\n";
	}


	# show detail information on this conftool user
	# calles e.g. by adminUserDetails
	function show_detailed($width, $align) {
		ct_load_lib('address.lib');
		global $session;
		$user =& $session->get_user();
		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_NAMESECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "<tr class=\"oddrow\"><td align=\"left\" valign=\"top\" width=\"80%\">\n";
		if ($this->get('title') != "") {
			echo "<span class=\"bold10\">".$this->get_special('title')."</span><br>\n";
		}
		echo "<span class=\"bold14\">".$this->get_special('name').", ".$this->get_special('firstname')."</span><br>\n";
		echo "<span	class=\"bold10\">".$this->get_special('organisation').", ".ct_get_country($this->get('country'))."</span>\n";
		echo "</td><td width=\"20%\" align=center valign=middle class=\"infoview_invert\">\n";
		echo "<span class=\"lightbold36\">".$this->get('ID')."</span>\n";
		echo "</td></tr>\n";
		echo "</table>\n";

		ct_vspacer();
		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_CONTACTSECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";

		echo "<tr class=\"evenrow\"><td width=\"50%\" align=left valign=top>\n";
		echo "<span	class=\"normal10\">".$this->get_special('organisation')."</span><br>\n";
		if ($this->get('organisation2'))
			echo "<span	class=\"normal10\">".$this->get_special('organisation2')."</span><br>\n";
		echo "<span class=\"normal10 fontlabel\">".$this->get_special('firstname')." ".$this->get_special('name')."</span><br>\n";
		echo "<span class=\"normal10\">\n";
		echo $this->get_special('addr1')."<br>\n";
		if ($this->get('addr2') != "") {
			echo $this->get_special('addr2')."<br>\n";
		}
		echo ct_address_format($this->get_special('country'),$this->get_special('zip'),$this->get_special('state'),$this->get_special('city'));
		echo ct_get_country($this->get('country'))."</span>\n";
		echo "</td><td width=\"50%\" align=left valign=top>\n";
		echo "<span class=\"label10\">".ct('S_USER_EMAIL').":</span> <a href=\"mailto:".$this->get_special('email')."\" class=\"normal10\">".$this->get_special('email')."</a><br>\n";
		if ($this->get('phone'))
			echo "<span class=\"label10\">".ct('S_USER_PHONE').":</span> <span class=\"normal10\">".$this->get_special('phone')."</span><br>\n";
		if ($this->get('fax'))
			echo "<span class=\"label10\">".ct('S_USER_FAX').":</span> <span class=\"normal10\">".$this->get_special('fax')."</span><br>\n";
		if ($this->get('url'))
			echo "<span class=\"label10\">".ct('S_USER_HOMEPAGE').":</span> <a href=\"".$this->get_special('url')."\" class=\"normal10\">".$this->get_special('url')."</a><br>\n";
		echo "</td></tr>\n";
		echo "</table>\n";

		ct_vspacer();
		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_LOGINSECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "<tr class=\"evenrow\"><td width=\"50%\" align=left valign=top>\n";
		echo "<span class=\"label10\">".ct('S_USER_STATUS')."</span><br>\n";
		echo "<ul>\n";
		if ($this->is_admin()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_ADMIN')."</span></li>\n"; }
		if ($this->is_participant()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_PARTICIPANT')."</span></li>\n"; }
		if ($this->is_author()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_AUTHOR')."</span></li>\n"; }
		if ($this->is_pc()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_PC')."</span></li>\n"; }
		if ($this->is_chair()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_CHAIR')."</span></li>\n"; }
		if ($this->is_frontdesk()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_FRONTDESK')."</span></li>\n"; }
		if ($this->is_assistant()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_ASSISTANT')."</span></li>\n"; }
		echo "</ul></td>\n";
		echo "<td width=\"50%\" align=left valign=top>\n";
		echo "<span class=\"label10\">".ct('S_USER_USERNAME').":</span> <span class=\"normal10\">".$this->get_special('username')."</span><br>\n";
		// echo "<span class=\"label10\">".ct('S_USER_PASSWORD').":</span> <span class=\"normal10\">".$this->get_special('password')."</span><br>\n";
		echo "<span class=\"label10\">".ct('S_USER_REGISTRATION_DATE').":</span> <span class=\"normal10\">".ct_datetime_format($this->get('creationdate'), true)."</span><br>\n";
		echo "<span class=\"label10\">".ct('S_USER_LOGIN_DATE').":</span> <span class=\"normal10\">".ct_datetime_format($this->get('logindate'), true)."</span><br>\n";
		echo "</td></tr>\n";
		echo "</table>\n";

		ct_vspacer();
		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_REMARKSECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "<tr class=\"oddrow\"><td width=\"50%\" align=left valign=top>\n";
		echo "<span class=\"label10\">".ct('S_USER_EXTERNALREMARK').":</span><br>\n";
		echo "<span class=\"normal10\">".ct_nl2br($this->get('externalremark'))."</span>\n";
		echo "</td>";
		echo "<td width=\"50%\" align=left valign=top>\n";
		if ($user->is_admin()) {
			echo "<span class=\"label10\">".ct('S_USER_INTERNALREMARK').":</span><br>\n";
			echo "<span class=\"normal10\">".ct_nl2br($this->get('internalremark'))."</span>\n";
		}
		echo "</td></tr>\n";
		echo "</table>\n";


		# Show possible actions
		if ($user->is_admin()) {
			ct_vspacer();
			echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
			echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_COMMANDSSECTION')."</span>\n";
			echo "</td></tr>\n";
			echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
			echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
			echo "</td></tr>\n";
			echo "<tr class=\"mediumbg\"><td colspan=2 align=left valign=top>\n";
			echo "<span class=\"bold10\">\n";
  			echo "<a href=\"".ct_pageurl('editPerson', array('form_userID' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_EDIT')."</a> &middot; ";
			echo "<a href=\"".ct_pageurl('editPerson', array('form_delete_ID' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_DELETE')."</a> &middot; ";
			echo "<a href=\"".ct_pageurl('adminUsersLoginAs', array('form_id' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_LOGINAS')."</a>";
			if (strstr($this->get('status'), 'participant')) {
				echo " &middot; <a href=\"".ct_pageurl('adminParticipantsDetails', array('form_id' => $this->get('ID')))."\">".ct('S_INDEX_CMD_PARTICIPATE_INFO')."</a>";
            } else {
				echo " &middot; <a href=\"".ct_pageurl('adminParticipantsEditDelete', array('form_userID' => $this->get('ID')))."\">".ct('S_PARTICIPATE_TITLE')."</a>";
			}
			echo " &middot; <a href=\"".ct_pageurl('newPaper', array('form_personID' => $this->get('ID')))."\">".ct('S_ADMIN_USERS_ACTION_NEWPAPER')."</a>";
			echo "</span>\n</td></tr>\n";
			echo "</table>";
			ct_vspacer();
		}

		# list papers of participant
		if ($this->is_author()) {
			ct_vspacer('10');
			echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
			echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_PAPERSECTION')."</span>\n";
			echo "</td></tr>\n";
			echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
			echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
			echo "</td></tr>\n";
			echo "</table>\n";

			$papers = $this->get_papers();
			while (list(,$v) = each($papers)) {
				$v->show_infobox('100%');
				ct_vspacer();
			}
		}

	}

	function show_mediuminfo($width, $align) {
		global $session;
		$user =& $session->get_user();
		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_NAMESECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "<tr class=\"oddrow\"><td align=\"left\" valign=\"top\" width=\"80%\">\n";
		if ($this->get('title') != "") {
			echo "<span class=\"normal10\">".$this->get_special('title')."</span><br>\n";
		}
		echo "<span class=\"bold14\">".$this->get_special('name').", ".$this->get_special('firstname')."</span><br>\n";
		echo "<span class=\"bold10\">".$this->get_special('organisation')."</span>\n";
		echo "</td><td width=\"20%\" align=center valign=middle class=\"infoview_invert\">\n";
		echo "<span class=\"lightbold36\">".$this->get('ID')."</span>\n";
		echo "</td></tr>\n";

		echo "</table>";

		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		if (!$this->get('externalremark') == "" || !$this->get('internalremark') == "") {

			echo "<tr class=\"lightbg\"><td colspan=2><img src=\"images/spacer.gif\" width=1 height=1 border=0></td></tr>\n";
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
			echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_REMARKSECTION')."</span>\n";
			echo "</td></tr>\n";
			echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
			echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
			echo "</td></tr>\n";

			echo "<tr class=\"oddrow\"><td width=\"50%\" align=left valign=top>\n";
			echo "<span class=\"label10\">".ct('S_USER_EXTERNALREMARK').":</span><br>\n";
			echo "<span class=\"normal10\">".ct_nl2br($this->get('externalremark'))."</span>\n";
			echo "</td><td width=\"50%\" align=left valign=top>\n";
			echo "<span class=\"label10\">".ct('S_USER_INTERNALREMARK').":</span><br>\n";
			echo "<span class=\"normal10\">".ct_nl2br($this->get('internalremark'))."</span>\n";

			echo "<tr class=\"lightbg\"><td colspan=2><img src=\"images/spacer.gif\" width=1 height=1 border=0></td></tr>\n";
		}
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=2>\n";
		echo "<span class=\"boldlabel10\">".ct('S_USER_DETAILED_LOGINSECTION')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=2>";
		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";
		echo "</table>\n";

		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"evenrow\"><td width=\"50%\" align=left valign=top colspan=2>\n";
		echo "<span class=\"label10\">".ct('S_USER_STATUS')."</span><br>\n";
		echo "<ul>\n";
		if ($this->is_admin()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_ADMIN')."</span></li>\n"; }
		if ($this->is_participant()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_PARTICIPANT')."</span></li>\n"; }
		if ($this->is_author()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_AUTHOR')."</span></li>\n"; }
		if ($this->is_pc()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_PC')."</span></li>\n"; }
		if ($this->is_chair()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_CHAIR')."</span></li>\n"; }
		if ($this->is_frontdesk()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_FRONTDESK')."</span></li>\n"; }
		if ($this->is_assistant()) { echo "<li> <span class=\"normal10\">".ct('S_USER_STATUS_ASSISTANT')."</span></li>\n"; }
		echo "</ul>\n";
		echo "</td></tr>\n";
		echo "</table>\n";
	}

}

?>