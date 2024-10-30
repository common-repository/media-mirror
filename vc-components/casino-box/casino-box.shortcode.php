<?php
add_shortcode( 'mm-casino-box', 'mm_casino_box' );
function mm_casino_box( $atts ) {

	if ( isset( $atts ) and isset( $atts['casino'] ) and ! empty( $atts['casino'] ) ) {

		$casino_id = $atts['casino'];
		$casino = ! empty( $casino_id ) ? get_post( $casino_id ) : null;

		if ( $casino ) {

			$image_attachment = wp_get_attachment_image_src( get_post_thumbnail_id( $casino->ID ), 'original' );
			$logo = $image_attachment[0];

			$meta = get_post_meta( $casino->ID );

			$data = array(
				'casino' => $casino,
				'affiliate_link' => esc_url( $meta['mediamirror_link'][0] ),
				'review_link' => get_permalink( $casino->ID ),
				'bonus_percent' => $meta['mediamirror_bonus_percent'][0],
				'bonus_amount' => $meta['mediamirror_bonus_amount'][0],
				'bonus_num_freespins' => $meta['mediamirror_bonus_freespins'][0],
				'currency' => $meta['mediamirror_bonus_currency'][0],
				'rating' => $meta['mediamirror_rating'][0],
				'logo' => $logo,
			);

			ob_start();
			mm_include_template_file( 'casino-box', $data );
			return ob_get_clean();
		}
	}

	return __( 'No casino was selected', 'mediamirror' );
}
