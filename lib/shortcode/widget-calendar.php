<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_calendar_widget', 11 );

function sandwich_calendar_widget() {
	
	add_shortcode( 'pbs_calendar_widget', 'sandwich_calendar_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_calendar_widget',
        array(
            'label' => __( 'Widget - Calendar', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Calendar', 'pbsandwich' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
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

function sandwich_calendar_widget_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Calendar', 'pbsandwich' ),
		'hide_title' => false
    ) );
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WP_Widget_Calendar', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}