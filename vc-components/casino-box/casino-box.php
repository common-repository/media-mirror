<?php
require_once( 'casino-box.shortcode.php' );

class MMVC_CasinoBox {
	function __construct() {

		// We safely integrate with VC with this hook
		add_action( 'init', array( $this, 'integrateWithVC' ) );

		// Use this when creating a shortcode addon
		add_shortcode( 'bartag', array( $this, 'renderMyBartag' ) );

		// Register CSS and JS
	}

	public function integrateWithVC() {

		// Check if Visual Composer is installed
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		/*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
		vc_map( array(
			'name' => __( 'Casino Box', 'mediamirror' ),
			'description' => __( 'A box with information about a specific casino.', 'mediamirror' ),
			'base' => 'mm-casino-box',
			'class' => '',
			'controls' => 'full',
			'icon' => plugins_url( 'icon.png', __FILE__ ),
			'category' => __( 'Media Mirror', 'mediamirror' ),
			'params' => array(
				array(
				  'type' => 'dropdown',
				  'heading' => __( 'Select Casino', 'mediamirror' ),
				  'param_name' => 'casino',
				  'value' => array_merge( array( 'None' => '' ), mm_get_casinos_array( ) ),
				  'description' => __( 'Which casino do you want to display?', 'mediamirror' ),
			  	),
		  	),
		) );
	}
}
// Finally initialize code
new MMVC_CasinoBox();
