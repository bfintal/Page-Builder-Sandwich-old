<?php

/**
 * Creates the view for WooCommerce shortcodes
 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/
 */

add_action( 'init', 'sandwich_woocommerce_product_category', 11 );

function sandwich_woocommerce_product_category() {

	$displayOrder['author'] = __( 'Author', 'pbsandwich' );
	$displayOrder['date'] = __( 'Item Date', 'pbsandwich' );
	$displayOrder['title'] = __( 'Title', 'pbsandwich' );
	$displayOrder['rand'] = __( 'Randomized', 'pbsandwich' );

	$displayDir['ASC'] = __( 'Ascending', 'pbsandwich' );
	$displayDir['DESC'] = __( 'Descending', 'pbsandwich' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Make sure WooCommerce is activated
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'product_category',
        array(
            'label' => __( 'WooCommerce - ', 'woocommerce' ) . ' ' . __( 'Product Category', 'pbsandwich' ),
            'listItemImage' => 'dashicons-admin-generic woocommerce',
            'attrs' => array(
                array(
                    'label' => __( 'Number of Products in a Category to display per page', 'pbsandwich' ),
                    'attr' => 'per_page',
                    'type' => 'text',
					'description' => __( 'You can choose how many Products to display from a Category in each page here.', 'pbsandwich' ),
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
					'options' => $displayOrder,
                ),
                array(
                    'label' => __( 'Display ordering criteria', 'pbsandwich' ),
                    'attr' => 'order',
                    'type' => 'select',
					'description' => __( 'Choose Descending to display your most recent Product first, or Ascending to choose your oldest item first.', 'pbsandwich' ),
					'value' => 'DESC',
					'options' => $displayDir,
                ),
                array(
                    'label' => __( 'Product Category', 'pbsandwich' ),
                    'attr' => 'category',
                    'type' => 'text',
					'description' => __( 'Select the slug name of the Category of the Products to display. IDs will NOT WORK.', 'pbsandwich' ),
					'value' => '',
                ),
            ),
        )
    );
}