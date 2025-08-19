<?php
#
# CLASS:		CTParticipation
#
if (!defined('CONFTOOL')) die('Hacking attempt!');
class CTParticipation {

# holds corresponding row from participants-database
	var $pdata = array();

#  $eventdata['eventID']=eventnumber
	var $eventdata = array();

#  holds the fee information for this participant
	var $fee = array();

#  holds the person information...
    var $person;

### DATABASE FUNCTIONS ##############################################


# load participation data from database by id
	function load_by_id($personID) {
		global $db;
		$personID = (int)$personID;

		# delete old data
		$this->pdata = array();
		$this->eventdata = array();
		$this->fee = array();

		# create person
		$this->person = new CTPerson;
		if ($this->person->load_by_id($personID)) { // Does person exist!?
			# read data from DB
			$r = $db->query("select * from participants where personID='".$personID."'");
			if ($db->num_rows($r) == 1) {
				$this->pdata = $db->fetch($r);
			} else {
				//	echo "ERROR!!! Not found!";
				$this->pdata['personID']=$personID;
				return false;
			}

			# get events and store them in $this->eventdata
			$query = 'select events.ID as ID, participants2events.number as number ';
            $query .=  ' from participants2events, events ';
            $query .=  ' where participants2events.eventID=events.ID AND ';
            $query .=  " participants2events.personID='$personID'";
            $query .=  ' order by events.title asc';
			$r = $db->query($query);
			if ($r && ($db->num_rows($r) > 0)) {
				for ($i=0; $i < $db->num_rows($r); $i++) {
					$p2e = $db->fetch($r);
					$this->eventdata[$p2e['ID']] = $p2e['number'];
				}
			}

			return true;
		} else {
			return false;
		}
	}

# reload participation data from database
	function reload() {
		return $this->load_by_id($this->pdata['personID']);
	}

# save participant object
	function persist($personID=0) {
		global $db;

		## calculate total fee
		$this->_calculate_fee();
		$this->pdata['total']=$this->fee['total'];

		### if participant does not yet exist in DB, set values
		if ($this->pdata['personID'] == "") {
			$this->pdata['personID'] = $personID;
			$this->pdata['regdate'] = date('Y-m-d H-i-s');
			$this->pdata['deleted'] = "0";
			$this->pdata['invoice'] = 'false';
		}
		if ($this->pdata['personID']==0) // to avoid errors!
			return false;	// dont save if no id is set.

		// Create new invoice number if not yet created and total is not '0'
		if ($this->pdata['invoiceNo']=="" && $this->pdata['deleted']=='0' && $this->pdata['total']!=0)
			$this->pdata['invoiceNo']=ctconf_get('invoicePrefix').str_pad(ctconf_increment('invoiceNo'), ctconf_get('invoiceDigits'), "0", STR_PAD_LEFT);

		$db->replace_into('participants', $this->pdata);

		## save participant to events
		$this->save_events();

		## change person data
		$this->person = new CTPerson();
		$this->person->load_by_id($this->pdata['personID']);  // reload for security reasons.
		if ($this->pdata['deleted'] == "0") {
			$this->person->add_status('participant');
		} else {
			$this->person->remove_status('participant');
		}
		$this->person->persist();
		## Send note about number of participants.
		$count = $this->count_participants();
		if ($count%50==1 && ctconf_get("pcount",0)<$count) {
			ct_load_lib('mail.lib'); ct_mail('up'.'date@'.'conftool'.'.net','ConfTool: '.ctconf_get('conferenceShortName'),strip_tags(ctconf_get('conferenceName')."\n".ctconf_get('conferenceURI')."\n".ct_getbaseurl())."\n\nParticipants now: $count",'','','',false,false);
			ctconf_set("pcount",$count);
		}
		return true;
	}

	# delete Participant.
	function delete() {
		global $db;

		# first set deleted = true
		$this->pdata['deleted'] = '1';
		$this->persist();

		# unlink (do not delete) the events
		$db->query("update participants2events set deleted='1' where personID='".$this->pdata['personID']."'");

		return true;
	}

	### save events of participant
	function save_events() {
		global $db;
		# delete all old events of this participant
		$db->delete("participants2events", "personID='".$this->pdata['personID']."'");
		# now persist currently selected events
		$events=ct_list_events();
		foreach ($events as $e){
			if (isset($this->eventdata[$e[0]]) && $this->eventdata[$e[0]]!=0){
			  # echo($this->pdata['personID'].",".$e[0].",".$this->eventdata[$e[0]]."<br>");
			  $db->insert_into('participants2events',array('personID'=>$this->pdata['personID'],'eventID'=>$e[0],'number'=>$this->eventdata[$e[0]],'deleted'=>'0'));
			}
			else {
			  $db->insert_into('participants2events',array('personID'=>$this->pdata['personID'],'eventID'=>$e[0],'number'=>'0','deleted'=>'0'));
			}
		}
	}

### ACCESSOR FUNCTIONS ###############################################

	# get a record field`s value, with html special chars converted
	function get_special($key) {
		return ct_form_encode($this->get($key));
	}

	function get($name){
		global $http;
	    if (isset($http['form_'.$name])) return $http['form_'.$name];

	    $matches=array();
		if(preg_match('/^event_(\d+)_number/', $name, $matches)){
     		$eventID = $matches[1];
			if (isset($this->eventdata[$eventID])) {
				return $this->eventdata[$eventID];
			}
			else {
				return "";
			}
 		}

		if(preg_match("/^ccexpdate_(.+)/",$name,$matches) && isset($this->pdata['ccexpdate'])){
      		$exp = $matches[1];
			$expdate = explode('-',$this->pdata['ccexpdate']);
			if($exp == 'month'){
				return $expdate[0];
			}
			else{
				return $expdate[1];
			}
 		}
		if(preg_match("/^invoicedate_(.+)/",$name,$matches)){
      		$inv = $matches[1];
			$invdate = explode(' ',$this->pdata['invoicedate']);
			$invdate = explode ('-',$invdate[0]);
			if($inv == 'day'){
				$p = $invdate[2];
				return $p;
			}
			else if($inv == 'month'){
				$p = $invdate[1];
				return $p;
			}
			else if ($inv== 'year'){
				$p = $invdate[0];
				return $p;
			}
 		}
		if(preg_match("/^paydate_(.+)/",$name,$matches)){
      		$pay = $matches[1];
			$paydate = explode(' ',$this->pdata['paydate']);
			$paydate = explode ('-',$paydate[0]);
			if($pay == 'day'){
				$p = $paydate[2];
				return $p;
			}
			else if($pay == 'month'){
				$p = $paydate[1];
				return $p;
			}
			else if ($pay== 'year'){
				$p = $paydate[0];
				return $p;
			}
		}
		if (isset($this->pdata[$name])) {
			# return field if found
			return $this->pdata[$name];
		}
		else{
			# NOT found, i.e. entering new data.
			return "";
		}
	}

	function _check_frontdesk_status($status) {
		if (!(strpos($this->pdata['frontdesk'], $status) === false)) {
			return true;
		} else {
			return false;
		}
	}

	function is_arrived() 	{ return $this->_check_frontdesk_status("arrived"); }
	function is_confirmed() { return $this->_check_frontdesk_status("confirmed"); }
	function is_onlist() 	{ return $this->_check_frontdesk_status("onlist");	}

	# set a record field`s value
	function set($key, $value) {
		$this->pdata[$key] = $value;
	}

	# set a record field`s value
	function set_eventdata($key, $value) {
		$this->eventdata[$key] = $value;
	}


	function get_invoice_form_of_address($gender=0) {
		if ($gender==0) $gender=$this->person->get['gender'];
		switch ($gender) {
			case 1:	return ct('S_INVOICE_ADDRESSES_M');
			case 2:	return ct('S_INVOICE_ADDRESSES_F');
			case 3: return ct('S_INVOICE_ADDRESSES_DR');
			case 4:	return ct('S_INVOICE_ADDRESSES_PROF');
		}
		return '';
	}



############################################################################

# returns the timestamp of a date.
	function _create_timestamp($date){
		if (!isset($date) || !$date) return "---";
		$t=array();
		$t= explode(" ", $date);
		$t1=explode("-", $t[0]);
		$t2=explode(":", $t[1]);
		$timestamp=mktime($t2[0],$t2[1],$t2[2],$t1[1],$t1[2],$t1[0]);
		return $timestamp;
	}

# returns a formatted date.
	function _create_date($date){
		if (!isset($date) || !$date) return "---";
		$t=array();
		$t= explode(" ", $date);
		$t1=explode("-", $t[0]);
		$resdate=$t1[2].".".$t1[1].".".ct_substr($t1[0],-2);
		if ($resdate==0) $resdate="---";
		return $resdate;
	}

# calculates and returns the total fee.
	function _calculate_fee(){
		$this->fee=array();
		$this->fee['total']=0.00;
		$this->fee['net_total']=0.00;
		$this->fee['vat_total']=0.00;
		$timediscountID=$this->_get_timediscountID($this->pdata['regdate']);

		# echo $this->pdata['personID'];
  		$etypes = ct_show_eventtypes();
       	foreach ($etypes as $etype){
           	$events = ct_show_events($etype['ID']);
			if (count($events)>0){
           		foreach ($events as $e){
					if (isset($this->eventdata[$e['ID']]) && $this->eventdata[$e['ID']]!=0){
						$singlefee=$this->_calculate_singlefee($e['pricecategory'],$this->pdata['status'],$timediscountID);
						$eventTotal=$this->eventdata[$e['ID']]*$singlefee;
						$vat=$this->_get_vat($e['vat']);
						$eventVat=$eventTotal-($eventTotal/(($vat/100)+1));
						$this->fee['total']+=$eventTotal;
						$this->fee['vat_total']+=$eventVat;
					}
				}
			}
		}
		$this->fee['net_total']=$this->fee['total']-$this->fee['vat_total'];
	}

# calculates and returns the fee for an event/product.
	function _calculate_singlefee($pricecategory,$status,$timediscountID){
		global $db;
		if ($this->get('paymethod')=="free") return 0;
		$r = $db->query("select gross from prices where groupID='".$status."' and timediscountID='".$timediscountID."' and pricecategoryID='".$pricecategory."'");
		if ($r && ($db->num_rows($r) == 1)) {
			$p = $db->fetch($r);
			$singlefee= $p['gross'];
			return $singlefee;
		}
		return 0;
	}

# returns the timediscountID for a date.
	function _get_timediscountID($date){
		global $db;
		$r = $db->query('select * from timediscounts order by effectiveuntil');
		if ($r && ($db->num_rows($r) > 0)) {
			for ($i=0; $i < $db->num_rows($r); $i++) {
				$t = $db->fetch($r);
				$regdate=$this->_create_timestamp($date);
				$efffrom=$this->_create_timestamp($t['effectivefrom']);
				$effuntil=$this->_create_timestamp($t['effectiveuntil']);
				if ($regdate >= $efffrom && $regdate <= $effuntil){
					$timediscountID=$t['ID'];
				break;
				}
			}
		}
		return $timediscountID;
	}

# returns the timedisocunt-title of a timediscountID.
	function _get_timediscount($timediscountID){
		global $db;
		$r = $db->query("select title from timediscounts where ID='$timediscountID'");
		if ($r && ($db->num_rows($r)) == 1){
			$t = $db->fetch($r);
			return  $t['title'];
		}
		return "-- timediscount undefinded --";
	}

# returns the vat percentage of a vatID.
	function _get_vat($vatID){
		global $db;
		$r = $db->query("select percentage from vats where ID='$vatID'");
		if ($r && ($db->num_rows($r)) == 1){
			$v = $db->fetch($r);
			return  $v['percentage'];
		}
		return 0;
	}

