<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_categories_widget', 11 );

function sandwich_categories_widget() {
	
	add_shortcode( 'pbs_categories_widget', 'sandwich_categories_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_categories_widget',
        array(
            'label' => __( 'Widget - Categories', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Categories', 'pbsandwich' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
                array(
					'label' => __( 'Display as dropdown ', 'default' ),
                    'attr'  => 'dropdown',
                    'type'  => 'checkbox',
					'value' => false,
                ),
				array(
					'label' => __( 'Show post counts', 'default' ),
					'attr'  => 'count',
					'type'  => 'checkbox',
					'value' => false,
				),
				array(
					'label' => __( 'Show hierarchy', 'default' ),
					'attr'  => 'hierarchical',
					'type'  => 'checkbox',
					'value' => false,
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

function sandwich_categories_widget_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Categories', 'pbsandwich' ),
		'count' => '0',
		'dropdown' => '0',
		'hierarchical' => '0',
		'hide_title' => false
    ) );
	
	$attr['count'] = $attr['count'] === 'true' || $attr['count'] === true ? '1' : '0';
	$attr['dropdown'] = $attr['dropdown'] === 'true' || $attr['dropdown'] === true ? '1' : '0';
	$attr['hierarchical'] = $attr['hierarchical'] === 'true' || $attr['hierarchical'] === true ? '1' : '0';
		
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WP_Widget_Categories', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}