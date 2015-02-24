<?php

/**
 * Shortcode Template File for Twitter Timeline
 */


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_twitter_timeline', 11 );
function sandwich_twitter_timeline() {
	
	// Register shortcode
	add_shortcode( 'shortcake-twitter-timeline', 'sandwich_twitter_timeline_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
        'twitter-timeline',
        array(
            'label' => __( 'Twitter Timeline', 'pbsandwich' ),
            'listItemImage' => 'dashicons-twitter',
            'attrs' => array(
                array(
                    'label' => __( 'Twitter Username', 'pbsandwich' ),
                    'attr'  => 'username',
                    'type'  => 'textarea',
                ),
                array(
                    'label' => __( 'Twitter Widget ID', 'pbsandwich' ),
                    'attr'  => 'id',
                    'type'  => 'text',
                ),
                array(
                    'label' => __( 'Twitter Widget Width', 'pbsandwich' ),
                    'attr'  => 'width',
                    'type'  => 'text',
					'value' => '100%',
                ),
                array(
                    'label' => __( 'Twitter Widget Height', 'pbsandwich' ),
                    'attr'  => 'height',
                    'type'  => 'text',
                ),
			),
        )
    );
	
}

