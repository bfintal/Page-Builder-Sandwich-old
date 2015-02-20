<?php

/**
 * Creates the view for Jetpack's google maps shortcode
 */

add_action( 'init', 'sandwich_jetpack_googlemaps', 11 );

function sandwich_jetpack_googlemaps() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'googlemaps',
        array(
            'label' => __( 'Jetpack' , 'jetpack' ) . ' ' . _x( 'Google Maps', 'Module Name', 'jetpack' ),
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

    add_shortcode( 'sandwich-googlemaps', 'sandwich_googlemaps' ); 

    shortcode_ui_register_for_shortcode(
        'sandwich-googlemaps',
        array(
            'label' => 'Jetpack Google Maps',
            'listItemImage' => 'dashicons-location-alt',
            'attrs' => array(
                array(
                    'label' => 'IFrame embed code',
                    'attr' => 'content',
                    'type' => 'textarea',
					'description' => 'Enter embed code generated from Google Maps.',
                ),
                array(
                    'label' => 'Height',
                    'attr' => 'height',
                    'type' => 'text',
					'description' => 'Enter height in pixels',
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
		add_action( 'print_media_templates', 'sandwich_jetpack_shortcodes_googlemaps_disabled' );		
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

    <div<?php echo is_admin() ? ' style="pointer-events: none' : '' ?>">
	   		<?php echo do_shortcode( '[googlemaps ' . $filteredcontent . ']' ) ?>
    </div>

    <?php
    return ob_get_clean();
}

function sandwich_jetpack_googlemaps_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'sandwich-googlemaps', "Requires Jetpack plugin" );
}

function sandwich_jetpack_shortcodes_googlemaps_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'sandwich-googlemaps', "Requires Jetpack's shortcode embed module" );
}