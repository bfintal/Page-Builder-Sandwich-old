<?php

/**
 * Shortcode Template File
 */


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
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
        'pbs_html5video',
        array(
            'label' => __( 'HTML5 Video (Self-Hosted)', 'pbsandwich' ),
            'listItemImage' => 'dashicons-video-alt2',
            'attrs' => array(			
                array(
                    'label' => __( 'Video Webm Format', 'pbsandwich' ),
                    'attr' => 'videowebm',
                    'type' => 'attachment',
					'description' => __( 'Choose the WEBM video to be embedded.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Video OGV Format', 'pbsandwich' ),
                    'attr' => 'videoogv',
                    'type' => 'attachment',
					'description' => __( 'Choose the OGV video to be embedded.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Video MP4 Format', 'pbsandwich' ),
                    'attr' => 'videomp4',
                    'type' => 'attachment',
					'description' => __( 'Choose the MP4 video to be embedded.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Video Poster', 'pbsandwich' ),
                    'attr'  => 'poster',
                    'type'  => 'attachment',
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
		'videowebm' => '',
		'videoogv' => '',
		'videomp4' => '',
        'poster' => '',
		'autoplay' => '',
        'preload' => '',
		'controls' => 'disabled',
    ) );
	
	if ( ! empty( $attr['videowebm'] ) ) { 
		$attr['videowebm'] = wp_get_attachment_url( $attr['videowebm'] ); 
	}
	if ( ! empty( $attr['videoogv'] ) ) { 
		$attr['videoogv'] = wp_get_attachment_url( $attr['videoogv'] );
	}
	if ( ! empty( $attr['videomp4'] ) ) { 
		$attr['videomp4'] = wp_get_attachment_url( $attr['videomp4'] );
	}

	$args = ( $attr['autoplay'] == "true" && ! is_admin() ? ' autoplay="' . esc_attr( $attr['autoplay'] ) . '"' : "" );
	$args .= ( $attr['preload'] == "auto" ? ' preload="' . esc_attr( $attr['preload'] ) . '"' : "" );
	$args .= ( $attr['controls'] == "disabled" ? " controls" : "" );	

	ob_start();
	?>
	
	<div class="sandwich">
		<video class="html5video"<?php echo $args ?> poster="<?php echo esc_attr( $attr['poster'] ) ?>" style="width: 100%; height: auto" >
			<?php 
				if ( ! empty( $attr['videowebm'] ) ) { 
					echo '<source src="' . esc_attr( $attr['videowebm'] ) . '" type="video/webm">';
				}
				if ( ! empty( $attr['videoogv'] ) ) { 
					echo '<source src="' . esc_attr( $attr['videoogv'] ) . '" type="video/ogv">';
				}
				if ( ! empty( $attr['videomp4'] ) ) { 
					echo '<source src="' . esc_attr( $attr['videomp4'] ) . '" type="video/mp4">';
				}
				?>
		</video>
	</div>
	
	<?php
		
	return ob_get_clean();
}