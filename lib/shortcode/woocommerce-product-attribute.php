<?php

/**
 * Creates the view for WooCommerce shortcodes
 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/
 */

add_action( 'init', 'sandwich_woocommerce_product_attribute', 11 );

function sandwich_woocommerce_product_attribute() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Make sure WooCommerce is activated
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'product_attribute',
        array(
            'label' => __( 'WooCommerce - ', 'woocommerce' ) . ' ' . __( 'Product Attribute', 'pbsandwich' ),
            'listItemImage' => 'dashicons-admin-generic woocommerce',
            'attrs' => array(
                array(
                    'label' => __( 'Number of Products to display per page', 'pbsandwich' ),
                    'attr' => 'per_page',
                    'type' => 'text',
					'description' => __( 'You can choose how many Products to display in each page here.', 'pbsandwich' ),
					'value' => '12',
                ),
                array(
                    'label' => __( 'Number of columns ', 'pbsandwich' ),
                    'attr' => 'columns',
                    'type' => 'text',
					'description' => __( 'Select the amount of columns where the Products will display.', 'pbsandwich' ),
					'value' => '4',
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
					'description' => __( 'Choose Descending to display your most recent Product first, or Ascending to choose your oldest item first.', 'pbsandwich' ),
					'value' => 'DESC',
					'options' => sandwich_woocommerce_display_dir(),
                ),
                array(
                    'label' => __( 'Product Attribute', 'pbsandwich' ),
                    'attr' => 'attribute',
                    'type' => 'text',
					'description' => __( 'Enter the attribute of a Product here to be used as basis of display.', 'pbsandwich' ),
					'value' => '',
                ),
                array(
                    'label' => __( 'Product Attribute Filter', 'pbsandwich' ),
                    'attr' => 'filter',
                    'type' => 'text',
					'description' => __( 'Only Products whose Attribute matches what was entered in this field will be displayed.', 'pbsandwich' ),
					'value' => '',
                ),
            ),
        )
    );
}