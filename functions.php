<?php

if ( ! defined( 'JSON_API_VERSION' ) )
	require( 'includes/WP-API/plugin.php' );

function cloudtree_print_microtemplates() {
	?>
	<script type="text/html" id="tmpl-media-item">
	<td class="hide_file"><i class="fa fa-eye"></i></td>
	<td valign="top" class="icon dir" data-ext="dir">
		<a href="/windex/.git">
			<img src="{{ data.model.attributes.sizes.thumbnail.url }}" alt="dir" width="24" height="24">
			</a>
		</td>
	<td class="file"><a href="{{data.model.get('url') }}">{{ data.model.get('title') }}</a></td>
	<td class="modified"><span class="log_time" title="Tuesday, August 12 2014 7:21 AM">13 hours ago</span></td>
	<td class="action download"><a href="/windex/.git" download=".git"><i class="fa fa-download"></i></a></td>
	<td class="action delete file" data-uri="/windex/.git"><i class="fa fa-trash-o"></i></td>
	</script><?php
}
add_action( 'print_media_templates', 'cloudtree_print_microtemplates' );

function cloudtree_scripts_styles() {
	$version = '0.1';
	wp_enqueue_media();
	wp_enqueue_script('bootstrap-js', '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js' );
	wp_enqueue_script('cloudtree-script', get_stylesheet_directory_uri() . '/js/script.js', array( 'media-views', 'media-models' ), $version, true );

	wp_enqueue_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css' );
}
add_action( 'wp_enqueue_scripts', 'cloudtree_scripts_styles' );