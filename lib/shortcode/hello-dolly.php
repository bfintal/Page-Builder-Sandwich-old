<?php

/**
 * Hello Dolly Shortcode
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// For demo purposes only
if ( ! WP_DEBUG ) {
	return;
}

add_action( 'init', 'sandwich_hello_dolly', 11 );

function sandwich_hello_dolly() {
	
	add_shortcode( 'pbs_hello_dolly', 'sandwich_hello_dolly_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Only create the UI when in the admin
	if ( ! is_admin() ) {
		return;
	}
	
	// Create our UI
	shortcode_ui_register_for_shortcode(
        'pbs_hello_dolly',
        array(
            'label' => 'Hello Dolly',
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Hello', 'pbsandwich' ),
                    'attr'  => 'name',
                    'type'  => 'text',
					'value' => 'Dolly',
                ),
			),
        )
    );
	
}

function sandwich_hello_dolly_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'name' => 'Dolly',
    ) );
	
	ob_start();
	
	?>
	
	<div class="sandwich hello_dolly">
		<?php _e( 'Hello', 'pbsandwich' ) ?> <?php echo esc_html( $attr['name'] ) ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}