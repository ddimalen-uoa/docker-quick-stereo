<?php
//
// PAGE:		adminExport
// DESC:		export functions
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

ct_requireadmin();
ct_pagepath(array('index'));

ct_load_class('CTForm');

global $http;
$sql = "";

if (isset($http['form_create_export'])) {
	// Export all users ---------------------------------------------------------
	if ($http['form_export_select']=="users") {
		$sql  = "SELECT p.ID as personID, ";
		$sql .= "CASE p.gender WHEN 1 THEN '".ct('S_USER_GENDER_MALE')."' WHEN 2 THEN '".ct('S_USER_GENDER_FEMALE')."' WHEN 3 THEN '".ct('S_USER_GENDER_DR')."' WHEN 4 THEN '".ct('S_USER_GENDER_PROF')."' END as gender_title, ";
		$sql .= "p.title as academic_title, p.name, p.firstname, p.email, '', p.organisation, p.addr1 as address1, p.addr2 as address2, p.zip, p.city, p.state, p.country as countrycode, c.title as country, '', p.status as status, ";
		$sql .= "p.creationdate as user_registration, p.deleted as deleted ";
		$sql .= "FROM persons as p ";
		$sql .= "LEFT JOIN countries as c ON p.country=c.ID ";
		if (!in_http('form_include_deleted','1')) $sql .= "where p.deleted='0' ";
		$sql .= "order by p.name, p.firstname";
	}

	// export all papers ---------------------------------------------------------
	if ($http['form_export_select']=="papers" && ($user->is_admin()||$user->is_conferencechair())) {
		$db->query("CREATE TABLE export_temp1 (paperID int(11) NOT NULL DEFAULT 0,topics text, PRIMARY KEY(paperID));");
		$res = $db->query("select topics.*, topics2papers.paperID as paperID FROM (topics, topics2papers) where topics.id=topics2papers.topicID order by paperID, topicID");
		$paperID=0; $topics="";
		for	($i	= 0; $i	< $db->num_rows($res); $i++) {
			$row = $db->fetch($res);
			if ($paperID!=$row['paperID'] || $i+1 == $db->num_rows($res)) {
				if ($topics!='') $db->insert_into('export_temp1',array('paperID'=>$paperID,'topics'=>$topics));
				$paperID = $row['paperID']; $topics = $row[ctlx($row,'title')];
			} else {
				$topics .= ', '.$row[ctlx($row,'title')];
			}
		}

		$sql = "SELECT p.ID as paperID, c.title as contribtion_type, p.author as authors, p.organisation, p.presentingauthor as presenting_author, p.presentingauthoremail as presenter_email, ' ', ";
		$sql .= "CONCAT(persons.name,', ',persons.firstname) as submitting_autor, persons.organisation as subm_author_organisation, persons.country as subm_author_countrycode, co.title as subm_author_country, ' ', ";
		$sql .= "p.title, p.keywords as keywords, e1.topics as topics, p.abstract as abstract, ' ', ";
		$sql .= "p.acceptstatus as acceptance_status, s.shorttitle as session, ' ', " ;
		$sql .= "p.abstract as abstract, ' ', " ;
		$sql .= "p.originalname as file, p.filename as name_on_server, '', ";
		$sql .= "p.withdrawn ";
	    $sql .= "FROM papers as p ";
	    $sql .= "LEFT JOIN persons ON p.personID=persons.ID ";
		$sql .= "LEFT JOIN countries as co ON persons.country=co.ID ";
	    $sql .= "LEFT JOIN sessions as s ON p.track=s.ID ";
	    $sql .= "LEFT JOIN contributiontypes as c ON p.contributiontypeID=c.ID ";
	    $sql .= "LEFT JOIN export_temp1 as e1 on p.ID=e1.paperID ";
		if (!in_http('form_include_deleted','1')) $sql .= "where p.withdrawn='0' ";
	    $sql .= "ORDER by p.acceptstatus desc, p.title";
	}

	// export all authors ---------------------------------------------------------
	if ($http['form_export_select']=="authors" && ($user->is_admin()||$user->is_conferencechair())) {
		#$res = $db->query("select papers.*, emails2papers.*, persons.email as email1, persons.email2 as email2 FROM papers, persons, emails2papers where papers.ID=emails2papers.paperID and persons.ID=papers.personID order by paperID, email");
		#if ($topics!='') $db->insert_into('export_temp1',array('paperID'=>$paperID,'topics'=>$topics));
		#echo ct_form_encode($names.": ".$emails)."<br>";
		#$db->query("CREATE TABLE export_temp1 (personID int(11) NOT NULL DEFAULT 0,name varchar(255),email varchar(255), PRIMARY KEY(paperID));");

		ct_load_lib('papers.lib');
		$res = $db->query("select * FROM papers ORDER BY ID");
		if ($res) {
			$db->query("DROP TABLE IF EXISTS export_temp1");
			$db->query("CREATE TABLE export_temp1 (paperID int(11) NOT NULL DEFAULT 0,name varchar(255), KEY paperID (paperID), KEY name (name));");

			# First test if a semicolon was used as delimiter: At least 1/4 of all submissions should have one...
			$colons=0;
			for	($i	= 0; $i	< $db->num_rows($res); $i++) {
				$row = $db->fetch($res);
				$authors = ct_unhtmlentities($row['author']);
				if (substr_count($authors,";")>0) $colons++;
			}
			$delimiter = ($colons*4>$db->num_rows($res))?";":","; #Use semicolon or comma...
			#echo "$colons/".$db->num_rows($res)." => delimiter is '$delimiter'<br>";

			# Now split the names
			$res = $db->query("select * FROM papers ORDER BY ID");
			for	($i	= 0; $i	< $db->num_rows($res); $i++) {
				$row = $db->fetch($res);
				#$authors = $row['author'];
				$authors = ct_unhtmlentities($row['author']);
				// replace and/und/et
				$authors = strtr($authors, array(" and "=>","," und "=>","," et "=>","));
				// remove organisation references in colons, e.g. Peter(1), Carl(2), Mike(1,2)
				$pattern = '/(\()([\d, ]{1,5})(\))/is';
				$format = ""; #"<sup>\$2</sup>";
				$authors = preg_replace($pattern, $format, $authors);
				#echo $authors;
				// Now split the author string.
				$names = ct_multi_explode($delimiter,$authors);
				#ct_print_r($names);
				// And store in DB
				foreach ($names as $name) {
					$db->insert_into('export_temp1',array('paperID'=>$row['ID'],'name'=>$name));
				}
			}
			$sql = "SELECT e1.name AS author_name, ";
			$sql .= "p.ID AS paperID, p.title as paper_title, ' ', ";
			$sql .= "s.ID AS session_ID, s.shorttitle AS session, s.title AS session_title, ' ',";
			$sql .= "p.acceptstatus AS acceptance_status, p.withdrawn as withdrawn, ' '," ;
			$sql .= "c.title as contribtion_type, p.author as authors, p.organisation as organisations ";
		    $sql .= "FROM (papers as p, export_temp1 as e1) ";
		    $sql .= "LEFT JOIN sessions as s ON p.track=s.ID ";
		    $sql .= "LEFT JOIN contributiontypes as c ON p.contributiontypeID=c.ID ";
		    $sql .= "WHERE p.ID=e1.paperID ";
			if (!in_http('form_include_deleted','1')) $sql .= "and p.withdrawn='0' ";
		    $sql .= "ORDER by e1.name, p.ID";
		}
	}

	// export all reviews ---------------------------------------------------------
	if ($http['form_export_select']=="reviews" && ($user->is_admin()||$user->is_conferencechair())) {
		$sql = "SELECT ";
		$sql .= "CONCAT(authors.name,', ',authors.firstname) as submitting_author_name, ";
		$sql .= "authors.organisation as submitting_author_organisation, ";
		$sql .= "papers.*, ";
		// $sql .= "t.*, ".
		$sql .= "expert.name as expert_name, ";
		$sql .= "expert.firstname as expert_firstname, ";
		$sql .= "expert.organisation as expert_organisation, ";
		$sql .= "r.* ";
		$sql .= "FROM (reviews r LEFT JOIN papers ON r.paperID = papers.ID) ";
		$sql .= "LEFT JOIN persons authors ON papers.personID = authors.ID ";
		// 		$sql .= "LEFT JOIN tracks ON papers.track = tracks.ID ";
		$sql .= "LEFT JOIN persons expert ON expert.ID=r.personID ";
		if (!in_http('form_include_deleted','1')) $sql .= "where r.creationdate!='0' ";
		$sql .= "GROUP BY papers.ID, expert.ID ";
		$sql .= "ORDER BY papers.ID";
	}

	// export all participants ---------------------------------------------------------
	if ($http['form_export_select']=="participants") {
		// First create a helper table to list all ordered sub-events and products
		$db->query("DROP TABLE IF EXISTS export_temp1");
		$db->query("CREATE TABLE export_temp1 (personID int(11) NOT NULL DEFAULT 0,events text, PRIMARY KEY(personID));");
		$query = "SELECT events.*, participants2events.personID as personID, participants2events.number as number, participants2events.start as start, participants2events.end as end, participants2events.text as text ";
		$query.=" FROM events, participants2events ";
		$query.=" WHERE events.ID=participants2events.eventID ORDER BY participants2events.personID, participants2events.eventID";
		$res = $db->query($query);
		$personID=0; $events="";
		for	($i	= 0; $i	< $db->num_rows($res); $i++) {
			$row = $db->fetch($res);
			if ($personID!=$row['personID'] || $i+1 == $db->num_rows($res)) {
				if ($events!='') $db->insert_into('export_temp1',array('personID'=>$personID,'events'=>$events));
				#if ($events!="") echo $personID.": ".$events."<br>";
				$personID = $row['personID']; $events = "";
			}
			$eventtext = "";
			if ($row['number']>0) {
				if ($row['number']>1) $eventtext .= $row['number']."x";
				if ($row['short']=="")
					$eventtext .= $row['title'];
				else
					$eventtext .= $row['short'];
				if ($row['datemode']=='selectday') $eventtext .= html_entity_decode(" [".ct_date_format($row['start'])."]");
				elseif ($row['datemode']!='false') $eventtext .= html_entity_decode(" [".ct_date_format($row['start']).'-'.ct_date_format($row['end'])."]");
				if ($row['hastext']!='false' && $row['text']!='') $eventtext .= " (".$row['text'].")";
				if ($events!="") $events.=", "; $events .= $eventtext;
			}
		}

		$sql = "select u.ID as personID, ";
		$sql .= "CASE u.gender WHEN 1 THEN '".ct('S_USER_GENDER_MALE')."' WHEN 2 THEN '".ct('S_USER_GENDER_FEMALE')."' WHEN 3 THEN '".ct('S_USER_GENDER_DR')."' WHEN 4 THEN '".ct('S_USER_GENDER_PROF')."' END as gender, ";
		$sql .= "u.title as title, u.name as name, u.firstname as firstname, TRIM(CONCAT(u.title,' ',u.firstname,' ',u.name)) as full_name, ' ', ";
		$sql .= "u.organisation as organisation, u.addr1 as address_line_1, u.addr2 as address_line_2, u.zip as zip, u.city as city, u.state as state, u.country as countrycode, c.title as country, u.status as user_status, u.email as email, '', ";
		$sql .= "p.regdate as registration_date, g.title as status, p.memberID as member_ID, p.frontdesk as confirmation, '', ";
		$sql .= "p.invoiceno, p.paymethod as payment_method, p.total as total_fee, p.payamount as amount_paid, p.paydate as payment_date, ' ', ";
		$sql .= "t.events as events, '', ";
		$sql .= "p.deleted as canceled_registration ";
		if (ct_test_lib('participation_extraoption.lib')) {	// if extra selection exisits
			$sql .= ", ' ', p.extraoption_1, p.extraoption_2, p.extraoption_3, p.extratext_1, p.extratext_2, p.extratext_3 ";
		}
		$sql .= "FROM (persons as u, participants as p) ";
		$sql .= "LEFT JOIN countries as c ON u.country=c.ID ";
		$sql .= "LEFT JOIN groups as g ON p.status=g.ID ";
		$sql .= "LEFT JOIN export_temp1 as t ON t.personID=u.ID ";
		$sql .= "WHERE u.ID=p.personID ";
		if (!in_http('form_include_deleted','1')) $sql .= "AND p.deleted=0 ";
		$sql .= "ORDER BY u.ID";
	}

	// List all selected products with details and customers --------------------------------
	if ($http['form_export_select']=="products") {
		$sql  = "SELECT e.short as event, e.title as title, '', CONCAT(u.name,', ',u.title,' ',u.firstname) as person, u.organisation as organisation, u.country as country_code, ' ', ";
		$sql .= "w.number as quantity, '', ";
		$sql .= "p.regdate as registration_date, g.title as status_of_participant, '', p.deleted as participantion_cancelled ";
		$sql .= "FROM (events as e, participants2events as w, persons as u, participants as p) ";
		$sql .= "LEFT JOIN groups as g ON p.status=g.ID ";
		$sql .= "WHERE u.ID=p.personID AND w.personID=u.ID AND e.ID=w.eventID AND w.number>0 ";
		if (!in_http('form_include_deleted','1')) $sql .= "AND u.deleted=0 AND p.deleted=0 AND w.deleted=0 ";
		$sql .= "ORDER BY e.short, u.name";
	}

	// export credit card details ----------------------------------------------------
	if ($http['form_export_select']=="creditcards" && ($user->is_admin()||$user->is_assistant())) {
		$sql = "select u.ID as personID, u.title as title, ".
				"CASE u.gender WHEN 1 THEN '".ct('S_USER_GENDER_MALE')."' WHEN 2 THEN '".ct('S_USER_GENDER_FEMALE')."' WHEN 3 THEN '".ct('S_USER_GENDER_DR')."' WHEN 4 THEN '".ct('S_USER_GENDER_PROF')."' END as gender, ".
				"u.name as name, u.firstname as firstname, u.organisation as organisation, u.addr1 as address_line_1, u.addr2 as address_line_2, u.zip as zip, u.city as city, u.state as state, u.country as countrycode, c.title as country, u.email as email, '', ".
				"p.regdate as registration_date, p.invoiceno, p.total as total_fee, p.payamount as amount_paid, '', ".
				"p.cctype as cc_type, p.ccholder as cc_holder, p.ccexpdate as cc_expiration_date, 
				CONCAT(SUBSTRING(p.ccnumber,1,4),' ',LTRIM(SUBSTRING(p.ccnumber,5,32))) as cc_number, 
				#'**** **** **** ****' as cc_number, 
				p.ccvc as cc_cvc ".
				"FROM (persons as u, participants as p) LEFT JOIN countries as c ON u.country=c.ID WHERE u.ID=p.personID AND p.deleted=0 AND p.paymethod='cc' ORDER BY p.regdate";
	}

	// export identities from identity management -------------------------------------
	if ($http['form_export_select']=="identities") {
		$sql = "select deleted, i.ID, personID, nickname, i.title, ".
				"CASE i.gender WHEN 1 THEN '".ct('S_USER_GENDER_MALE')."' WHEN 2 THEN '".ct('S_USER_GENDER_FEMALE')."' WHEN 3 THEN '".ct('S_USER_GENDER_DR')."' WHEN 4 THEN '".ct('S_USER_GENDER_PROF')."' END as gender, ".
				"fullname, name, organisation, city, state, i.country as country_code, c.title as country, '', email, icq, aim, msn, yahoo, url as homepage, '', iconname, visibility ".
		        "from identities as i LEFT JOIN countries as c ON i.country=c.ID order by personID, i.ID";
	}

	// send to browser
	if ($sql!="") {
		ob_end_clean();

		// Do not cache the export for HTTPS, as this causes problems with Internet Explorer
		if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
			session_cache_limiter('public');
			header('Pragma: public');	// HTTP 1.0
			header('Cache-Control: cache, must-revalidate');
		}
		// Create export
		$time = date('Y-m-d_H-i-s',ct_time());
		if (in_http('form_export_format','csv_semicolon')) { // csv format, with semicolon (for old german excel)
			$filename = $http['form_export_select'].'_'.$time.".csv";
			header('Content-Type: application/octetstream');
			header('Content-Disposition: attachment; filename="' . $filename .'"');
			createDumpCSV($sql, ';');
		}
		elseif (in_http('form_export_format','csv_comma')) { // csv format, with comma (for old US excel)
			$filename = $http['form_export_select'].'_'.$time.".csv";
			header('Content-Type: application/octetstream');
			header('Content-Disposition: attachment; filename="' . $filename .'"');
			createDumpCSV($sql, ',');
		}
		#else { // xls format
		#	$filename = $http['form_export_select'].'_'.$time.".xls";
		#	#header("Content-Type: application/vnd.ms-excel"); // Internet explorer tries to open the file inside the browser!
		#	header('Content-Type: application/octetstream');
		#	header('Content-Disposition: attachment; filename="' . $filename .'"');
		#	createDumpExcel($sql, $http['form_export_select']);
		#}

		$db->query("DROP TABLE IF EXISTS export_temp1");
		$db->query("DROP TABLE IF EXISTS export_temp2");

		if (is_object($db)) $db->disconnect();
		exit();
	}
}

