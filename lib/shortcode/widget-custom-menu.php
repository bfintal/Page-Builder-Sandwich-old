<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_custom_menu_widget', 11 );

function sandwich_custom_menu_widget() {
	
	add_shortcode( 'pbs_custom_menu_widget', 'sandwich_custom_menu_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	$options = array(
		'0' => sprintf( '— %s —', __( 'Select', 'pbsandwich' ) ),
	);
	
	$menus = wp_get_nav_menus();
	foreach ( $menus as $menu ) {
		$options[ $menu->term_id ] = $menu->name;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_custom_menu_widget',
        array(
            'label' => __( 'Widget - Custom Menu', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Custom Menu', 'pbsandwich' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
                array(
					'label' => __( 'Select Menu', 'pbsandwich' ),
                    'attr'  => 'nav_menu',
                    'type'  => 'select',
					'value' => false,
					'options' => $options,
                ),
				array(
					'label' => __( 'Hide widget title', 'pbsandwich' ),
					'attr'  => 'hide_title',
					'type'  => 'checkbox',
					'value' => false,
				),
			),
        )
    );
	
}

function sandwich_custom_menu_widget_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Categories', 'pbsandwich' ),
		'nav_menu' => '0',
		'hide_title' => false
    ) );
		
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WP_Nav_Menu_Widget', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}