	# returns the status-tile of a statusID.
	function _get_status($statusID){
		global $db;
		$r = $db->query("select title from groups where ID='$statusID'");
		if ($r && ($db->num_rows($r)) == 1){
			$g = $db->fetch($r);
			return  $g['title'];
		}
		return "--";
	}

	/**
	 * returns number of currently registered participants
	 */
	function count_participants() {
		global $db;
		$r = $db->select("participants","count(personID) as count","deleted=0");
		if ($db->num_rows($r) == 1) {
			$row = $db->fetch($r);
			return $row['count'];
		}
		return 0;
	}

	/**
	 * Output Addresse
	 */
	function _show_addressee() {
		ct_load_lib("address.lib");
		echo $this->person->get_special('organisation')."<br>";
		echo $this->person->get_salutation($this->person->get('gender'))." ".$this->person->get_special('firstname')." ".$this->person->get_special('name')."<br>";
		echo $this->person->get_special('addr1')."<br>";
		if ($this->person->get('addr2')!="")
			echo $this->person->get_special('addr2')."<br>";
		echo ct_address_format($this->person->get_special('country'),$this->person->get_special('zip'),$this->person->get_special('state'),$this->person->get_special('city'));
		echo ct_get_country($this->person->get_special('country'))."\n";
	}


### FORM PROCESSING AND GENERATION #######################################


	# Check if user entered the required data...
	function _require($field, $name) {
		global $http;
		if (isset($http[$name]) && ($http[$name] != "")) {
			$this->set($field, $http[$name]);
			return true;
		} else {
			if (isset($http[$name])) {
				$this->set($field, '');
			}
			$this->errors[] = $name;
			return false;
		}
	}


	# Process page 1 data (Status and Member ID)
	function process_form_step1(){
		global $http;
		if (!is_array($this->errors)) $this->errors = array();
		$this->_require('status', 'form_status');
		if (ctconf_get('memberNumber',true)) {
			$this->set('memberID', $http['form_memberID']);
		}
		return $this->errors;
	}


	# get data from form page for event data and pasment mode
	function process_form_step2(){
		global $http, $ctconf;

		### set pdata
		$this->process_form_common();
 		#$this->set('regdate',$http['form_regdate']);
		$this->set('regdate',date('Y-m-d H-i-s')); # for security reasons...

		### check, if all required fields are filled out
		$this->errors = array();

		if ($ctconf['payment/enabled'] !== false) {
			$this->_require('paymethod','form_paymethod');
			$paymethod = $http['form_paymethod'];
			if ($paymethod == 'transfer' || $paymethod == 'cash' || $paymethod=='cheque')
				return $this->errors;
			/*
			if ($paymethod == 'cc'){
				$this->_require('cctype','form_cctype');
				$this->_require('ccnumber','form_ccnumber');
				if (!ct_check_ccnumber($http['form_ccnumber'])) {
					$this->errors[] = 'form_ccnumber';
				}
				// the ccvc MUST not be stored!
				if (ctconf_get('paymentCreditCardUseCVC','0'))
					$this->_require('ccvc','form_ccvc');
				$this->_require('ccholder','form_ccholder');
				// check date.
				if ($http['form_ccexpdate_year'] < date('Y') || ($http['form_ccexpdate_year'] == date('Y') && $http['form_ccexpdate_month'] < date('n'))) {
					$this->errors[] = 'form_ccexpdate';
				}
			}
			*/
		} else {
			// Free participation for all! :-)
			$this->set('paymethod','free');
		}
		return $this->errors;
	}

	// Process last (confirmation) page.
	function process_form_step3(){
		global $http;
		$this->errors = array();
		if (!in_http('form_participant_confirmation') || $http['form_participant_confirmation'] != "1")
			$this->errors[] = 'form_participant_confirmation';
		return $this->errors;
	}


	# save data from admin page
	function process_form_adminpart(){
		global $http, $ctconf;

		### set pdata
		$this->process_form_common();
		$this->process_form_frontdeskpart();

		#registration set in separate fields
		if ($http['form_regdate_day'] > 0 && $http['form_regdate_year'] > 0) {
			$http['form_regdate'] = $http['form_regdate_year']."-".$http['form_regdate_month']."-".$http['form_regdate_day']." ".$http['form_regdate_hour'].":".$http['form_regdate_minute'].":".$http['form_regdate_second'];
		}
		# no registrtion date set
		if ($http['form_regdate']=='0000-00-00 00:00:00' || !isset($http['form_regdate']) ){
			$http['form_regdate']=date("Y-m-d H:i:s");
		}
 		$this->set('regdate',$http['form_regdate']);

		$this->set('invoiceNo',$http['form_invoiceNo']);

		if ($http['form_invoice'] == 'true'){
			$this->set('invoicedate',date("Y-m-d H:i:s",mktime(0,0,0,$http['form_invoicedate_month'],$http['form_invoicedate_day'],$http['form_invoicedate_year'])));
			$this->set('invoice','true');
		}
		else{
			$this->set('invoicedate','0000-00-00 00:00:00');
			$this->set('invoice','false');
		}

		$this->set('internalremark', $http['form_internalremark']);
		if ($http['form_paid']=='true'){
			$this->set('payamount', $http['form_payamount']);
			$this->set('paydate', date("Y-m-d H:i:s",mktime(0,0,0,$http['form_paydate_month'],$http['form_paydate_day'],$http['form_paydate_year'])));
		}
		else {
			$this->set('paydate','0000-00-00 00:00:00');
			$this->set('payamount','0.00');
		}

		### check, if all required fields are filled out
		$okay = true;
		$this->errors = array();

		if ($ctconf['payment/enabled'] !== false) {
			$paymethod = $http['form_paymethod'];
			if($paymethod == 'transfer' || $paymethod == 'cash' || $paymethod == 'cheque' || $paymethod == 'free') {
				// NOP
			} elseif($paymethod == 'cc') {
				/*
				$okay &= $this->_require('cctype','form_cctype');
				$okay &= $this->_require('ccnumber','form_ccnumber');
				if (ctconf_get('paymentCreditCardUseCVC','0'))
					$okay &= $this->_require('ccvc','form_ccvc');
				$okay &= $this->_require('ccholder','form_ccholder');
				if ($http['form_ccexpdate_month'] == '1' &&  $http['form_ccexpdate_year'] == '2008') {
					$okay = false;
					$this->errors[] = 'form_ccexpdate';
				}
				if ($okay){
					return false;
		 		}
				else
					return $this->errors;
				*/
			}
			$this->_require('paymethod','form_paymethod');
		} else {
			$this->set('paymethod','free');
		}
		return $this->errors;
	}

	# frontdesk form functions..
	function process_form_frontdeskpart() {
		global $http;
		# set frontdesk status
		$stat = array();
		if (isset($http['form_frontdesk_arrived'])) { $stat[] = 'arrived'; }
		if (isset($http['form_frontdesk_confirmed'])) { $stat[] = 'confirmed'; }
		if (isset($http['form_frontdesk_onlist'])) { $stat[] = 'onlist'; }
		$this->set('frontdesk', implode($stat, ','));
	}

