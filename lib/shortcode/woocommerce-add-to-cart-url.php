<?php

/**
 * Creates the view for WooCommerce shortcodes
 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/
 */

add_action( 'init', 'sandwich_woocommerce_add_to_cart_url', 11 );

function sandwich_woocommerce_add_to_cart_url() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Make sure WooCommerce is activated
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'add_to_cart_url',
        array(
            'label' => __( 'WooCommerce - ', 'woocommerce' ) . ' ' . __( 'Add to Cart URL', 'pbsandwich' ),
            'listItemImage' => 'dashicons-admin-generic woocommerce',
            'attrs' => array(
                array(
                    'label' => __( 'Item ID', 'pbsandwich' ),
                    'attr' => 'id',
                    'type' => 'text',
					'description' => __( 'Enter the WordPress post ID of the Product to echo its URL. NOTE: If the ID is specified, do not populate the SKU field.', 'pbsandwich' ),
					'value' => '',
                ),
                array(
                    'label' => __( 'Item SKU', 'pbsandwich' ),
                    'attr' => 'sku',
                    'type' => 'text',
					'description' => __( 'Enter the SKU code of the Product to echo its URL. NOTE: If the SKU is specified, do not use populate the ID field.', 'pbsandwich' ),
					'value' => '',
                ),
            ),
        )
    );
}