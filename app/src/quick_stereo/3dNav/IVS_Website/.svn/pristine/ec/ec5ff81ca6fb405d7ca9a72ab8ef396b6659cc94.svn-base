<?php
//
// MySQL Database Interface for ConfTool
// =====================================
//
if (!defined('CONFTOOL')) die('Hacking attempt!');

global $db;

ct_load_lib('base.dbi');

class MySQL_DBI extends Base_DBI {

	var $dberror = false;
//  	var $dberrmsg = '';

	/**
	 * Create a connection to the mysql server.
	 */
	function connect() {
		global $ctconf;

		// die($ctconf['db/host'].':'.$ctconf['db/port'].','.$ctconf['db/database'].' ('.$ctconf['db/username'].','. $ctconf['db/password'].')');
		if (isset($ctconf['db/persistent']) && $ctconf['db/persistent']===false)
			$db_link = mysql_connect($ctconf['db/host'].':'.$ctconf['db/port'],$ctconf['db/username'], $ctconf['db/password']);
		else // Default are persistent connections, as they are some milliseconds faster...
			$db_link = mysql_pconnect($ctconf['db/host'].':'.$ctconf['db/port'],$ctconf['db/username'], $ctconf['db/password']);
		if (!$db_link) {
			$this->dberror = true;
			$this->dberrmsg = '<B>Cannot connect to mysql server:</B> '.mysql_error();
			echo ('<B>Error connecting to mysql server:</B><br> '.mysql_error());
			return false;
		}

		$db_selected = mysql_select_db($ctconf['db/database'],$db_link);
		if (!$db_selected) {
			$this->dberror = true;
			$this->dberrmsg = 'Cannot select database \''.$ctconf['db/database'].'\':</b> (Does it exist?)<br> '.mysql_error();
			echo ('<b>Error! Cannot select database \''.$ctconf['db/database'].'\':</b> (Does it exist?)<br> ' . mysql_error());
			return false;
		}
	    //Add full utf8 handling for Mysql 4.1 or later installations...
		// Attention: Storing UTF-8 characters may require up to 3 bytes per character!
		//  http://dev.mysql.com/doc/refman/5.1/de/charset-mysql.html
	    if (strtoupper($ctconf['charset'])=='UTF-8') {
	    	mysql_query("SET NAMES utf8", $db_link);
        	mysql_query("SET CHARACTER SET utf8", $db_link);
        	mysql_query("SET character_set_results=NULL", $db_link);  // No conversion.
	    }
	    if (strtoupper($ctconf['charset'])=='ISO-8859-1') { // "Western European"
	    	mysql_query("SET NAMES latin1", $db_link);
        	mysql_query("SET CHARACTER SET latin1", $db_link);
        	mysql_query("SET character_set_results=NULL", $db_link);
	    }
	    if (strtoupper($ctconf['charset'])=='ISO-8859-2') {	// "Central European" (Poland etc.)
	    	mysql_query("SET NAMES latin2", $db_link);
        	mysql_query("SET CHARACTER SET latin2", $db_link);
        	mysql_query("SET character_set_results=NULL", $db_link);
	    }
		return true;
	}


	/**
	 * Close connection, if it is not a persistent connection
	 */
	function disconnect() {
		if (isset($ctconf['db/persistent']) && $ctconf['db/persistent']===false)
			mysql_client_encoding($db_link);
	}

