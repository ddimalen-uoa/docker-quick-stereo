<?php
//
// general mail function
//
if (!defined('CONFTOOL')) die('Hacking attempt!');
/**
 * Function to send emails form ConfTool. It uses PHPMailer (preferred) or the internal
 * mail function if access to an SMTP server is not possible.
 *
 * @param string $mail_receiver email address of receiver of this mail.
 * @param string $mail_subject 	string with subject of mail.
 * @param string $mail_content 	string with content of mail. Plain text is default, the
 *  							"charset" of this conftool installation is used.
 * 								HTML can be used if "type" is set to "html".
 * @param string $mail_from 	email of sender. Default is ctconf_get("conferenceSenderMail").
 * @param string $mail_fromname name of sender. Default is ctconf_get("conferenceSenderName").
 * @param string $mail_replyto	reply-to address. Default is empty, use "-" for default address.
 * @param array $mail_cc		Array with email addresses for CCs.
 * @param array $mail_bcc		Array with email addresses for BCCs. if set to boolean "false"
 * 								no BCC will be sent to the default $ctconf['mail/bcc'];
 * @return boolean				was sending this mail successfull?
 */
function ct_mail($mail_receiver, $mail_subject, $mail_content,
					$mail_from="", $mail_fromname='-', $mail_replyto="-",
					$mail_cc=array(),$mail_bcc=array()){
	global $ctconf;

	// Test if the receiver is really a valid email address...
	if (!ct_validate_email($mail_receiver, false)) {
        ct_error_log("Mailer Warning: Illegal e-mail address <$mail_receiver>");
		return false;
	}

	// PHP Mailer is default
	if (!isset($ctconf['mail/phpmailer']) || $ctconf['mail/phpmailer']) {
		ct_load_lib('phpmailer/class.phpmailer');
		$mail = new PHPMailer();

	    $mail->IsSMTP();       // set mailer to use SMTP
		// Set smtp server with "ssl" or "tls" for secure connection
		if (is_string(ctconf_get('mail/smtpsecure')) &&
				in_array(ctconf_get('mail/smtpsecure'),array('ssl','sslv2','sslv3','tls')))
			$mail->Host = ctconf_get('mail/smtpsecure').'://'.ctconf_get('mail/smtphost');
		else
			$mail->Host = ctconf_get('mail/smtphost');
		// Set SMTP port
		if (ctconf_get('mail/smtpport')) $mail->Port = ctconf_get('mail/smtpport');
		// Set Hostname for message ID etc.
		if (ctconf_get('mail/hostname')) $mail->Hostname = ctconf_get('mail/hostname');

	    $mail->SMTPAuth = ctconf_get('mail/SMTPAuth');  // SMTP authentication req.?
		$mail->Username = ctconf_get('mail/username');  // SMTP username
		$mail->Password = ctconf_get('mail/password');  // SMTP password

		// Receiver
		$mail->AddAddress(trim($mail_receiver));

	    // Sender
	    if (trim($mail_from)=="") {
	    	$mail_from = ctconf_get('conferenceSenderEmail');
	    }
	    $mail->From = trim($mail_from);

	    // Name of sender
	    $mail->FromName = '';
	    if ($mail_fromname!='-' && $mail_fromname!='') {
		    $mail->FromName = $mail_fromname;
	    } elseif ($mail_from==ctconf_get('conferenceSenderEmail')) {
		    if (strip_tags(ctconf_get('conferenceSenderName'))!='')
			    $mail->FromName = strip_tags(ctconf_get('conferenceSenderName'));
	    }

		// Reply-to email
	    if (is_array($mail_replyto) && ct_validate_email($mail_replyto[0],false))	// An array was given: 1. email, 2. Name
	    	$mail->AddReplyTo($mail_replyto[0],$mail_replyto[1]);
	    elseif (is_string($mail_replyto) && ct_validate_email($mail_replyto,false))	// Only Email was given.
	    	$mail->AddReplyTo($mail_replyto);
	    elseif (is_string($mail_replyto) && ($mail_replyto=='-' || $mail_replyto=='')) {
	    	if ($mail_from==ctconf_get('conferenceSenderEmail') && ct_validate_email(ctconf_get('conferenceReplytoEmail',''),false)) // Use default replyto-mail
		    	$mail->AddReplyTo(trim(ctconf_get('conferenceReplytoEmail','')));
		    elseif (ct_validate_email(ctconf_get('mail/sender','')) )	// User sender as replyto mail, when "Sender" for spf is set...
		    	 $mail->AddReplyTo($mail_from);
	   	}

		// Add technical Sender - useful if SPF is used.
	    if (ctconf_get('mail/sender','') && ct_validate_email(ctconf_get('mail/sender',''),false)) {
	    	$mail->AddCustomHeader("Sender: ".trim(ctconf_get('mail/sender','')));
	    }

		// Add CCs if any given...
	    if (is_array($mail_cc)) {
	    	foreach ($mail_cc as $c) {
				if (ct_validate_email(stripslashes($c),false)) $mail->AddCC(trim(stripslashes($c)));
	    	}
	    }
		// Add BCCs if any given...
	    if (is_array($mail_bcc)) {
	    	foreach ($mail_bcc as $c) {
	    		if (ct_validate_email(stripslashes($c), false)) $mail->AddBCC(trim(stripslashes($c)));
	    	}
	    }
		// Add BCC to every mail if required and not supppressed by bcc=false.
		if ($mail_bcc!==false && isset($ctconf['mail/bcc']) && ct_validate_email($ctconf['mail/bcc'],false))
			$mail->AddBCC(trim($ctconf['mail/bcc']));

	    $mail->Subject = $mail_subject;

	    $mail->WordWrap = 78;
	    $mail->IsHTML( false );
		if (isset($ctconf['charset']))
			$mail->CharSet=$ctconf['charset'];

		$mail_content = stripslashes($mail_content);
	    $mail_content = htmlspecialchars_decode( $mail_content );
	    $mail_content = str_replace("&nbsp;", " ", $mail_content);
	    $mail_content = preg_replace('/(<br\s*\/?>)\r?\n?/i', "\n", $mail_content); // Do not double br if follwed by a newline.
	    $mail->Body   = $mail_content;

		// Test if there are any entities in the body text => Decode and send mail as UTF8
		if (preg_match('~&#x([0-9a-f]+);~ei', $mail_content) || preg_match('~&#([0-9]+);~e', $mail_content)) {
			ct_load_lib('conversion.lib');
			$mail_content =	ct_toUTF8($mail_content);
			$mail_content = ct_decode_entities_to_UTF8($mail_content);
			$mail->CharSet= 'UTF-8';
			$mail->Body   = $mail_content;
		}

	    $return = $mail->Send();

	    if(!$return) {
	        ct_error_log("Mailer error: " . $mail->ErrorInfo);
	        $_SESSION['last_mail_error']=$mail->ErrorInfo;
	   		#echo "Mailer Error: " . $mail->ErrorInfo;
	   		#ct_print_r("Header: ".$mail->CreateHeader()); #die();
		    // Try alternative host?
		    if (ctconf_get('mail/smtphost2')) {
		        ct_error_log("Trying alternative mail host: " . ctconf_get('mail/smtphost2'));
					if (is_string(ctconf_get('mail/smtpsecure2')) &&
							in_array(ctconf_get('mail/smtpsecure2'),array('ssl','sslv2','sslv3','tls')))
						$mail->Host = ctconf_get('mail/smtpsecure2').'://'.ctconf_get('mail/smtphost2');
					else
						$mail->Host = ctconf_get('mail/smtphost2');
				if (ctconf_get('mail/smtpport2')) $mail->Port = ctconf_get('mail/smtpport2'); else $mail->Port=25;
				if (ctconf_get('mail/hostname2')) $mail->Hostname = ctconf_get('mail/hostname2'); else $mail->Hostname='';
			    if (ctconf_get('mail/SMTPAuth2')) $mail->SMTPAuth = ctconf_get('mail/SMTPAuth2'); else $mail->SMTPAuth=false;
				if (ctconf_get('mail/username2')) $mail->Username = ctconf_get('mail/username2'); else $mail->Username='';
				if (ctconf_get('mail/password2')) $mail->Password = ctconf_get('mail/password2'); else $mail->Password='';
			    $return = $mail->Send(); // Try again!
	    		if(!$return) {
			        ct_error_log("Alternative mail host failed: " . $mail->ErrorInfo);
			        $_SESSION['last_mail_error']=$mail->ErrorInfo;
	    		}
		    }
		}
	    return $return;

	} else {
		// "Conventional" method to send mail using the build-in php mail function.
		// this sometimes caused problems, so I use phpmailer instead.
		if ($mail_from=="") $mail_from = trim(ctconf_get('conferenceSenderEmail'));

		// Reply-to email
	    if (is_array($mail_replyto) && ct_validate_email($mail_replyto[0],false))
	    	$mail_replyto=$mail_replyto[0];
	    elseif (is_string($mail_replyto) && ($mail_replyto=='-' || $mail_replyto=='')) {
	    	if ($mail_from==ctconf_get('conferenceSenderEmail') && ct_validate_email(ctconf_get('conferenceReplytoEmail',''),false)) // Use default replyto-mail
		    	$mail_replyto = trim(ctconf_get('conferenceReplytoEmail',''));
		    elseif (ct_validate_email(ctconf_get('mail/sender','')) )	// User sender as replyto mail, when "Sender" for spf is set...
		    	$mail_replyto = $mail_from;
	   	}

		$mailFromName = "";
	    if ($mail_fromname!='-' && $mail_fromname!='') {
		    $mailFromName = $mail_fromname;
	    } elseif ($mail_from==ctconf_get('conferenceSenderEmail')) {
		    if (strip_tags(ctconf_get('conferenceSenderName'))!='')
			    $mailFromName = strip_tags(ctconf_get('conferenceSenderName'));
	    }

	    // Set headers...
		$headers  = "";

	    if ($mailFromName!='')
			$headers .= "From: $mailFromName <$mail_from>\n";
		else
			$headers .= "From: $mail_from\n";

		// Add reply-to
		if (ct_validate_email($mail_replyto,false))
			$headers .= "Reply-To: $mail_replyto\n";

		// Add technical Sender - sometimes useful if SPF is used.
	    if (ctconf_get('mail/sender','') && ct_validate_email(ctconf_get('mail/sender',''),false))
			$headers .= "Sender: ".trim(ctconf_get('mail/sender',''));

		// Add CCs if any given...
	    if (is_array($mail_cc)) {
	    	foreach ($mail_cc as $c) {
				if (ct_validate_email($c,false)) $headers .= "Cc: ".$c."\n";
	    	}
	    }
		// Add BCCs if any given...
	    if (is_array($mail_bcc)) {
	    	foreach ($mail_bcc as $c) {
	    		if (ct_validate_email($c, false)) $headers .= "Bcc: ".$c."\n";
	    	}
	    }
		// Add BCC to every mail if required and not supppressed by bcc=false.
		if ($mail_bcc!==false && isset($ctconf['mail/bcc']) && ct_validate_email($ctconf['mail/bcc'],false))
			$headers .= "Bcc: ".$ctconf['mail/bcc']."\n";

		// Add mime type etc.
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Transfer_Encoding: 8bit\n";

		// Set Content-type header
		$charset='ISO-8859-1';
		if (isset($ctconf['charset'])) $charset=$ctconf['charset'];

		// Test if there are any entities in the body text => Decode and send mail as UTF8
		if ((preg_match('~&#x([0-9a-f]+);~ei', $mail_content) || preg_match('~&#([0-9]+);~e', $mail_content))) {
			ct_load_lib('conversion.lib');
			$mail_content =	ct_toUTF8($mail_content);
			$mail_content = ct_decode_entities_to_UTF8($mail_content);
			$charset	  = 'UTF-8';
		}
		$headers .= "Content-type: text/plain; charset=\"$charset\"\n";

		// Add name of software etc.
		$headers .= "X-Mailer: ConfTool ".$ctconf['version']." / PHP " .substr(phpversion(),0,3);

		return mail($mail_receiver, $mail_subject, $mail_content, $headers);
	}
}



