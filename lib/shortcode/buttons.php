<?php

/**
 * Selections for button styles
 */

function sandwich_buttons_style_selection() {
	$output = array();
	$output['btn-default'] = "Default";
	$output['btn-primary'] = "Primary";
	$output['btn-success'] = "Success";
	$output['btn-info'] = "Informational";
	$output['btn-warning'] = "Warning";
	$output['btn-danger'] = "Danger";
	return $output;
}

/**
 * Selections for button sizes
 */

function sandwich_buttons_size_selection() {
	$output = array();
	$output['btn-md'] = "Default";
	$output['btn-xs'] = "Extra Small";
	$output['btn-sm'] = "Small";
	$output['btn-lg'] = "Large";
	return $output;
}

/**
 * Selections for button sizes
 */

function sandwich_buttons_button_type() {
	$output = array();
	$output['normal'] = "Normal";
	$output['ghost'] = "Ghost";
	return $output;
}

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
                    'label' => __( 'Button Design', 'pbsandwich' ),
                    'attr' => 'design',
                    'type' => 'select',
					'options' => sandwich_buttons_button_type(),
					'description' => __( 'Choose the type of button to use. Ghost type makes the button translucent.', 'pbsandwich' ),
                ),						
                array(
                    'label' => __( 'Button Label', 'pbsandwich' ),
                    'attr' => 'caption',
                    'type' => 'text',
					'value' => 'Click Here',
					'description' => __( 'Enter the text to apply to the button.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Color', 'pbsandwich' ),
                    'attr' => 'bstyle',
                    'type' => 'select',
					'options' => sandwich_buttons_style_selection(),
					'description' => __( 'Choose the styling of buttons to use.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Text Color', 'pbsandwich' ),
                    'attr' => 'textcolor',
                    'type' => 'color',
					'value' => '',
					'description' => __( 'Choose the text color of the button.', 'pbsandwich' ),
                ),				
                array(
                    'label' => __( 'Custom Button Background / Border Color', 'pbsandwich' ),
                    'attr' => 'cbuttoncolor',
                    'type' => 'color',
					'description' => __( 'Choose the background color of the button.', 'pbsandwich' ),
                ),				
                array(
                    'label' => __( 'Button Size', 'pbsandwich' ),
                    'attr' => 'size',
                    'type' => 'select',
					'options' => sandwich_buttons_size_selection(),
					'description' => __( 'Choose the size to render the button.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Custom Button Border', 'pbsandwich' ),
                    'attr' => 'cbuttonborder',
                    'type' => 'text',
					'value' => '2',
					'description' => __( 'Enter a numerical value, in pixels, to define the thickness of the button border.', 'pbsandwich' ),					
                ),
                array(
                    'label' => __( 'Custom Button Border Radius', 'pbsandwich' ),
                    'attr' => 'cbuttonradius',
                    'type' => 'text',
					'value' => '6',
					'description' => __( 'Enter a numerical value, in pixels, to define the radius or roundness of the button.', 'pbsandwich' ),					
                ),		
                array(
                    'label' => __( 'URL to link to', 'pbsandwich' ),
                    'attr' => 'url',
                    'type' => 'url',
					'value' => '#',
					'description' => __( 'To use the button as a link, enter the URL where the button should go to when clicked.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Open link in new windows', 'pbsandwich' ),
                    'attr' => 'target',
                    'type' => 'checkbox',
					'value' => 'true',
					'description' => __( 'Check this box to open hyperlinks in a new window.', 'pbsandwich' ),
                ),			
                array(
                    'label' => __( 'Full-width Buttons', 'pbsandwich' ),
                    'attr' => 'blocklevel',
                    'type' => 'checkbox',
					'value' => 'false',
					'description' => __( 'Check this box to expand the buttons into full width.', 'pbsandwich' ),
                ),				
			),
        )
    );
}

function sandwich_buttons_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'design' => 'normal',		
        'caption' => 'Click Here',
        'bstyle' => 'btn-default',
        'textcolor' => '',
        'cbuttoncolor' => '',
        'size' => 'btn-md',
        'cbuttonborder' => '2',
        'cbuttonradius' => '6',		
        'url' => '#',
        'target' => 'true',		
        'blocklevel' => 'false',		
    ) );
	
	global $_sandwich_buttons_id;
	
	if ( ! isset( $_sandwich_buttons_id ) ) {
		$_sandwich_buttons_id = 1;
	}
	
	$id = strtolower( str_replace( ' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', $attr['bstyle'] ) ) ) . '-' . $_sandwich_buttons_id++;
	
	$btype = "button";

	$btnclass = " " . esc_attr( $attr['bstyle'] ) . " " . esc_attr( $attr['size'] );

	if ( $attr['blocklevel'] == 'true' ) {
		$btnclass .= ' btn-block';
	}

	$appendices = ' href="' . esc_attr( $attr['url'] ) . '"';
	if ( $attr['target'] == 'true' ) {
		$appendices .= ' target="_blank"';
	}

	$styling = ' styles="';

	if ( $attr['design'] == 'ghost' ) {
		$styling .= 'opacity: 0.5';
	}
	if ( $attr['textcolor'] != '' ) {
		$styling .= 'color: ' . $attr['textcolor'].'; ';
	}
	if ( $attr['cbuttoncolor'] != '' ) {
		$styling .= 'background-color: ' . $attr['cbuttoncolor'].'; ';
	}
	if ( $attr['cbuttonborder'] != '' ) {
		$styling .= 'border: ' . $attr['cbuttonborder'].'px solid black; ';
	}
	if ( $attr['cbuttonradius'] != '' ) {
		$styling .= 'border-radius: ' . $attr['cbuttonradius'].'px; ';
	}	
	
	$styling .= '"';

	ob_start();
	
	?>
	
	<div class="sandwich">

		<?php echo '<a id="button-' . esc_attr( $id ) . '" class="btn' . $btnclass . '"' . $appendices . $styling . '>'; 
			echo esc_attr( $attr['caption'] );
			echo '</a>';			
		 ?>

	</div>
	
	<?php
		
	return ob_get_clean();
}