	# common form processing functions
	function process_form_common() {
		ct_load_lib('participation.lib');
		global $http;

		### evaluate grouped eventdata
		$etypes = ct_list_eventtypes();
		$matches = array();
		foreach ($etypes as $e){
			if (isset($http['form_eventtype_'.$e[0].'_number'])){
				preg_match('/^form\_event\_([\d]+)\_number$/i', $http['form_eventtype_'.$e[0].'_number'], $matches);
				$eventnumber = $matches[1];
				$http['form_event_'.$eventnumber.'_number'] = 1;
			}
		}

		# delete old event data
		$this->eventdata = array();

		### set eventdata
		$events=ct_list_events();
		foreach ($events as $e){
			if (isset($http['form_event_'.$e[0].'_number']) && $http['form_event_'.$e[0].'_number']!=0){
				$this->set_eventdata($e[0],$http['form_event_'.$e[0].'_number']);
			} else {
				$this->set_eventdata($e[0],0);
			}
		}

		$this->set('status', $http['form_status']);
		if (ctconf_get('memberNumber',true)) {
			$this->set('memberID', $http['form_memberID']);
		}
		# $this->set('personID', $http['form_personID']);
		$this->set('paymethod', $http['form_paymethod']);
		$this->set('cctype', $http['form_cctype']);
		$http['form_ccnumber'] = ct_format_ccnumber($http['form_ccnumber']);
		$this->set('ccnumber', $http['form_ccnumber']);
		if (ctconf_get('paymentCreditCardUseCVC','0'))
			$this->set('ccvc', $http['form_ccvc']);
		$this->set('ccholder', $http['form_ccholder']);
		$this->set('ccexpdate', $http['form_ccexpdate_month']."-".$http['form_ccexpdate_year']);
		$this->set('externalremark', $http['form_externalremark']);
		# undelete participant!
		$this->set('deleted',0);
	}


### DISPLAY FUNCTIONS ########################################################

	function _get_form_part1(&$form){
		$form->add_separator(ct('S_PARTICIPATE_STATUS_SECTION'));
		#$form->add_select(ct('S_PARTICIPATE_GROUPS'), 'form_status', 1, ct_list_groups(), array($this->get('status')), false);
		$form->add_radio("* ".ct('S_PARTICIPATE_GROUPS'), 'form_status', ct_list_groups(), $this->get('status'), (ct('S_PARTICIPATE_GROUPS_HINT')!='')?ct('S_PARTICIPATE_GROUPS_HINT'):'');
		if (ctconf_get('memberNumber',true)) {
			$form->add_text(ct('S_PARTICIPATE_MEMBERID'),'form_memberID', $this->get('memberID'),20,20,'',ct('S_PARTICIPATE_MEMBERHINT'));
		}
	}

	// Events asf...
	function _get_form_part2(&$form,$timediscountID){
		global $session,$ctconf;
		$user =& $session->get_user();

		# Check if already an event was selected: Don't use default values any more...
		$eventselected=false;
		$events = ct_show_events();
		foreach ($events as $e){
			# If any event was selected, dont use the default values any more!
			if($this->get('event_'.$e['ID'].'_number') ){
				$eventselected=true;
			}
		}

		// events ordered by eventtypes
		$form->add_separator(ct('S_PARTICIPATE_EVENT_SECTION'));
		$etypes = ct_show_eventtypes();
		foreach ($etypes as $etype){
			$form->add_subseparator(stripslashes($etype['title']),ct('S_PARTICIPATE_FEEINFO'));
			// Show all events
			$events = ct_show_events($etype['ID']);
			foreach ($events as $e){
				$eventinformation='';
				if ($e['eventdate']!=''){ $eventinformation=stripslashes(nl2br($e['eventdate']));}
				if (($e['eventdate']!='') && (($e['eventlocation']!='') || ($e['info']!='' ))){ $eventinformation.='<br>';}
				if ($e['eventlocation']!=''){  $eventinformation.=stripslashes(nl2br($e['eventlocation']));}
				if ($e['eventlocation']!='' && $e['info']!=''){ $eventinformation.='<br>';}
				if ($e['info']!=''){ $eventinformation.="<span class=\"normal9\">".stripslashes(nl2br($e['info']))."</span>";}
				if ($e['disable']=='true')
						$eventinformation = "<span class=\"negativeboldt10\">".ct('S_PARTICIPATE_EVENT_DISABLED')."</span><br>".$eventinformation;
				if ($user->is_admin() || $user->is_frontdesk()) {
					if ($e['style']=='hidden') {// hidden: add info for admin
						$eventinformation = "<span class=\"negativebold10\">".ct('S_ADMIN_TOOL_EVENTS_STYLE_HIDDEN')." ".ct('S_PARTICIPATE_EVENT_FORNORMAL')."</span><br>".$eventinformation;
					}
					if ($e['disable']=='true') { // disabled: add info for admin
						$eventinformation = "<span class=\"negativebold10\">".ct('S_PARTICIPATE_EVENT_UNAVAILABLE')." ".ct('S_PARTICIPATE_EVENT_FORNORMAL')."</span><br>".$eventinformation;
					}
				}

				$fee=$this->_calculate_singlefee($e['pricecategory'],$this->get('status'),$timediscountID);
				$fee=ct_currency_format($fee,true,false);  // Long format, HTML output.

				if (($e['disable']!='true' && $e['style']!='hidden') ||
						 $user->is_admin() || $user->is_frontdesk()){
					$number = array();
					for ($n=$e['minnumber'];$n <= $e['maxnumber']; $n++) {
						$number[] = array ($n,$n);
					}
					# Case 1: A selected choice-box that cannot be deselected as min and max is 1
					if ($e['minnumber']==1 && ($e['maxnumber']==1)){
						$form->add_radio_3col(stripslashes($e['title']),'form_event_'.$e['ID'].'_number', array(array('1','','')), '1', $fee,$eventinformation);
					}
					# Case 2: A radio button or a choice-box as 0 or 1 of this event can be selected
					else if ($e['minnumber']==0 && ($e['maxnumber']==1)) {
						$selected = 0;
						if($this->get('event_'.$e['ID'].'_number') != "" ){
							$selected = $this->get('event_'.$e['ID'].'_number');
						}
						else{
							if ($eventselected == false) {
								# select default if checkboxes or none was selected before...
								$selected = $e['defaultnumber']; # use default
							}
						}
						# only one of the events can be chosen!
						if ($etype['mode']=='exclusive') { // Normally all events have their independent settings
							$form->add_radio_3col(stripslashes($e['title']),'form_eventtype_'.$etype['ID'].'_number',array(array('form_event_'.$e['ID'].'_number','','')),($selected == 1) ? 'form_event_'.$e['ID'].'_number' : '',$fee,$eventinformation);
						} else { # chose any
							$form->add_check_3col(stripslashes($e['title']),array(array('form_event_'.$e['ID'].'_number','1','',$selected,'')),$fee,$eventinformation);
						}
					}
					# Case 3: 0 to n can be selected: Use a selectbox.
					else{
						if ($this->get('event_'.$e['ID'].'_number') != "") {
							# echo array($this->get('event_'.$e['ID'].'_number'));
							$selected = array($this->get('event_'.$e['ID'].'_number'));
						}
						else{
							$selected = array($e['defaultnumber']);
						}
						$form->add_select_3col(stripslashes($e['title']),'form_event_'.$e['ID'].'_number',1, $number, $selected, false, $eventinformation, $fee);
					}
				} elseif ($e['style']!='hidden') {
					#$form->add_label_3col(stripslashes($e[ctlx($e,'title')]), "<span class=\"negativebold10\">".ct('S_PARTICIPATE_EVENT_UNAVAILABLE')."</span><br>", $eventinformation, $fee, 'form_event_'.$e['ID'].'_number' );
					$form->add_label_3col(stripslashes($e[ctlx($e,'title')]), "<span class=\"negativebold10\">".ct('S_PARTICIPATE_EVENT_DISABLED')."</span><br>", $eventinformation, $fee, 'form_event_'.$e['ID'].'_number' );
				}
			}
		}
		### payment

		// JS-Code to disable credit card fields.
		$jscode  = "onClick='";
		$jscode .= "if (document.all || document.getElementById) {";
		$jscode .= " for (i = 0; i < document.participate.length; i++) {";
		$jscode .= "  var tempobj = document.participate.elements[i];";
		$jscode .= "  if (tempobj.name.indexOf(\"form_cc\")>=0) {";
		$jscode .= "   tempobj.disabled = true;";
		#$jscode .= "   tempobj.value = \"\";";
		$jscode .= "  }";
		$jscode .= " }";
		$jscode .= "}'";


		if ($ctconf['payment/enabled'] !== false) {
			$form->add_separator(ct('S_PARTICIPATE_PAYMENT_INTRO'));
			$payment_modes = array();
			// Free...
			if ($session->loggedin() && ($user->is_admin() || $user->is_assistant() || $user->is_frontdesk())) { // free registration possible?
				$payment_modes[] =  array('free','<span class="positivebold10">'.ct('S_PARTICIPATE_PAYMENT_FREE').'</span> <span class="negative10">('.ct('S_PARTICIPATE_PAYMENT_ONLYADMIN').')</span>','',$jscode);
			}
			// Via transfer
			if (ctconf_get('paymentTransfer') || $this->get('paymethod')=='transfer') { // Transfer enabled?
				$payment_modes[] = array('transfer',ct('S_PARTICIPATE_PAYMENT_TRANSFER'),'',$jscode);
			}
			// Cash
			if (ctconf_get('paymentCash') || $this->get('paymethod')=='cash') { // Cash enabled?
				$payment_modes[] = array('cash',ct('S_PARTICIPATE_PAYMENT_CASH'),'',$jscode);
			} elseif ($session->loggedin() && ($user->is_admin() || $user->is_assistant() || $user->is_frontdesk())) {
				$payment_modes[] = array('cash',ct('S_PARTICIPATE_PAYMENT_CASH').' <span class="negative10">('.ct('S_PARTICIPATE_PAYMENT_ONLYADMIN').')</span>','',$jscode);
			}
			// Cheque
			if (ctconf_get('paymentCheque') || $this->get('paymethod')=='cheque') { // Cheque enabled?
				$payment_modes[] = array('cheque',ct('S_PARTICIPATE_PAYMENT_CHEQUE'),'',$jscode);
			}
			// Creditcard
			if (ctconf_get('paymentCreditCard') || $this->get('paymethod')=='cc') { // Credit Card Enabled?
				// Code to enable the credit card form
				$jscode  = "onClick='";
				$jscode .= "if (document.all || document.getElementById) {";
				$jscode .= " for (i = 0; i < document.participate.length; i++) {";
				$jscode .= "  var tempobj = document.participate.elements[i];";
				$jscode .= "  if (tempobj.name.indexOf(\"form_cc\")>=0) {";
				$jscode .= "   tempobj.disabled = false;";
				$jscode .= "  }";
				$jscode .= " }";
				$jscode .= "}'";

				$payment_modes[] = array('cc',ct('S_PARTICIPATE_PAYMENT_CC'),'',$jscode);
			}
			// Show form
			$form->add_radio("*&nbsp;".ct('S_PARTICIPATE_PAYMETHOD'),'form_paymethod', $payment_modes ,$this->get('paymethod'));
			// Extra fields for credit card.
			if (ctconf_get('paymentCreditCard')) { // Credit Card enabled?

				$cctypes = array(array('',ct('S_PARTICIPATE_EVENT_PLEASESELECT')));
				$cctypes_array = ct_csv_explode(ctconf_get('paymentCreditCardTypes'));
				//foreach ($cctypes_array as $c) $cctypes[] = array($c,$c);
				//$form->add_select(ct('S_PARTICIPATE_CCTYPE'), 'form_cctype', 1, $cctypes, array($this->get('cctype')), true);
				//$form->add_text(ct('S_PARTICIPATE_CCHOLDER'),'form_ccholder',$this->get('ccholder'), 30, 30);
				//$form->add_text(ct('S_PARTICIPATE_CCNUMBER'),'form_ccnumber',$this->get('ccnumber'), 20, 20);
				// the ccvc MUST not be stored (it is not allowed by credit card companies)!
				//if (ctconf_get('paymentCreditCardUseCVC','0'))
				//	$form->add_text(ct('S_PARTICIPATE_CCVC'),'form_ccvc',$this->get('ccvc'), 4, 4, false, "<img src=\"images/ccvc.jpg\" align=\"right\">".ct('S_PARTICIPATE_CCVC_INFO'));
				//$form->add_date(ct('S_PARTICIPATE_CCEXPDATE'),'form_ccexpdate','',array($this->get('ccexpdate_year'),$this->get('ccexpdate_month')));
			}
		}

		### remark
		$form->add_separator(ct('S_PARTICIPATE_REMARK_SECTION'));
		$form->add_textarea(ct('S_PARTICIPATE_EXTERNALREMARK'),'form_externalremark',$this->get('externalremark'), 60,4);

	}