echo "<h1>".ct('S_ADMIN_EXPORT')."</h1>\n";
echo "<p class=\"fontnormal font10\">".ct('S_ADMIN_EXPORT_INTRO2')."</p>\n";

$form = new CTform(ct_pageurl('adminExport'), 'post');
$form->width='99%';
$form->align='center';
if ($ctconf['demomode']===true) $form->demomode=true;

$form->add_separator(ct('S_ADMIN_EXPORT'));

$form->add_radio_3col(ct('S_ADMIN_EXPORT_NEWSLETTER'), "form_export_select",  array(array('users','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_NEWSLETTER_INFO'));
if (ctconf_get('submission/enabled')) {
	if ($user->is_admin()||$user->is_conferencechair()) {
		$form->add_radio_3col(ct('S_ADMIN_EXPORT_PAPERS'), "form_export_select",  array(array('papers','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_PAPERS_INFO'));
		$form->add_radio_3col(ct('S_ADMIN_EXPORT_AUTHORS'), "form_export_select",  array(array('authors','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_AUTHORS_INFO'));
		$form->add_radio_3col(ct('S_ADMIN_EXPORT_REVIEW'), "form_export_select",  array(array('reviews','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_REVIEW_INFO'));
	}
}
if (ctconf_get('participation/enabled')) {
	$form->add_radio_3col(ct('S_ADMIN_EXPORT_PARTICIPANTS'), "form_export_select",  array(array('participants','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_PARTICIPANTS_INFO'));
	#$form->add_radio_3col(ct('S_ADMIN_EXPORT_NAMETAGS'), "form_export_select",  array(array('nametags','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_NAMETAGS_INFO'));
	$form->add_radio_3col(ct('S_ADMIN_EXPORT_PRODUCTS'), "form_export_select",  array(array('products','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_PRODUCTS_INFO'));
	if ($user->is_admin()||$user->is_assistant()) {
		$form->add_radio_3col(ct('S_ADMIN_EXPORT_CC'), "form_export_select",  array(array('creditcards','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_CC_INFO'));
	}
	if (ctconf_get('participation/identities')=='true') $form->add_radio_3col(ct('S_ADMIN_EXPORT_IDENTITIES'), "form_export_select",  array(array('identities','')), '0', '&nbsp;', ct('S_ADMIN_EXPORT_IDENTITIES_INFO'));
}
$form->add_separator(ct('S_ADMIN_EXPORT_FILTERSECTION'));
$form->add_select_3col(ct('S_ADMIN_EXPORT_DELETED'), 'form_include_deleted', 1, array(array('1',ct('S_YES')),array('0',ct('S_NO'))), array((in_http('form_include_deleted','1')?1:0)),false,ct('S_ADMIN_EXPORT_DELETED_INFO'),'&nbsp;');
$form->add_spacer();
$form->add_select_3col(ct('S_ADMIN_EXPORT_FORMAT'), 'form_export_format', 1,
		array( #array('xls',ct('S_ADMIN_EXPORT_FORMAT_XLS')),
				array('csv_comma',ct('S_ADMIN_EXPORT_FORMAT_CSV')),
				array('csv_semicolon',ct('S_ADMIN_EXPORT_FORMAT_CSV_SEMICOLON'))
				), array((in_http('format','csv')?'csv':'xls')));

$form->add_submit('form_create_export', ct('S_ADMIN_EXPORT_CREATE_CSV'));
$form->show();

echo "<br><br>\n";


//
// Library for data export functions...
//

/**
 * create CSV of a sql query result
 * see also: http://www.creativyst.com/Doc/Articles/CSV/CSV01.htm
 *
 * @param string $sql sql query for data export
 * @param string $delimiter delimiter symbol
 * @return unknown
 */
function createDumpCSV($sql, $delimiter=',') {
	global $db,$ctconf;

	$charset='ISO-8859-1';
	if (isset($ctconf['charset'])) $charset=$ctconf['charset'];
	if (in_http('form_export_encoding','UTF-8')) {
		ct_load_lib('conversion.lib');
		$charset='UTF-8';
	}

	$result = $db->query($sql);

	if ($result && ($db->num_rows($result))>0) {

		$num_rows = $db->num_rows($result);
		$num_fields = $db->num_fields($result);

		// Output code to identify UTF-8 files...
		if ($charset=='UTF-8') {
			echo "﻿";
		}

		// field name in first line
		for ($col = 0; $col < $num_fields; $col++) {
			if ($col>0)
				echo $delimiter;
			$field_name = mysql_field_name($result, $col);
			echo "\"".$field_name."\"";
		}
		echo "\n";

		// Data
		for ($i = 0; $i < $num_rows; $i++) {
			$zeile = $db->fetch_raw($result);

			for ($k=0;$k < $num_fields;$k++) {
				$t = csv_excape($zeile[$k]);
				if (in_http('form_export_encoding','UTF-8')) {
					ct_load_lib('conversion.lib');
					$t = ct_toUTF8($t);
					$t = ct_decode_entities_to_UTF8($t);
				} elseif($charset=='UTF-8') {
					$t = ct_html_entity_decode($t,ENT_COMPAT,$charset);
				}
				echo "\"".$t."\"";
				if ($k != ($num_fields - 1))
					echo $delimiter;
			}

			echo "\n";
			if ($i%10==0) { ob_flush(); flush(); }
		}
	} else {
		echo "No data found!\n";
		if ($ctconf['display/debug'] && mysql_errno()>0)
			echo mysql_errno().": ".mysql_error()."\n";
	}
	return true;
}


/**
 * Escape text for CSV export.
 *
 * @param string $text
 * @return string
 */
function csv_excape($text) {
	$text = str_replace("\"","\"\"",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\r","\n",$text);
	return $text;
}


?>
