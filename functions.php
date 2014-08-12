<?php

require( 'includes/WP-API/plugin.php' );

$tdir = get_template_directory_uri().'/includes/';
define('TDIR', $tdir);

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