/**
 * Send registration confirmation email
 *
 * @param CTPerson $person Person object to send...
 * @param boolean $send_password shall password be sent as well?
 * @return boolean Did the mail operation succeed?
 */
function ct_mail_user_registration_confirmation($person,$send_password=false,$extratext='') {
	ct_load_lib('mail.lib');
	global $session;

	$mail_content="";
	// Dear...
	$mail_content =ct_get_mail_greeting_fullname($person).",\n\n";

	$mail_content.=ct('S_USER_REG_EMAIL_CONTENT1')."\n\n";

	$mail_content.=ct_mail_get_conferencenameurl();

	// Username
	$mail_content.=ct('S_USER_REG_EMAIL_CONTENT4')." ".stripslashes($person->get('username'))."\n";
	if ($send_password)
		$mail_content.=ct('S_USER_REG_EMAIL_CONTENT5')." ".stripslashes($person->get('password'))."\n\n";

	$mail_content.="\n";

	if ($extratext!='')
		$mail_content.=$extratext."\n\n";

	$mail_content.=ct_br2nl(ct('S_USER_REG_EMAIL_REGARDS',array(strip_tags(ctconf_get('conferenceShortName')))))."\n";

	$mail_content.=ct_get_mail_signature();

	// Subject
	$subject=ctconf_get('conferenceShortName').': '.ct('S_USER_REG_EMAIL_SUBJECT')	;

	// send email now...
	$ret = ct_mail(stripslashes($person->get('email')),$subject,$mail_content,'','','','',$bcc);

	return $ret;
}



