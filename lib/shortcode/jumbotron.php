<?php

/**
 * Creates the view for Jetpack's contact form
 */

add_action( 'init', 'sandwich_jumbotron', 11 );

function sandwich_jumbotron() {
	
	add_shortcode( 'pbs_jumbotron', 'sandwich_jumbotron_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_jumbotron',
        array(
            'label' => __( 'Jumbotron', 'pbsandwich' ),
            'listItemImage' => 'dashicons-plus',
            'attrs' => array(
                array(
                    'label' => __( 'Full Width', 'pbsandwich' ),
                    'attr' => 'fullwidth',
                    'type' => 'checkbox',
					'value' => 'true',
                    'description' => __( 'Check to expand the Jumbotron into full width.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Content', 'pbsandwich' ),
                    'attr' => 'content',
                    'type' => 'textarea',
                ),
			),
        )
    );
	
}

function sandwich_jumbotron_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'fullwidth' => 'true',
    ) );
	
	global $_sandwich_jumbotron_id;
	
	if ( ! isset( $_sandwich_jumbotron_id ) ) {
		$_sandwich_jumbotron_id = 1;
	}
	
	$id = strtolower( str_replace( ' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', $attr['title'] ) ) ) . '-' . $_sandwich_jumbotron_id++;
			
	ob_start();
	
	?>
	
	<div class="sandwich">
		<div class="jumbotron" id="jumbotron-<?php echo esc_attr( $id ) ?>">
			<?php if ( $attr['fullwidth'] == 'true' ) {
				echo '<div class="container">';
			}
				echo wpautop( do_shortcode( $content ) );
				if ( $attr['fullwidth'] == 'true' ) {
					echo '</div>';
				}
			?>
		</div>
	</div>
	
	<?php
		
	return ob_get_clean();
}