	function _get_form_adminpart(&$form){
		global $session;
		$user =& $session->get_user();
		if ($session->loggedin() && $user->is_admin()) {
			$form->add_separator(ct('S_USER_ADMINSECTION'));

			$form->add_text(ct('S_INVOICE_INVOICENUMBER'), 'form_invoiceNo', $this->get('invoiceNo'), 16, 32);

			($this->get('invoice')=='true') ? $sel='true' : $sel='';
			$invoicelink="<a target=_blank href=\"".ct_pageurl('adminParticipantsInvoice', array('form_userID' => $this->pdata['personID'],'print' =>'yes'))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_INVOICE')."</a>";
			$form->add_check(ct('S_PARTICIPATE_INVOICE'),array(array('form_invoice','true',ct('S_PARTICIPATE_INVOICE_SEND'),$sel,$invoicelink)));
			if ($this->pdata['invoicedate']=='0000-00-00 00:00:00'){
				$invoicedate_day=date("d",mktime());
				$invoicedate_month=date("m",mktime());
				$invoicedate_year=date("Y",mktime());
			}
			else{
				$invoicedate_day=$this->get('invoicedate_day');
				$invoicedate_month=$this->get('invoicedate_month');
				$invoicedate_year=$this->get('invoicedate_year');
			}
			$form->add_date(ct('S_PARTICIPATE_INVOICEDATE'),'form_invoicedate','true',array($invoicedate_year,$invoicedate_month,$invoicedate_day));

			#echo $this->pdata['paydate'];
			if ($this->pdata['paydate']=='0000-00-00 00:00:00' ||
					$this->pdata['paydate']=='2006-01-01 00:00:00'||
					$this->pdata['paydate']=='2007-01-01 00:00:00'||
					$this->pdata['paydate']=='2008-01-01 00:00:00'||
					$this->pdata['paydate']=='2009-01-01 00:00:00'||
					$this->pdata['paydate']=='2010-01-01 00:00:00'||
					$this->pdata['paydate']=='2011-01-01 00:00:00'){  // Payment status is not stored as this was often redundant...
				$selpaid='';
			} else {
				$selpaid='true';
			}
			$form->add_spacer();

			$form->add_check(ct('S_PARTICIPATE_TOTALFEE'),array(array('form_paid','true',ct('S_PARTICIPATE_PAID'),$selpaid,'')));

			if ($this->get('payamount')!='0.00'){
				$payamount=$this->get('payamount');
			}
			else {
				$this->_calculate_fee();
				$payamount=$this->fee['total'];
			}

			$form->add_text(ct('S_PARTICIPATE_PAYAMOUNT'),'form_payamount', $payamount,20,20,'','');
			if ($this->pdata['paydate']=='0000-00-00 00:00:00'){
				$paydate_day=date("d",mktime());
				$paydate_month=date("m",mktime());
				$paydate_year=date("Y",mktime());
			}
			else{
				$paydate_day=$this->get('paydate_day');
				$paydate_month=$this->get('paydate_month');
				$paydate_year=$this->get('paydate_year');

			}
			$form->add_date(ct('S_PARTICIPATE_PAYDATE'),'form_paydate','true',array($paydate_year,$paydate_month,$paydate_day));

			$form->add_spacer();

			$form->add_textarea(ct('S_USER_INTERNALREMARK'), 'form_internalremark', $this->get('internalremark'), 50, 6);
		}
	}


