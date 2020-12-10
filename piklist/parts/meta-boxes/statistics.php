<?php
/*
Title: Add new payment to the organization
Post Type: organization
*/

piklist( 'field', [
	'type' => 'text',
	'field' => 'payment-amount',
	'label' => __( 'Payment amount (EUR)', 'paw' ),
	'columns' => 2,
]);

piklist( 'field', [
	'type' => 'datepicker',
	'field' => 'payment-date',
	'label' => __( 'Payment date', 'paw' ),
	'columns' => 2,
	 'value' => date('d/M/Y' ),
	'options' => [
		'dateFormat'=>'dd/mm/yy',
		'firstDay'=>1,
	]
]);

piklist( 'field', [
	'type' => 'select',
	'field' => 'payment-type',
	'label' => __( 'Payment type', 'paw' ),
	'columns' => 4,
	'choices' => [
		'paypal' => __('Paypal', 'paw'),
		'direct-debit' => __('Bank transfer', 'paw'),
		'cash' => __('Cash', 'paw'),
		'other' => __('Other', 'paw'),
	]
]);

piklist( 'field', [
	'type' => 'textarea',
	'field' => 'payment-notes',
	'label' => __( 'Notes', 'paw' ),
	'columns' => 4,
]);
