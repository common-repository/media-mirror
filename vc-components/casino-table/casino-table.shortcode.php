<?php
add_shortcode( 'mm-casino-table', 'mm_casino_table' );
function mm_casino_table( $atts ) {

	$args = array(
		'post_type' => 'mm_casino',
		'posts_per_page' => -1,
	);

	// Sort by language / country code
	if (  isset( $atts ) and isset( $atts['country'] ) and ! empty( $atts['country'] ) ) {
		$args['meta_query'][] = array(
		'key' => 'mediamirror_language',
		'value' => esc_attr( $atts['country'] ),
		'compare' => '=',
		);
	}

	// Sort by taxonomy
	if ( isset( $atts ) and isset( $atts['category'] ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'mm_casino_sort_field',
				'field' => 'slug',
				'terms' => array( $atts['category'] ),
			),
		);
	}

	$rows = array();
	$casinos = new WP_Query( $args );

	while ( $casinos->have_posts() ) {
		$casinos->the_post();
		$meta = get_post_meta( get_the_id() );

		$image_attachment = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'original' );
		$logo = $image_attachment[0];

		$rows[] = array(
			'id' => get_the_id(),
			'casino' => $post,
			'affiliate_link' => esc_url( $meta['mediamirror_link'][0] ),
			'review_link' => get_permalink( $casino->ID ),
			'bonus_percent' => $meta['mediamirror_bonus_percent'][0],
			'bonus_amount' => $meta['mediamirror_bonus_amount'][0],
			'bonus_num_freespins' => $meta['mediamirror_bonus_freespins'][0],
			'bonus_info' => $meta['mediamirror_bonus_info'][0],
			'currency' => $meta['mediamirror_bonus_currency'][0],
			'rating' => $meta['mediamirror_rating'][0],
			'has_review' => $meta['mediamirror_has_review'][0],
			'logo' => $logo,
		);
	}

	wp_reset_postdata();

	ob_start();
	mm_include_template_file( 'casino-table', array( 'rows' => $rows ) );
	return ob_get_clean();
}
