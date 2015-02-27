<?php

/**
 * Creates the view for WooCommerce shortcodes
 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/
 */

add_action( 'init', 'sandwich_woocommerce_add_to_cart', 11 );

function sandwich_woocommerce_add_to_cart() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Make sure WooCommerce is activated
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'add_to_cart',
        array(
            'label' => __( 'WooCommerce - ', 'woocommerce' ) . ' ' . __( 'Add to Cart', 'pbsandwich' ),
            'listItemImage' => 'dashicons-admin-generic woocommerce',
            'attrs' => array(
                array(
                    'label' => __( 'Product Item', 'pbsandwich' ),
                    'attr' => 'id',
                    'type' => 'select',
					'description' => __( 'Select the Product to show its price and Add to Cart button.', 'pbsandwich' ),
					'options' => sandwich_woocommerce_product_list(),
                ),
                array(
                    'label' => __( 'Styling', 'pbsandwich' ),
                    'attr' => 'style',
                    'type' => 'textarea',
					'description' => __( 'Enter custom styling for this element.', 'pbsandwich' ),
					'value' => '',
                ),
            ),
        )
    );
}