<?php

require( 'includes/WP-API/plugin.php' );


// App Name — What you call the place where you drop files
$app_name = 'DesignDev';

// App Version
$app_version = 'v2.0';
define('VERSION', $app_version);

$app_author = 'Jeremy Zilar';

// Organization Name
$org_name = 'The New York Times';


$tdir = get_template_directory().'/includes/';
define('TDIR', $tdir);


$theme = get_template_directory_uri();
define('THEME', $theme);


$loggedin = false;
define('LOGGEDIN', $loggedin);



require( 'functions/wp_enqueue_script.php' );

// Editable
//   If true, the each directory will be editable.
//   If MySQL is not hooked up, it will write a root.js file to the DIR.
$editable = true;
// $editable = false;
define('EDITABLE', $editable);


// D A T A B A S E
//   If TRUE, the you will be able to edit, sort, name and describe project folders.
//   Logging also becomes active.
//   EDITABLE should also be made TRUE
$database = false;
// $database = true;
define('DATABASE', $database);


// Enable Logging
$logging = false;
// $logging = true;
define('LOGGING', $logging);

include 'functions/get_images.php';
include 'functions/show_files.php';