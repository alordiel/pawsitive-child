<?php
add_action( 'init', 'pp_phrase_lessons_cpt' );
function pp_phrase_lessons_cpt() {
	$labels = array(
		'name'               => _x( 'Organizations', 'post type general name', 'paw' ),
		'singular_name'      => _x( 'Organization', 'post type singular name', 'paw' ),
		'menu_name'          => _x( 'Organizations', 'admin menu', 'paw' ),
		'name_admin_bar'     => _x( 'Organization', 'add new on admin bar', 'paw' ),
		'add_new'            => _x( 'Add New', 'Organization', 'paw' ),
		'add_new_item'       => __( 'Add New', 'paw' ),
		'new_item'           => __( 'New organization', 'paw' ),
		'edit_item'          => __( 'Edit organization', 'paw' ),
		'view_item'          => __( 'View organization', 'paw' ),
		'all_items'          => __( 'All organizations', 'paw' ),
		'search_items'       => __( 'Search organizations', 'paw' ),
		'parent_item_colon'  => __( 'Parent organizations:', 'paw' ),
		'not_found'          => __( 'No organization found.', 'paw' ),
		'not_found_in_trash' => __( 'No organization found in Trash.', 'paw' )
	);

	$args = array(
		'labels'              => $labels,
		'description'         => __( 'Description.', 'paw' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'organization' ),
		'capability_type'     => 'post',
		'exclude_from_search' => false,
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => null,
		'supports'            => array( 'title', 'editor', 'excerpt' )
	);
	register_post_type( 'organization', $args );
}

//making the meta box (Note: meta box != custom meta field)
add_action( 'add_meta_boxes', 'paw_statistic_html_box' );
function paw_statistic_html_box() {
	add_meta_box(
		'statistics_box',
		'Organization\'s earnings and payments',
		'paw_add_statistics_to_the_organization',
		'organization',
		'normal',
		'high'
	);
}

//showing custom form fields
function paw_add_statistics_to_the_organization() {

	global $post;
	$statistics = get_post_meta( $post->ID, 'pwa_statistics', true );
	$payment_name = [
		'paypal' => __('Paypal', 'paw'),
		'direct-debit' => __('Bank transfer', 'paw'),
		'cash' => __('Cash', 'paw'),
		'other' => __('Other', 'paw'),
	];
	?>

	<?php if ( !empty( $statistics ) ) : ?>
		<?php $left_to_pay = (float) ( $statistics['received'] - $statistics['total_paid'] ); ?>
		<p style="font-size: 1rem"><strong><?php _e('Total earned', 'paw') ?>: <?php echo (float) $statistics['received']; ?>€</strong></p>
		<p style="font-size: 1rem"><strong><?php _e('Total paid', 'paw') ?>: <?php echo (float) $statistics['total_paid']; ?>€</strong></p>
		<p style="font-size: 1rem"><strong><?php _e('Left for payment', 'paw') ?>: <?php echo $left_to_pay; ?> €</strong>
			<small><a href="" id="copy-amount" data-amount="<?php echo $left_to_pay ?>"><?php _e('(pay)', 'paw') ?> </a></small>
		</p>

		<?php if ( ! empty($statistics['payments']) ) : ?>
			<h3><?php _e('Previous payments', 'paw'); ?></h3>
			<table class="wp-list-table widefat fixed striped">
				<thead>
				<tr>
					<th><?php _e('Date','paw'); ?></th>
					<th><?php _e('Amount','paw'); ?></th>
					<th><?php _e('Method','paw'); ?></th>
					<th style="width: 50%"><?php _e('Notes','paw'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( array_reverse( $statistics['payments'] ) as $payment ) : ?>

					<tr>
						<td><?php echo $payment['date'] ; ?></td>
						<td><?php echo $payment['amount'] ; ?> €</td>
						<td><?php echo $payment_name[ $payment['type'] ] ; ?></td>
						<td><?php echo $payment['notes'] ; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

		<?php endif; ?>

		<script>
			document.getElementById('copy-amount').addEventListener('click',function(e){
				e.preventDefault();
				let paymentField = document.getElementById("_post_meta_payment-amount_0");
				paymentField.value = e.target.dataset.amount;
				paymentField.scrollIntoView({ behavior: "smooth" });
			});
		</script>

	<?php else: ?>

		<p><?php _e('No payments and earning where found', 'paw'); ?></p>

	<?php endif; ?>

	<?php
}


add_action( 'save_post_organization', 'paw_on_save_of_organization_payment', 20);
function paw_on_save_of_organization_payment( $post_id ) {

	$old_stats = get_post_meta($post_id, 'pwa_statistics',true);

	if ( empty( $old_stats ) ) {
		$old_stats = [
			'payments' => [],
			'received' => 0,
		];
	}

	$amount    = get_post_meta($post_id,'payment-amount', true);
	$notes     = get_post_meta($post_id,'payment-notes', true);
	$type      = get_post_meta($post_id,'payment-type', true);
	$date      = get_post_meta($post_id,'payment-date', true);

	if ( !empty( $amount ) ) {

		$new_payment = [
			'amount' => $amount,
			'notes' => $notes,
			'type' => $type,
			'date' => $date,
		];

		if( isset( $old_stats['payments'] ) ) {
			$old_stats['payments'][] = $new_payment;
			$old_stats['total_paid'] +=  (float) $amount;
		} else {
			$old_stats['payments'] = [$new_payment];
			$old_stats['total_paid'] =  (float) $amount;
		}

		update_post_meta($post_id, 'pwa_statistics', $old_stats);

		delete_post_meta($post_id, 'payment-amount');
		delete_post_meta($post_id, 'payment-notes');
		delete_post_meta($post_id, 'payment-type');
		delete_post_meta($post_id, 'payment-date');
	}
}
