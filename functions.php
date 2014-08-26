<?php

function cloudtree_register_post_types() {
	$args = array(
		'labels'              => array(
			'name'                => __( 'Media Folders', 'cloudtree' ),
			'singular_name'       => __( 'Media Folder', 'cloudtree' ),
			'add_new'             => _x( 'Add New Media Folder', 'cloudtree', 'cloudtree' ),
			'add_new_item'        => __( 'Add New Media Folder', 'cloudtree' ),
			'edit_item'           => __( 'Edit Media Folder', 'cloudtree' ),
			'new_item'            => __( 'New Media Folder', 'cloudtree' ),
			'view_item'           => __( 'View Media Folder', 'cloudtree' ),
			'search_items'        => __( 'Search Media Folders', 'cloudtree' ),
			'not_found'           => __( 'No Media Folders found', 'cloudtree' ),
			'not_found_in_trash'  => __( 'No Media Folders found in Trash', 'cloudtree' ),
			'parent_item_colon'   => __( 'Parent Media Folder:', 'cloudtree' ),
			'menu_name'           => __( 'Media Folders', 'cloudtree' ),
		),
		'hierarchical'        => true,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array( 'title' )
	);

	register_post_type( 'media-folder', $args );
}
add_action( 'init', 'cloudtree_register_post_types' );

if ( ! defined( 'JSON_API_VERSION' ) ) {
	require( 'includes/vendor/WP-API/plugin.php' );
}
require( 'includes/wp-api.php' );


add_filter( 'wp_unique_post_slug', function( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
	if ( $post_type === 'attachment' || $post_type === 'media-folder' ) {
		return $original_slug;
	} else {
		return $slug;
	}
}, 10, 6 );
function cloudtree_print_microtemplates() {
	?>
	<script type="text/html" id="tmpl-media-item">
	<td class="hide_file">
		<i class="fa fa-eye"></i>
	</td>
	<td valign="top" class="icon dir" data-ext="dir">
		<# if ( data.model.attributes.type == 'attachment' && data.model.attributes.attachment_meta.sizes && data.model.attributes.attachment_meta.sizes.thumbnail ) { #>
		<img src="{{ data.model.attributes.attachment_meta.sizes.thumbnail.url }}" alt="dir" width="24" height="24">
		<# } #>
		<# if ( data.model.attributes.type == 'media-folder' ) { #>
			<img src="<?php echo get_stylesheet_directory_uri() . '/includes/images/directory.png' ?>" alt="dir" width="24" height="24">
		<# } #>
	</td>
	<td class="file">
		<a href="#{{data.model.get( 'path' ) }}">{{ data.model.get('title') }}</a>
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
	wp_enqueue_script( 'bootstrap-js', '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js' );
	/**
	 * Check if WP API functionality exists. Not using is_plugin_active in prepartion for
	 */
	if ( ! function_exists( 'json_get_url_prefix' ) ) {
		return;
	}

	wp_enqueue_script( 'wp-api-js', get_stylesheet_directory_uri() . '/includes/vendor/client-js/build/js/wp-api.js', array( 'backbone' ), '1.0', true );
	$settings = array( 'root' => home_url( json_get_url_prefix() ), 'nonce' => wp_create_nonce( 'wp_json' ) );
	wp_localize_script( 'wp-api-js', 'WP_API_Settings', $settings );
	wp_register_script( 'marionette', get_stylesheet_directory_uri() . '/includes/vendor/marionette.js', array( 'backbone' ), $version, true );
	wp_register_script( 'cloudtree-script', get_stylesheet_directory_uri() . '/js/script.js', array( 'wp-api-js', 'media-views', 'media-models', 'jquery-ui-draggable', 'jquery-ui-droppable', 'marionette' ), $version, true );
	wp_localize_script( 'cloudtree-script', 'cloudtreeSettings', array( 'themeURL' => get_stylesheet_directory_uri() ) );
	wp_enqueue_script( 'cloudtree-script' );
	wp_enqueue_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css' );
	wp_enqueue_style( 'cloudtree', get_stylesheet_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'cloudtree_scripts_styles' );

/**
 * [cloudtree_get_attachment_path description]
 *
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function cloudtree_get_attachment_path( $post_id ) {
	$post = get_post( $post_id );
	$path = $post->post_name;
	while ( $post_id ) {
		$post_id = get_post_meta( $post_id, 'media_folder_parent', true );
		if ( $post_id ) {
			$post = get_post( $post_id );
			$path = $post->post_name . '/' . $path;
		}
	}
	return $path;
}