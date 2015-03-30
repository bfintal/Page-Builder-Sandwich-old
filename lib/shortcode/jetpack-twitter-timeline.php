<?php

/**
 * Shortcode Template File for Twitter Timeline
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_jetpack_twitter_timeline', 11 );
function sandwich_jetpack_twitter_timeline() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
        'twitter-timeline',
        array(
            'label' => __( 'Jetpack Twitter Timeline', 'pbsandwich' ),
            'listItemImage' => 'dashicons-twitter',
            'attrs' => array(
                array(
                    'label' => __( 'Twitter Username', 'pbsandwich' ),
                    'attr' => 'username',
                    'type' => 'text',
                ),
                array(
                    'label' => __( 'Twitter Widget ID', 'pbsandwich' ),
                    'attr' => 'id',
                    'type' => 'text',
					'description' => __( 'Enter your Twitter Widget ID here. If you do not have one, log in to your Twitter account and generate your free Widget ID: https://en.support.wordpress.com/widgets/twitter-timeline-widget/', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Twitter Widget Width', 'pbsandwich' ),
                    'attr' => 'width',
                    'type' => 'text',
					'value' => '100%',
					'description' => __( 'Enter the width of the widget, in pixels or percentage.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Twitter Widget Height', 'pbsandwich' ),
                    'attr' => 'height',
                    'type' => 'text',
					'value' => '282',
					'description' => __( 'Enter the height of the widget, in pixels or percentage.', 'pbsandwich' ),
                ),
			),
        )
    );

	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_twitter_timeline_disabled' );
		return;
	}

	// Make sure the Jetpack shortcode module is turned on
	if ( ! Jetpack::is_module_active( 'shortcodes' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_twitter_timeline_disabled' );		
		return;
	}	
}

function sandwich_jetpack_twitter_timeline_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'twitter-timeline', __( "Requires Jetpack's Shortcode Embed module", 'pbsandwich' ) );
}
