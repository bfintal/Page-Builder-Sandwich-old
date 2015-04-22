<?php

/**
 * Creates the view for Jetpack's google maps shortcode
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_jetpack_googlemaps', 11 );

function sandwich_jetpack_googlemaps() {

    add_shortcode( 'pbs_googlemaps', 'sandwich_googlemaps' ); 
	
	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	// shortcode_ui_register_for_shortcode(
	//         'googlemaps',
	//         array(
	//             'label' => __( 'Jetpack' , 'pbsandwich' ) . ' ' . __( 'Google Maps', 'pbsandwich' ),
	//             'listItemImage' => 'dashicons-location-alt',
	//             'attrs' => array(
	//                 array(
	//                    'label' => 'blah',
	//                    'attr'  => 0,
	//                    'type'  => 'textarea',
	//                 ),
	// 		),
	//         )
	//     );

    shortcode_ui_register_for_shortcode(
        'pbs_googlemaps',
        array(
            'label' => __( 'Jetpack' , 'pbsandwich' ) . ' ' . __( 'Google Maps', 'pbsandwich' ),
            'listItemImage' => 'dashicons-location-alt',
            'attrs' => array(
                array(
                    'label' => __( 'Height', 'pbsandwich' ),
                    'attr' => 'height',
                    'type' => 'text',
					'description' => __( 'Enter height in pixels', 'pbsandwich' ),
					'value' => '300'
                ),
            ),
			'inner_content' => array(
				'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d59371640.34088013!2d-97.61066094515606!3d24.73520958531114!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1428309298913" width="600" height="450" frameborder="0" style="border:0"></iframe>',
				'type' => 'textarea',
				'description' => __( 'Enter iframe embed code generated from Google Maps.', 'pbsandwich' ),
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

    <div class='sandwich' <?php echo is_admin() ? 'style="pointer-events: none"' : '' ?>>
		<?php echo do_shortcode( '[googlemaps ' . esc_attr( $filteredcontent ) . ']' ) ?>
    </div>

    <?php
    return ob_get_clean();
}


function sandwich_jetpack_googlemaps_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'pbs_googlemaps', __( "Requires Jetpack's Shortcode Embed module", 'pbsandwich' ) );
}