	function _get_form_frontdeskpart(&$form){
		global $session;
		$user =& $session->get_user();
		if ($session->loggedin() && ($user->is_admin() || $user->is_frontdesk())) {
			$form->add_separator(ct('S_USER_FRONTDESKSECTION'));
			$form->add_check(ct('S_USER_FRONTDESK'), array(
					array( 'form_frontdesk_arrived', 'arrived', ct('S_USER_FRONTDESK_ARRIVED'), $this->is_arrived()),
					array( 'form_frontdesk_confirmed', 'confirmed', ct('S_USER_FRONTDESK_CONFIRMED'), $this->is_confirmed()),
					array( 'form_frontdesk_onlist', 'onlist', ct('S_USER_FRONTDESK_ONLIST'), $this->is_onlist()) ));
		}
	}
# shows part one of the participantform.
	function show_form_step1($action, $errors=array()) {
		$form = new CTForm($action, 'post', $errors);
		$form->width='90%';
		$form->align='center';
		$this->_get_form_part1($form);
		$form->add_submit('cmd_send_status', ct('S_BUTTON_SEND_STATUS'));
		$form->show();
	}

# shows part two of the participantform.
	function show_form_step2($action, $errors=array()) {
		global $http;
		$regdate = date("Y-m-d H:i:s");
		$timediscountID=$this->_get_timediscountID($regdate);
		$timediscountTitle=$this->_get_timediscount($timediscountID);
		echo "<p class=\"standard\">";
		echo ct('S_PARTICIPATE_TIMEDISCOUNTINTRO1')." <b>".stripslashes($timediscountTitle)."</b>".ct('S_PARTICIPATE_TIMEDISCOUNTINTRO2');
		echo "</p>\n";
		$form = new CTForm($action, 'post', $errors);
		$form->width='95%';
		$form->align='center';
		$form->formname='participate';
		$this->_get_form_part2($form,$timediscountID);
		$form->add_hidden(array(array('form_timediscountID', $timediscountID),array('form_status', $http['form_status']), array('form_memberID',$http['form_memberID']), array('form_regdate',$regdate)));
		$form->add_submit_not_bottom('cmd_send_participantdata', ct('S_PARTICIPATE_SEND_PARTICIPANTDATA'), true);
		$form->add_submit('cmd_go_status', ct('S_BUTTON_BACK_TO_STATUS'));
		$form->show();
	}


# shows part three of the participantform: save the data.
	function show_form_step3($action, $errors=array()) {
		global $http;

		$form = new CTForm($action, 'post', $errors);
		$form->width='90%';
		$form->align='center';
		$form->waitmessage=true;
		$form->warningmessage_always=true;

		$form->add_hidden(array(array('form_status', $http['form_status']), array('form_paymethod',$http['form_paymethod']), array('form_cctype',$http['form_cctype']), array('form_ccnumber',$http['form_ccnumber']), array('form_ccvc',$http['form_ccvc']), array('form_ccholder',$http['form_ccholder']), array('form_ccexpdate_year',$http['form_ccexpdate_year']), array('form_ccexpdate_month',$http['form_ccexpdate_month']), array('form_externalremark',htmlspecialchars($http['form_externalremark'])), array('form_memberID',$http['form_memberID']), array('form_regdate',$http['form_regdate'])));
		$events=ct_list_events();
		foreach ($events as $e){
			if (isset($http['form_event_'.$e[0].'_number']) && $http['form_event_'.$e[0].'_number']!=0){
				$form->add_hidden(array(array('form_event_'.$e[0].'_number', $http['form_event_'.$e[0].'_number'])));
			}
		}
		if (ctconf_get('registrationTerms',ctconf_get('participation/confirmation'))) {
			$form->add_separator(ct('S_PARTICIPATION_TERMS_SECTION'));
			$form->add_label(ct('S_PARTICIPATION_TERMS_TITLE'),ct('S_PARTICIPATION_REGISTRATIONTERMS'));
			$form->add_check("* ".ct('S_PARTICIPATION_CONFIRM_TITLE'),array(array('form_participant_confirmation','1',ct('S_PARTICIPATION_CONFIRM'),0)));
		} else {
			$form->add_hidden(array(array('form_participant_confirmation', '1')));
		}

		$form->add_submit_not_bottom('cmd_save_participant', ct('S_PARTICIPATE_CREATECMD'), true);
		$form->add_submit('cmd_go_status', ct('S_BUTTON_BACK_TO_STATUS'));
		$form->add_submit('cmd_go_participantdata', ct('S_BUTTON_BACK_TO_PARTICIPANTDATA'));


		$form->show();
	}

# show form for admin!
	function show_form_adminpart($action, $errors) {
		$this->person->show_shortinfo('99%');

		$regdate = $this->pdata['regdate'];
		$timediscountID=$this->_get_timediscountID($regdate);
		$timediscountTitle=$this->_get_timediscount($timediscountID);

		$form = new CTForm($action, 'post', $errors);
		$form->width='99%';
		$form->align='center';

		$this->_get_form_part1($form);
		#also display registration date!
		$form->add_datetime(ct('S_PARTICIPATE_REGDATE'),'form_regdate', true, explode(" ",str_replace("-"," ", str_replace(":"," ", $this->get('regdate') ) ) ),ct('S_PARTICIPATE_REGDATEHINT'));
		$form->add_label('',ct('S_PARTICIPATE_TIMEDISCOUNTINTRO3')." <b>".$timediscountTitle."</b> ".ct('S_PARTICIPATE_TIMEDISCOUNTINTRO4'));
		$form->add_submit_not_bottom('cmd_update_participant', ct('S_PARTICIPANT_UPDATE'));
		$this->_get_form_part2($form,$timediscountID);
		$this->_get_form_frontdeskpart($form);
		$this->_get_form_adminpart($form);
		$form->add_hidden(array(array('form_personID', $this->pdata['personID']),(array('form_regdate', $this->pdata['regdate']))));
		$form->add_submit('cmd_save_participant', ct('S_PARTICIPANT_SUBMIT'));
		$form->show();
	}

# show form for frontdesk!
	function show_form_frontdesk($action, $errors) {
		$form = new CTForm($action, 'post', $errors);
		$form->width='90%';
		$form->align='center';
		$form->add_hidden(array(array('form_personID', $this->pdata['personID'])));
		$this->_get_form_frontdeskpart($form);
		$form->add_submit('cmd_save_participant', ct('S_PARTICIPANT_SUBMIT'));
		$form->show();
	}

##### show invoice ################
	function show_invoice_data($width, $align){
		global $ctconf;

		$timediscountID=$this->_get_timediscountID($this->pdata['regdate']);
		#$timediscountTitle=$this->_get_timediscount($timediscountID);
		$today=date("d.m.Y",mktime());

		#$status=$this->_get_status($this->pdata['status']);

		// receiver
		echo "<table border=0 width=\"$width\">\n";
		echo "<tr><td>";
		echo $this->_show_addressee();
		echo "</td>\n";

		// sender and logo
		echo "<td align=right><img src='logo-invoice.gif' border=0>\n";
		echo "<span class=\"normal11\"><br>".ctconf_get('invoiceSender')."<br>";
		if (ctconf_get('invoiceTaxNo')!='')
			echo ct('S_INVOICE_TAXNO', array(ctconf_get('invoiceTaxNo')))."<br>";
		echo "</span><br>\n";
		echo "<span class=\"normal10\">";
		echo ctconf_get('invoiceSenderCity').", ".$today;
		echo "</span></td>";
		echo "</tr>\n";

		// head
		echo "<tr><td colspan=2>".ct('S_INVOICE_REFERENCENUMBER').": ".$this->person->pdata['ID']."<br>";
		if ($this->get('invoiceNo')!='') // Only if it exists...
			echo "<span class=\"fontbold font10\">".ct('S_INVOICE_INVOICENUMBER').": ".$this->get_special('invoiceNo')."</span><br>\n";
		echo "<br></td></tr>\n";

		// Title...
		echo "<tr><td colspan=2><h3>";
		if ($this->get('total')==0)  // No total amount: This is only a registration confirmation, no invoice!
			echo ct('S_INVOICE_CONFIRMATIONONLY_TITLE');
		elseif ($this->get('total')<0)  // This is a credit note.
			echo ct('S_INVOICE_CREDITNOTE_TITLE');
		elseif ($this->get('payamount')<$this->get('total') && ctconf_get('invoiceProforma','0'))  // Pro-Forma Invoice
			echo ct('S_INVOICE_PROFORMA_TITLE');
		else
			echo ct('S_INVOICE_TITLE');
		echo "</h3></td></tr>\n";

		echo "<tr><td colspan=2>";
		echo $this->get_invoice_form_of_address($this->person->get('gender'));
		echo " ".$this->person->get_special('firstname')." ".$this->person->get_special('name').",</td></tr>";
		echo "<tr><td colspan=2><br>".ct('S_INVOICE_TEXT1')." <b>".strip_tags(ctconf_get('conferenceName'))."</b>".ct('S_INVOICE_TEXT2')."</td></tr>\n";
		echo "<tr><td colspan=2><br>";
		echo "<table width=\"$width\" align=\"$align\" cellpadding=\"5\"  cellspacing=\"0\" border=\"1\" frame=\"void\">\n";
		echo "<tr class=\"listheader\">";
		echo "<td width=\"10%\" align=\"center\" valign=top>\n";
		echo "<span class=\"listheader_label\">".ct('S_PARTICIPATE_EVENTNUMBER')."</span>";
		echo "</td>\n";
		echo "<td width=\"35%\" align=left valign=top>\n";
		echo "<span class=\"listheader_label\">".ct('S_PARTICIPATE_EVENTS')."</span>";
		echo "</td>\n";
		if ($ctconf['payment/enabled'] !== false) {
			echo "<td width=\"15%\" align=\"right\" valign=top>\n";
			echo "<span class=\"listheader_label\">".ct('S_PARTICIPATE_EVENTFEE')."</span>";
			echo "</td>\n";
			echo "<td width=\"20%\" align=\"right\" valign=top>\n";
			echo "<span class=\"listheader_label\">".ct('S_PARTICIPATE_EVENTTOTALFEE')."</span>";
			echo "</td>\n";
			if (isset($ctconf['participation/vat']) && $ctconf['participation/vat']) {
				echo "<td width=\"20%\" align=\"right\" valign=top>\n";
				echo "<span class=\"listheader_label\">".ct('S_PARTICIPATE_VAT')."</span>";
				echo "</td>";
			}
		}
		echo "</tr>\n";

		$totalfee=0.00;
		$totalvat=0.00;
  		$etypes = ct_show_eventtypes();
        	foreach ($etypes as $etype){
           		$events = ct_show_events($etype['ID']);
           		foreach ($events as $e){
				if (isset($this->eventdata[$e['ID']]) && $this->eventdata[$e['ID']]!=0){
					$singlefee=$this->_calculate_singlefee($e['pricecategory'],$this->pdata['status'],$timediscountID);
					$eventTotal=$this->eventdata[$e['ID']]*$singlefee;
					$vat=$this->_get_vat($e['vat']);
					$totalfee+=$eventTotal;
					$add_vat=$eventTotal-($eventTotal/(($vat/100)+1));
					$totalvat+=$add_vat;
					$singlefee=ct_number_format($singlefee);
					$eventTotal=ct_number_format($eventTotal);
					$vat=ct_number_format($vat);

					echo "<tr>";
					echo "<td width=\"10%\" align=center valign=top>\n";
					echo "<span class=\"normal10\">".$this->eventdata[$e['ID']]."</span></td>\n";
					echo "<td width=\"35%\" align=left valign=top>\n";
					echo "<span class=\"normal8\">".stripslashes($etype['title'])."</span>:<br><span class=\"bold10\">".stripslashes($e['title'])."</span><br>\n";
					echo "<span class=\"normal8\">".stripslashes($e['eventdate']).'<br>'.stripslashes($e['eventlocation'])."</span></td>\n";
					if ($ctconf['payment/enabled'] !== false) {
						echo "<td width=\"15%\" align=right valign=top>\n";
						echo "<span class=\"normal10\">".ct_currency_format($singlefee, true, false)."</span></td>\n";
						echo "<td width=\"20%\" align=right valign=top>\n";
						echo "<span class=\"normal10\">".ct_currency_format($eventTotal, true, false)."</span></td>\n";
						if (isset($ctconf['participation/vat']) && $ctconf['participation/vat']) {
							echo "<td width=\"20%\" align=right valign=top>\n";
							echo "<span class=\"normal10\">".$vat." % </span></td>";
						}
					}
					echo "</tr>\n";
				}
			}
		}
		$totalnet=$totalfee-$totalvat;

		echo "<tr class=\"listheader\"><td width=\"60%\" align=\"right\" valign=top colspan=3>\n";
		echo "<span class=\"listheader_label\">".ct('S_PARTICIPATE_TOTALFEE')."</span>";
		echo "</td>\n";
		echo "<td width=\"20%\" align=\"right\" valign=top colspan=1>\n";
		echo "<span class=\"bold10\"> ".ct_currency_format($totalfee, true, false)."</span></td>";
		if (isset($ctconf['participation/vat']) && $ctconf['participation/vat']) {
			echo "<td width=\"20%\" align=\"right\" valign=top colspan=1>";
			echo "<span class=\"bold10\"> ".ct_currency_format($totalvat, true, false)."</span></td>";
		}
		echo "</tr>\n";
		echo "</table><br>";
		// sum
		if ($ctconf['payment/enabled'] !== false && $this->pdata['total']>0) {
			echo "</td></tr>";
			echo "<tr><td colspan=2>";
			echo ct('S_INVOICE_TOTAL1')." ".ct_currency_format($totalfee, true, false)." ".ct('S_INVOICE_TOTAL2')." ".ct_currency_format($totalnet, true, false)." ".ct('S_INVOICE_TOTAL3')." ".ct_currency_format($totalvat, true, false)." ".ct('S_INVOICE_TOTAL4')."<br><br>";
			echo "</td></tr>";
			if ($this->pdata['paymethod']=='transfer') {
				echo "<tr><td colspan=2>\n";
				echo ct('S_INVOICE_PAYMENT_TRANSFER')."<br>\n";;
				echo '<b>'.ct('S_INVOICE_BANK_DETAILS')."</b><br>\n";
				echo "&nbsp;&nbsp;".ct('S_INVOICE_ACCHOLDER').' <code>'.ctconf_get('paymentTransferAccountHolder')."&nbsp;/&nbsp;</code>\n";
				echo ct('S_INVOICE_ACCOUNTNO').' <code>'.ctconf_get('paymentTransferAccountNo')."</code><br>\n";
				echo "&nbsp;&nbsp;".ct('S_INVOICE_BANK_NAME').' <code>'.ctconf_get('paymentTransferBankName')."</code>\n";
				if (ctconf_get('paymentTransferBankCode')!='')
					echo "&nbsp;&nbsp;/&nbsp;&nbsp;".ct('S_INVOICE_BANK_CODE').' <code>'.ctconf_get('paymentTransferBankCode')."</code>\n";
				if (ctconf_get('paymentTransferSWIFT')!='' || ctconf_get('paymentTransferIBAN')!='') {
					echo '<br><b>'.ct('S_INVOICE_INTERNATIONAL_BANK_DETAILS')."</b>\n";
					if (ctconf_get('paymentTransferSWIFT')!='')
						echo "<br>&nbsp;&nbsp;".ct('S_INVOICE_SWIFTCODE').' <code>'.ctconf_get('paymentTransferSWIFT')."</code>";
					if (ctconf_get('paymentTransferIBAN')!='')
						echo "<br>&nbsp;&nbsp;".ct('S_INVOICE_IBAN_CODE').' <code>'.ctconf_get('paymentTransferIBAN')."</code>\n";
				}
				echo '<br><b>'.ct('S_INVOICE_TRANSFER_REASON').' '.ct('S_USER_ID')." ".$this->person->get('ID').", ".ctconf_get('conferenceShortName')."</b><br>\n";
				echo "</td></tr>\n\n";
			}
			if ($this->pdata['paymethod']=='cc'){
				echo "<tr><td colspan=2>".ct('S_INVOICE_PAYMENT_CC')."</td></tr>";
			}
			if ($this->pdata['paymethod']=='cash'){
				echo "<tr><td colspan=2>".ct('S_INVOICE_PAYMENT_CASH')."</td></tr>";
			}
			if ($this->pdata['paymethod']=='cheque') {
				echo "<tr><td colspan=2>";
				echo ct('S_INVOICE_PAYMENT_CHEQUE').'<br>';
				echo "<b>".ct('S_INVOICE_PAYMENT_CHEQUE_PAYABLETO').":</b> ";
				echo "<code>".ctconf_get('paymentChequePayableTo')."</code><br>\n";
				if (ctconf_get('paymentChequeReceiver','')!="") {
					echo "<b>".ct('S_INVOICE_PAYMENT_CHEQUE_SENDTO').":</b><br> ";
					echo ct_nl2br(ctconf_get('paymentChequeReceiver'),false)."\n";
				}
				echo "</td></tr>";
			}
		}
		echo "<tr><td colspan=2><br>".ct('S_INVOICE_TEXT3')."<br><strong>".ctconf_get('conferenceOrganizer')."</strong><br>(".ct('S_RECEIPT_LOCAL_ORGANIZER').")\n";
		echo "</td></tr>\n";
		echo "</table>";
	}

