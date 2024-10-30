<?php
if ( ! defined( 'MM_CUSTOM_STYLING' ) ) {
	add_action( 'wp_enqueue_scripts', 'mm_enqueue_front_end' );
}

function mm_enqueue_front_end() {
	wp_enqueue_style( 'media-mirror-css', MEDIAMIRROR__PLUGIN_URL . 'assets/css/mediamirror.css' );
}
