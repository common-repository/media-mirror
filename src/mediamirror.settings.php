<?php
add_action( 'tf_create_options', 'mm_options_page' );

function mm_options_page() {
	$titan = TitanFramework::getInstance( 'mediamirror' );

	$panel = $titan->createAdminPanel( array(
		'name' => __( 'Media Mirror', 'mediamirror' ),
	));

	$import_tab = $panel->createTab( array(
		'name' => __( 'Import casinos / programs', 'mediamirror' ),
	));

	// $popup_tab = $panel->createTab( array(
	// 	'name' => __('Popup Settings', 'mediamirror'),
	// ));

	mm_import_tab( $import_tab );
}

function mm_import_tab( $panel ) {

	$panel->createOption( array(
		'name' => __( 'Channel ID', 'mediamirror' ),
		'id' => 'mm_channel_id',
		'type' => 'text',
		'desc' => __( 'Enter your unique channel ID', 'mediamirror' ),
	));

	$panel->createOption( array(
		'name' => __( 'Channel Token', 'mediamirror' ),
		'id' => 'mm_channel_token',
		'type' => 'text',
		'desc' => __( 'Enter your secret channel token', 'mediamirror' ),
	));

	$panel->createOption( array(
		'name' => __( 'Select country', 'mediamirror' ),
		'id' => 'mm_country_code',
		'type' => 'select',
		'options' => array(
			'' => __( 'Select a country', 'mediamirror' ),
			'en' => __( 'English (Global)', 'mediamirror' ),
			'se' => __( 'Swedish', 'mediamirror' ),
			'no' => __( 'Norway', 'mediamirror' ),
			'dk' => __( 'Denmark', 'mediamirror' ),
			'fi' => __( 'Suomi', 'mediamirror' ),
		),
		'desc' => __( 'Select which country language your site is targeting.', 'mediamirror' ),
		'default' => '',
	));

	$panel->createOption( array(
		'use_reset' => false,
		'type' => 'save',
		'save' => __( 'Import / Sync Programs', 'mediamirror' ),
	));
}
