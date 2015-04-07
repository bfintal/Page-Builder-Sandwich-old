<?php

/**
 * Shortcode Template File
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_niconico_video', 11 );
function sandwich_niconico_video() {
	
	// Register shortcode
	add_shortcode( 'pbs_niconico_video', 'sandwich_niconico_video_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	if ( ! is_admin() ) {
		return;
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
		'pbs_niconico_video',
		array(
			'label' => __( 'Nico Nico Douga Video', 'pbsandwich' ),
			'listItemImage' => 'dashicons-video-alt2',
			'attrs' => array(			
				array(
					'label' => __( 'Video URL', 'pbsandwich' ),
					'attr' => 'videourl',
					'type' => 'url',
					'description' => __( 'The URL of the Nico Nico Douga Video. Video URLs look like this: http://www.nicovideo.jp/watch/smxxxxxxx.', 'pbsandwich' ),
				),
				// array(
					// 'label' => __( 'Autoplay Video', 'pbsandwich' ),
					// 'attr' => 'autoplay',
					// 'type' => 'checkbox',
					// 'description' => __( "Check this to let videos play right after they load.", 'pbsandwich' ),
					// 'value' => "true",
				// ),
				// array(
					// 'label' => __( 'Preload Video', 'pbsandwich' ),
					// 'attr' => 'preload',
					// 'type' => 'checkbox',
					// 'description' => __( "Check this to let videos buffer while the page loads.", 'pbsandwich' ),
					// 'value' => "auto",
				// ),
				// array(
					// 'label' => __( 'Hide video controls', 'pbsandwich' ),
					// 'attr' => 'controls',
					// 'type' => 'checkbox',
					// 'description' => __( "Check this to disable control of video playback.", 'pbsandwich' ),
					// 'value' => 'disabled',
				// ),
			),
		)
	);
	
}


/**
 * Render our shortcode
 */
function sandwich_niconico_video_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
		'videourl' => '',
		'autoplay' => '',
		'preload' => '',
		'controls' => 'disabled',
	) );

	$uri_parts = parse_url( esc_attr( $attr['videourl'] ) );
	$split_parts = explode('/', $uri_parts['path']);
	
	$args = $attr['autoplay'] == "true" && ! is_admin() ? ' autoplay="true"' : "";
	$args .= $attr['preload'] == "auto" ? ' preload="auto"' : "";
	$args .= $attr['controls'] == "disabled" ? ' controls' : "";
	
	ob_start();
	
	?>
	
	<div class="sandwich niconicovideo">
		<?php echo '<script type="text/javascript" charset="utf-8" src="http://ext.nicovideo.jp/thumb_watch/' . esc_attr( $split_parts[2] ) . '"></script>' ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}