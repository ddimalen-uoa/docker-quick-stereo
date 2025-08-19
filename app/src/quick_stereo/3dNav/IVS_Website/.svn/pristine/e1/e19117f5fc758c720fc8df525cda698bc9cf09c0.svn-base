<?php

if (!defined('CONFTOOL')) die('Hacking attempt!');

/**
 * check if password is OK.
 *
 * @param string $password the password to be checked.
 * @param CTPerson $person a person object to compare the password data to.
 * @return array an array with the error messages. If the password is fine, the array is empty.
 */
function ct_validate_password($password="",$person=null) {
	global $ctconf;
    $dict = array('conftool','conferen',
    				'2009','2010','2011','2012','2013','2014',
    				'secret','geheim','unknown','test','tester','pass','passwor',
    				'0123','12345','11111','asdf','qwert','xxx',
					ctconf_get('conferenceName'),ctconf_get('conferenceShortName'),ctconf_get('conferenceURI'),ctconf_get('conferenceCity'));

	$word = ct_strtolower($password);
	$rword = strrev($word); // In fact this is not MB-Save, but it does no harm.
	$return = array();
	if (!isset($word) || ct_strlen($word)<2)
		return array(ct('S_ERROR_REGISTER_PASSWORD_TOO_SHORT'));	// Password missing or really silly. Just return it is too short, remaining tests are not necessary.
	if (ct_strlen($word)<5)
		$return[]=ct('S_ERROR_REGISTER_PASSWORD_TOO_SHORT');
	if (!(preg_match("/[a-z]/", $word) && preg_match("%\d+%", $word)))
		$return[]=ct('S_ERROR_REGISTER_PASSWORD_LETTERS_NUMBERS');

	// too many similar characters and less than 5 different characters
	$count_chars = count_chars($word,3);
    if(ct_strlen($count_chars) < (ct_strlen($password) / 2) && ct_strlen($count_chars)<5)
        $return[]=ct('S_ERROR_REGISTER_PASSWORD_TOO_SIMPLE');

	if ((is_object($person)) && $person->get('name')!='') {
		$dict[]=$person->get('username');
		$dict[]=$person->get('firstname'); $dict[]=$person->get('name');
		$dict[]=$person->get('organisation'); $dict[]=$person->get('organisation2');
		$dict[]=$person->get('addr1'); $dict[]=$person->get('addr2'); $dict[]=$person->get('addr3'); $dict[]=$person->get('addr4');
		$dict[]=$person->get('city');
		$dict[]=$person->get('zip');
		$dict[]=$person->get('email'); $dict[]=$person->get('email2');
		$dict[]=$person->get('url');
	}
	foreach ($dict as $d) {
		$d=strtolower(trim($d));
        if (ct_strlen($d)>=3 &&
        	((stristr($d,$word) !== FALSE  )  || // is pwd part of dictionary-word?
        	 (stristr($word,$d) !== FALSE  && (ct_strlen($word)-ct_strlen($d))<=3)  || // is dictionary-word part of password (only if password not 3 or more letters longer...)
        	 (stristr($d,$rword) !== FALSE ) ||
        	 (stristr($rword,$d) !== FALSE && (ct_strlen($rword)-ct_strlen($d))<=3))) {
		        $return[] = ct('S_ERROR_REGISTER_PASSWORD_IN_DICTIONARY',array($d));
		        break;
        }
	}
	// Check for extra passwords from main configurations
	if (is_array(ct_csv_explode(ctconf_get('blockPasswords','')))) {
		$dict2 = ct_csv_explode(ctconf_get('blockPasswords',''));
		foreach ($dict2 as $d) {
			$d=strtolower(trim($d));
		    if (strlen($d)>=3 &&
		    	((stristr($d,$word) !== FALSE  )  || // is pwd part of dictionary-word?
		    	 (stristr($word,$d) !== FALSE  && (ct_strlen($word)-ct_strlen($d))<=3)  || // is dictionary-word part of password (only if password not 3 or more letters longer...)
		    	 (stristr($d,$rword) !== FALSE ) ||
		    	 (stristr($rword,$d) !== FALSE && (ct_strlen($rword)-ct_strlen($d))<=3))) {
			        $return[] = ct('S_ERROR_REGISTER_PASSWORD_IN_DICTIONARY',array($word));
			        break;
			        // Show what the user entered here, as we do not want to display the blocked passwords
		    }
		}
	}

	return $return; // All fine

}

/**
 * Generates a random string with the specified length. Tries to void any "confusing" characters.
 */
function ct_randString($length=16,$alphanumeric_only=true,$readable=true){
	mt_srand((double)microtime()*1000000);
	$salt   = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  // salt to select chars from. Similar looking chars have been removed...
	$saltXL = "abcdefghijkmnopqrstuvwxyzACDEFGHJKLMNPQRSTUVWXYZ23456789$%@#!-+=*";  // salt to select chars from. Similar looking chars have been removed...
	$consonants = "bcdfghjkmnprstvwxyzCDFGHJKLMNPRSTVWXYZ"; // Omit Characters that may be mis-intepreted!
	$vowels = "aeiou";
	$other  = "123456789$!-+"; // "23456789$%@#!-+=*";

	$string="";

	if ($alphanumeric_only) // Only a-z and 0-9
		for ($i=0;$i<$length;$i++) $string .= substr($salt, mt_rand(0,strlen($salt))-1, 1);
	elseif (!$readable) { // makes sure the password starts with a letter and ends with a number, so it will fulfill the password requrements of conftool!
		if ($length>=1) $string.=chr(mt_rand(65,90)); // A-Z
		for ($i=0;$i<$length-2;$i++) $string .= substr($saltXL, mt_rand(0,strlen($saltXL))-1, 1);
		if ($length>=2) $string.=chr(mt_rand(49,57)); // 1-9
	} else { // make sure the password starts with a letter and ends with a number, so it will fulfill the password requrements of conftool!
	  			// and tries to make it (even more) readable.
		if ($length>=1) $string.=chr(mt_rand(65,90)); // A-Z
		for ($i=0;$i<$length-2;$i++) {
			if (mt_rand(0,8)==0) {
				$string .= substr($other, mt_rand(0,strlen($other))-1, 1); // Mix in some special chars...
			} else { // Alternate vowels and consonants.
				if ($i%2)
					$string .= substr($consonants, mt_rand(0,strlen($consonants))-1, 1);
				else
					$string .= substr($vowels, mt_rand(0,strlen($vowels))-1, 1);
			}
		}
		if ($length>=2) $string.=chr(mt_rand(49,57)); // End with 1-9
	}
	return $string;
}


?>