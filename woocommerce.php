<?php
/**
 * Add custom field to the checkout page
 */

add_action('woocommerce_after_order_notes', 'paw_custom_checkout_field');
function paw_custom_checkout_field($checkout)
{
	echo '<div id="donation-organization-container"><h3>' . __('Select donation organization', 'paw') . '</h3>';

	woocommerce_form_field(
		'donation_organization',
		array(
			'type' => 'radio',
			'class' => array(
				'donation-organizations'
			),
			'required' => true,
			'label' => __('Select the donation organization', 'paw'),
			'placeholder' => __('Select organization', 'paw'),
		),
		$checkout->get_value('donation_organization')
	);

	include_once 'checkout-page/checkout-organizations.php';

	echo '</div>';

}


/**
 * Checkout Process
 */
add_action('woocommerce_checkout_process', 'paw_customised_checkout_field_process');
function paw_customised_checkout_field_process()
{

	if (!$_POST['donation_organization']) {
		wc_add_notice(__('Please select organization!', 'paw'), 'error');
	}
}

/**
 * Update the value given in custom field
 */
add_action('woocommerce_checkout_update_order_meta', 'paw_custom_checkout_field_update_order_meta');
function paw_custom_checkout_field_update_order_meta($order_id)
{

	if (!empty($_POST['donation_organization'])) {
		update_post_meta($order_id, 'donation_goes_to', sanitize_text_field($_POST['donation_organization']));
	}

}


/**
 * Enqueue styles and scripts for checkout page
 */
add_action('wp_enqueue_scripts', 'paw_organizations_checkout_page_scripts_and_styles1');
function paw_organizations_checkout_page_scripts_and_styles1()
{
	if (!is_checkout()) {
		return;
	}

	wp_enqueue_style(
		'paw_checkout_organizations_style',
		PAW_URL . '/assets/checkout-organizations.css',
		'',
		filemtime(PAW_ABS . '/assets/checkout-organizations.css')
	);

	wp_enqueue_script(
		'paw_checkout_organizations_style',
		PAW_URL . '/assets/checkout-organizations.js',
		'jquery',
		filemtime(PAW_ABS . '/assets/checkout-organizations.js')
	);
}




// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'hrx_variation_settings_fields', 10, 3 );

// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'hrx_save_variation_settings_fields', 10, 2 );

/**
 * Create new fields for variations
 *
*/
function hrx_variation_settings_fields( $loop, $variation_data, $variation ) {

	// Text Field
	woocommerce_wp_text_input(
		array(
			'id'          => '_print_file_link[' . $variation->ID . ']',
			'label'       => __( 'Print File Link', 'woocommerce' ),
			'placeholder' => 'http://',
			'desc_tip'    => 'true',
			'description' => __( 'Add your print file link here', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_print_file_link', true )
		)
	);

}

/**
 * Save new fields for variations
 *
*/
function hrx_save_variation_settings_fields( $post_id ) {

	// Text Field
	$print_file_link = $_POST['_print_file_link'][ $post_id ];
	if( ! empty( $print_file_link ) ) {
		update_post_meta( $post_id, '_print_file_link', esc_attr( $print_file_link ) );
	}


}
