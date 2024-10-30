<?php
function mm_get_terms_array( $taxonomy ) {
	$output = array();
	$terms = get_terms( 'mm_casino_sort_field', array( 'hide_empty' => false ) );
	foreach ( $terms as $term ) {
		$output[ $term->name ] = $term->slug;
	}
	return $output;
}

function mm_get_casinos_array() {

	$casinos = new WP_Query( array(
		'post_type' => 'mm_casino',
		'posts_per_page' => -1,
	));

	$output = array();
	while ( $casinos->have_posts() ) {
		$casinos->the_post();
		$output[ get_the_title() ] = get_the_id();
	}
	wp_reset_postdata();

	return $output;
}

function mm_include_template_file( $template_file, $data = array() ) {
	$template_file = esc_attr( $template_file );

	$template_url = get_stylesheet_directory() . "/mm-templates/{$template_file}.tpl.php";
	$fallback_url = MEDIAMIRROR__PLUGIN_DIR . "mm-templates/{$template_file}.tpl.php";

	extract( $data );

	if ( file_exists( $template_url ) ) {
		include( $template_url );
	} else {
		include( $fallback_url );
	}

}

function mm_rating_stars( $number = 1 ) {
	$number = (int) $number;

	$star_url = MEDIAMIRROR__PLUGIN_URL . 'assets/images/star.png';
	$star_filled_url = MEDIAMIRROR__PLUGIN_URL . 'assets/images/star-filled.png';

	for ( $i = 1;$i <= 5;$i++ ) {

		if ( $i <= $number ) {
			echo "<img src='{$star_filled_url}' class='mm-star'>";
		} else {
			echo "<img src='{$star_url}' class='mm-star'>";
		}
	}
}
