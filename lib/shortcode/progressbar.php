<?php

/**
 * Selections for button styles
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
                    'label' => __( 'Progress Bar Color', 'pbsandwich' ),
                    'attr' => 'color',
                    'type' => 'color',
					'value' => '',
                ),
                array(
                    'label' => __( 'Striped Progress Bar', 'pbsandwich' ),
                    'attr' => 'stripe',
                    'type' => 'checkbox',
					'value' => 'false',
                ),
                array(
                    'label' => __( 'Animated Stripes', 'pbsandwich' ),
                    'attr' => 'animated',
                    'type' => 'checkbox',
					'value' => 'false',
                ),					
                array(
                    'label' => __( 'Percentage', 'pbsandwich' ),
                    'attr' => 'percentage',
                    'type' => 'text',
					'value' => '50',
                    'description' => __( 'Enter the percentage filled in the progress bar. Value should be from 0 to 100.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Label', 'pbsandwich' ),
                    'attr'  => 'label',
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
        'color' => '',
        'stripe' => 'false',
        'animated' => 'false',
        'percentage' => '50',
        'label' => '',					
    ) );
	
	global $_sandwich_progressbar_id;
	
	if ( ! isset( $_sandwich_progressbar_id ) ) {
		$_sandwich_progressbar_id = 1;
	}

	$additions = " " . esc_html( $attr['type'] );
	if ( $attr['stripe'] == 'true' ) {
		$additions .= ' progress-bar-striped';
	}
	if ( $attr['animated'] == 'true' ) {
		$additions .= ' active';
	}
	
	$styling = 'width: ' . esc_attr( $attr['percentage'] ) . '%; ';	
	if ( $attr['color'] != '' ) {
		$styling .= 'background-color: ' . esc_attr( $attr['color'] ) . '; ';
	}	
	
	ob_start();
	
	?>
	
	<div class="sandwich">
		<div class="progress">
			<div id="progressbar-<?php echo esc_attr( $_sandwich_progressbar_id++ ) ?>" class="progress-bar<?php echo esc_attr( $additions ) ?>" role="progressbar" aria-valuemin="0" aria-valuenow="<?php echo esc_attr( $attr['percentage'] ) ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo $styling ?>">
				<span><?php echo esc_html( $attr['label'] ) ?></span>
			</div>
		</div>
	</div>
	
	<?php
		
	return ob_get_clean();
}