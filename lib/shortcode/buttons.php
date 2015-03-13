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
	$output['btn-link'] = "Classic Link";
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

	add_shortcode( 'pbs_button', 'sandwich_buttons_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}

	shortcode_ui_register_for_shortcode(
        'pbs_button',
        array(
            'label' => __( 'Buttons', 'pbsandwich' ),
            'listItemImage' => 'dashicons-plus',
            'attrs' => array(
                array(
                    'label' => __( 'Button Label', 'pbsandwich' ),
                    'attr' => 'caption',
                    'type' => 'text',
					'value' => 'Click Here',
                ),
                array(
                    'label' => __( 'Button Design', 'pbsandwich' ),
                    'attr' => 'design',
                    'type' => 'select',
					'options' => sandwich_buttons_button_type(),
					'description' => __( 'Choose the design to use. Ghost type renders the background color of the button transparent with a colored border.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Color Scheme', 'pbsandwich' ),
                    'attr' => 'bstyle',
                    'type' => 'select',
					'options' => sandwich_buttons_style_selection(),
					'description' => __( 'Choose the color scheme of the button.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Custom Button Background Color', 'pbsandwich' ),
                    'attr' => 'cbuttoncolor',
                    'type' => 'color',
					'description' => __( 'You can override the button color here. Leave this blank to use the default color scheme above.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Custom Button Border Color', 'pbsandwich' ),
                    'attr' => 'cbordercolor',
                    'type' => 'color',
					'description' => __( 'You can override the button border color here. Leave this blank to use the default color scheme above.', 'pbsandwich' ),
                ),				
                array(
                    'label' => __( 'Text Color', 'pbsandwich' ),
                    'attr' => 'textcolor',
                    'type' => 'color',
					'value' => '',
					'description' => __( 'The color of the label of the button. Leave this blank to use the default color', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Hover Color', 'pbsandwich' ),
                    'attr' => 'cbuttonhovercolor',
                    'type' => 'color',
					'description' => __( 'The color of the button when hovered over by the mouse cursor. Leave this blank to use the default color scheme above.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Border Hover Color', 'pbsandwich' ),
                    'attr' => 'cborderhovercolor',
                    'type' => 'color',
					'description' => __( 'The color of the border when hovered over by the mouse cursor. Leave this blank to use the default color scheme above.', 'pbsandwich' ),
                ),				
                array(
                    'label' => __( 'Text Hover Color', 'pbsandwich' ),
                    'attr' => 'texthovercolor',
                    'type' => 'color',
					'value' => '',
					'description' => __( 'The color of the label of the button when hovered over by the mouse cursor. Leave this blank to use the default color', 'pbsandwich' ),
                ),				
                array(
                    'label' => __( 'Button Size', 'pbsandwich' ),
                    'attr' => 'size',
                    'type' => 'select',
					'options' => sandwich_buttons_size_selection(),
					'description' => __( 'The size of the button.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Border Thickness', 'pbsandwich' ),
                    'attr' => 'cbuttonborder',
                    'type' => 'text',
					'value' => '',
					'description' => __( 'Enter a number in pixels. Borders are only applicable for ghost buttons.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Border Radius', 'pbsandwich' ),
                    'attr' => 'cbuttonradius',
                    'type' => 'text',
					'value' => '',
					'description' => __( 'Enter a number in pixels.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Link URL', 'pbsandwich' ),
                    'attr' => 'url',
                    'type' => 'url',
					'value' => '#',
                ),
                array(
                    'label' => __( 'Open link in a new window?', 'pbsandwich' ),
                    'attr' => 'target',
                    'type' => 'checkbox',
					'value' => 'true',
                ),
                array(
                    'label' => __( 'Full-width Button', 'pbsandwich' ),
                    'attr' => 'blocklevel',
                    'type' => 'checkbox',
					'value' => 'false',
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
        'cbordercolor' => '',
        'texthovercolor' => '',
        'cbuttonhovercolor' => '',
        'cborderhovercolor' => '',			
        'size' => 'btn-md',
        'cbuttonborder' => '',
        'cbuttonradius' => '',
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

	$styling = ' style="';

	if ( $attr['design'] == 'ghost' ) {
		$styling .= 'background-color: transparent;';
		$styling .= 'border-style: solid;';
		$styling .= 'border-color: ' . $attr['cbordercolor'] . '; ';
	}
	if ( $attr['textcolor'] != '' ) {
		$styling .= 'color: ' . $attr['textcolor'].'; ';
	}
	if ( $attr['cbuttoncolor'] != '' && $attr['design'] != 'ghost' ) {
		$styling .= 'background-color: ' . $attr['cbuttoncolor'].'; ';
	}
	if ( $attr['cbuttonborder'] != '' ) {
		$styling .= 'border: ' . $attr['cbuttonborder'].'px solid ' . $attr['cbordercolor'] . '; ';
	}
	if ( $attr['cbuttonradius'] != '' ) {
		$styling .= 'border-radius: ' . $attr['cbuttonradius'].'px; ';
	}

	$styling .= '"';

	$customstyle = '';
	
	if ( $attr['texthovercolor'] != '' ) {
		$customstyle .= 'color: ' . $attr['texthovercolor'].' !important; ';
	}
	if ( $attr['cbuttonhovercolor'] != '' && $attr['design'] != 'ghost' ) {
		$customstyle .= 'background-color: ' . $attr['cbuttonhovercolor'].' !important; ';
	}
	if ( $attr['cborderhovercolor'] != '' ) {
		$customstyle .= 'border-color: ' . $attr['cborderhovercolor'] . ' !important; ';
	}	

	if ( ! empty ($customstyle) ) {
		$customstyling = '<style> #button-' . esc_attr( $id ) . ':hover {' . $customstyle . '} </style>';
	}

	ob_start();

	if ( ! empty ($customstyle) ) {
		echo $customstyling;
	}

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