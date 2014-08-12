<?php

function get_log_roll(){
  global $r, $q, $u;
  $mysqli = new mysqli("127.0.0.1", "root", "", "design");
  $query = "SELECT * FROM log WHERE log_path LIKE '%$r%' ORDER BY id DESC";
  $results = $mysqli->query( $query );
  $rows = array();
  if (!empty($results)) {
    while ( $result = $results->fetch_assoc()) {
      array_push($rows, $result);
    }
  }
  // print_r($rows);
  return $rows;
}


?>