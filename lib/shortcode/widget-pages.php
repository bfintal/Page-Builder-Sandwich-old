<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_pages_widget', 11 );

function sandwich_pages_widget() {
	
	add_shortcode( 'pbs_pages_widget', 'sandwich_pages_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_pages_widget',
        array(
            'label' => __( 'Widget - Pages', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Pages', 'pbsandwich' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
				array(
					'label' => __( 'Sort by', 'pbsandwich' ),
					'attr' => 'sortby',
					'type' => 'select',
					'value' => 'post_title',
					'options' => array(
						'post_title' => __( 'Page title', 'default' ),
						'menu_order' => __( 'Page order', 'default' ),
						'ID' => __( 'Page ID', 'default' ),
					),
				),
				array(
					'label' => __( 'Exclude pages', 'pbsandwich' ),
					'attr' => 'exclude',
					'type' => 'text',
					'value' => '',
					'description' => __( 'Page IDs, separated by commas', 'default' ),
				),
				array(
					'label' => __( 'Hide widget title', 'pbsandwich' ),
					'attr' => 'hide_title',
					'type' => 'checkbox',
					'value' => false,
				),
			),
        )
    );
	
}

function sandwich_pages_widget_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Pages', 'pbsandwich' ),
		'sortby' => 'post_title',
		'exclude' => '',
		'hide_title' => false,
    ) );
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WP_Widget_Pages', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}