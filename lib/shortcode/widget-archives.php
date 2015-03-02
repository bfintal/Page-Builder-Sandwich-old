<?php

/**
 * Creates the view for the archives widget
 */

add_action( 'init', 'sandwich_archives_widget', 11 );

function sandwich_archives_widget() {
	
	add_shortcode( 'pbs_archives_widget', 'sandwich_archives_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_archives_widget',
        array(
            'label' => __( 'Widget - Archives', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Archives', 'pbsandwich' ),
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
					'label' => __( 'Hide widget title', 'pbsandwich' ),
					'attr'  => 'hide_title',
					'type'  => 'checkbox',
					'value' => false,
				),
			),
        )
    );
	
}

function sandwich_archives_widget_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Archives', 'pbsandwich' ),
		'count' => '0',
		'dropdown' => '0',
		'hide_title' => false
    ) );
	
	$attr['count'] = $attr['count'] === 'true' || $attr['count'] === true ? '1' : '0';
	$attr['dropdown'] = $attr['dropdown'] === 'true' || $attr['dropdown'] === true ? '1' : '0';
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WP_Widget_Archives', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}