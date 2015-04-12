<?php

/**
 * Shortcode Template File
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Embed type for Nico Nico Douga video.
 */

function sandwich_niconico_video_embed_type () {
	$output['video'] = __( 'Video Player', 'pbsandwich' );
	$output['thumb'] = __( 'Thumbnail and Description', 'pbsandwich' );
	return $output;
}


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
					'label' => __( 'Embed Type', 'pbsandwich' ),
					'attr' => 'type',
					'type' => 'select',
					'description' => __( "Choose whether to embed the video itself or just a thumbnail and description of the video.", 'pbsandwich' ),
					'options' => sandwich_niconico_video_embed_type (),
				),
				array(
					'label' => __( 'Video URL', 'pbsandwich' ),
					'attr' => 'videourl',
					'type' => 'url',
					'description' => __( 'The URL of the Nico Nico Douga Video. Video URLs look like this: http://www.nicovideo.jp/watch/smxxxxxxx.', 'pbsandwich' ),
				),
                array(
                    'label' => __( 'Video Label', 'pbsandwich' ),
                    'attr' => 'label',
                    'type' => 'text',
					'description' => __( 'You can put a description of your video here, that will only appear if JavaScript is unsupported. If none is given, the description defaults to the video URL.', 'pbsandwich' ),
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
		'type' => 'video',		
		'videourl' => '',
		'label' => '',		
		'autoplay' => '',
		'preload' => '',
		'controls' => 'disabled',
	) );

	$videourl = esc_attr( $attr['videourl'] );
	$uri_parts = parse_url( $videourl );
	$split_parts = explode('/', $uri_parts['path']);
	
	$args = $attr['autoplay'] == "true" && ! is_admin() ? ' autoplay="true"' : "";
	$args .= $attr['preload'] == "auto" ? ' preload="auto"' : "";
	$args .= $attr['controls'] == "disabled" ? ' controls' : "";
	
	if ( ! empty ( $attr['label'] ) ) {
		$label = esc_attr( $attr['label'] );
	} else {
		$label = $videourl;
	}

	$label = 
	
	ob_start();
	
	?>
	
	<div class="sandwich pbs-niconicovideo">
		<?php 
		if ( $attr['type'] == 'thumb' ) {
			echo '<iframe id="pbs-nnd-video-thumb" width="312" height="185" scrolling="no" frameborder="0" src="http://ext.nicovideo.jp/thumb/' . esc_attr( $split_parts[2] ) . '"><a href="' . esc_attr( $attr['videourl'] ) . '" target="_blank">' . $label . '</a></iframe>';			
		}
		else {
			echo '<script type="text/javascript" charset="utf-8" src="http://ext.nicovideo.jp/thumb_watch/' . esc_attr( $split_parts[2] ) . '" scrolling="no" style="border:solid 1px #CCC;" frameborder="0"></script><noscript><a href="' . esc_attr( $attr['videourl'] ) . '" target="_blank">' . $label . '</a></noscript>';
		}
		?>
	</div>
	
	<?php
		
	return ob_get_clean();
}