<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_jetpack_facebook_like_box_widget', 11 );

function sandwich_jetpack_facebook_like_box_widget() {
	
	add_shortcode( 'pbs_jetpack_facebook_like_box_widget', 'sandwich_jetpack_facebook_like_box_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	if ( ! is_admin() ) {
		return;
	}
	
	$numberOfPosts = array();
	for ( $i = 1; $i <= 10; $i++ ) {
		$numberOfPosts[ $i ] = $i;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_jetpack_facebook_like_box_widget',
        array(
            'label' => __( 'Jetpack Widget - Facebook Like Box', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress jetpack-logo',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Facebook Like Box', 'jetpack' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
				array(
					'label' => __( 'Facebook Page URL', 'jetpack' ),
					'attr' => 'href',
					'type' => 'text',
					'value' => '',
					'description' => __( 'The Like Box only works with Facebook Pages.', 'pbsandwich' ),
				),
				// array(
				// 	'label' => __( 'Width', 'jetpack' ),
				// 	'attr' => 'width',
				// 	'type' => 'text',
				// 	'value' => '200',
				// ),
				array(
					'label' => __( 'Height', 'jetpack' ),
					'attr' => 'height',
					'type' => 'text',
					'value' => '432',
				),
				array(
					'label' => __( 'Color Scheme', 'jetpack' ),
					'attr' => 'colorscheme',
					'type' => 'select',
					'value' => 'light',
					'options' => array(
						'light' => __( 'Light', 'jetpack' ),
						'dark' => __( 'Dark', 'jetpack' ),
					),
				),
				array(
					'label' => __( 'Hide Faces', 'jetpack' ),
					'attr' => 'hide_faces',
					'type' => 'checkbox',
					'value' => 'false',
					'description' => __( 'Show profile photos in the plugin.', 'jetpack' ),
				),
				array(
					'label' => __( 'Show Stream', 'jetpack' ),
					'attr' => 'stream',
					'type' => 'checkbox',
					'value' => 'false',
					'description' => __( 'Show the profile stream for the public profile.', 'jetpack' ),
				),
				array(
					'label' => __( 'Hide Border', 'jetpack' ),
					'attr' => 'hide_border',
					'type' => 'checkbox',
					'value' => 'false',
					'description' => __( 'Show a border around the plugin.', 'jetpack' ),
				),
				array(
					'label' => __( 'Show Wall', 'jetpack' ),
					'attr' => 'force_wall',
					'type' => 'checkbox',
					'value' => 'false',
					'description' => __( 'Show the wall for a Places page rather than friend activity.', 'jetpack' ),
				),
				array(
					'label' => __( 'Hide widget title', 'pbsandwich' ),
					'attr' => 'hide_title',
					'type' => 'checkbox',
					'value' => 'false',
				),
			),
        )
    );
	
	if ( ! class_exists( 'WPCOM_Widget_Facebook_LikeBox' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_facebook_like_box_widget_shortcode_disabled' );		
		return;
	}
}


function sandwich_jetpack_facebook_like_box_widget_shortcode( $attr, $content ) {
	
	if ( ! class_exists( 'WPCOM_Widget_Facebook_LikeBox' ) ) {
		return '';
	}
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Facebook Like Box', 'jetpack' ),
		'href' => '',
		'width' => '200', // Width is overridden to 100% in frontend.css
		'height' => '432',
		'colorscheme' => 'light',
		// 'show_faces' => 'true',
		'hide_faces' => 'false',
		'stream' => 'false',
		// 'show_border' => 'true',
		'hide_border' => 'false',
		'force_wall' => 'false',
		'hide_title' => 'false',
    ) );
	
	// Shortcake isn't playing well with checkboxes that are true by default, fix it
	$attr['show_faces'] = false;
	if ( $attr['hide_faces'] == 'false' || $attr['hide_faces'] === false ) {
		$attr['show_faces'] = true;
	}
	$attr['show_border'] = false;
	if ( $attr['hide_border'] == 'false' || $attr['hide_border'] === false ) {
		$attr['show_border'] = true;
	}
	
	// Convert our string booleans to real booleans since the widget needs it that way
	$attr['stream'] = $attr['stream'] == 'true' || $attr['stream'] === true ? true : false;
	$attr['force_wall'] = $attr['force_wall'] == 'true' || $attr['force_wall'] === true ? true : false;
	$attr['width'] = '';
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] == 'true' || $attr['hide_title'] === true ) {
		$hideTitleClass = 'hide_title';
	}
	
	// This is unique to this widget, the attributes are wrapped inside an array under the key 'like_args'
	$attr = array_merge( $attr, array( 'like_args' => $attr ) );
			
	ob_start();
	
	?>
	
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WPCOM_Widget_Facebook_LikeBox', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}


function sandwich_jetpack_facebook_like_box_widget_shortcode_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'pbs_jetpack_facebook_like_box_widget', __( "Requires Jetpack's Extra Sidebar Widgets module", 'pbsandwich' ) );
}