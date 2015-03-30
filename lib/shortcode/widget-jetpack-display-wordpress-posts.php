<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_jetpack_display_wordpress_posts_widget', 11 );

function sandwich_jetpack_display_wordpress_posts_widget() {
	
	add_shortcode( 'pbs_jetpack_display_wordpress_posts_widget', 'sandwich_jetpack_display_wordpress_posts_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_jetpack_display_wordpress_posts_widget',
        array(
            'label' => __( 'Jetpack Widget - Display WordPress Posts', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress jetpack-logo',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Display WordPress Posts', 'jetpack' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
				array(
					'label' => __( 'Blog URL', 'pbsandwich' ),
					'attr' => 'url',
					'type' => 'text',
					'value' => '',
					'description' => __( 'Enter a WordPress.com or Jetpack WordPress site URL.', 'jetpack' ),
				),
				array(
					'label' => __( 'Number of Posts to Display', 'pbsandwich' ),
					'attr' => 'number_of_posts',
					'type' => 'select',
					'value' => '5',
					'options' => array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ),
				),
				array(
					'label' => __( 'Show Featured Image', 'pbsandwich' ),
					'attr' => 'featured_image',
					'type' => 'checkbox',
					'value' => false,
				),
				array(
					'label' => __( 'Show Excerpts', 'pbsandwich' ),
					'attr' => 'show_excerpts',
					'type' => 'checkbox',
					'value' => false,
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
	
	if ( ! class_exists( 'Jetpack_Display_Posts_Widget' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_display_wordpress_posts_widget_shortcode_disabled' );		
		return;
	}
}


function sandwich_jetpack_display_wordpress_posts_widget_shortcode( $attr, $content ) {
	
	if ( ! class_exists( 'Jetpack_Display_Posts_Widget' ) ) {
		return '';
	}
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Display WordPress Posts', 'jetpack' ),
		'url' => '',
		'number_of_posts' => 5,
		'featured_image' => false,
		'show_excerpts' => false,
		'hide_title' => false
    ) );
	
	$attr['featured_image'] = $attr['featured_image'] === 'true' || $attr['featured_image'] === true ? true : false;
	$attr['show_excerpts'] = $attr['show_excerpts'] === 'true' || $attr['show_excerpts'] === true ? true : false;
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'Jetpack_Display_Posts_Widget', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}


function sandwich_jetpack_display_wordpress_posts_widget_shortcode_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'pbs_jetpack_display_wordpress_posts_widget', __( "Requires Jetpack's Extra Sidebar Widgets module", 'pbsandwich' ) );
}