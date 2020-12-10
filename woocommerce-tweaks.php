<?php
/**
 * Add custom field to the checkout page
 */

add_action('woocommerce_after_order_notes', 'paw_custom_checkout_field_order');
function paw_custom_checkout_field_order($checkout)
{
	echo '<div id="donation-organization-container"><h3>' . __('Select charity organization', 'paw') . '</h3>';
	$cart_products = WC()->cart->get_cart();
	$charity_total = 0;
	foreach ($cart_products as $product) {
		$post_id = !empty($product['variation_id']) ? $product['variation_id'] : $product['product_id'];
		$charity_total += (float) get_post_meta($post_id, 'charity_part', true) * $product['quantity'];
	}
	$charity_total = number_format($charity_total, 2);
	echo '<p>'. sprintf(__("The amount of %s € from your order will go to one of the organizations below. Please click on any of the logos to reveal more information about the organizations. A pop up will open with the detainls. To confirm selected organization click the 'select' button from the pop up.  Thank you.", 'paw'), $charity_total) . '</p>';
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


/**
 * Create new fields for variations
 *
 */
function paw_variation_settings_fields($loop, $variation_data, $variation)
{

	// Text Field
	woocommerce_wp_text_input(
		array(
			'id' => 'charity_part[' . $variation->ID . ']',
			'label' => __('Ammount that goes to selected charity organization', 'paw'),
			'placeholder' => '0.00',
			'desc_tip' => 'true',
			'description' => __('Make sure it number with decimal like 2.00', 'paw'),
			'value' => get_post_meta($variation->ID, 'charity_part', true)
		)
	);

}

add_action('woocommerce_product_after_variable_attributes', 'paw_variation_settings_fields', 10, 3);

/**
 * Save new fields for variations
 *
 */
function paw_save_variation_settings_fields($post_id)
{

	// Text Field
	$charity_value = $_POST['charity_part'][$post_id];
	if (!empty($charity_value)) {
		update_post_meta($post_id, 'charity_part', esc_attr($charity_value));
	}


}

add_action('woocommerce_save_product_variation', 'paw_save_variation_settings_fields', 10, 2);
