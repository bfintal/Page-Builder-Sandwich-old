<?php

/**
 * Creates the view for Jetpack's google maps shortcode
 */

add_action( 'init', 'sandwich_jetpack_googlemaps', 11 );

function sandwich_jetpack_googlemaps() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

    add_shortcode( 'pbs_googlemaps', 'sandwich_googlemaps' ); 

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'googlemaps',
        array(
            'label' => __( 'Jetpack' , 'pbsandwich' ) . ' ' . __( 'Google Maps', 'pbsandwich' ),
            'listItemImage' => 'dashicons-location-alt',
            'attrs' => array(
                //array(
                //    'label' => '',
                //    'attr'  => 'content',
                //    'type'  => 'textarea',
                //),
			),
        )
    );

    shortcode_ui_register_for_shortcode(
        'pbs_googlemaps',
        array(
            'label' => __( 'Jetpack' , 'pbsandwich' ) . ' ' . __( 'Google Maps', 'pbsandwich' ),
            'listItemImage' => 'dashicons-location-alt',
            'attrs' => array(
                array(
                    'label' => __( 'IFrame embed code', 'pbsandwich' ),
                    'attr' => 'content',
                    'type' => 'textarea',
					'description' => __( 'Enter embed code generated from Google Maps.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Height', 'pbsandwich' ),
                    'attr' => 'height',
                    'type' => 'text',
					'description' => __( 'Enter height in pixels', 'pbsandwich' ),
					'value' => '300'
                ),
            ),
        )
    );	
	
	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_googlemaps_disabled' );
		return;
	}

	// Make sure the google maps shortcode module is turned on
	if ( ! Jetpack::is_module_active( 'shortcodes' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_googlemaps_disabled' );		
		return;
	}
	
}


function sandwich_googlemaps( $attr, $content = '' ) {
    $attr = wp_parse_args( $attr, array(
        'height' => '300'
    ) );

	preg_match( "/\<iframe.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/", $content, $matches );

	if ( count( $matches ) < 1 ) {
		return '';
	}

	$filteredcontent = $matches[1] . "&amp;w=100%&amp;h=" . $attr['height'];

    ob_start();
    ?>

    <div <?php echo is_admin() ? 'style="pointer-events: none"' : '' ?>>
		<?php echo do_shortcode( '[googlemaps ' . esc_attr( $filteredcontent ) . ']' ) ?>
    </div>

    <?php
    return ob_get_clean();
}


function sandwich_jetpack_googlemaps_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'pbs_googlemaps', __( "Requires Jetpack's Shortcode Embed module", 'pbsandwich' ) );
}