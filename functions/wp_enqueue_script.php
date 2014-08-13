<?php
function scripts_styles() {
	global $wp_styles;
	$q = 'v113';
	

	// Le JS
	wp_enqueue_script('bootstrap-js', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), $q, true );
	wp_enqueue_script('moment-js', get_stylesheet_directory_uri() . '/js/moment.min.js', array( 'jquery' ), $q, true );
	wp_enqueue_script('indieweb', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ), $q, true );
	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css',array(), $q);
	wp_enqueue_style( 'openwebicons', get_stylesheet_directory_uri() . '/css/files.css',array(), $q);


	// Le CSS
	wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css',array(), $q);
	wp_enqueue_style( 'cloud-tree-log', get_stylesheet_directory_uri() . '/css/log.css',array(), $q);
	wp_enqueue_style( 'cloud-tree-showtell', get_stylesheet_directory_uri() . '/css/showtell.css',array(), $q);
	wp_enqueue_style( 'cloud-tree-upload', get_stylesheet_directory_uri() . '/css/upload.css',array(), $q);
	wp_enqueue_style( 'cloud-tree-style', get_stylesheet_directory_uri() . '/css/style.css',array(), $q);
}
add_action( 'wp_enqueue_scripts', 'scripts_styles' );
