<?php
  // Resets local Directory by removing any local root.json file
  //ini_set('display_errors', 'On');
  //error_reporting(E_ALL);

  $project_db = $_POST['project_db'];
  $project_editable = $_POST['project_editable'];
  $project_url = $_POST['project_url'];
	$project_path = $_SERVER['DOCUMENT_ROOT'].$_POST['project_url'].'root.json';

	// If EDITABLE and but does not have a DATABASE
  if ($project_editable == true) {
    if (file_exists($project_path)) {
      echo $project_path;
      unlink($project_path);
    }
  }

  // If EDITABLE and has a DATABASE
  if ($project_db == true) {
    $mysqli = new mysqli("127.0.0.1", "root", "", "design");
	  $query = "DELETE FROM projects WHERE project_url = '$project_url'";
    $results = $mysqli->query( $query );
  }
?>