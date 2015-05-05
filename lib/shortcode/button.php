<?php

/**
 * Selections for button styles
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function sandwich_buttons_style_selection() {
	$output = array();
	$output['btn-default'] = __( "Default", 'pbsandwich' );
	$output['btn-primary'] = __( "Primary", 'pbsandwich' );
	$output['btn-success'] = __( "Success", 'pbsandwich' );
	$output['btn-info'] = __( "Informational", 'pbsandwich' );
	$output['btn-warning'] = __( "Warning", 'pbsandwich' );
	$output['btn-danger'] = __( "Danger", 'pbsandwich' );
	$output['btn-link'] = __( "Classic Link", 'pbsandwich' );
	return $output;
}

/**
 * Selections for button sizes
 */

function sandwich_buttons_size_selection() {
	$output = array();
	$output['btn-md'] = __( "Default", 'pbsandwich' );
	$output['btn-xs'] = __( "Extra Small", 'pbsandwich' );
	$output['btn-sm'] = __( "Small", 'pbsandwich' );
	$output['btn-lg'] = __( "Large", 'pbsandwich' );
	return $output;
}

/**
 * Selections for button sizes
 */

function sandwich_buttons_button_type() {
	$output = array();
	$output['normal'] = __( "Normal", 'pbsandwich' );
	$output['ghost'] = __( "Ghost", 'pbsandwich' );
	return $output;
}

/**
 * Selections for button alignment
 */

function sandwich_buttons_align() {
	$output = array();
	$output['left'] = __( "left", 'pbsandwich' );
	$output['center'] = __( "center", 'pbsandwich' );
	$output['right'] = __( "right", 'pbsandwich' );
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
            'label' => __( 'Button', 'pbsandwich' ),
            'listItemImage' => 'dashicons-plus',
            'attrs' => array(
                array(
                    'label' => __( 'Button Label', 'pbsandwich' ),
                    'attr' => 'label',
                    'type' => 'text',
					'value' => __( 'Click Here', 'pbsandwich' ),
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
                    'attr' => 'style',
                    'type' => 'select',
					'options' => sandwich_buttons_style_selection(),
					'description' => __( 'Choose the color scheme of the button.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Custom Button Background Color', 'pbsandwich' ),
                    'attr' => 'button_color',
                    'type' => 'color',
					'description' => __( 'You can override the button color here. Leave this blank to use the default color scheme above.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Custom Button Border Color', 'pbsandwich' ),
                    'attr' => 'border_color',
                    'type' => 'color',
					'description' => __( 'You can override the button border color here. Leave this blank to use the default color scheme above.', 'pbsandwich' ),
                ),				
                array(
                    'label' => __( 'Text Color', 'pbsandwich' ),
                    'attr' => 'text_color',
                    'type' => 'color',
					'value' => '',
					'description' => __( 'The color of the label of the button. Leave this blank to use the default color', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Hover Color', 'pbsandwich' ),
                    'attr' => 'button_hover_color',
                    'type' => 'color',
					'description' => __( 'The color of the button when hovered over by the mouse cursor. Leave this blank to use the default color scheme above.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Button Border Hover Color', 'pbsandwich' ),
                    'attr' => 'border_hover_color',
                    'type' => 'color',
					'description' => __( 'The color of the border when hovered over by the mouse cursor. Leave this blank to use the default color scheme above.', 'pbsandwich' ),
                ),				
                array(
                    'label' => __( 'Text Hover Color', 'pbsandwich' ),
                    'attr' => 'text_hover_color',
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
                    'attr' => 'border',
                    'type' => 'text',
					'value' => '',
					'description' => __( 'Enter a number in pixels. Borders are only applicable for ghost buttons.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Border Radius', 'pbsandwich' ),
                    'attr' => 'radius',
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
					'value' => 'false',
                ),
                array(
                    'label' => __( 'Alignment', 'pbsandwich' ),
                    'attr' => 'align',
                    'type' => 'select',
					'options' => sandwich_buttons_align(),
					'value' => 'center',
                ),
                array(
                    'label' => __( 'Full-width Button', 'pbsandwich' ),
                    'attr' => 'full_width',
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
        'label' => __( 'Click Here', 'pbsandwich' ),
        'style' => 'btn-default',
        'text_color' => '',
        'button_color' => '',
        'border_color' => '',
        'text_hover_color' => '',
        'button_hover_color' => '',
        'border_hover_color' => '',		
        'size' => 'btn-md',
        'border' => '',
        'radius' => '',
        'url' => '#',
        'target' => 'false',
		'align' => 'center',
        'full_width' => 'false',
    ) );

	global $_sandwich_buttons_id;

	if ( ! isset( $_sandwich_buttons_id ) ) {
		$_sandwich_buttons_id = 1;
	}

	$btnclass = " " . esc_attr( $attr['style'] ) . " " . esc_attr( $attr['size'] );

	if ( $attr['full_width'] == 'true' ) {
		$btnclass .= ' btn-block';
	}

	$appendices = ' href="' . esc_url( $attr['url'] ) . '"';
	if ( $attr['target'] == 'true' || $attr['target'] === true ) {
		$appendices .= ' target="_blank"';
	}

	$styling = ' style="';

	if ( $attr['design'] == 'ghost' ) {
		$styling .= 'background-color: transparent;';
		$styling .= 'border-style: solid;';
		
	} else {
		if ( $attr['button_color'] != '' ) {
			$styling .= 'background-color: ' . $attr['button_color'] . '; ';
		}
	}
	if ( $attr['border_color'] != '' ) {
		$styling .= 'border-color: ' . $attr['border_color'] . '; ';
	}
	if ( $attr['text_color'] != '' ) {
		$styling .= 'color: ' . $attr['text_color'] . '; ';
	}
	if ( $attr['border'] != '' ) {
		$styling .= 'border-width: ' . $attr['border'] . 'px; ';
	}
	if ( $attr['radius'] != '' ) {
		$styling .= 'border-radius: ' . $attr['radius'] . 'px; ';
	}

	$styling .= '"';

	$customstyle = '';
	
	if ( $attr['text_hover_color'] != '' ) {
		$customstyle .= 'color: ' . $attr['text_hover_color'] . ' !important; ';
	}
	if ( $attr['button_hover_color'] != '' && $attr['design'] != 'ghost' ) {
		$customstyle .= 'background-color: ' . $attr['button_hover_color'] . ' !important; ';
	}
	if ( $attr['border_hover_color'] != '' ) {
		$customstyle .= 'border-color: ' . $attr['border_hover_color'] . ' !important; ';
	}

	ob_start();

	if ( ! empty ( $customstyle ) ) {
		$bogus = is_admin() ? 'data-mce-bogus="1"' : '';
		?>
		<style <?php echo $bogus ?>>
		#pbs_button-<?php echo esc_attr( $_sandwich_buttons_id ) ?>:hover {
			<?php echo $customstyle ?>
		}
		</style>
		<?php
	}

	?>

	<div class="sandwich pbs_button pbs_button_align_<?php echo esc_attr( $attr['align'] ) ?>">
		<a id="pbs_button-<?php echo esc_attr( $_sandwich_buttons_id ) ?>" class="pbs_button btn<?php echo $btnclass ?>" <?php echo $appendices ?> <?php echo $styling ?>>
			<?php echo esc_attr( $attr['label'] ) ?>
		</a>
	</div>

	<?php
	
	$_sandwich_buttons_id++;

	return ob_get_clean();
}