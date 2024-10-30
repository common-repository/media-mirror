<?php
require_once( 'casino-table.shortcode.php' );

class MMVC_CasinoTable {
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
			'name' => __( 'Comparison table', 'mediamirror' ),
			'description' => __( 'Renders a list of casinos.', 'mediamirror' ),
			'base' => 'mm-casino-table',
			'class' => '',
			'controls' => 'full',
			'icon' => plugins_url( 'icon.png', __FILE__ ),
			'category' => __( 'Media Mirror', 'mediamirror' ),
			'params' => array(
				array(
				  'type' => 'dropdown',
				  'heading' => __( 'Select country', 'mediamirror' ),
				  'param_name' => 'country',
				  'value' => array( 'All', 'en', 'se', 'no', 'dk', 'fi' ),
				  'description' => __( 'Filter casinos by country / language', 'mediamirror' ),
			  	),
				array(
				  'type' => 'dropdown',
				  'heading' => __( 'Select category', 'mediamirror' ),
				  'param_name' => 'category',
				  'value' => array_merge( array( 'All' ), mm_get_terms_array( 'mm_casino_sort_field' ) ),
				  'description' => __( 'Filter casinos by category', 'mediamirror' ),
			  	),
			),
		) );
	}
}
// Finally initialize code
new MMVC_CasinoTable();