	/**
	 * Execute mysql query.
	 *
	 * @param string $query the query string
	 * @return the resultset
	 */
	function query($query) {
		global $session,$db,$ctconf;
		if (isset($ctconf['display/debug']) && $ctconf['display/debug'] && isset($session)) {
			$qstring = "";
			$starttime = 0;
			$format_query = ct_form_encode($query);
			$format_query = str_replace(array("select ",'SELECT '),"<b>SELECT</b> ",$format_query);
			$format_query = str_replace(array("replace into ",'REPLACE INTO '),"<b>REPLACE INTO</b> ",$format_query);
			$format_query = str_replace(array("insert into ",'INSERT INTO '),"<b>INSERT INTO</b> ",$format_query);
			$format_query = str_replace(array(" from ",' FROM '),"<br>&nbsp;&nbsp;&nbsp;<b>FROM</b> ",$format_query);
			$format_query = str_replace(array(" values(",' VALUES('," values (",' VALUES ('),"<br>&nbsp;&nbsp;&nbsp;<b>VALUES</b> (",$format_query);
			$format_query = str_replace(array(" where ",' WHERE '),"<br>&nbsp;&nbsp;&nbsp;<b>WHERE</b> ",$format_query);
			$format_query = str_replace(array(" order by",' ORDER BY'),"<br>&nbsp;&nbsp;&nbsp;<b>ORDER BY</b>",$format_query);
			$format_query = str_replace(array(" left join",' LEFT JOIN'),"<br>&nbsp;&nbsp;&nbsp;<b>LEFT JOIN</b>",$format_query);
			$format_query = str_replace(array(" group by",' GROUP BY'),"<br>&nbsp;&nbsp;&nbsp;<b>GROUP BY</b>",$format_query);
			$format_query = str_replace(array(" having ",' HAVING '),"<br>&nbsp;&nbsp;&nbsp;<b>HAVING</b> ",$format_query);
			$format_query = str_replace(array("%20")," ",$format_query);
			$qstring .= '<tr><td colspan=2><br></td></tr>';
			$qstring .= '<tr class=listheader><td class="fontnormal font8" colspan=2>'.$format_query."</td></tr>";

			$res = mysql_query('explain '.$query);  // Sometimes Errors here... Ignore :-)
			if ($res) {
				$row= $db->fetch($res);
				if ($row['table']!='') $qstring .= '<tr class=evenrow><td class="fontbold font8" width="15%">Table</td><td class="fontnormal font8">'.$row['table']."</td></tr>";
				if ($row['type']!='') $qstring .= '<tr class=oddrow><td class="fontbold font8" width="15%">Type</td><td class="fontnormal font8">'.$row['type']."</td></tr>";
				if ($row['possible_keys']!='') $qstring .= '<tr class=evenrow><td class="fontbold font8" width="15%">Possible Keys</td><td class="fontnormal font8">'.$row['possible_keys']."</td></tr>";
				if ($row['key']!='') $qstring .= '<tr class=oddrow><td class="fontbold font8" width="15%">Key</td><td class="fontnormal font8">'.$row['key']."</td></tr>";
				if ($row['key_len']!='') $qstring .= '<tr class=evenrow><td class="fontbold font8" width="15%">Key_Len</td><td class="fontnormal font8">'.$row['key_len']."</td></tr>";
				if ($row['ref']!='') $qstring .= '<tr class=oddrow><td class="fontbold font8" width="15%">Ref</td><td class="fontnormal font8">'.$row['ref']."</td></tr>";
				if ($row['Extra']!='') $qstring .= '<tr class=evenrow><td class="fontbold font8" width="15%">Extra</td><td class="fontnormal font8">'.$row['Extra']."</td></tr>";
				if ($row['rows']!='') $qstring .= '<tr class=oddrow><td class="fontbold font8" width="15%">Rows affected</td><td class="fontnormal font8">'.$row['rows']."</td></tr>\n";
			} else {
				$qstring .= "<tr class=yellowbg><td class=\"fontbold font8\" width=\"15%\">Resultset</td><td class=\"fontnormal font8\">Query did not return a resultset</td></tr>\n";
			}
			$qstring = $session->get('queries').$qstring;
			$session->put('queries',$qstring);
			$session->put('querycount',$session->get('querycount')+1);
       		$starttime = ct_get_microtime();
		}

		$result = mysql_query($query);

		if (!$result) {
            ct_error_log(mysql_error());
            ct_error_log('Query: '.$query);
            if (isset($ctconf['display/debug']) && $ctconf['display/debug']) {
                echo '<p class="fontbold fontnegative font8">Error: '.ct_htmlentities(mysql_error())."</p>\n";
            	echo '<p class="fontnegative font8">Error: '.ct_htmlentities($query)."</p>\n";
			}
        }

		if (isset($ctconf['display/debug']) && $ctconf['display/debug'] && isset($session)) {
			if ($res && $result and $this->num_rows($result)) // add the number of lines that where returned.
				$qstring .= '<tr class=evenrow><td class="fontbold font8" width="15%">Rows returned</td><td class="fontbold font8">'.$this->num_rows($result)."</td></tr>\n";
			$qstring .= '<tr class=evenrow2><td class="fontbold font8" width="15%">Time</td><td class="fontnormal font8">'. sprintf("%0.3f", (ct_get_microtime()-$starttime)*1000)."ms</td></tr>";
			$session->put('queries',$qstring);
		}
		return $result;
	}

	/**
	 * Get the number of affected rows of the query.
	 *
	 * @param resultset $id
	 * @return int
	 */
	function affected_rows($id) {
		return mysql_affected_rows();
	}

	function num_rows($id) {
		if ($id)
			return mysql_num_rows($id);
		else
			return 0;
	}

