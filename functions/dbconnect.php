<?php
	function dberror(){
		echo "not connected";
	  return;
	}
	function db_query($type, $query ) {
		static $db = null;
		if (DATABASE === true) {
			if (!$db) {
				$db = dbconnect();
			}
			$results = $db->query( $query );
			if ($type == 'query') { // Check if type=query, avoiding inserts
				$rows = array();
				if ($results) {
					while ( $result = $results->fetch_assoc()) {
						$rows[] = $result;
					}
					return $rows;
				}
			} else {
				return;
			}
		}
	}

	function dbconnect(){
		global $dbhost, $dbuser, $dbpass, $dbdatabase;
		if (DATABASE === true) {
			$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbdatabase);
			/* check connection */
			if (mysqli_connect_errno()) {
				// dberror();
			  // printf("NOPE — Connect failed: %s\n", mysqli_connect_error());
			  exit();
			}
			return $mysqli;
		}
	}
?>