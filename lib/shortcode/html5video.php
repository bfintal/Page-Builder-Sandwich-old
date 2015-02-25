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
	add_shortcode( 'html5video', 'sandwich_html5video_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	// Register Shortcake UI
	shortcode_ui_register_for_shortcode(
        'html5video',
        array(
            'label' => __( 'HTML5 self-hosted video', 'pbsandwich' ),
            'listItemImage' => 'dashicons-video-alt2',
            'attrs' => array(			
                array(
                    'label' => __( 'Video URL - WEBM', 'pbsandwich' ),
                    'attr' => 'videowebm',
                    'type' => 'attachment',
					'description' => __( 'Choose the WEBM video to be embedded.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Video URL - OGV', 'pbsandwich' ),
                    'attr' => 'videoogv',
                    'type' => 'attachment',
					'description' => __( 'Choose the OGV video to be embedded.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Video URL - MP4', 'pbsandwich' ),
                    'attr' => 'videomp4',
                    'type' => 'attachment',
					'description' => __( 'Choose the MP4 video to be embedded.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Video Billboard', 'pbsandwich' ),
                    'attr'  => 'poster',
                    'type'  => 'attachment',
					'description' => __( "Choose an image that will serve as the video's poster while the video has not been played.", 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Video Width', 'pbsandwich' ),
                    'attr' => 'width',
                    'type' => 'text',
					'value' => '100%',
					'description' => __( 'Enter the width of the video, in pixels or percentage.', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Video Height', 'pbsandwich' ),
                    'attr' => 'height',
                    'type' => 'text',
					'value' => '282',
					'description' => __( 'Enter the height of the video, in pixels or percentage.', 'pbsandwich' ),
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
	
	$attr['videowebm'] = wp_get_attachment_url( $attr['videowebm'] );
	$attr['videoogv'] = wp_get_attachment_url( $attr['videoogv'] );
	$attr['videomp4'] = wp_get_attachment_url( $attr['videomp4'] );
		
	ob_start();
	//echo 'URL of webm video is ' . wp_get_attachment_url( $attr['videowebm'] );
	?>
	
	<video class="html5video"<?php echo ( $attr['autoplay'] == "true" && ! is_admin() ? ' autoplay="' . esc_attr( $attr['autoplay'] ) . '"' : "" ) ?><?php echo ( $attr['preload'] == "auto" ? ' preload="' . esc_attr( $attr['preload'] ) . '"' : "" ) ?><?php echo ( $attr['controls'] == "disabled" ? " controls" : "" ) ?> poster="<?php echo esc_attr( $attr['poster'] ) ?>" style="width: <?php echo esc_attr( $attr['width'] ) ?>; height: <?php echo esc_attr( $attr['height'] ) ?>" >
		<source src="<?php echo $attr['videowebm'] ?>" type="video/webm">
		<source src="<?php echo $attr['videoogv'] ?>" type="video/ogv">
		<source src="<?php echo $attr['videomp4'] ?>" type="video/mp4">
	</video>
	
	<?php
		
	return ob_get_clean();
}