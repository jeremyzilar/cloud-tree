<?php

if ( ! defined( 'JSON_API_VERSION' ) ) {
	require( 'includes/vendor/WP-API/plugin.php' );
}
require( 'includes/wp-api.php' );


add_filter( 'wp_unique_post_slug', function( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
	if ( $post_type === 'attachment' ) {
		return $original_slug;
	} else {
		return $slug;
	}
}, 10, 6 );
function cloudtree_print_microtemplates() {
	?>
	<script type="text/html" id="tmpl-media-item">
	<td class="hide_file"><i class="fa fa-eye"></i></td>
	<td valign="top" class="icon dir" data-ext="dir">
		<a href="/windex/.git">
			<# if ( data.model.attributes.attachment_meta.sizes
				&& data.model.attributes.attachment_meta.sizes.thumbnail ) { #>
			<img src="{{ data.model.attributes.attachment_meta.sizes.thumbnail.url }}" alt="dir" width="24" height="24">
			<# } #>
			</a>
		</td>
	<td class="file">
		<a href="{{data.model.get('url') }}">{{ data.model.get('title') }}</a>
	</td>
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
	/**
	 * Check if WP API functionality exists. Not using is_plugin_active in prepartion for
	 */
	if ( ! function_exists( 'json_get_url_prefix' ) ) {
		return;
	}

	wp_enqueue_script( 'wp-api-js', get_stylesheet_directory_uri() . '/includes/vendor/client-js/build/js/wp-api.js', array( 'jquery', 'underscore', 'backbone' ), '1.0', true );

	$settings = array( 'root' => home_url( json_get_url_prefix() ), 'nonce' => wp_create_nonce( 'wp_json' ) );
	wp_localize_script( 'wp-api-js', 'WP_API_Settings', $settings );

	wp_enqueue_script('cloudtree-script', get_stylesheet_directory_uri() . '/js/script.js', array( 'wp-api-js', 'media-views', 'media-models' ), $version, true );
	wp_enqueue_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css' );
}
add_action( 'wp_enqueue_scripts', 'cloudtree_scripts_styles' );

// Front end request this/that/and/the/other/thing
// allow slugs to be duplicated?
//