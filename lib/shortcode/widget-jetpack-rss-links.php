<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_jetpack_rss_links_widget', 11 );

function sandwich_jetpack_rss_links_widget() {
	
	add_shortcode( 'pbs_jetpack_rss_links_widget', 'sandwich_jetpack_rss_links_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	$displaysOptions = array(
		'posts' => __( 'Posts', 'jetpack' ),
		'comments' => __( 'Comments', 'jetpack' ),
		'posts-comments' => __( 'Posts & Comments', 'jetpack' )
	);
	
	$formatOptions = array(
		'text' => __( 'Text Link', 'jetpack' ),
		'image' => __( 'Image Link', 'jetpack' ),
		'text-image' => __( 'Text & Image Links', 'jetpack' )
	);
	
	$sizeOptions = array(
		'small' => __( 'Small', 'jetpack' ),
		'medium' => __( 'Medium', 'jetpack' ),
		'large' => __( 'Large', 'jetpack' )
	);
	
	$colorOptions = array(
		'red' => __( 'Red', 'jetpack' ),
		'orange' => __( 'Orange', 'jetpack' ),
		'green' => __( 'Green', 'jetpack' ),
		'blue' => __( 'Blue', 'jetpack' ),
		'purple' => __( 'Purple', 'jetpack' ),
		'pink' => __( 'Pink', 'jetpack' ),
		'silver' => __( 'Silver', 'jetpack' ),
	);
	
	shortcode_ui_register_for_shortcode(
        'pbs_jetpack_rss_links_widget',
        array(
            'label' => __( 'Jetpack Widget - RSS Links', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress jetpack-logo',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'RSS Links', 'pbsandwich' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
				array(
					'label' => __( 'Feed(s) to Display', 'pbsandwich' ),
					'attr' => 'display',
					'type' => 'select',
					'value' => 'posts-comments',
					'options' => $displaysOptions,
				),
				array(
					'label' => __( 'Format', 'pbsandwich' ),
					'attr' => 'format',
					'type' => 'select',
					'value' => 'text',
					'options' => $formatOptions,
				),
				array(
					'label' => __( 'Image Size', 'pbsandwich' ),
					'attr' => 'imagesize',
					'type' => 'select',
					'value' => 'large',
					'options' => $sizeOptions,
					'description' => __( 'This setting only applies if you selected a format that includes images above.', 'pbsandwich' ),
				),
				array(
					'label' => __( 'Image Color', 'pbsandwich' ),
					'attr' => 'imagecolor',
					'type' => 'select',
					'value' => 'red',
					'options' => $colorOptions,
					'description' => __( 'This setting only applies if you selected a format that includes images above.', 'pbsandwich' ),
				),
				array(
					'label' => __( 'Hide widget title', 'pbsandwich' ),
					'attr' => 'hide_title',
					'type' => 'checkbox',
					'value' => false,
				),
			),
        )
    );
	
	if ( ! class_exists( 'Jetpack_RSS_Links_Widget' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_rss_links_widget_shortcode_disabled' );		
		return;
	}
}


function sandwich_jetpack_rss_links_widget_shortcode( $attr, $content ) {
	
	if ( ! class_exists( 'Jetpack_RSS_Links_Widget' ) ) {
		return '';
	}
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'RSS Links', 'pbsandwich' ),
		'display' => 'posts-comments',
		'format' => 'text',
		'imagesize' => 'large',
		'imagecolor' => 'red',
		'hide_title' => false
    ) );
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'Jetpack_RSS_Links_Widget', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}


function sandwich_jetpack_rss_links_widget_shortcode_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'pbs_jetpack_rss_links_widget', __( "Requires Jetpack's Extra Sidebar Widgets module", 'pbsandwich' ) );
}