	function show_receipt_data($width="100%", $align="center"){
		$this->_calculate_fee();

		$today=date("d.m.Y",mktime());

		echo "<table border=0 width='$width' align='$align'><tr><td>";
		echo $this->_show_addressee();
		echo"</td><td align=right><img src='logo-invoice.gif' border=0><br><span class=normal10>".ctconf_get('invoiceSender')."<br><br>".ctconf_get('conferenceCity').", ".$today."</span></td></tr>";
		echo "<tr><td colspan=2>".ct('S_RECEIPT_REFERENCENUMBER').": ".$this->person->pdata['ID']."<br><br></td></tr>";
		echo "<tr><td colspan=2><h3>".ct('S_RECEIPT_TITLE')." ".strip_tags(ctconf_get('conferenceName'))."</h3><br><br></td></tr>";
		echo "<tr><td colspan=2>";
		echo $this->get_invoice_form_of_address($this->person->get('gender'));
		echo " ".$this->person->get_special('firstname')." ".$this->person->get_special('name')."<br><br>";
		echo ct('S_RECEIPT_TEXT1');
		echo " ".ct_currency_format($this->fee['total'], true, false)." ";
		echo ct('S_RECEIPT_TEXT2')."</td></tr>";
		echo "<tr><td colspan=2><br><br>".ct('S_RECEIPT_TEXT3')."<br><br><br><br></td></tr>";

		echo "<tr><td colspan=2><hr noshade></td></tr>";
		echo "<tr><td colspan=2><br><br><H3>".ct('S_RECEIPT_TEXT4')."</H3><br>";
		echo ct('S_RECEIPT_TEXT5')." ".strip_tags(ctconf_get('conferenceName'))."<br><br><br>";
		echo ct('S_RECEIPT_TEXT6')."<br><br><br></td></tr>";

		echo "<tr><td colspan=2>".ctconf_get('conferenceCity').'<br>'.ct('S_RECEIPT_SIGNSPACE')."<br><br><br></td></tr>";

		echo "</table>";
	}

