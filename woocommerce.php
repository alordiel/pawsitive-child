<?php
/**
 * Add custom field to the checkout page
 */

add_action('woocommerce_after_order_notes', 'paw_custom_checkout_field');
function paw_custom_checkout_field($checkout)
{
	$organizations = [
		['key' => '3234', 'org' => 'ARS', 'link' => 'https://example.com', 'img' => 'https://arsofia.com/wp-content/themes/arsofiav6.0/images/arsv5logo.png', 'desc' => 'Animal Rescue Sofia is a Bulgarian organization working to solve the stray dog and cats problem in Sofia. ',],
		['key' => '234', 'org' => 'ЕкоОбщностс', 'link' => 'https://example.com', 'img' => 'https://bepf-bg.org/bepf2015/wp-content/themes/ecosociety/lib/images/site-logo.png', 'desc' => 'Фондация „ЕкоОбщност допринася за устойчиво развитие на жизнената среда като вдъхновява и изгражда пълноценно, отговорно отношение и действия на човека към природата.',],
		['key' => '241445', 'org' => 'Човешката библиотека', 'link' => 'https://example.com', 'img' => 'https://www.shadowdance.info/magazine/wp-content/themes/combomag/images/ShadowDance-logo-glow-1.png', 'desc' => 'The Human Library Foundation is a non-profit association of Bulgarian writers, translators and avid readers whose mission is to promote human-evolving fiction both in Bulgaria and around the world.',],
	];

	$options = [];
	foreach ($organizations as $org) {
		$options[$org['key']] = json_encode([
			'name' => $org['org'],
			'img' => $org['img'],
			'desc' => $org['desc'],
			'link' => $org['link'],
			'id' => $org['key'],
		]);
	}

	echo '<div id="donation-organization-container"><h3>' . __('Select donation organization', 'paw') . '</h3>';
	woocommerce_form_field('donation_organization', array(
		'type' => 'radio',
		'class' => array(
			'donation-organizations'
		),
		'options' => $options,
		'required' => true,
		'label' => __('Select the donation organization', 'paw'),
		'placeholder' => __('Select organization', 'paw'),
	),

		$checkout->get_value('donation_organization'));
	include_once('checkout-page/script-and-style.php');
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
add_action('wp_enqueue_scripts', 'paw_organizations_checkout_page_scripts_and_styles');
function paw_organizations_checkout_page_scripts_and_styles()
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

	wp_enqueue_script('paw_popper_js', PAW_URL . '/assets/popper.min.js', 'jquery', '2.5.4');
	wp_enqueue_script(
		'paw_checkout_organizations_style',
		PAW_URL . '/assets/checkout-organizations.js',
		'paw_popper_js',
		filemtime(PAW_ABS . '/assets/checkout-organizations.js')
	);
}
