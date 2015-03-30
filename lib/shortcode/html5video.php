<?php

/**
 * Shortcode Template File
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Create our shortcode
 */
add_action( 'init', 'sandwich_html5video', 11 );
function sandwich_html5video() {
	
	// Register shortcode
	add_shortcode( 'pbs_html5video', 'sandwich_html5video_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
		'pbs_html5video',
		array(
			'label' => __( 'HTML5 Video (Self-Hosted)', 'pbsandwich' ),
			'listItemImage' => 'dashicons-video-alt2',
			'attrs' => array(			
				array(
					'label' => __( 'Video Webm Format', 'pbsandwich' ),
					'attr' => 'webm',
					'type' => 'attachment',
					'description' => __( 'Choose the WEBM video to be embedded.', 'pbsandwich' ),
				),
				array(
					'label' => __( 'Video OGV Format', 'pbsandwich' ),
					'attr' => 'ogv',
					'type' => 'attachment',
					'description' => __( 'Choose the OGV video to be embedded.', 'pbsandwich' ),
				),
				array(
					'label' => __( 'Video MP4 Format', 'pbsandwich' ),
					'attr' => 'mp4',
					'type' => 'attachment',
					'description' => __( 'Choose the MP4 video to be embedded.', 'pbsandwich' ),
				),
				array(
					'label' => __( 'Video Poster', 'pbsandwich' ),
					'attr'	=> 'poster',
					'type'	=> 'attachment',
					'description' => __( "Choose an image that will serve as the video's poster while the video has not been played.", 'pbsandwich' ),
				),
				array(
					'label' => __( 'Autoplay Video', 'pbsandwich' ),
					'attr' => 'autoplay',
					'type' => 'checkbox',
					'description' => __( "Check this to let videos play right after they load.", 'pbsandwich' ),
					'value' => "true",
				),
				array(
					'label' => __( 'Preload Video', 'pbsandwich' ),
					'attr' => 'preload',
					'type' => 'checkbox',
					'description' => __( "Check this to let videos buffer while the page loads.", 'pbsandwich' ),
					'value' => "auto",
				),
				array(
					'label' => __( 'Hide video controls', 'pbsandwich' ),
					'attr' => 'controls',
					'type' => 'checkbox',
					'description' => __( "Check this to disable control of video playback.", 'pbsandwich' ),
					'value' => 'disabled',
				),
			),
		)
	);
	
}


/**
 * Render our shortcode
 */
function sandwich_html5video_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
		'webm' => '',
		'ogv' => '',
		'mp4' => '',
		'poster' => '',
		'autoplay' => '',
		'preload' => '',
		'controls' => 'disabled',
	) );
	
	if ( ! empty( $attr['webm'] ) ) { 
		$attr['webm'] = wp_get_attachment_url( $attr['webm'] ); 
	}
	if ( ! empty( $attr['ogv'] ) ) { 
		$attr['ogv'] = wp_get_attachment_url( $attr['ogv'] );
	}
	if ( ! empty( $attr['mp4'] ) ) { 
		$attr['mp4'] = wp_get_attachment_url( $attr['mp4'] );
	}

	$args = $attr['autoplay'] == "true" && ! is_admin() ? ' autoplay="true"' : "";
	$args .= $attr['preload'] == "auto" ? ' preload="auto"' : "";
	$args .= $attr['controls'] == "disabled" ? ' controls' : "";
	
	if ( ! empty( $attr['poster'] ) ) {
		$imageAttributes = wp_get_attachment_image_src( $attr['poster'], 'large' );
		if ( $imageAttributes ) {
			$args .= ' poster="' . esc_attr( $imageAttributes[0] ) . '"';
		}
	}

	ob_start();
	
	?>
	
	<div class="sandwich">
		<video class="html5video" <?php echo $args ?>>
			<?php 
				if ( ! empty( $attr['webm'] ) ) { 
					echo '<source src="' . esc_attr( $attr['webm'] ) . '" type="video/webm">';
				}
				if ( ! empty( $attr['ogv'] ) ) { 
					echo '<source src="' . esc_attr( $attr['ogv'] ) . '" type="video/ogv">';
				}
				if ( ! empty( $attr['mp4'] ) ) { 
					echo '<source src="' . esc_attr( $attr['mp4'] ) . '" type="video/mp4">';
				}
				?>
		</video>
	</div>
	
	<?php
		
	return ob_get_clean();
}