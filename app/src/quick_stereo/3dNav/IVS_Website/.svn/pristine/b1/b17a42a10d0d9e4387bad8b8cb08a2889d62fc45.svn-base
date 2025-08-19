<?php
#
# Base-implementation of database interface
#
if (!defined('CONFTOOL')) die('Hacking attempt!');


class Base_DBI {

	# these methods have to be implemented by dbms-specific
	# subclasses to handle db access

	function connect() {
		die('connect() needs to be implemented!');
	}

	function disconnect() {
		die('disconnect() needs to be implemented!');
	}

	function query($query) {
		die('query() needs to be implemented!');
	}

	function affected_rows($id) {
		die('affected_rows() needs to be implemented!');
	}

	function num_rows($id) {
		die('num_rows() needs to be implemented!');
	}

	function num_fields($id) {
		die('num_fields() needs to be implemented!');
	}

	function fetch($id) {
		die('fetch() needs to be implemented!');
	}

	function fetch_raw($id) {
		die('fetch_raw() needs to be implemented!');
	}

	function last_id() {
		die('last_id() needs to be implemented!');
	}

	function list_fields($tbl) {
		die('list_fields() needs to be implemented!');
	}

	function seek($id, $row_number) {
		die('seek() needs to be implemented!');
	}

	# the following functions provide a convenient way to
	# access data entities from the db.

	# insert a record into $rel which links $id1 to $id2
	# $rel should be a n:m-mapping relation
	function link($rel, $id1, $id2) {
		$r = $this->query("replace into $rel values('$id1', '$id2')");
	}

	# delete record(s) from a n:m-mapping relation. if $id1 or
	# $id2 is an empty string, it is used as a wildcard.
	function unlink($rel, $id1, $id2) {
		$fields = $this->list_fields($rel);
		if (($id1 != "") && ($id2 != "")) {
			$this->query("delete from $rel where ".$fields[0][0]."='$id1' and ".$fields[1][0]."='$id2'");
		} else if (($id1 != "") && ($id2 == "")) {
			$this->query("delete from $rel where ".$fields[0][0]."='$id1'");
		} else if (($id1 == "") && ($id2 != "")) {
			$this->query("delete from $rel where ".$fields[1][0]."='$id2'");
		}
	}

	function get_links($rel, $id1, $id2) {
		$fields = $this->list_fields($rel);
		if (($id1 != "") && ($id2 == "")) {
			$r = $this->query("SELECT * FROM $rel where ".$fields[0][0]."='$id1'");
		} else if (($id1 == "") && ($id2 != "")) {
			$r = $this->query("SELECT * FROM $rel where ".$fields[1][0]."='$id2'");
		}
		$rows = array();
		if (isset($r) && ($this->num_rows($r) > 0)) {
			for ($i = 0; $i < $this->num_rows($r); $i++) {
				$rows[] = $this->fetch($r);
			}
		}
		return $rows;
	}

	/**
	 * Create a list of values for a database query.
	 *
	 * @param string $table name of the table
	 * @param array $values values to be entered into the database
	 * @param boolean $colname shall the names of the columns be added too? Used for the UPDATE command only
	 * @return unknown
	 */
	function _create_values($table, $values, $colname=false) {
		$fields = $this->list_fields($table);
		#ct_print_r($fields);
		$query = "";
		for ($i=0; $i < sizeof($fields); $i++) {
			//	echo $fields[$i][0]." : ".$fields[$i][1]." = ".$values[$fields[$i][0]]. "<br>\n";
			if (!$colname || isset($values[$fields[$i][0]])) {
				if ($query!="") $query .= ", ";	// Add comma if not first field
				if ($colname) $query .= $fields[$i][0]."=";	// Add name of column before value - used for "update" command
				// Get value
				$value = '';
				if (isset($values[$fields[$i][0]]))
					$value = stripslashes($values[$fields[$i][0]]);
				// now add value and check type of value.
				switch (strtoupper($fields[$i][1])) {
					case 'STRING':
					case 'BLOB':
					case 'UNKNOWN':
					if ((int)$fields[$i][2]>0 && strlen($value)>(int)$fields[$i][2])
						$value = substr($value,0,(int)$fields[$i][2]);
					$query .= "'".ct_mysql_escape_string($value)."'";
					break;

					case 'INT':
					$value = (int)$value;
					$query .= $value;
					break;

					case 'FLOAT':	# php type name
					case 'REAL':	# from mysql_field_types
					$value = (float)$value;
					$query .= "'".ct_mysql_escape_string($value)."'";
					break;

					case 'ENUM':
					$v = explode(',',$fields[$i][2]);
					if (!is_array($v) && !in_array($value,$v)) // Check value. If illegal, set to default.
						$value = $fields[$i][3];	# Use default value.
					$query .= "'".ct_mysql_escape_string($value)."'";
					break;

					case 'SET':
					$query .= "'".ct_mysql_escape_string($value)."'";
					break;

					case 'DATETIME':
					if ($value=="" or $value==0) // set default date for empty values.
						$value='0000-00-00 00:00:00';
					$query .= "'".ct_mysql_escape_string($value)."'";
					break;

					case 'DATE':
					if ($value=="" or $value==0) $value='0000-00-00';
					$query .= "'".ct_mysql_escape_string($value)."'";
					break;

					case 'TIME':
					if ($value=="" or $value==0) $value='00:00:00';
					$query .= "'".ct_mysql_escape_string($value)."'";
					break;

					case 'TIMESTAMP':
					$query .= "NULL";
					break;
				}
			}
		}
		return $query;
	}

