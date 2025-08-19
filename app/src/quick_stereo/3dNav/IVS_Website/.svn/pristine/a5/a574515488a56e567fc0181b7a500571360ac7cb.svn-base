<?php
// ConfTool address functions (C) Harald Weinreich
// get and list countries, states, provinces
// country specific adresses formatting
if (!defined('CONFTOOL')) die('Hacking attempt!');


/**
 * Get the written country name the ISO code
 */
function ct_get_country($code) {
	global $ctconf;
	// First try countries from ctconf.
	if (is_array(ctconf_get('web/favoritecountries'))) {
		$c = ctconf_get('web/favoritecountries');
		while (list ($key, $val) = each ($c)){
			if (ct_strlen($key)==2 && strtoupper($key)==$code) // So the array value has the form 'DE'=>'Deutschland'
				return $val;
		}
	}
	// Load default countries...
	$c = _get_countries();
	if ($c[$code]=="") return $code;
	else return $c[$code];
}

/**
 * List states etc. for select box
 */
function ct_list_states() {
	$li   = array();
	$li[] = array("-",ct('S_USER_STATE_SELECT'));
	// $li[] = array('-','-----------------------------------------');

	// US states
	$li[] = array('-',ct('S_USER_STATE_US'));
	$us = _get_us_states();
	while (list ($key, $val) = each ($us)) $li[] = array('US,'.$key,$val);

	// Canadian province
	$li[] = array('-',ct('S_USER_STATE_CA'));
	$ca = _get_ca_provinces();
	while (list ($key, $val) = each ($ca)) $li[] = array('CA,'.$key,$val);

	// Aust. territory
	$li[] = array('-',ct('S_USER_STATE_AU'));
	$au = _get_au_territories();
	while (list ($key, $val) = each ($au)) $li[] = array('AU,'.$key,$val);
	return $li;
}

/**
 * List countries for select box
 */
function ct_list_countries($userselect=true) {
	global $ctconf;
	$c1 = _get_countries();
	$c2 = array();
	if ($userselect) $c2[] = array("-",ct('S_USER_COUNTRY_SELECT'));
	if ($userselect) $c2[] = array('-','-----------------------------------------');
	if ($userselect && is_array(ctconf_get('web/favoritecountries')) && count(ctconf_get('web/favoritecountries'))>0 ) {
		$c = ctconf_get('web/favoritecountries');
		while (list ($key, $val) = each ($c)){
			if (ct_strlen($val)==2) // So the array value has the form 'DE'=>'Deutschland'
				$c2[] = array(strtoupper($val),$c1[strtoupper($val)]);
			else
				$c2[] = array(strtoupper($key),$val);
		}
		$c2[] = array('-','-----------------------------------------');
	}
	while (list($key,$val) = each ($c1))
		// if (!in_array($key,ctconf_get('web/favoritecountries'))) // Do not repeat country...
			$c2[] = array($key,$val);
	return $c2;
}

/**
 * Format ZIP/STATE/City part of address for invoice etc.
 */
function ct_address_format($countrycode,$zip,$statecode,$city) {
	if (ct_strlen($statecode)>2) $statecode=ct_substr($statecode,3); // Remove country
	if (in_array($countrycode,array("US","CA","AU","IN")) && ct_strlen($statecode)>0)
		return  $city.", ".$statecode." ".$zip."<br>";
	// Alternative also for the above, when State Code is missing...
	if (in_array($countrycode,array("IO","NZ","SG","KR","TW","JP","US","CA","AU","IN")))
		return $city." ".$zip."<br>";
	if (in_array($countrycode,array("IT")) && ct_strlen($statecode)>0)
		return  $zip." ".$city." ".$statecode."<br>"; # http://www.poste.it/en/postali/cap/howto.shtml
	if (in_array($countrycode,array("MX")) && ct_strlen($statecode)>0)
		return  $zip." ".$city.", ".$statecode."<br>";
	if (in_array($countrycode,array("BR")) && ct_strlen($statecode)>0)
		return  $city." - ".$statecode."<br>".$zip."<br>";
	if (in_array($countrycode,array("GB","UK","BR")))
		return  $city."<br>".$zip."<br>";
	if (ct_strlen($statecode)>0)
		return $zip." ". $city .", ".$statecode."<br>";
	if (ct_strlen($zip)==0)
		return $city."<br>";
	return $zip." ". $city ."<br>";
}


//---- internal functions............

