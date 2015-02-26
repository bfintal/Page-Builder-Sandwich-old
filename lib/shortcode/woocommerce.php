<?php

/**
 * Creates the view for WooCommerce shortcodes
 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/
 */

add_action( 'init', 'sandwich_woocommerce_init_editor_styles', 10 );
function sandwich_woocommerce_init_editor_styles() {
	if ( class_exists( 'WC_Frontend_Scripts' ) ) {
		
	}
}


add_action( 'init', 'sandwich_woocommerce_recent_products', 11 );

function sandwich_woocommerce_recent_products() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Make sure WooCommerce is activated
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'recent_products',
        array(
            'label' => __( 'WooCommerce', 'woocommerce' ) . ' ' . __( 'Recent Products', 'pbsandwich' ),
            'listItemImage' => 'dashicons-admin-generic woocommerce',
            'attrs' => array(
                array(
                    'label' => __( 'Content', 'pbsandwich' ),
                    'attr'  => 'per_page',
                    'type'  => 'text',
					'value' => '12',
                ),
                array(
                    'label' => __( 'Content', 'pbsandwich' ),
                    'attr'  => 'columns',
                    'type'  => 'text',
					'value' => '4',
                ),
                array(
                    'label' => __( 'Content', 'pbsandwich' ),
                    'attr'  => 'orderby',
                    'type'  => 'text',
					'value' => 'date',
                ),
                array(
                    'label' => __( 'Content', 'pbsandwich' ),
                    'attr'  => 'order',
                    'type'  => 'text',
					'value' => 'desc',
                ),
            ),
        )
    );
	
}