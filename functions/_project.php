<?php
  // Posts to local root.json file via AJAX
  //ini_set('display_errors', 'On');
  //error_reporting(E_ALL);
	// include 'functions/dbconnect.php';
	
	
  $project_data = $_POST['project_data'];
  $project_files = $project_data;
  $project_url = $_POST['project_url'];
  $project_db = $_POST['project_db'];
  $project_editable = $_POST['project_editable'];
  $path = $_SERVER['DOCUMENT_ROOT'].$_POST['project_url'].'root.json';
  // print_r('*** $project_data = ' . $project_data);
  // print_r('*** $project_files = ' . $project_files);
  // print_r('*** $project_url = ' . $project_url);
  // print_r('*** $project_db = ' . $project_db);
  // echo $path;

  if ($project_editable == true){
    $file = fopen($path ,'w+');
    fwrite($file, $project_data);
    fclose($file);
    echo $project_data;
  }
  
  if ($project_db == true){
    $mysqli = new mysqli("127.0.0.1", "root", "", "design");
    $query = "INSERT INTO projects (id, project_url, project_data, project_files) VALUES (0, '$project_url', '$project_data', '$project_files') ON DUPLICATE KEY UPDATE project_data = VALUES(project_data), project_files = VALUES(project_files)";
    $results = $mysqli->query( $query );
    return $results;
  }
  
?>