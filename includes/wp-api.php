<?php

function cloudtree_api_default_filters( $server ) {
	$wp_json_media_folder = new WP_JSON_Media_Folder( $server );
	add_filter( 'json_endpoints', array( $wp_json_media_folder, 'register_routes' ), 1 );
}

/**
 * Allow meta query to be passed as a query var.
 */
add_filter( 'json_query_vars', function( $valid_vars ) {
	$valid_vars[] = 'meta_query';
	return $valid_vars;
} );

add_action( 'wp_json_server_before_serve', 'cloudtree_api_default_filters', 10, 1 );

// get folders and files within a folder
// create a folder
//
class WP_JSON_Media_Folder extends WP_JSON_Media {
	/**
	 * Register the media-related routes
	 *
	 * @param array $routes Existing routes
	 * @return array Modified routes
	 */
	public function register_routes( $routes ) {
		$media_routes = array(
			'/media-folder'             => array(
				array( array( $this, 'get_posts_under_folder_path' ), WP_JSON_Server::READABLE ),
				array( array( $this, 'create_folder' ), WP_JSON_Server::CREATABLE ),
			),
			'/media-folder/(?P<path>\S*)'             => array(
				array( array( $this, 'get_posts_under_folder_path' ), WP_JSON_Server::READABLE ),
			),
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

		if ( isset( $filter['folder_id'] ) ) {
			$filter['meta_query'][] = array(
				'key'   => '_media_folder_parent',
				'value' => $filter['folder_id'],
				'type'  => 'NUMERIC'
			);
			if ( $filter['folder_id'] === 0 ) {
				$filter['meta_query']['relation'] = 'OR';
				$filter['meta_query'][] = array(
					'key'     => '_media_folder_parent',
					'compare' => 'NOT EXISTS'
				);
			}
		}
		$filter['posts_per_page'] = -1;
		$filter['orderby'] = 'title';
		$filter['order'] = 'ASC';
		$response = parent::get_posts( $filter, $context, 'attachment', $page );


		$folder_query_args = $filter;
		$folder_query_args['post_type'] = 'media-folder';
		$folder_query = new WP_Query();
		$folder_posts = $folder_query->query( $folder_query_args );

		// holds all the posts data
		$struct = array();
		foreach ( $folder_posts as $post ) {
			$post = get_object_vars( $post );
			// Do we have permission to read this post?
			if ( ! $this->check_read_permission( $post ) ) {
				continue;
			}

			$response->link_header( 'item', json_url( '/posts/' . $post['ID'] ), array( 'title' => $post['post_title'] ) );
			$post_data = $this->prepare_post( $post, $context );
			if ( is_wp_error( $post_data ) ) {
				continue;
			}

			$struct[] = $post_data;
		}

		$response->set_data( array_merge( $struct, $response->get_data() ) );
		return $response;
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
		return new WP_JSON_Response( $this->get_posts_under_folder_path( $path, 'edit' ), 201, $headers );
	}

	protected function prepare_post( $post, $context = 'single' ) {
		$data = parent::prepare_post( $post, $context );
		if ( $data['type'] === 'media-folder' ) {
			// Any specific data for folders
		}
		$data['path'] = cloudtree_get_attachment_path( $post['ID'] );
		return $data;
	}

	/**
	 * Get all media files/folders under a folder path.
	 */
	public function get_posts_under_folder_path( $path = '', $context = 'view' ) {
		if ( strpos( $path, '/' ) ) {
			$post_slugs = explode( '/', $path );
		} else if ( $path ) {
			$post_slugs = array( $path );
		}

		$query = array();
		$folder_id = 0;
		if ( ! empty( $path ) ) {
			foreach ( $post_slugs as $slug ) {
				$query_args = array(
					'name' => $slug,
					'post_type' => 'media-folder',
				);
				$query_args['meta_query'][] = array(
					'key' => '_media_folder_parent',
					'value' => $folder_id
				);
				if ( $folder_id === 0 ) {
					$query_args['meta_query']['relation'] = 'OR';
					$query_args['meta_query'][] = array(
						'key'     => '_media_folder_parent',
						'compare' => 'NOT EXISTS'
					);
				}

				$posts = get_posts( $query_args );
				if ( empty( $posts ) ) {
					return false;
				}
				$folder_id = $posts[0]->ID;
				// @todo add capabilities checks for each folder as we go.
			}
		}
		$query['folder_id'] = $folder_id;

		return $this->get_posts( $query, $context );
	}

}