function _get_us_states() {
	$states=array();
	$states['AL']='Alabama';
	$states['AK']='Alaska';
	$states['AZ']='Arizona';
	$states['AR']='Arkansas';
	$states['CA']='California';
	$states['CO']='Colorado';
	$states['CT']='Connecticut';
	$states['DE']='Delaware';
	$states['DC']='District of Columbia';
	$states['FL']='Florida';
	$states['GA']='Georgia';
	$states['HI']='Hawaii';
	$states['ID']='Idaho';
	$states['IL']='Illinois';
	$states['IN']='Indiana';
	$states['IA']='Iowa';
	$states['KS']='Kansas';
	$states['KY']='Kentucky';
	$states['LA']='Louisiana';
	$states['ME']='Maine';
	$states['MD']='Maryland';
	$states['MA']='Massachusetts';
	$states['MI']='Michigan';
	$states['MN']='Minnesota';
	$states['MS']='Mississippi';
	$states['MO']='Missouri';
	$states['MT']='Montana';
	$states['NE']='Nebraska';
	$states['NV']='Nevada';
	$states['NH']='New Hampshire';
	$states['NJ']='New Jersey';
	$states['NM']='New Mexico';
	$states['NY']='New York';
	$states['NC']='North Carolina';
	$states['ND']='North Dakota';
	$states['OH']='Ohio';
	$states['OK']='Oklahoma';
	$states['OR']='Oregon';
	$states['PA']='Pennsylvania';
	$states['RI']='Rhode Island';
	$states['SC']='South Carolina';
	$states['SD']='South Dakota';
	$states['TN']='Tennessee';
	$states['TX']='Texas';
	$states['UT']='Utah';
	$states['VT']='Vermont';
	$states['VA']='Virginia';
	$states['WA']='Washington';
	$states['WV']='West Virginia';
	$states['WI']='Wisconsin';
	$states['WY']='Wyoming';
	return $states;
}

function _get_ca_provinces() {
    $caprovinces=array();
    $caprovinces['AB']='Alberta';
    $caprovinces['BC']='British Columbia';
    $caprovinces['MB']='Manitoba';
    $caprovinces['NB']='New Brunswick';
    $caprovinces['NL']='Newfoundland and Labrador';
    $caprovinces['NS']='Nova Scotia';
    $caprovinces['NU']='Nunavut';
    $caprovinces['ON']='Ontario';
    $caprovinces['PE']='Prince Edward Island';
    $caprovinces['QC']='Qu&eacute;bec';
    $caprovinces['SK']='Saskatchewan';
    $caprovinces['NT']='Northwest Territories';
    $caprovinces['YT']='Yukon Territory';
	return $caprovinces;
}

function _get_au_territories() {
	$auterritories=array();
	$auterritories['ACT']='Australian Capital Territory';
	$auterritories['NSW']='New South Wales';
	$auterritories['NT'] ='Northern Territory';
	$auterritories['QLD']='Queensland';
	$auterritories['SA'] ='South Australia';
	$auterritories['TAS']='Tasmania';
	$auterritories['VIC']='Victoria';
	$auterritories['WA'] ='Western Australia';
	return $auterritories;
}

/**
 * Get all countries from database.
 *
 * @param short return short form of country (in english only!), otherwise long format in 4 languages.
 * @return array with country ID and country name, ordered by country name
 */
function _get_countries($short=false) {
	global $db,$session;

	if ($session->get('db_countries')) return $session->get('db_countries');

	$countries=array();
	if ($short) { // short title only
		$query='select * FROM countries where ID!="-" ORDER BY shorttitle';
	} else {
		$query='select * FROM countries where ID!="-" ORDER BY trim(concat(title'.(($session->get('langno')!='1')?$session->get('langno').',title':'').'))';
	}
	$r = $db->query($query);
	if ($r && ($db->num_rows($r) > 0)) {
		for ($i=0; $i < $db->num_rows($r); $i++) {
			$c = $db->fetch($r);
			if ($short) { // short title only
				$countries[$c['ID']] = ct_html_entity_decode($c['shorttitle']);
			} else {
				$countries[$c['ID']] = ct_html_entity_decode($c[ctlx($c,'title')]);
			}
		}
	}
	$session->put('db_countries',$countries);
	return $countries;
}

/**
 * Returns array with country codes of countries without zip / postal code.
 *
 * @return array
 */
function ct_get_countries_without_postal_code() {
	return array('AE','AF','AI','AL','AO','AW','BJ','BO','BS','BW','BZ','CF','CK','CO','CD','CG',
					'DM','FJ','GM','GS','GH','GD','HK','IE','JM','KY','LB','LC','LY','MO','MW','MU','MS','NA','NR',
					'PA','RW','SB','SC','SL','SY','TD','TG','UG','VU','WS','YE','ZW');
}


?>