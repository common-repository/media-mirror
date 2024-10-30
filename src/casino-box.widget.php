<?php

add_action('widgets_init',
	create_function( '', 'return register_widget("Casinobox_Widget");' )
);

/**
 * Adds Casinobox_Widget widget.
 */
class Casinobox_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Casinobox_Widget', // Base ID
			esc_html__( 'Casino Box', 'affiliatemirror' ), // Name
			array( 'description' => esc_html__( 'Displays a single casino', 'affiliatemirror' ) ) // Args
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$casinos = get_posts( array( 'post_type' => 'mm_casino', 'posts_per_page' => -1 ) );
		$selected_casino = ! empty( $instance['casino'] ) ? $instance['casino'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'casino' ) ); ?>"></label>
			<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'casino' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'casino' ) ); ?>">
				<option value=""><?php echo __( 'Choose Casino', 'affiliatemirror' ); ?></option>
				<?php foreach ( $casinos as $casino ) : ?>
					<option value="<?php echo $casino->ID; ?>"
					<?php if ( $selected_casino == $casino->ID ) { echo 'selected';} ?>><?php echo $casino->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['casino'] = ( ! empty( $new_instance['casino'] ) ) ? strip_tags( $new_instance['casino'] ) : '';

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$casino = ! empty( $instance['casino'] ) ? get_post( $instance['casino'] ) : null;
		if ( ! $casino ) {
			return;
		}
		echo $args['before_widget'];
		$meta = get_post_meta( $casino->ID );
	?>
		<div class="mm-casino-widget">
			<div class="casino-logo">
				<?php
				$imageAttachment = wp_get_attachment_image_src( get_post_thumbnail_id( $casino->ID ), 'original' );
				$logo = $imageAttachment[0];
				?>
				<a target="_blank" href="<?php echo esc_url( $meta['mediamirror_link'][0] ); ?>"><img src="<?php echo $logo; ?>" alt=""></a>
			</div>
			<div class="casino-specs">
				<table>
					<tr>
						<td class="casino-bonus">
							<span class="bonus-percent"><?php echo $meta['mediamirror_bonus_percent'][0]; ?>% <?php _e( 'up to', 'mediamirror' ); ?></span><br>
							<span class="bonus-amount"><strong><?php echo $meta['mediamirror_bonus_amount'][0]; ?> <?php echo $meta['mediamirror_bonus_currency'][0]; ?></strong> bonus</span><br>
							<span class="bonus-freespins"><strong><?php echo $meta['mediamirror_bonus_freespins'][0]; ?></strong> <?php _e( 'freespins', 'mediamirror' ); ?></span>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="rating">
							<?php echo mm_rating_stars( $meta['mediamirror_rating'][0] ); ?>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="button">
							<a target="_blank" href="<?php echo esc_url( $meta['mediamirror_link'][0] ); ?>" class="mediamirror-button">
							<?php _e( 'Play now!', 'mediamirror' ); ?>
						</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	<?php
		echo $args['after_widget'];
	}

} // class Casinobox_Widget
