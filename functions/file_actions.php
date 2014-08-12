<?php

// Utility functions

// Delete 
function delete($path)
{
    if (is_dir($path) === true) {
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            Delete(realpath($path) . '/' . $file);
        }
        return rmdir($path);
    } else if (is_file($path) === true) {
        return unlink($path);
    }
    return false;
}


// Read requests method following REST loosely.

$response = array();

// Delete
switch($_SERVER["REQUEST_METHOD"]){
	case "DELETE":
		// Get input variables for DELETE request via php://input($_DELETE doesn't store them)
		parse_str(file_get_contents("php://input"), $vars);
		$path = realpath($_SERVER['DOCUMENT_ROOT'].$vars["uri"]);
		if($path && is_readable($path) && delete($path)){
			$response["status"] = 200;
			$response["message"] = "ok";
			$response["data"] = $vars;
		}else{
			$response["status"] = 404;
			$response["message"] = "Error";
			$response["data"] = $vars;
		}
		break;
}

// Return
echo json_encode($response);
?>