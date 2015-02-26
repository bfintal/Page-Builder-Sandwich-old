<?php

/**
 * Creates the view for WooCommerce shortcodes
 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/
 */

add_action( 'init', 'sandwich_woocommerce_product_categories', 11 );

function sandwich_woocommerce_product_categories() {

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
        'product_categories',
        array(
            'label' => __( 'WooCommerce - ', 'woocommerce' ) . ' ' . __( 'Product Categories', 'pbsandwich' ),
            'listItemImage' => 'dashicons-admin-generic woocommerce',
            'attrs' => array(
                array(
                    'label' => __( 'Number of Products to display per page', 'pbsandwich' ),
                    'attr' => 'number',
                    'type' => 'text',
					'description' => __( 'You can choose how many Products to display from the specified Categories in each page here.', 'pbsandwich' ),
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
                    'label' => __( 'Hide Empty Categories', 'pbsandwich' ),
                    'attr' => 'hide_empty',
                    'type' => 'checkbox',
					'description' => __( 'Check this to hide Categories that do not have Products associated with them.', 'pbsandwich' ),
					'value' => '1',
                ),
                array(
                    'label' => __( 'Display Parent Category', 'pbsandwich' ),
                    'attr' => 'parent',
                    'type' => 'text',
					'description' => __( 'To display Products from top level Categories, leave it at 0.', 'pbsandwich' ),
					'value' => '0',
                ),						
                array(
                    'label' => __( 'Product Category IDs', 'pbsandwich' ),
                    'attr' => 'ids',
                    'type' => 'text',
					'description' => __( 'Select the IDs of the Categories to display. If multiple IDs are to be entered, separate them by a comma.', 'pbsandwich' ),
					'value' => '',
                ),
            ),
        )
    );
}