	function replace_into($table, $values) {
		global $db;

		$query = "replace into $table values (";
		$query .= $this->_create_values($table, $values);
		$query .= ")";
		// echo "\n<!-- REPLACE INTO: $query -->\n";
		// echo "<pre>REPLACE INTO:<br>$query</pre><br>\n";
		$db->query($query);
		return (mysql_errno()==0?true:false);
	}

	/**
	 * Insert a new entry into one table of the database.
	 *
	 * @param string $table name of table
	 * @param array $values associative array with names and values of fields. Example array('personID'=>$this->pdata['personID'], 'deleted'=>$this->pdata['deleted']));
	 * @return boolean did the operation succeed?
	 */
	function insert_into($table, $values) {
		global $db;

		$query = "insert into $table values (";
		$query .= $this->_create_values($table, $values);
		$query .= ")";
		// echo "\n<!-- INSERT INTO: $query -->\n";
		// echo "INSERT INTO: $query<br>";
		$db->query($query);
		return (mysql_errno()==0?true:false);
	}

	/**
	 * Insert multiple entries into one table of the database.
	 *
	 * @param string $table name of table
	 * @param array $values array of associative arrays with names and values of fields.
	 * @return boolean did the operation succeed?
	 */
	function insert_into_multi($table, $valuearray) {
		global $db;

		$query = "";
		$fields = "";
		$values = "";

		$fieldarray = $this->list_fields($table);
		foreach ($fieldarray as $f) {
			if ($fields!="") $fields .= ", "; // Add comma if not first field
			$fields .= "`$f[0]`";
		}

		foreach ($valuearray as $v) {
			if ($values!="") $values .= ", "; // Add comma if not first field
			$values .= '('.$this->_create_values($table, $v).')';
		}
		$query = "INSERT INTO $table ($fields) VALUES $values";
		// echo "\n<!-- INSERT INTO MULTI: $query -->\n";
		// echo "INSERT INTO MULTI: $query<br>";
		$db->query($query);
		return (mysql_errno()==0?true:false);
	}



	/**
	 * Update values in database table
	 *
	 * @param string $table name of table
	 * @param string $cond where condition indicating the fields to be updated
	 * @param array $values associative array with names and values of fields to be updated.
	 * 			Example array('deleted'=>'0');
	 * @return boolean did the operation succeed?
	 */
	function update($table, $cond, $values) {
		global $db;

		$query = "UPDATE $table SET ";
		$query .= $this->_create_values($table, $values, true);
		$query .= " WHERE " . $cond;
		// echo "\n<!-- UPDATE: $query -->\n";
		// echo "\n<br>UPDATE: $query <br>\n";
		$db->query($query);
		//  return (mysql_errno()==0?true:false);
		return $query;
	}

	function delete($table, $cond="false") {
		global $db;

		$query = "DELETE FROM $table ";
		$query .= " WHERE " . $cond;
		//echo "\n<!-- \$db->delete(): $query -->\n";
		$db->query($query);
		//	return (mysql_errno()==0?true:false);
		return $query;
	}

	/**
	 * Select values from database table
	 *
	 * @param string $table name of table
	 * @param string $fields list of fields like "name, firstname as f"
	 * @param string $cond where condition indicating the fields to be updated
	 * @param string $order order of output
	 * @return resultset of the query.
	 */
	function select($table, $fields, $cond="", $order="", $group="") {
		global $db;

		$query = "SELECT $fields FROM ($table) ";
		if ($cond!="")
			$query .= " WHERE " . $cond;
		if ($group!="")
			$query .= " GROUP BY " . $group;
		if ($order!="")
			$query .= " ORDER BY " . $order;
		#echo "\n<!-- \$db->select(): $query -->\n";
		#echo "<br>\$db->select(): $query<br>\n";
		$res=$db->query($query);
		return $res;
	}

}

?>
