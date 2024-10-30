<div class="mm-casino-table-wrapper">
<table class="mm-casino-table">
	<thead>
		<th class="mm-casino-table__logo"><?php _e( 'Casino', 'mediamirror' ); ?></th>
		<th class="mm-casino-table__bonus"><?php _e( 'Bonus', 'mediamirror' ); ?></th>
		<th class="mm-casino-table__info"><?php _e( 'Bonus Offers / Info', 'mediamirror' ); ?></th>
		<th class="mm-casino-table__actions"><?php _e( 'Review', 'mediamirror' ); ?></th>
	</thead>
	<tbody>
	<?php
	foreach ( $rows as $row ) :
		extract( $row );
	?>
		<tr id="row-<?php echo $id; ?>">
			<td class="mm-casino-table__logo">
				<a target="_blank" href="<?php echo $affiliate_link; ?>">
					<img src="<?php echo $logo; ?>" alt="">
				</a>
			</td>

			<td class="mm-casino-table__bonus">
				<?php if ($bonus_percent > 0): ?>
				<div class="bonus-percent">
					<?php printf( __( '%s up to', 'mediamirror' ), "{$bonus_percent}%" ); ?>
				</div>
				<?php endif; ?>
				
				<?php if ($bonus_amount > 0): ?>
				<div class="bonus-amount">
					<?php printf( __( '<strong>%s</strong> bonus', 'mediamirror' ), "{$bonus_amount} {$currency}" ); ?>
				</div>
				<?php endif; ?>
					
				<?php if ($bonus_num_freespins > 0): ?>
				<div class="bonus-freespins">
					<?php printf( __( '<strong>%s</strong> freespins', 'mediamirror' ), $bonus_num_freespins ); ?>
				</div>
				<?php endif; ?>
			</td>

			<td class="mm-casino-table__info">
				<?php echo $bonus_info; ?>
			</td>

			<td class="mm-casino-table__actions">
				<?php echo mm_rating_stars( $rating ); ?><br>
				<?php if ( $has_review ) : ?>
					<a href="<?php echo $review_link; ?>" class="mm-casino-table__review-btn">
						<?php _e( 'Review', 'mediamirror' ); ?>
					</a>
				<?php endif; ?>
				<a target="_blank" href="<?php echo $affiliate_link; ?>" class="mm-casino-table__play-btn">
					<?php _e( 'Play now!', 'mediamirror' ); ?>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>