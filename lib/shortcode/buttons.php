<?php

/**
 * Creates the view for Bootstrap Buttons
 */

add_action( 'init', 'sandwich_buttons', 11 );

function sandwich_buttons() {
	
	add_shortcode( 'pbs_buttons', 'sandwich_buttons_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_buttons',
        array(
            'label' => __( 'Buttons', 'pbsandwich' ),
            'listItemImage' => 'dashicons-plus',
            'attrs' => array(
                array(
                    'label' => __( 'Button Type', 'pbsandwich' ),
                    'attr'  => 'type',
                    'type'  => 'text',
                ),
                array(
                    'label' => __( 'Button Style', 'pbsandwich' ),
                    'attr'  => 'bstyle',
                    'type'  => 'select',
                ),
                array(
                    'label' => __( 'Button Caption', 'pbsandwich' ),
                    'attr'  => 'content',
                    'type'  => 'textarea',
                ),
                array(
                    'label' => __( 'Custom Button Color', 'pbsandwich' ),
                    'attr'  => 'cbuttoncolor',
                    'type'  => 'color',
                ),
                array(
                    'label' => __( 'Custom Button Border', 'pbsandwich' ),
                    'attr'  => 'cbuttonborder',
                    'type'  => 'text',
                ),
			),
        )
    );
	
}

function sandwich_buttons_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => '',
    ) );
	
	global $_sandwich_buttons_id;
	
	if ( ! isset( $_sandwich_buttons_id ) ) {
		$_sandwich_buttons_id = 1;
	}
	
	$id = strtolower( str_replace( ' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', $attr['title'] ) ) ) . '-' . $_sandwich_buttons_id++;
			
	ob_start();
	
	?>
	
	<div class="sandwich">
		<div class="panel panel-default toggle">
			<div class="panel-body">
				<a data-toggle="collapse" href="#<?php echo esc_attr( $id ) ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $id ) ?>"><?php echo esc_html( $attr['title'] ) ?></a>
				<div class="collapse" id="<?php echo esc_attr( $id ) ?>"><?php echo wpautop( do_shortcode( $content ) ) ?></div>
			</div>
		</div>
	</div>
	
	<?php
		
	return ob_get_clean();
}