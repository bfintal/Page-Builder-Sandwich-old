<?php

/**
 * Selections unique to the Bootstrap Buttons
 */

function sandwich_buttons_type_selection() {
	$output = array();
	$output['button'] = "Button Element";
	$output['input'] = "Form Input Element";
	$output['hyperlink'] = "Anchor Hyperlink";
	return $output;
}

/**
 * Selections for button function
 */

function sandwich_buttons_function_selection() {
	$output = array();
	$output['button'] = "Simple Button";
	$output['submit'] = "Submit Button";
	$output['reset'] = "Reset Button";
	return $output;
}

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
	$output['custom'] = "Customized";	
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
	$output['btn-lg'] = "large";
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
                    'label' => __( 'Button Type', 'pbsandwich' ),
                    'attr' => 'type',
                    'type' => 'select',
					'options' => sandwich_buttons_type_selection(),
					'description' => __( 'Choose the HTML type of buttons to render. It is generally recommended to use the dedicated button form element for the best compatibility.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Function', 'pbsandwich' ),
                    'attr' => 'role',
                    'type' => 'select',
					'options' => sandwich_buttons_function_selection(),
					'value' => 'button',
					'description' => __( 'Choose the role of the button to use.', 'pbsandwich' ),
                ),				
                array(
                    'label' => __( 'Button Style', 'pbsandwich' ),
                    'attr' => 'bstyle',
                    'type' => 'select',
					'options' => sandwich_buttons_style_selection(),
					'description' => __( 'Choose the styling of buttons to use.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Caption', 'pbsandwich' ),
                    'attr' => 'caption',
                    'type' => 'text',
					'value' => 'Click Here',
					'description' => __( 'Enter the text to apply to the button.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Size', 'pbsandwich' ),
                    'attr' => 'size',
                    'type' => 'select',
					'options' => sandwich_buttons_size_selection(),
					'description' => __( 'Choose the size to render the button.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Full-width Buttons', 'pbsandwich' ),
                    'attr' => 'blocklevel',
                    'type' => 'checkbox',
					'value' => 'true',
					'description' => __( 'Check this box to expand the buttons into full width.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Render Button in Active state', 'pbsandwich' ),
                    'attr' => 'active',
                    'type' => 'checkbox',
					'value' => 'true',
					'description' => __( 'Check this box to render the buttons in an active state, ie. clicked.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Render Button in Disabled state', 'pbsandwich' ),
                    'attr' => 'disabled',
                    'type' => 'checkbox',
					'value' => 'true',
					'description' => __( 'Check this box to render the buttons in a disabled state.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Custom Button Color', 'pbsandwich' ),
                    'attr' => 'cbuttoncolor',
                    'type' => 'color',
                ),
                array(
                    'label' => __( 'Custom Button Border', 'pbsandwich' ),
                    'attr' => 'cbuttonborder',
                    'type' => 'text',
					'value' => '6px',
                ),
			),
        )
    );
	
}

function sandwich_buttons_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'type' => 'button',
        'role' => 'button',		
        'bstyle' => 'btn-default',
        'caption' => 'Click Here',
        'size' => 'btn-md',
        'blocklevel' => 'false',
        'active' => 'false',
        'disabled' => 'false',
        'cbuttoncolor' => '#ffffff',
        'cbuttonborder' => '6px',													
    ) );
	
	global $_sandwich_buttons_id;
	
	if ( ! isset( $_sandwich_buttons_id ) ) {
		$_sandwich_buttons_id = 1;
	}
	
	$id = strtolower( str_replace( ' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', $attr['type'] ) ) ) . '-' . $_sandwich_buttons_id++;
	
	$btype = "button";
	$addition = '';
	
	switch ( $attr['type'] ) {
		case 'input' :
			$btype = "input";
			$addition = ' type="' . $role . '" value="' . esc_attr( $caption ) . '"';			
		break;
		case 'hyperlink' :
			$btype = "a";
			$addition = ' href="#" role="' . $role . '"';
		break;
		case 'button' :
		default :
			$btype = "button";
			$addition = ' type="' . $role . '"';
		break;
	}

	$btnclass = " " . esc_attr( $bstyle ) . " " . esc_attr( $size );

	ob_start();
	
	?>
	
	<div class="sandwich">

		<?php echo '<' . $btype . ' id="buttons-' . esc_attr( $id ) . '" class="btn' . $btnclass . '"' . $addition . '>'; 			
		if ( $btype == 'button' || $btype == 'a' ) {
			echo esc_attr( $caption );
			echo '</' . $btype . '>'			
		} ?>

	</div>
	
	<?php
		
	return ob_get_clean();
}