<?php

/**
 * Creates the Alerts selection
 */

function sandwich_alerts_type_selection() {
	$output = array();
	$output['alert-info'] = "Informational";
	$output['alert-success'] = "Success";
	$output['alert-warning'] = "Warning";
	$output['alert-danger'] = "Danger";
	return $output;
}

/**
 * Creates the view for Bootstrap Alerts
 */

add_action( 'init', 'sandwich_alerts', 11 );

function sandwich_alerts() {
	
	add_shortcode( 'pbs_alerts', 'sandwich_alerts_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_alerts',
        array(
            'label' => __( 'Alerts', 'pbsandwich' ),
            'listItemImage' => 'dashicons-plus',
            'attrs' => array(
                array(
                    'label' => __( 'Alert Type', 'pbsandwich' ),
                    'attr' => 'type',
                    'type' => 'select',
					'options' => sandwich_alerts_type_selection(),
                ),
                array(
                    'label' => __( 'Alert Content', 'pbsandwich' ),
                    'attr' => 'content',
                    'type' => 'textarea',
					'description' => __( 'Enter the contents of the Alert box here.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Dismissable Alerts', 'pbsandwich' ),
                    'attr' => 'dismissable',
                    'type' => 'checkbox',
					'value' => 'false',
                    'description' => __( 'Check this box to enable users to dismiss the Alert.', 'pbsandwich' ),
                ),
			),
        )
    );
	
}

function sandwich_alerts_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'type' => 'alert-info',
    ) );
	
	global $_sandwich_alerts_id;
	
	if ( ! isset( $_sandwich_alerts_id ) ) {
		$_sandwich_alerts_id = 1;
	}
	
	$id = strtolower( str_replace( ' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', $attr['type'] ) ) ) . '-' . $_sandwich_alerts_id++;
			
	$classes = " " . $attr['type'];
	if ( $attr['dismissable'] == 'true' ) {
		$classes .= ' alert-dismissable';
	}
			
	ob_start();
	
	?>
	
	<div class="sandwich">
		<div id="alert-<?php echo esc_attr( $id ) ?>" class="alert<?php echo esc_html( $classes ) ?>" role="alert">
			<?php if ( $attr['dismissable'] == 'true' ) {
				echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			}
			echo wpautop( do_shortcode( $content ) ) ?>
		</div>
	</div>
	
	<?php
		
	return ob_get_clean();
}