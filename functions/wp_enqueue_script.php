<?php

function scripts_styles() {
	global $wp_styles;
	$q = 'v112';
	// Le JS
	wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), $q, true );
	wp_enqueue_script('moment-js', get_template_directory_uri() . '/js/moment.min.js', array( 'jquery' ), $q, true );
	wp_enqueue_script('indieweb', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), $q, true );

	// Le CSS
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css',array(), $q);
	wp_enqueue_style( 'openwebicons', get_template_directory_uri() . '/css/files.css',array(), $q);
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/log.css',array(), $q);
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/showtell.css',array(), $q);
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/upload.css',array(), $q);
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/style.css',array(), $q);
	// wp_enqueue_style( 'indieweb', get_stylesheet_uri(),array(), $q);
}
add_action( 'wp_enqueue_scripts', 'scripts_styles' );


?>
