<?php

/**
 * Allow post_parent as a public query var for JSON.
 *
 * For some reason, logged in users don't get private query vars in wp-api.
 */
add_filter( 'json_query_vars', function( $valid_vars ) {
	$valid_vars[] = 'post_parent';
	return $valid_vars;
} );

function cloudtree_api_default_filters( $server ) {
	$wp_json_media_filesystem = new WP_JSON_Media_Filesystem( $server );
	add_filter( 'json_endpoints', array( $wp_json_media_filesystem, 'register_routes' ), 1 );
}

add_action( 'wp_json_server_before_serve', 'cloudtree_api_default_filters', 10, 1 );

class WP_JSON_Media_Filesystem extends WP_JSON_Media {
	/**
	 * Register the media-related routes
	 *
	 * @param array $routes Existing routes
	 * @return array Modified routes
	 */
	public function register_routes( $routes ) {
		$media_routes = array(
			'/filesystem-folder'             => array(
				array( array( $this, 'get_posts' ),         WP_JSON_Server::READABLE ),
			),
			'/filesystem-folder/(?P<path>\S*)'             => array(
				array( array( $this, 'get_post' ),         WP_JSON_Server::READABLE ),
			),
			// '/media/(?P<id>\d+)' => array(
			// 	array( array( $this, 'get_post' ),    WP_JSON_Server::READABLE ),
			// 	array( array( $this, 'edit_post' ),   WP_JSON_Server::EDITABLE ),
			// 	array( array( $this, 'delete_post' ), WP_JSON_Server::DELETABLE ),
			// ),
		);
		return array_merge( $routes, $media_routes );
	}

	/**
	 * Retrieve media.
	 *
	 * Overrides the $type to set to 'attachment', then passes through to the post
	 * endpoints.
	 *
	 * @see WP_JSON_Posts::get_posts()
	 */
	public function get_posts( $filter = array(), $context = 'view', $type = 'attachment', $page = 1 ) {
		if ( $type !== 'attachment' ) {
			return new WP_Error( 'json_post_invalid_type', __( 'Invalid post type' ), array( 'status' => 400 ) );
		}

		if ( empty( $filter['post_parent'])) {
			$filter['post_parent'] = 0;
		}

		$filter['posts_per_page'] = -1;

		$posts = parent::get_posts( $filter, $context, 'attachment', $page );

		return $posts;
	}

	/**
	 * Retrieve a attachment
	 *
	 * @see WP_JSON_Posts::get_post()
	 */
	public function get_post( $path, $context = 'view' ) {
		if ( strpos( $path, '/' ) ) {
			$post_slugs = explode( '/', $path );
		} else {
			$post_slugs = array( $path );
		}

		$current_parent = 0;
		foreach ( $post_slugs as $slug ) {
			$posts = get_posts( array(
				'name' => $slug,
				'post_parent' => $current_parent,
				'post_type' => 'attachment'
			) );
			if ( empty( $posts ) ) {
				return false;
			}
			$current_post = $posts[0];
			// @todo add capabilities checks for each folder as we go.
		}

		return $this->get_posts( array( 'post_parent' => $current_post->ID ), $context );
	}

}