/**
 * Send email when paper was submitted or modified
 *
 * @param CTPaper $paperclass  object with details about paper
 * @param boolean $newpaper is it a new submission (true) or not (and update)
 * @return boolean was mail successfully sent?
 */
function ct_mail_author_submission_confirmation($paper,$newpaper=true) {
	ct_load_lib('mail.lib');
	global $session;

	$author=$paper->get_author();

	$mail_content =ct_get_mail_greeting_fullname($author).",\n\n";

	// body text
	if ($newpaper) {
		$mail_content.=ct('S_PAPER_EMAIL_NEW_BODY')."\n\n";
		// New PAPER: No reviewer was assigned, yet...
	} else {
		$mail_content.=ct('S_PAPER_EMAIL_UPDATED_BODY')."\n\n";
	}

	$mail_content.= ct('S_PAPER_EMAIL_DETAILS')."\n";
	$mail_content.= str_repeat("=",ct_strlen(ct('S_PAPER_EMAIL_DETAILS')))."\n";

	$maxlen = max(array_map('ct_strlen',array(ct('S_ID'),ct('S_PAPER_TYPE'),ct('S_PAPER_TITLE'),ct('S_PAPER_AUTHOR'))));
	if ($maxlen>50) $maxlen=50;
	$mail_content.= str_pad(ct('S_ID'),$maxlen).": ".$paper->get('ID')."\n";
	$mail_content.= str_pad(ct('S_PAPER_TITLE'),$maxlen).": ".stripslashes($paper->get('title'))."\n";
	$mail_content.= str_pad(ct('S_PAPER_AUTHOR'),$maxlen).": ".stripslashes(str_replace(array("\n","\r","  ")," ",$paper->get('author')))."\n";
	$mail_content.="\n";

	$mail_content.= ct('S_PAPER_EMAIL_UPLOADS')."\n";
	$mail_content.= str_repeat("=",ct_strlen(ct('S_PAPER_EMAIL_UPLOADS')))."\n";
	$originalname = stripslashes($paper->get('originalname'));
	if ($originalname=="") {
		$mail_content.= ct('S_INDEX_PAPER_NOUPLOAD')."\n\n";
	} else {
		$mail_content.= $originalname."\n";
		$mail_content.= ct('S_PAPER_LASTUPLOAD').': '.$paper->get('lastupload')."\n\n";
	}
	$mail_content.="\n";


	$mail_content.=ct_br2nl(ct('S_USER_REG_EMAIL_REGARDS',array(strip_tags(ctconf_get('conferenceShortName')))))."\n";
	$mail_content.=ct_get_mail_signature();

	// Subject
	if ($newpaper)
		$subject=ctconf_get('conferenceShortName').': '.ct('S_PAPER_EMAIL_NEW_SUBJECT',array($paper->get_id()))	;
	else
		$subject=ctconf_get('conferenceShortName').': '.ct('S_PAPER_EMAIL_UPDATED_SUBJECT',array($paper->get_id()));

	// send email now...
	$ret = ct_mail(stripslashes($author->get('email')),$subject,$mail_content);

	return $ret;
}


