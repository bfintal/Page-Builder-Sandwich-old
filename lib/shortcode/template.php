<?php

/**
 * Shortcode Template File
 */


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_myshortcode', 11 );
function sandwich_myshortcode() {
	
	// Register shortcode
	add_shortcode( 'pbs_myshortcode', 'sandwich_myshortcode_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
        'pbs_myshortcode',
        array(
            'label' => __( 'My Shortcode', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Content', 'pbsandwich' ),
                    'attr'  => 'content',
                    'type'  => 'textarea',
                ),
                array(
                    'label' => __( 'Some Text', 'pbsandwich' ),
                    'attr'  => 'some_text',
                    'type'  => 'text',
                ),
                array(
                    'label' => __( 'Some Color', 'pbsandwich' ),
                    'attr'  => 'some_color',
                    'type'  => 'color',
					'value' => '#333333',
                ),
			),
        )
    );
	
}


/**
 * Render our shortcode
 */
function sandwich_myshortcode_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'some_text' => '',
		'some_color' => '',
    ) );
	
	ob_start();
	
	?>
	
	<div class="sandwich">
		<div class="myshortcode" style="background-color: <?php echo esc_attr( $attr['some_color'] ) ?>">
			<h3><?php echo esc_html( $attr['some_text'] ) ?></h3>
			<?php echo wpautop( do_shortcode( $content ) ) ?>
		</div>
	</div>
	
	<?php
		
	return ob_get_clean();
}