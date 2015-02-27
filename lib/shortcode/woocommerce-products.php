<?php

/**
 * Creates the view for WooCommerce shortcodes
 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/
 */

add_action( 'init', 'sandwich_woocommerce_products', 11 );

function sandwich_woocommerce_products() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Make sure WooCommerce is activated
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'products',
        array(
            'label' => __( 'WooCommerce - ', 'woocommerce' ) . ' ' . __( 'Products', 'pbsandwich' ),
            'listItemImage' => 'dashicons-admin-generic woocommerce',
            'attrs' => array(
                array(
                    'label' => __( 'Item ID', 'pbsandwich' ),
                    'attr' => 'ids',
                    'type' => 'text',
					'description' => __( 'Enter the ID(s) to display by WordPress post ID. If multiple IDs are to be entered, separate them by a comma. NOTE: If the ID is specified, do not populate the SKU field.', 'pbsandwich' ),
					'value' => '',
                ),
                array(
                    'label' => __( 'Item SKU', 'pbsandwich' ),
                    'attr' => 'skus',
                    'type' => 'text',
					'description' => __( 'Enter the SKU(s) to display by SKU code. If multiple SKUs are to be entered, separate them by a comma. NOTE: If the SKU is specified, do not use populate the ID field.', 'pbsandwich' ),
					'value' => '',
                ),
                array(
                    'label' => __( 'Display ordering', 'pbsandwich' ),
                    'attr' => 'orderby',
                    'type' => 'select',
					'description' => __( 'Select the ordering of the Products to display.', 'pbsandwich' ),
					'value' => 'date',
					'options' => sandwich_woocommerce_display_order(),
                ),				
                array(
                    'label' => __( 'Display ordering criteria', 'pbsandwich' ),
                    'attr' => 'order',
                    'type' => 'select',
					'description' => __( 'Choose Descending to display your newest Product first, or Ascending to choose your oldest Product first.', 'pbsandwich' ),
					'value' => 'DESC',
					'options' => sandwich_woocommerce_display_dir(),
                ),
            ),
        )
    );
}