/**
 * ct_validate_email - validate a supplied e-mail-address by regular expression and dns check
 *
 * @param  $email String
 * @return true if the address is valid
 */
function ct_validate_email($email, $check_dns=true){
	// See http://en.wikipedia.org/wiki/E-mail_address
	$exp = '/^([a-z0-9]+([.!#$%*`~&\'+=_-]{0,3}[a-z0-9]+)*@([a-z0-9]+([._-]?[a-z0-9]{0,62}))+\.[a-z]{2,6})$/i';	    // Check syntax (by HW :-)
	if(preg_match($exp,stripslashes(trim($email)))){	// correct!	:-)
		if (ctconf_get('mail/checkdns')===false || $check_dns===false) return true; // Do NOT check DNS of mail-address
		$emailarray = explode("@",$email);		// These lines could be less complicated but there is an PHP-Error in some versions of array_pop.
		$domain     = array_pop($emailarray);  // Get domain
		// Additional sanity check
   		$domain = preg_replace('/\s/s', '', $domain);
   		if (strlen($domain)>3) {
	   		if(ct_checkdnsrr($domain,"MX")) return true; // Check MX record, works usually
	   		if(ct_checkdnsrr($domain,"CNAME")) return true; // also check CNAME and A, as MX is not obligatory
	   		if(ct_checkdnsrr($domain,"A")) return true; //
   		}
	}
	return false;
}


