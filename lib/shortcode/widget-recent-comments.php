<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_recent_comments_widget', 11 );

function sandwich_recent_comments_widget() {
	
	add_shortcode( 'pbs_recent_comments_widget', 'sandwich_recent_comments_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_recent_comments_widget',
        array(
            'label' => __( 'Widget - Recent Comments', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Recent Comments', 'pbsandwich' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
                array(
					'label' => __( 'Number of comments to show ', 'pbsandwich' ),
                    'attr'  => 'number',
                    'type'  => 'text',
					'value' => '5',
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

function sandwich_recent_comments_widget_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Recent Comments', 'pbsandwich' ),
		'number' => '5',
		'hide_title' => false
    ) );
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WP_Widget_Recent_Comments', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}