	function num_fields($id) {
		return mysql_num_fields($id);
	}

	/**
	 * Get next data entry from mysql. All Quotes are escaped!
	 *
	 * @param resultset $id
	 * @return array
	 */
	function fetch($id) {
		return ct_deepslash(mysql_fetch_array($id));
	}

	/**
	 * Get next data entry from mysql. Quotes are NOT escaped!
	 *
	 * @param resultset $id
	 * @return array
	 */
	function fetch_raw($id) {
		return mysql_fetch_array($id);
	}

	function free_result($id) {
		return mysql_free_result($id);
	}

	function last_id() {
		return mysql_insert_id();
	}

	// List field types...
	// --------------------
	function list_fields($tbl) {
		global $ctconf;

		$fields = array();

		// The following code uses mysql functions to get detailed information about
		// the table structure.
		// see http://www.htmlite.com/mysql003.php
		$res = mysql_query("SHOW COLUMNS FROM $tbl");
		$i = 0;
		while ($row = mysql_fetch_array($res)) {
			#ct_print_r($row);
			$name = $row['Field'];
			$fulltype = $row['Type'];
			preg_match("/^([a-z]*)(\((.*)\))?(.*)$/" ,$fulltype, $t);
			$type = $t[1];	// Type of this variable
			$ext1A = $t[2];	// Extension of type (is in brackets) like varchar(255) -> (255)
			$ext1B = $t[3];  // Extension without brackets: enum('true','false') -> 'true','false'
			$ext2 = trim($t[4]);  // Extension without brackets: enum('true','false') -> 'true','false'
  			//  echo $name . ': ' . $fulltype.' / ' . $type.' ('.$ext.')<br>';
			switch (strtoupper($type)) {
			 case 'CHAR':
			 case 'VARCHAR':
			 case 'TINYTEXT':
				$fields[] = array($name, 'string', $ext1B); 	break;
			 case 'TEXT':
				$fields[] = array($name, 'string', 65535); 	break;
			 case 'MEDIUMTEXT':
				$fields[] = array($name, 'string', 16777215); 	break;
			 case 'LONGTEXT':
				$fields[] = array($name, 'string', 4294967295); 	break;
			 case 'TINYBLOB':
				$fields[] = array($name, 'blob', 255);	break;
			 case 'BLOB':
				$fields[] = array($name, 'blob', 65535);	break;
			 case 'MEDIUMBLOB':
				$fields[] = array($name, 'blob', 16777215);	break;
			 case 'LONGBLOB':
				$fields[] = array($name, 'blob', 4294967295);	break;
			 case 'TINYINT':
			 case 'SMALLINT':
			 case 'MEDIUMINT':
			 case 'INT':
			 case 'INTEGER':
				$fields[] = array($name, 'int', $ext1B, $ext2);		break;
			 case 'BIGINT': // Too big for php int
				$fields[] = array($name, 'float', $ext1B, $ext2);		break;
			 case 'FLOAT':
			 case 'DOUBLE':
			 case 'DECIMAL':
			 case 'NUMERIC':
				$fields[] = array($name, 'float', $ext1B);	break;
			 case 'ENUM':
				$fields[] = array($name, 'enum', $ext1B, $row['Default']); 	break;
			 case 'SET':
				$fields[] = array($name, 'set', $ext1B); 	break;
			 case 'DATETIME':
				$fields[] = array($name, 'datetime'); 	break;
			 case 'DATE':
				$fields[] = array($name, 'date'); 	break;
			 case 'TIME':
				$fields[] = array($name, 'time'); 	break;
			 case 'TIMESTAMP':
				$fields[] = array($name, 'timestamp'); 	break;
			 default:
				$fields[] = array($name, 'unknown', $ext1B);		break;
			}
		}

		/*
		// Old method:
		// On Some servers mysql_field_type does not return the right values...
		// Furthermore details like the filed length are missing.
		$res = mysql_list_fields($ctconf['db/database'], $tbl);
		$cols = mysql_num_fields($res);
		for ($i = 0; $i < $cols; $i++) {
			$fields[] = array(mysql_field_name($res, $i), mysql_field_type($res, $i));
		}
		*/
		return $fields;
	}

	/**
	 * Go to a certain row in the result set.
	 *
	 * @param resultset $id pointer to resultset of mysql query.
	 * @param int $row_number number or row...
	 */
	function seek($id, $row_number) {
		mysql_data_seek($id, $row_number);
	}

}

$db = new MySQL_DBI;
$db->connect();

?>