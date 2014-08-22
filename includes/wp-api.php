<?php

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
				array( array( $this, 'get_posts' ),     WP_JSON_Server::READABLE ),
				array( array( $this, 'create_folder' ), WP_JSON_Server::CREATABLE ),
			),
			'/filesystem-folder/(?P<path>\S*)'             => array(
				array( array( $this, 'get_post_by_path' ),         WP_JSON_Server::READABLE ),
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
	 * Retrieve media from the root folder.
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

	public function create_folder( $_files, $_headers, $post_id = 0 ) {
		$post_type = get_post_type_object( 'attachment' );

		if ( $post_id == 0 ) {
			$post_parent_type = get_post_type_object( 'post' );
		} else {
			$post_parent_type = get_post_type_object( get_post_type( $post_id ) );
		}

		// Make sure we have an int or 0
		$post_id = (int) $post_id;

		if ( ! $post_type ) {
			return new WP_Error( 'json_invalid_post_type', __( 'Invalid post type' ), array( 'status' => 400 ) );
		}

		// Permissions check - Note: "upload_files" cap is returned for an attachment by $post_type->cap->create_posts
		if ( ! current_user_can( $post_type->cap->create_posts ) || ! current_user_can( $post_type->cap->edit_posts ) ) {
			return new WP_Error( 'json_cannot_create', __( 'Sorry, you are not allowed to post on this site.' ), array( 'status' => 400 ) );
		}

		// If a user is trying to attach to a post make sure they have permissions. Bail early if post_id is not being passed
		if ( $post_id !== 0 && ! current_user_can( $post_parent_type->cap->edit_post, $post_id ) ) {
			return new WP_Error( 'json_cannot_edit', __( 'Sorry, you are not allowed to edit this post.' ), array( 'status' => 401 ) );
		}

		// Get the file via $_FILES or raw data
		if ( empty( $_files ) ) {
			$file = $this->upload_from_data( $_files, $_headers );
		} else {
			$file = $this->upload_from_file( $_files, $_headers );
		}

		if ( ! is_wp_error( $file ) ) {
			$name       = basename( $file['file'] );
			$name_parts = pathinfo( $name );
			$name       = trim( substr( $name, 0, -(1 + strlen($name_parts['extension'])) ) );

			$url     = $file['url'];
			$type    = $file['type'];
			$file    = $file['file'];
			$title   = $name;
			$content = '';

			// use image exif/iptc data for title and caption defaults if possible
			if ( $image_meta = @wp_read_image_metadata($file) ) {
				if ( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) ) {
					$title = $image_meta['title'];
				}

				if ( trim( $image_meta['caption'] ) ) {
					$content = $image_meta['caption'];
				}
			}

		}


		// Construct the attachment array
		$post_data  = array();
		$attachment = array(
			'post_mime_type' => 'application/x-directory',
			'guid'           => '',
			'post_parent'    => $post_id,
			'post_title'     => 'New Folder',
			'post_content'   => '',
		);

		// This should never be set as it would then overwrite an existing attachment.
		if ( isset( $attachment['ID'] ) ) {
			unset( $attachment['ID'] );
		}

		// Save the data
		$id = wp_insert_attachment($attachment, null, $post_id );

		if ( !is_wp_error($id) ) {
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
		}

		$headers = array( 'Location' => json_url( '/media/' . $id ) );
		$path = cloudtree_get_attachment_path( $id );
		return new WP_JSON_Response( $this->get_post_by_path( $path, 'edit' ), 201, $headers );
	}

	protected function prepare_post( $post, $context = 'single' ) {
		$data = parent::prepare_post( $post, $context );
		$data['mime_type'] = $post['post_mime_type'];
		$data['path'] = cloudtree_get_attachment_path( $post['ID'] );
		return $data;
	}

	/**
	 * Retrieve a attachment
	 *
	 * @see WP_JSON_Posts::get_post()
	 */
	public function get_post_by_path( $path, $context = 'view' ) {
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