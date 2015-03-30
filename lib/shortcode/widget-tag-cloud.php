<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_tag_cloud_widget', 11 );

function sandwich_tag_cloud_widget() {
	
	add_shortcode( 'pbs_tag_cloud_widget', 'sandwich_tag_cloud_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_tag_cloud_widget',
        array(
            'label' => __( 'Widget - Tag Cloud', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Tag Cloud', 'pbsandwich' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
                array(
					'label' => __( 'Taxonomy', 'pbsandwich' ),
                    'attr' => 'taxonomy',
                    'type' => 'select',
					'value' => '',
					'options' => sandwich_functions_taxonomy_list ( 'tag' ),
                ),
			),
        )
    );
}

function sandwich_tag_cloud_widget_shortcode( $attr, $content ) {
	
	$default = '';
	foreach ( get_taxonomies() as $taxonomy ) {
		$tax = get_taxonomy( $taxonomy );
		if ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) {
			continue;
		}
		$default = esc_attr( $taxonomy );
		break;
	}
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Tag Cloud', 'pbsandwich' ),
		'taxonomy' => $default,
		'hide_title' => false
    ) );
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WP_Widget_Tag_Cloud', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}