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


$project_title = "Project Title";


include 'functions/get_images.php';
include 'functions/show_files.php';

function cloudtree_print_microtemplates() {
	?>
	<script type="text/html" id="tmpl-media-item">
        <td class="hide_file"><i class="fa fa-eye"></i></td>
        <td valign="top" class="icon dir" data-ext="dir"><a href="/windex/.git"><img src="/windex/icons/dir.png" alt="dir" width="24" height="24"></a></td>
        <td class="file"><a href="{{data.model.get('url') }}">{{ data.model.get('title') }}</a></td>
        <td class="modified"><span class="log_time" title="Tuesday, August 12 2014 7:21 AM">13 hours ago</span></td>
        <td class="action download"><a href="/windex/.git" download=".git"><i class="fa fa-download"></i></a></td>
        <td class="action delete file" data-uri="/windex/.git"><i class="fa fa-trash-o"></i></td>
	</script><?php
}
add_action( 'print_media_templates', 'cloudtree_print_microtemplates' );