<?php
// Register Custom Post Type
function mm_casino() {

	$labels = array(
		'name'                  => _x( 'Casinos', 'Post Type General Name', 'mediamirror' ),
		'singular_name'         => _x( 'Casino', 'Post Type Singular Name', 'mediamirror' ),
		'menu_name'             => __( 'Casinos', 'mediamirror' ),
		'name_admin_bar'        => __( 'Casino', 'mediamirror' ),
		'archives'              => __( 'Casino Archive', 'mediamirror' ),
		'parent_item_colon'     => __( 'Parent Casino:', 'mediamirror' ),
		'all_items'             => __( 'All Casinos', 'mediamirror' ),
		'add_new_item'          => __( 'Add New Casino', 'mediamirror' ),
		'add_new'               => __( 'Add New', 'mediamirror' ),
		'new_item'              => __( 'New Casino', 'mediamirror' ),
		'edit_item'             => __( 'Edit Casino', 'mediamirror' ),
		'update_item'           => __( 'Update Casino', 'mediamirror' ),
		'view_item'             => __( 'View Casino', 'mediamirror' ),
		'search_items'          => __( 'Search Casino', 'mediamirror' ),
		'not_found'             => __( 'Not found', 'mediamirror' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'mediamirror' ),
		'featured_image'        => __( 'Logo Image', 'mediamirror' ),
		'set_featured_image'    => __( 'Set Logo Image', 'mediamirror' ),
		'remove_featured_image' => __( 'Remove Logo Image', 'mediamirror' ),
		'use_featured_image'    => __( 'Use as Logo Image', 'mediamirror' ),
		'insert_into_item'      => __( 'Insert into Casino', 'mediamirror' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Casino', 'mediamirror' ),
		'items_list'            => __( 'Casinos list', 'mediamirror' ),
		'items_list_navigation' => __( 'Casinos list navigation', 'mediamirror' ),
		'filter_items_list'     => __( 'Filter Casinos list', 'mediamirror' ),
	);

	$rewrite = array(
		'slug'                  => 'casino',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);

	$args = array(
		'label'                 => __( 'Casino', 'mediamirror' ),
		'description'           => __( 'List of casinos', 'mediamirror' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 100,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'query_var'             => 'mm_casino',
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'mm_casino', $args );

}

add_action( 'init', 'mm_casino', 0 );

// Add language column to casino list in Wordpress Dashboard
Jigsaw::add_column('mm_casino', __( 'Country / Language', 'mediamirror' ), function( $post_id ) {
	$meta = get_post_meta( $post_id );
	$flag_code = esc_attr( $meta['mediamirror_language'][0] );
	$image_url = esc_url( "https://login.mediamirror.net/assets/flags/{$flag_code}.png" );
	echo "<img src='{$image_url}' />";
}, 2);




//restrict the posts by an additional author filter
function mm_add_country_filter_to_admin_query( $query ) {

	global $post_type, $pagenow;

	//if we are currently on the edit screen of the post type listings
	if ( $pagenow == 'edit.php' && $post_type == 'mm_casino' ) {

		if ( isset( $_GET['country'] ) and ! empty( $_GET['country'] ) ) {

			$meta_value = esc_attr( $_GET['country'] );

					$query->set( 'meta_query', array( array(
							'key' => 'mediamirror_language',
							'value' => $meta_value,
							'compare' => '=',
						),
					));

		}
	}
}


/**
 * SELECT PAGE TEMPLATE WP DASHBOARD FOR SPECIFIC CASINO
*/

function mm_add_template_select_meta_box() {
	add_meta_box( 'pagetemplatediv',
		__( 'Template' ),
		'mm_page_template_meta_box',
		'mm_casino', 'side', 'low'
	);
}

function mm_page_template_meta_box( $post ) {
	$template = get_post_meta( $post->ID, '_wp_page_template', true );
?>
	<label class="screen-reader-text" for="page_template"><?php _e( 'Template' ) ?></label>
	<select name="page_template" id="page_template">
	<option value='default'><?php _e( 'Default Template', 'custom-post-type-page-template' ); ?></option>
	<?php page_template_dropdown( $template ); ?>
	</select>
<?php
}

function mm_set_template_on_save_post( $post_id ) {
	if ( ! empty( $_POST['page_template'] ) ) :
		if ( $_POST['page_template'] != 'default' ) :
			update_post_meta( $post_id, '_wp_page_template', $_POST['page_template'] );
		else :
			delete_post_meta( $post_id, '_wp_page_template' );
		endif;
	endif;
}


add_action( 'admin_init', 'mm_add_template_select_meta_box' );
add_action( 'save_post', 'mm_set_template_on_save_post' );

/**
 * ORDER BY COUNTRY / LANGUAGE IN WP DASHBOARD
*/

add_filter('months_dropdown_results', function( $default ) {
	if ( isset( $_GET['post_type'] ) and $_GET['post_type'] == 'mm_casino' ) {
		return false;
	} else {
		return $default;
	}
});

//defining the filter that will be used so we can select posts by 'author'
function mm_add_country_filter_to_casino_admin_list() {

	//execute only on the 'post' content type
	global $post_type;
	if ( $post_type == 'mm_casino' ) {

		if ( isset( $_GET['country'] ) and ! empty( $_GET['country'] ) ) {
			$cc = esc_attr( $_GET['country'] );
		} else {
			$cc = '';
		}

				$meta_value = esc_attr( $_GET['country'] );

		//display the users as a drop down
		?>
				<select name="country">
					<option value=""><?php _e( 'Sort by country / language', 'mediamirror' ); ?></option>
					<option value="en">English / Global</option>
					<option value="se">Sweden</option>
					<option value="no">Norway</option>
					<option value="dk">Denmark</option>
					<option value="fi">Finland</option>
				</select>
		<?php
	}

}

add_action( 'pre_get_posts','mm_add_country_filter_to_admin_query' );
add_action( 'restrict_manage_posts','mm_add_country_filter_to_casino_admin_list' );




/**
 * Casino sort taxonomy
*/

// Register Custom Taxonomy
function mm_casino_sort_fields() {

	$labels = array(
		'name'                       => __( 'Categories', 'mediamirror' ),
		'singular_name'              => __( 'Category', 'mediamirror' ),
		'menu_name'                  => __( 'Categories', 'mediamirror' ),
		'all_items'                  => __( 'All Items', 'mediamirror' ),
		'parent_item'                => __( 'Parent Item', 'mediamirror' ),
		'parent_item_colon'          => __( 'Parent Item:', 'mediamirror' ),
		'new_item_name'              => __( 'New Item Name', 'mediamirror' ),
		'add_new_item'               => __( 'Add New Item', 'mediamirror' ),
		'edit_item'                  => __( 'Edit Item', 'mediamirror' ),
		'update_item'                => __( 'Update Item', 'mediamirror' ),
		'view_item'                  => __( 'View Item', 'mediamirror' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'mediamirror' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'mediamirror' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'mediamirror' ),
		'popular_items'              => __( 'Popular Items', 'mediamirror' ),
		'search_items'               => __( 'Search Items', 'mediamirror' ),
		'not_found'                  => __( 'Not Found', 'mediamirror' ),
		'no_terms'                   => __( 'No items', 'mediamirror' ),
		'items_list'                 => __( 'Items list', 'mediamirror' ),
		'items_list_navigation'      => __( 'Items list navigation', 'mediamirror' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'taxonomies'				 => array( 'casino_sort_field' ),
	);
	register_taxonomy( 'mm_casino_sort_field', array( 'mm_casino' ), $args );

}
add_action( 'init', 'mm_casino_sort_fields', 0 );