/**
 * ct_validate_uri - validate a supplied uri by regular expression
 *
 * URI can be HTTP and HTTPS
 * @param  $uri String
 * @return true if the address is valid
 */
function ct_validate_uri($uri) {
    return preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]){2,8}(:([0-9]){1,5})?(/.*)+$|i', $uri );
}

/**
 * Check domain	name - our own function. Needed	as windows behaves differently...
 */
function ct_checkdnsrr(	$host, $type=''	) {
	global $ct_starttime;
	$host = trim($host);
	$output = ''; $hashost='';

	// overall timeout of five seconds: After that don't check DNS any more (as some requests take very long!)
	if ( (ct_get_microtime()-(float)$ct_starttime) > 5 ) return true;

	// Test if a legal hostname was given...
	if (!empty(	$host ) && eregi('^([a-z0-9]+([._-]?[a-z0-9]{0,62}))+\.[a-z]{2,6}$',$host)) {
		$host = $host."."; // Add a DOT for DNS check.
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || !function_exists('checkdnsrr')) {
			// This must be windows...
			if ($type!="") $type = " -type=".$type;
			@exec( "nslookup $type $host", $output ); // use nslookup function...
			if (is_array($output)) {
				while (list($k, $line ) = each( $output )) {
				   if (eregi("^$host", $line ))	    return true; //	Valid records begin	with "hostname:"
				   if (eregi("unknown", strtolower($line) ))	return true; //	Computer seems to be off-line: Skip DNS test!
				   if (eregi("request timed out", strtolower($line) ))	return true; //	Computer seems to be off-line: Skip DNS test!
				}
			} else return true; // System call did not work.
		} else {
			// Unix machine...
			// "host" has a much faster timeout than the checkdnsrr function, try this first...
			if (function_exists('exec'))
				@exec( "which host", $hashost ); // Does host function exist? (unix...)
			if (is_array($hashost) && count($hashost)>0 && eregi("host$",$hashost[0])) {
				// Workaround:
				if ($type!="") $type = " -t	".$type;
				@exec( "host $type $host", $output ); // use host function (unix...)
				if (is_array($output)) {
					while (list($k,$line ) = each( $output )) {
					   if (eregi("^$host", $line ))	return true; //	Valid records begin	with "hostname"
					}
				} else return true;	// system call did not work
			} elseif (function_exists('checkdnsrr')) { //	Does php function exist? NOT on windows...
			    if (checkdnsrr($host,$type)) return true;  # This has a very long timeout. Might be annoying.
			}
		}
	}
	return false;
}

/**
 * Get the salutation part of an email like "Dear Prof...." or "Dear Mr...." - but without the name
 *
 * @param unknown_type $gender
 * @return unknown
 */
function ct_get_mail_salutation($gender,$person='') {
	$title = ct('S_USER_PART_EMAIL_ADDRESSES_N');
	switch ($gender) {
		case 1: $title = ct('S_USER_PART_EMAIL_ADDRESSES_M'); break;
		case 2: $title = ct('S_USER_PART_EMAIL_ADDRESSES_F'); break;
		case 3: $title = ct('S_USER_PART_EMAIL_ADDRESSES_DR'); break;
		case 4: $title = ct('S_USER_PART_EMAIL_ADDRESSES_PROF'); break;
	}

	if ($person=='')
		return trim(str_replace("  "," ",$title));
	else {
		if (ereg('%1',$title)===false) {
			return trim(str_replace("  "," ",$title.' '.$person));
		} else {
			return trim(str_replace("  "," ",ereg_replace('%1', $person, $title)));
		}
	}
}

