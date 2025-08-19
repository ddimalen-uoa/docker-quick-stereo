<?PHP
if (!defined('CONFTOOL')) die('Hacking attempt!');

//
// functions to check and save master and mail data.
// called by adminToolMasterData and adminToolMailSettings
//
function save_ctconf_setting($name,$type='') {
	global $http,$form_errors,$session;
	if ($name=='') {
		ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED'));
		return '';
	}
	ctconf_set($name,$http['form_'.$name]);

	if ($type!='optional' && !isset($http['form_'.$name])) {
		ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": is missing</b>");
		return 'form_'.$name;
	}
	if ($type=='required' && (!in_http('form_'.$name) || $http['form_'.$name]=='')) {
		ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": is required</b>");
		return 'form_'.$name;
	}
	if (($type=='email' || $type=='email?' ) && !ct_validate_email($http['form_'.$name])) {
		if ($type=='email?' && strlen($http['form_'.$name])==0)
			return null; # This might also be empty.
		ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": is not a valid e-mail address</b>");
		return 'form_'.$name;
	}
	if ($type=='emails') {
		$emails = ct_csv_explode($http['form_'.$name]);
		$http['form_'.$name] = ct_csv_implode($emails);
		foreach ($emails as $email) {
			if (!ct_validate_email($email)) {
				ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": $email is not a valid e-mail address</b>");
				return 'form_'.$name;
			}
		}
	}
	if ($type=='uri' || $type=='uri?') {
		if ($type=='uri?' && strlen($http['form_'.$name])==0)
			return null; # This might also be empty.
		$http['form_'.$name]=trim($http['form_'.$name]);
		if (ct_strtolower(ct_substr($http['form_'.$name],0,4))!="http" && ct_strlen($http['form_'.$name])>5) $http['form_'.$name] = 'http://'.$http['form_'.$name];
		if (ct_substr($http['form_'.$name],-1)!="/" && substr_count($http['form_'.$name],'/')==2) $http['form_'.$name] = $http['form_'.$name].'/';
		if (!ct_validate_uri($http['form_'.$name])) {
			ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": is not a valid uri</b>");
			return 'form_'.$name;
		}
	}
	if ($type=='boolean' && ($http['form_'.$name]!='0' && $http['form_'.$name]!='1')) {
		ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": value must be boolean</b>");
		return 'form_'.$name;
	}
	if ($type=='number' && !(preg_match("/^[-]?[0-9]+$/",$http['form_'.$name]))) {
		ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": value must be a number</b> (".$http['form_'.$name].")");
		return 'form_'.$name;
	}
	if ($type=='number5-11' && (($http['form_'.$name]+0)<5 || ($http['form_'.$name]+0)>11)) {
		ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": value must be a number &gt;=5 and &lt;=11 </b>".($http['form_'.$name]+0));
		return 'form_'.$name;
	}
	if ($type=='csv') {
		$temp = ct_csv_explode($http['form_'.$name]);
		$http['form_'.$name] = ct_csv_implode($temp);
		if ($http['form_'.$name]=='') {
			ct_errorbox(ct('S_ERROR_SAVE'),ct('S_ERROR_SAVE_FAILED')."<b><br>".$name.": must be comma separated values.</b>");
			return 'form_'.$name;
		}
	}

	// Save again...
	ctconf_set($name,$http['form_'.$name]);
	if ($type=='lang') {
		if (!isset($ctconf['lang']['2']))
			ctconf_set($name.'2',$http['form_'.$name.'2']);
		if (!isset($ctconf['lang']['3']))
			ctconf_set($name.'3',$http['form_'.$name.'3']);
		if (!isset($ctconf['lang']['4']))
			ctconf_set($name.'4',$http['form_'.$name.'4']);
	}
	return null;
}


function ct_get_basesettings() {
	$return  = strip_tags(ctconf_get('conferenceShortName')."\n".ctconf_get('conferenceName')."\n".ctconf_get('conferenceSubtitle')."\n".ctconf_get('conferenceSubtitle2')."\n\n".ctconf_get('conferenceOrganizer')."\n".ctconf_get('conferenceSenderName'))." <".ctconf_get('conferenceContactEmail').">"."\n\n".ctconf_get('conferenceURI')."\n".ct_getbaseurl()."\n\n";
	$return .= preg_replace("/[\n\r ]+/"," ","<a target='_blank' href='".ctconf_get('conferenceURI')."'>".strip_tags(ctconf_get('conferenceName'))." (".strip_tags(ctconf_get('conferenceShortName')).")</a>, ".strip_tags(ctconf_get('conferenceSubtitle')).", ".strip_tags(ctconf_get('conferenceSubtitle2')));
	return $return;
}

?>