<?php
add_action( 'tf_create_options', 'mm_casino_posttype_meta' );
function mm_casino_posttype_meta() {
	$titan = TitanFramework::getInstance( 'mediamirror' );

	$metaBox = $titan->createMetaBox( array(
		'name' => __( 'Table columns', 'mediamirror' ),
		'post_type' => 'mm_casino',
	));

	$metaBox->createOption( array(
		'name' => __( 'Has review', 'mediamirror' ),
		'id' => 'has_review',
		'type' => 'checkbox',
		'desc' => __( 'Display link/button to review page', 'mediamirror' ),
		'default' => false,
	));

	$metaBox->createOption(array(
		'name' => __( 'Bonus Percent', 'mediamirror' ),
		'id' => 'bonus_percent',
		'type' => 'text',
	));

	$metaBox->createOption(array(
		'name' => __( 'Bonus Amount', 'mediamirror' ),
		'id' => 'bonus_amount',
		'type' => 'text',
	));

	$metaBox->createOption(array(
		'name' => __( 'Freespins', 'mediamirror' ),
		'id' => 'bonus_freespins',
		'type' => 'text',
	));

	$metaBox->createOption(array(
		'name' => __( 'Bonus offer / info', 'mediamirror' ),
		'id' => 'bonus_info',
		'type' => 'editor',
		'rows' => 3,
	));

	$metaBox->createOption(array(
		'name' => __( 'Affiliate Link', 'mediamirror' ),
		'id' => 'link',
		'type' => 'text',
		'desc' => __( 'Your unique affiliate URL', 'mediamirror' ),
	));

	$metaBox->createOption( array(
		'name' => __( 'Rating', 'mediamirror' ),
		'id' => 'rating',
		'type' => 'radio',
		'desc' => __( 'Select one', 'mediamirror' ),
		'default' => '1',
		'options' => array(
			'1' => '1/5',
			'2' => '2/5',
			'3' => '3/5',
			'4' => '4/5',
			'5' => '5/5',
		),
	));

}