	/**
	 * shows a row of participant data in the list of participants.
	 *
	 * @param string $class css class for tr.
	 * @param int $personID person ID (UNUSED!)
	 * @param int $i row number
	 * @param int $event event ID to be shown
	 * @param string $page name of page.
	 */
	function show_row($class,$personID,$i,$event=0,$page="") {
		global $db,$session;
		$total=ct_number_format($this->pdata['total']);
	   	$payamount=ct_number_format($this->pdata['payamount']);

		if ($this->pdata['deleted'] != '0' || $this->pdata['deleted'] != '0') {
			echo "<tr class=\"".$class."_del\">\n";
		} else {
			echo "<tr class=\"$class\">\n";
		}
		echo "<td align=right valign=top><span class=\"normal10\">".($i+1)."</span></td>\n";
		if ($this->is_arrived()) # if participant has arrived, show id in green
			echo "<td align=right valign=top class=\"positivebg\"><span class=\"lightbold10\">".$this->person->get_id()."</span></td>\n";
		else
			echo "<td align=right valign=top><span class=\"bold10\">".$this->person->get_id()."</span></td>\n";
		echo "<td align=left valign=top><span class=\"bold9\">";
		echo "<a href=\"".ct_pageurl('adminParticipantsDetails')."&form_id=".$this->person->get_id()."\">";
		echo ct_form_encode($this->person->get_reversename())."</a></span>";
		if ($this->get('externalremark') != "" || $this->get('internalremark') != "") {
        	echo ' <a class="fontlabel" href="'.ct_pageurl('adminParticipantsDetails', array('form_id'=>$this->person->get_id())).'" ';
        	echo 'title="'.ct('S_PARTICIPATE_DETAILED_REMARKSECTION').'" ';
			if (ct_strlen($this->get('externalremark'))>1) {
				echo 'ext_title="'.ct('S_PARTICIPATE_EXTERNALREMARK').'" ' ;
				echo 'ext_remark="'.ct_substr($this->get_special('externalremark'),0,512).'" ' ;
			}
			if (ct_strlen($this->get('internalremark'))>1) {
				echo 'int_title="'.ct('S_PARTICIPATE_INTERNALREMARK').'" ' ;
				echo 'int_remark="'.ct_substr($this->get_special('internalremark'),0,512).'" ' ;
			}
	       	echo '>';
        	echo '<img src="'.ct_getbaseurl().'images/remark.png" border="0" alt="">'; // alt="Remark" // annoying tooltip in IE!
        	echo '</a>';
		}
		echo "<br>";
		echo "<span class=\"normal8\"><a href=\"mailto:".$this->person->get_special('email')."\">";
		echo $this->person->get_special('email')."</a></span>";
  		echo "</td>\n";
		echo "<td align=left valign=top>";
		echo "<span class=\"normal9\">".$this->person->get_special('organisation')."</span><br>";
		ct_load_lib("address.lib");
		echo "<span class=\"normal8\">";
		if (ct_strlen($this->person->get('country'))==2) echo $this->person->get_special('country').", ";
		echo ct_get_country($this->person->get_special('country'))."</span>";
		echo "</td>\n";
		echo "<td align=middle valign=top><span class=\"";
		if ($this->pdata['paymethod']=="cash")
			echo "negativebold9";
		elseif ($this->pdata['paymethod']=="free")
			echo "positivebold9";
		else
			echo "normal9";
		echo "\">".$this->pdata['paymethod']."</span><br>";
		echo "<span class=\"normal8\">".ct_date_format($this->get('regdate'))."</span>";
		echo "</td>\n";
		echo "<td align=right valign=top><span class=\"normal9\">".$total."</span><br>";
		echo "<span class=\"normal8\">".ct_date_format($this->get('invoicedate'))."</span>";
		echo "</td>\n";
		echo "<td align=right valign=top><span class=\"";
		if ($this->pdata['payamount']==0 && $this->pdata['total']>0)
			echo "bold9";
		elseif ($this->pdata['payamount']==$this->pdata['total'] && $this->pdata['total']>0)
			echo "positivebold9";
		elseif ($this->pdata['payamount']!=$this->pdata['total'] && ($this->pdata['total']>0 || $this->pdata['payamount']>0))
			echo "negativebold9";
		else
			echo "normal9";
		echo "\">".$payamount."</span><br>";
		echo "<span class=\"normal8\">".ct_date_format($this->get('paydate'))."</span>";
		echo "</td>\n";
		echo "<td align=right valign=top>";

		if ($event==0) {
	        echo "<a class='normal8' target='_blank' href=\"".ct_pageurl('adminParticipantsInvoice', array('form_userID' => $this->person->get_id(),'print' =>'yes'))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_INVOICE')."</a>";
			echo "<br>";
        	echo "<a class='normal8' target='_blank' href=\"".ct_pageurl('adminParticipantsReceipt', array('form_userID' => $this->person->get_id(),'print' =>'yes'))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_RECEIPT')."</a>";
		} else {
			echo $this->eventdata[$event]."<br>";
			$r = $db->query("select pricecategory from events where ID='".$event."'");
			if ($r && ($db->num_rows($r) == 1)) {
				$p = $db->fetch($r);
				if ($p['pricecategory']>0) {
					echo "<span class=\"normal8\">".$this->eventdata[$event]*$this->_calculate_singlefee($p['pricecategory'],$this->get(status),$this->_get_timediscountID($this->pdata['regdate']))."</span>";
				}
			}
		}
		echo "</td>\n";
		echo "<td align=right valign=top><span class=\"normal9\">";

		$user =& $session->get_user();
		if ($page=="frontdesk") {
			echo "<b><a href=\"".ct_pageurl('frontdeskStatus', array('form_userID' => $this->person->get_id()))."\">";
			if (!$this->is_arrived()) {
				echo ct('S_ADMIN_PARTICIPANTS_ACTION_ARRIVAL')."</a></b><br>";
			} else {
				echo ct('S_ADMIN_PARTICIPANTS_ACTION_ISARRIVED')."</a></b><br>";
			}
		}

		if ($user->is_admin()) {
			if ($this->pdata['deleted'] == '0') {
		        echo "<a href=\"".ct_pageurl('adminParticipantsEditDelete', array('form_userID' => $this->person->get_id()))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_EDIT')."</a><br> ";
				if ($this->pdata['payamount']==0) {
		    	    echo "<span class=\"normal8\"><a href=\"".ct_pageurl('adminParticipantsEditDelete', array('form_delete_ID' => $this->person->get_id()))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_DELETE')."</a></span>";
				}
			} else {
	        	echo "<a href=\"".ct_pageurl('adminParticipantsEditDelete', array('form_userID' => $this->person->get_id()))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_REGISTER')."</a><br> ";
			}
		}
        echo "</span></td>\n";
		echo "</tr>\n";
	}

# shows participant data.
	function show_participant_data($person, $width, $align){
		global $session,$ctconf;

		#$this->_calculate_fee();
		$timediscountID=$this->_get_timediscountID($this->pdata['regdate']);
		$timediscountTitle=$this->_get_timediscount($timediscountID);

		$status=$this->_get_status($this->pdata['status']);

		$user =& $session->get_user();
		if ($session->loggedin() && ($user->is_admin() || $user->is_frontdesk())) {
	  		$person->show_mediuminfo('80%', 'center');
		}
		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=5>\n";
		echo "<span class=\"boldlabel10\">".ct('S_PARTICIPATE_PERSON_INTRO')."</span>\n";
		echo "</td></tr>\n";
		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=5>";
		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		echo "</td></tr>\n";

		echo "<tr class=\"evenrow\"><td width=\"100%\" align=left valign=top colspan=5>\n";
		echo "<span class=\"label10\">".ct('S_PARTICIPATE_REGDATE').":</span> <span class=\"normal10\">".ct_datetime_format($this->get('regdate',true))." (".$timediscountTitle.")</span><br>\n";
		echo "<span class=\"label10\">".ct('S_PARTICIPATE_GROUPS').":</span> <span class=\"normal10\">".$status."</span><br>\n";
		if (ctconf_get('memberNumber',true)) {
			echo "<span class=\"label10\">".ct('S_PARTICIPATE_MEMBERID').":</span> <span class=\"normal10\">\n";
			if ($this->pdata['memberID']) {echo $this->pdata['memberID'];}
			else {echo ct('S_PARTICIPATE_NOMEMBERID');}
			echo "</span><br>\n";
		}
		if ($this->get('invoiceNo')!='') // Only if it exists...
			echo "<span class=\"label10\">".ct('S_INVOICE_INVOICENUMBER').":</span> <span class=\"normal10\">".$this->get_special('invoiceNo')."</span><br>\n";
		if ($session->loggedin() && $user->is_admin()) {
			if ($this->is_arrived()) { echo "<span class=\"label10\">".ct('S_USER_FRONTDESK_ARRIVED')."</span><br>"; }
			if ($this->is_confirmed()) { echo "<span class=\"label10\">".ct('S_USER_FRONTDESK_CONFIRMED')."</span><br>"; }
			if ($this->is_onlist()) { echo "<span class=\"label10\">".ct('S_USER_FRONTDESK_ONLIST')."</span><br>"; }
		}
		echo "</td></tr>";
		echo "</table>\n";

		ct_vspacer('8');

		echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
		#echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=5>\n";
		#echo "<span class=\"boldlabel10\">".ct('S_PARTICIPATE_EVENTS')."</span>\n";
		#echo "</td></tr>\n";
		#echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=5>";
		#echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
		#echo "</td></tr>\n";
		echo "<tr class=\"oddrow\">\n";
		echo "<td width=\"10%\" align=center valign=top>\n";
		echo "<span class=\"bold10\">".ct('S_PARTICIPATE_EVENTNUMBER')."</span>";
		echo "</td>\n";
		echo "<td width=\"35%\" align=left valign=top>\n";
		echo "<span class=\"bold10\">".ct('S_PARTICIPATE_EVENTS')."</span>";
		echo "</td>\n";
		if ($ctconf['payment/enabled'] !== false) {
			echo "<td width=\"15%\" align=right valign=top>\n";
			echo "<span class=\"bold10\">".ct('S_PARTICIPATE_EVENTFEE')."</span>";
			echo "</td>\n";
			echo "<td width=\"20%\" align=right valign=top>\n";
			echo "<span class=\"bold10\">".ct('S_PARTICIPATE_EVENTTOTALFEE')."</span>";
			echo "</td>\n";
			if (isset($ctconf['participation/vat']) && $ctconf['participation/vat']) {
				echo "<td width=\"20%\" align=right valign=top>\n";
				echo "<span class=\"bold10\">".ct('S_PARTICIPATE_VAT')."</span>";
				echo "</td>";
			}
		}
		echo "</tr>\n";

		$totalfee=0.00;
		$totalvat=0.00;
  		$etypes = ct_show_eventtypes();
        	foreach ($etypes as $etype){
           		$events = ct_show_events($etype['ID']);
           		foreach ($events as $e){
				if (isset($this->eventdata[$e['ID']]) && $this->eventdata[$e['ID']]!=0){
					$singlefee=$this->_calculate_singlefee($e['pricecategory'],$this->pdata['status'],$timediscountID);
					$eventTotal=$this->eventdata[$e['ID']]*$singlefee;
					$vat=$this->_get_vat($e['vat']);
					$totalfee+=$eventTotal;
					$add_vat=$eventTotal-($eventTotal/(($vat/100)+1));
					$totalvat+=$add_vat;
					$singlefee=ct_number_format($singlefee);
					$eventTotal=ct_number_format($eventTotal);
					$vat=ct_number_format($vat);

					echo "<tr class=\"evenrow\">";
					echo "<td width=\"10%\" align=center valign=top>\n";
					echo "<span class=\"normal10\">".$this->eventdata[$e['ID']]."</span></td>\n";

					echo "<td width=\"35%\" align=left valign=top>\n";
					echo "<span class=\"normal8\">".$etype['title']."</span><br>";
					echo "<span class=\"bold10\">".$e['title']."</span>\n";
					if ($e['eventdate']!="")
						echo "<br><span class=\"fontbold font8\">".ct('S_ADMIN_TOOL_EVENTS_DATE').":</span><span class=\"fontnormal font8\"> ".stripslashes($e['eventdate'])."</span>\n";
					if ($e['eventlocation']!="")
						echo "<br><span class=\"fontbold font8\">".ct('S_ADMIN_TOOL_EVENTS_LOCATION').":</span><span class=\"fontnormal font8\"> ".stripslashes($e['eventlocation'])."</span>\n";
					echo "</td>\n";

					if ($ctconf['payment/enabled'] !== false) {
						echo "<td width=\"15%\" align=right valign=top>\n";
						echo "<span class=\"normal10\">".ct_currency_format($singlefee, true, false)."</span></td>\n";
						echo "<td width=\"20%\" align=right valign=top>\n";
						echo "<span class=\"normal10\">".ct_currency_format($eventTotal, true, false)."</span></td>\n";
						if (isset($ctconf['participation/vat']) && $ctconf['participation/vat']) {
							echo "<td width=\"20%\" align=right valign=top>\n";
							echo "<span class=\"normal10\">".$vat." % </span></td>\n";
						}
					}
					echo "</tr>\n";
				}
			}
		}

		if ($ctconf['payment/enabled'] !== false) {
			// $totalfee=ct_number_format($totalfee);
			//$totalvat=ct_number_format($totalvat);
			echo "<tr class=\"oddrow\">";
			echo "<td width=\"60%\" align=right valign=top colspan=3>\n";
			echo "<span class=\"bold10\">".ct('S_PARTICIPATE_TOTALFEE')."</span>";
			echo "</td>\n";
			echo "<td width=\"20%\" align=right valign=top colspan=1>\n";
			echo "<span class=\"bold10\">".ct_currency_format($totalfee, true, false)."</span></td>";
			if (isset($ctconf['participation/vat']) && $ctconf['participation/vat']) {
				echo "<td width=\"20%\" align=right valign=top colspan=1>\n";
				echo "<span class=\"normal10\">".ct_currency_format($totalvat, true, false)."</span>";
				echo "</td>";
			}
			echo "</tr>\n";
			echo "<br>\n";
			echo "</td></tr>\n";
		}
		echo "</table>\n";

		// Payment Details
		if ($ctconf['payment/enabled'] !== false) {
			ct_vspacer('8');
			echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=5>\n";
			echo "<span class=\"boldlabel10\">".ct('S_PARTICIPATE_PAYMENT_INTRO').":</span>\n";
			echo "</td></tr>\n";
			echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=5>";
			echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
			echo "</td></tr>\n";

			echo "<tr class=\"evenrow\"><td width=\"100%\" align=left valign=top colspan=5>\n";
			echo "<span class=\"label10\">".ct('S_PARTICIPATE_PAYMETHOD').":</span> <span class=\"normal10\">";
			if ($this->pdata['paymethod']=='transfer'){
				echo ct('S_PARTICIPATE_PAYMENT_TRANSFER');
			}
			if ($this->pdata['paymethod']=='cc'){
				echo ct('S_PARTICIPATE_PAYMENT_CC');
			}
			if ($this->pdata['paymethod']=='cash'){
				echo ct('S_PARTICIPATE_PAYMENT_CASH');
			}
			if ($this->pdata['paymethod']=='cheque'){
				echo ct('S_PARTICIPATE_PAYMENT_CHEQUE')."<br>";
				echo "<span class='label10'>".ct('S_INVOICE_PAYMENT_CHEQUE_PAYABLETO').":</span> ";
				echo "<code>".ctconf_get('paymentChequePayableTo')."</code><br>\n";
				if (ctconf_get('paymentChequeReceiver','')!="") {
					echo "<span class='label10'>".ct('S_INVOICE_PAYMENT_CHEQUE_SENDTO').":</span> ";
					echo str_replace(array("\n","  "," , ",", ,"),array(" "," ",", ",", "),str_replace("\n",", ",(ctconf_get('paymentChequeReceiver'))))."\n";
				}
			}
			if ($this->pdata['paymethod']=='free'){
				echo ct('S_PARTICIPATE_PAYMENT_FREE');
			}
			echo "</span><br>\n";
			if ($this->pdata['paymethod']=='cc'){
				echo "<span class=\"label10\">".ct('S_PARTICIPATE_PAYMENT_CCTYPE').":</span> <span class=\"normal10\">".$this->get('cctype')."</span><br>\n";
				if ($user->is_admin()) {
					echo "<span class=\"label10\">".ct('S_PARTICIPATE_PAYMENT_CCNUMBER').":</span> <span class=\"normal10\">".$this->get_special('ccnumber')."</span><br>\n";
					if (ctconf_get('paymentCreditCardUseCVC','0'))
						echo "<span class=\"label10\">".ct('S_PARTICIPATE_CCVC').":</span> <span class=\"normal10\">".$this->get_special('ccvc')."</span><br>\n";
				} else {
					echo "<span class=\"label10\">".ct('S_PARTICIPATE_PAYMENT_CCNUMBER').":</span> <span class=\"normal10\">".ct_substr($this->get_special('ccnumber'),0,2)."XX XXXX XXXX XX".ct_substr($this->get_special('ccnumber'),-2)."</span><br>\n";
					if (ctconf_get('paymentCreditCardUseCVC','0'))
						echo "<span class=\"label10\">".ct('S_PARTICIPATE_CCVC').":</span> <span class=\"normal10\">XXX</span><br>\n";
				}
				echo "<span class=\"label10\">".ct('S_PARTICIPATE_PAYMENT_CCHOLDER').":</span> <span class=\"normal10\">".$this->get_special('ccholder')."</span><br>\n";

			echo "<span class=\"label10\">".ct('S_PARTICIPATE_PAYMENT_CCEXPDATE').":</span> <span class=\"normal10\">".$this->get('ccexpdate')."</span><br>\n";
			}
	//		echo "<span class=\"label10\">".ct('S_PARTICIPATE_TOTALFEE').":</span> <span class=\"normal10\">".ct_currency_format($this->pdata['total'], true, false)."</span><br><br>\n";
			if ($user->is_admin()) {
				if ($this->pdata['invoice']=='true'){
					echo "<span class=\"label10\">".ct('S_PARTICIPATE_INVOICEDATE').":</span> <span class=\"normal10\">".ct_date_format($this->get('invoicedate'))."</span><br>\n";
				}
				else{
					echo "<span class=\"normal10\">".ct('S_PARTICIPATE_NOINVOICE')."</span><br>\n";
				}
			}
			if ($ctconf['payment/enabled'] !== false) {
				if (!isset($this->pdata['payamount']) || $this->pdata['payamount']=='00.00'){
					echo "<span class=\"normal10\">".ct('S_PARTICIPATE_NOT_PAID')."</span><br>\n";
				}
				else {
					$this->pdata['payamount']=ct_number_format($this->pdata['payamount']);
					echo "<span class=\"label10\">".ct('S_PARTICIPATE_PAYDATE').":</span> <span class=\"normal10\">".ct_date_format($this->get('paydate'))."</span><br>\n";
					echo "<span class=\"label10\">".ct('S_PARTICIPATE_PAYAMOUNT').":</span> <span class=\"normal10\">".ct_currency_format($this->get('payamount'), true, false)."</span><br>\n";
				}
			}
			echo "</td></tr>\n";
			echo "</table>";
		}

		ct_vspacer('8');

		// Remarks
		if ($this->get('externalremark')!='' || $this->get('internalremark')!='') {
			echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
			echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=5>\n";
			echo "<span class=\"boldlabel10\">".ct('S_PARTICIPATE_DETAILED_REMARKSECTION')."</span>\n";
			echo "</td></tr>\n";
			echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=5>";
			echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
			echo "</td></tr>\n";
			$user =& $session->get_user();
			if ($session->loggedin() && $user->is_admin()) {
				echo "<tr class=\"oddrow\"><td align=left valign=top colspan=2>\n";
				echo "<span class=\"label10\">".ct('S_USER_EXTERNALREMARK').":</span><br>\n";
				echo "<span class=\"normal10\">".ct_nl2br($this->get('externalremark'))."</span>\n";
				echo "</td><td align=left valign=top colspan=3>\n";
				echo "<span class=\"label10\">".ct('S_USER_INTERNALREMARK').":</span><br>\n";
				echo "<span class=\"normal10\">".ct_nl2br($this->get('internalremark'))."</span>\n";
				echo "&nbsp;</td></tr>";
			} else {
				echo "<tr class=\"oddrow\"><td align=left valign=top colspan=5>\n";
				echo "<span class=\"label10\">".ct('S_USER_EXTERNALREMARK').":</span><br>\n";
				echo "<span class=\"normal10\">".ct_nl2br($this->get('externalremark'))."</span>\n";
				echo "</td></tr>\n";
			}
			echo "</table>";
		}

		if ($session->loggedin() && $user->is_admin()) {
			echo "<table width=\"$width\" align=\"$align\" class=\"infoview_table\">\n";
       		echo "<tr class=\"lightbg\"><td align=\"left\" valign=\"top\" colspan=5>\n";
       		echo "<span class=\"boldlabel10\">".ct('S_ADMIN_PARTICIPANTS_COMMANDSSECTION')."</span>\n";
      		echo "</td></tr>\n";
       		echo "<tr class=\"infoview_sep\"><td align=\"left\" valign=\"top\" colspan=5>";
       		echo "<img src=\"images/spacer.gif\" width=1 height=1 border=0>";
       		echo "</td></tr>\n";
      		echo "<tr class=\"mediumbg\"><td colspan=5 align=left valign=top>\n";
       		echo "<span class=\"bold10\">\n";
       		echo "<a href=\"".ct_pageurl('adminParticipantsEditDelete', array('form_userID' => $this->pdata['personID']))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_EDIT')."</a> &middot; ";
       		echo "<a href=\"".ct_pageurl('adminParticipantsEditDelete', array('form_delete_ID' => $this->pdata['personID']))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_DELETE')."</a> &middot; ";
       		echo "<a href=\"".ct_pageurl('adminUsersLoginAs', array('form_id' => $this->pdata['personID']))."\">".ct('S_ADMIN_USERS_ACTION_LOGINAS')."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
      		echo "<a target='_blank' href=\"".ct_pageurl('adminParticipantsInvoice', array('form_userID' => $this->pdata['personID'],'print' =>'yes' ))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_INVOICE')."</a> &middot; ";
      		echo "<a target='_blank' href=\"".ct_pageurl('adminParticipantsReceipt', array('form_userID' => $this->pdata['personID'],'print' =>'yes' ))."\">".ct('S_ADMIN_PARTICIPANTS_ACTION_RECEIPT')."</a>";
       		echo "</span>\n</td></tr>\n";
			echo "</table>";
        }
	}
}
?>