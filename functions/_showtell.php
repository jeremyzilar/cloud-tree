<?php
  //ini_set('display_errors', 'On');
  //error_reporting(E_ALL);
  
  // $pk = $_POST['pk'];
  // $log_data = $_POST['data'];
  $log_type = $_POST['name'];
  $log_data = $_POST['value'];
  // $log_path = $_POST['path'];
  // $log_designer = $_POST['designer_ldap'];
  $log_path = '/example/';
  $log_designer = 'Jeremy Zilar';
  $log_path = mysql_escape_string($log_path);
  $log_data = mysql_escape_string($log_data);
  $is_present = true;

  // echo $log_data;
  
  if(!empty($log_data)) {
    
    $con = mysql_connect("127.0.0.1","root","") or die(mysql_error());
    if (!$con) {
      
    } else {
      $mysqli->select_db("design", $con);
      $query = $mysqli->query("INSERT INTO log (id, log_path, log_data, is_present, log_type, designer)
      VALUES (0, '$log_path', '$log_data', '$is_present', '$log_type', '$log_designer')");
      return $mysqli->query($query);
    }
    
  } else {
    header('HTTP 400 Bad Request', true, 400);
    echo "This field is required!";
  }
?>