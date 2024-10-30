<?php
add_action( 'tf_save_admin_mediamirror', 'mm_download_media_programs', 10, 3 );
add_action( 'tf_create_options', 'mm_sync_programs_init' );

function mm_sync_programs_init() {

	$titan = TitanFramework::getInstance( 'mediamirror' );

	if ( $titan->getOption( 'mm_channel_id' ) and $titan->getOption( 'mm_channel_token' ) ) {

		if ( false === ( $value = get_transient( 'mm_last_casinos_sync' ) ) ) {
			mm_download_media_programs();
			set_transient( 'mm_last_casinos_sync', date( 'Y-m-d H:i:s' ), 3600 * 2 );
		}
	}
}

function mm_download_media_programs() {

	$titan = TitanFramework::getInstance( 'mediamirror' );

	$channel_id = esc_attr( $titan->getOption( 'mm_channel_id' ) );
	$token = esc_attr( $titan->getOption( 'mm_channel_token' ) );

	$url = "https://cdn.mediamirror.net/api/table.json?cid={$channel_id}&token={$token}";

	try {
		// Do the HTTP request.
		$response = wp_remote_get( $url );

		if ( is_array( $response ) ) {
			$header = $response['headers']; // array of http header lines
			$body = $response['body']; // use the content
			$json = json_decode( $body );

			if ( isset( $json->rows ) and isset( $json->status ) and $json->status == 'success' ) {
				mm_sync_programs( $json->rows );
			}
		}
	} catch (\Exception $e) { }
}

function mm_sync_programs( $programs ) {

	$titan = TitanFramework::getInstance( 'mediamirror' );
	$country_code = esc_attr( $titan->getOption( 'mm_country_code' ) );

	foreach ( $programs as $program ) {

		// Continue if not matches correct country code.
		if ( $country_code != $program->language and ! empty( $country_code ) ) {
			continue;
		}

		$args = array(
		    'meta_query' => array(
		        array(
		            'key' => 'mediamirror_unique_id',
		            'value' => $program->unique_id,
		        ),
		    ),
		    'post_type' => 'mm_casino',
		    'post_status' => 'any',
		    'posts_per_page' => -1,
		);

		$posts = get_posts( $args );

		if ( count( $posts ) == 0 ) {
			$post_id = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'mm_casino',
				'post_title' => $program->casino_name . ' ' . strtoupper( $program->language ),
				'post_content' => '<!-- Write your own casino review here. -->',
			));

			update_post_meta( $post_id, 'mediamirror_unique_id', $program->unique_id );
			update_post_meta( $post_id, 'mediamirror_rating', 5 );

			mm_update_logo( $post_id, 'casino-' . $program->unique_id . '.png', 'http:' . $program->logo );
	   	} else {
	   		$post = $posts[0];
	   		$post_id = $post->ID;
	   	}

		update_post_meta( $post_id, 'mediamirror_bonus_freespins', $program->bonus_freespins );
		update_post_meta( $post_id, 'mediamirror_bonus_percent', $program->bonus_percent );
		update_post_meta( $post_id, 'mediamirror_bonus_amount', $program->bonus_amount );
		update_post_meta( $post_id, 'mediamirror_bonus_currency', strtoupper( $program->bonus_currency ) );

		update_post_meta( $post_id, 'mediamirror_language', $program->language );
		update_post_meta( $post_id, 'mediamirror_link', $program->link );

	}
}

function mm_update_logo( $post_id, $image_name, $image_url ) {

	// Add Featured Image to Post
	$image_url        = $image_url; // Define the image URL here
	$upload_dir       = wp_upload_dir(); // Set upload folder
	$image_data       = file_get_contents( $image_url ); // Get image data
	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
	$filename         = basename( $unique_file_name ); // Create image file name

	// Check folder permission and define file location
	if ( wp_mkdir_p( $upload_dir['path'] ) ) {
	    $file = $upload_dir['path'] . '/' . $filename;
	} else {
	    $file = $upload_dir['basedir'] . '/' . $filename;
	}

	// Create the image  file on the server
	file_put_contents( $file, $image_data );

	// Check image file type
	$wp_filetype = wp_check_filetype( $filename, null );

	// Set attachment data
	$attachment = array(
	    'post_mime_type' => $wp_filetype['type'],
	    'post_title'     => sanitize_file_name( $filename ),
	    'post_content'   => '',
	    'post_status'    => 'inherit',
	);

	// Create the attachment
	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

	// Include image.php
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

	// Assign metadata to attachment
	wp_update_attachment_metadata( $attach_id, $attach_data );

	// And finally assign featured image to post
	set_post_thumbnail( $post_id, $attach_id );
}
