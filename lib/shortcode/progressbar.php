<?php

/**
 * Selections for button styles
 */

function sandwich_progressbar_style_selection() {
	$output = array();
	$output['progress-bar-info'] = "Informational";
	$output['progress-bar-success'] = "Success";
	$output['progress-bar-warning'] = "Warning";
	$output['progress-bar-danger'] = "Danger";
	return $output;
}

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
                    'label' => __( 'Progress Bar Design', 'pbsandwich' ),
                    'attr' => 'type',
                    'type' => 'select',
					'options' => sandwich_progressbar_style_selection(),
					'description' => __( 'Choose the type of Progress Bar to use.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Striped Progress Bar', 'pbsandwich' ),
                    'attr' => 'stripe',
                    'type' => 'checkbox',
					'value' => 'false',
					'description' => __( 'Check this box to include stripes in the Progress Bar.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Animated Progress Bar', 'pbsandwich' ),
                    'attr' => 'animated',
                    'type' => 'checkbox',
					'value' => 'false',
					'description' => __( 'Check this box to animate the Progress Bar.', 'pbsandwich' ),
                ),					
                array(
                    'label' => __( 'Current Percentage', 'pbsandwich' ),
                    'attr' => 'percentage',
                    'type' => 'text',
					'value' => '50',
                    'description' => __( 'Enter the initial percentage of the progress bar.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Maximum Percentage', 'pbsandwich' ),
                    'attr' => 'max-percentage',
                    'type' => 'text',
					'value' => '100',
                    'description' => __( 'Enter the maximum percentage of the progress bar.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Percentage suffix', 'pbsandwich' ),
                    'attr'  => 'suffix',
                    'type'  => 'text',
					'value' => '',
                    'description' => __( 'Enter text to go along beside the progress bar percentage.', 'pbsandwich' ),
                ),
			),
        )
    );
	
}

function sandwich_progressbar_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'type' => 'progress-bar-info',
        'stripe' => 'false',
        'animated' => 'false',
        'percentage' => '50',
        'max-percentage' => '100',				
        'suffix' => '',					
    ) );
	
	global $_sandwich_progressbar_id;
	
	if ( ! isset( $_sandwich_progressbar_id ) ) {
		$_sandwich_progressbar_id = 1;
	}
	
	$id = strtolower( str_replace( ' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', $attr['title'] ) ) ) . '-' . $_sandwich_progressbar_id++;

	$additions = " " . esc_html( $attr['type'] );
	if ( $attr['stripe'] == 'true' ) {
		$additions .= ' progress-bar-striped';
	}
	if ( $attr['animated'] == 'true' ) {
		$additions .= ' active';
	}
			
	ob_start();
	
	?>
	
	<div class="sandwich">
		<div class="progress">
			<div id="progressbar-<?php echo esc_attr( $id ) ?>" class="progress-bar<?php echo $additions ?>" role="progressbar" aria-valuemin="0" aria-valuenow="<?php echo esc_html( $attr['percentage'] ) ?>" aria-valuemax="<?php echo esc_html( $attr['max-percentage'] ) ?>" style="width: <?php echo esc_html( $attr['percentage'] ) ?>%">
				<span><?php echo esc_html( $attr['percentage'] ) . "% " . esc_html( $attr['suffix'] ); ?></span>
			</div>
		</div>
	</div>
	
	<?php
		
	return ob_get_clean();
}