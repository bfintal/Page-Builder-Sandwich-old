<?php

/**
 * Creates the view for Progress bar
 */

add_action( 'init', 'sandwich_progressbar', 11 );

function sandwich_progressbar() {
	
	add_shortcode( 'pbs_progressbar', 'sandwich_progressbar_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_progressbar',
        array(
            'label' => __( 'Progress Bar', 'pbsandwich' ),
            'listItemImage' => 'dashicons-plus',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr'  => 'title',
                    'type'  => 'text',
                ),
                array(
                    'label' => __( 'Toggled Content', 'pbsandwich' ),
                    'attr'  => 'content',
                    'type'  => 'textarea',
                ),
			),
        )
    );
	
}

function sandwich_progressbar_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => '',
    ) );
	
	global $_sandwich_progressbar_id;
	
	if ( ! isset( $_sandwich_progressbar_id ) ) {
		$_sandwich_progressbar_id = 1;
	}
	
	$id = strtolower( str_replace( ' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', $attr['title'] ) ) ) . '-' . $_sandwich_progressbar_id++;
			
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