/**
 * Get the greeting to start an email with for all Mails of Conftool
 *
 * @param CTPerson $person person object to send the mail to...
 */
function ct_get_mail_greeting_fullname($person) {
	return ct_get_mail_salutation($person->get('gender'),stripslashes($person->get('firstname').' '.$person->get('name')));
}


/**
 * @return string mail text with name and URL of conference.
 */
function ct_mail_get_conferencenameurl() {
	// Conference name
	$mail_content.=ct('S_USER_REG_EMAIL_CONTENT2')." ".strip_tags(ctconf_get('conferenceName'))."\n";
	// Conference URL
	$mail_content.=ct('S_USER_REG_EMAIL_CONTENT3')." ".ct_get_loginurl();
	#$mail_content.='&ctusername='.$person->get('username');

	$mail_content.="\n\n";

	return $mail_content;
}

/**
 * Create signature for ConfTool email
 *
 * @return text with signature.
 */
function ct_get_mail_signature() {
	$mail_signature='';
	#$mail_signature.="\n";
	#$mail_signature.=ctconf_get('conferenceSenderName');
	$mail_signature.="\n-- \n";
	$mail_signature.=strip_tags(ctconf_get('conferenceName'))."\n";
	$mail_signature.=ct_get_loginurl();
	return $mail_signature;
}

/**
 * get the login url of this conftool instalation
 *
 * @return text with login url of conftool installation for this conference
 */
function ct_get_loginurl() {
	global $session;
	$mail_signature.=ct_getbaseurl();
	return $mail_signature;
}

/**
 * Opposite function to nl2br - used for emails.
 * Replace <br>, <p> and <li> by a new line symbol 0D
 * Please note: "<br>\r\n" asf. will also be replaced by "\n"
 *
 * @param string $text
 * @return string with new lines instead of <br>s
 */
function br2nl($text) {
    return  preg_replace('/\r?\n?(<br\s*\/?>|<li\s*\/?>|<p\s*\/?>)\r?\n?/i', "\n", $text);
}


/**
 * replace_mail_patterns - for person and author mails
 *
 * @param  $mail_content String
 * @param  $person String
 * @return string the message with the replaced, personalized text
 */
function replace_mail_patterns_persons($mail_content,$person) {
	GLOBAL $session;
	$user=$session->get_user();

	$addressee=str_replace("  "," ",stripslashes(ct('S_USER_REG_EMAIL_ADDRESS')." ".stripslashes($person->get_fullname())));
	$mail_content=str_replace("{dear_fullname}",$addressee,$mail_content);

	$addressee=str_replace("  "," ",ct_get_mail_salutation($person->get('gender'),stripslashes($person->get('name'))));
	$mail_content=str_replace("{dear_form_name}",$addressee,$mail_content);

	$mail_content=str_replace("{person_id}",$person->get_id(),$mail_content);
	$mail_content=str_replace("{person_name}",stripslashes($person->get('name')),$mail_content);
	$mail_content=str_replace("{person_username}",stripslashes($person->get('username')),$mail_content);
	$passwordtext = stripslashes($person->get('password'));
	$mail_content=str_replace("{person_password}",$passwordtext,$mail_content);
	return $mail_content;
}


/**
 * replace_mail_patterns - for mails to authors only
 *
 * @param  $mail_content String
 * @param  $person String
 * @return string the message with the replaced, personalized text
 */
function replace_mail_patterns_papers($mail_content,$paper) {

	$mail_content=str_replace("{contribution_id}",$paper->get_id(),$mail_content);
	$mail_content=str_replace("{contribution_title}",stripslashes($paper->get('title')),$mail_content);
	$mail_content=str_replace("{contribution_type}",stripslashes($paper->_get_contributiontype_title()),$mail_content);
	$mail_content=str_replace("{contribution_status}",stripslashes($paper->get_acceptstatus()),$mail_content);

	return $mail_content;
}


?>