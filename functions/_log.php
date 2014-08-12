<?php
  //ini_set('display_errors', 'On');
  //error_reporting(E_ALL);
	include 'functions/dbconnect.php';
  
  if(isset($_POST['log_data'])) {
    $log_data = $_POST['log_data'];
    $log_path = $_POST['log_path'];
    $log_type = $_POST['log_type'];
    $log_designer = $_POST['log_designer'];
    $project_db = $_POST['project_db'];
    
    // print_r('*** $log_data '. $log_data);
    // print_r('*** $log_path '. $log_path);
    // print_r('*** $log_type '. $log_type);
    // print_r('*** $log_designer '. $log_designer);
    // print_r('*** $project_db '. $project_db);
    
    if ($project_db == true){
      $mysqli = new mysqli("127.0.0.1", "root", "", "design");
      $log_path = $mysqli->real_escape_string($log_path);
      $log_data = $mysqli->real_escape_string($log_data);
      $is_present = true;
      
      $query = "INSERT INTO log (id, log_path, log_data, is_present, log_type, designer) VALUES (0, '$log_path', '$log_data', '$is_present', '$log_type', '$log_designer')";
      $result = $mysqli->query( $query );
      return $result;
    }
    
		
  } else {
     echo 'NO LOG DATA WAS PASSED TO THE LOG!';
  }
?>