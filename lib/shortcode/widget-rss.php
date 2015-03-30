<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_rss_widget', 11 );

function sandwich_rss_widget() {
	
	add_shortcode( 'pbs_rss_widget', 'sandwich_rss_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_rss_widget',
        array(
            'label' => __( 'Widget - RSS', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'RSS', 'pbsandwich' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
                array(
					'label' => __( 'RSS feed URL', 'pbsandwich' ),
                    'attr' => 'url',
                    'type' => 'text',
					'value' => '',
                ),
                array(
					'label' => __( 'How many items would you like to display?', 'default' ),
                    'attr' => 'items',
                    'type' => 'select',
					'value' => '10',
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
						'9' => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
						'13' => '13',
						'14' => '14',
						'15' => '15',
						'16' => '16',
						'17' => '17',
						'18' => '18',
						'19' => '19',
						'20' => '20',
					)
                ),
				array(
					'label' => __( 'Display item content?', 'default' ),
					'attr' => 'show_summary',
					'type' => 'checkbox',
					'value' => false,
				),
				array(
					'label' => __( 'Display item author if available?', 'default' ),
					'attr' => 'show_author',
					'type' => 'checkbox',
					'value' => false,
				),
				array(
					'label' => __( 'Display item date?', 'default' ),
					'attr' => 'show_date',
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
	
}

function sandwich_rss_widget_shortcode( $attr, $content ) {
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'RSS', 'pbsandwich' ),
		'url' => '',
		'show_summary' => '10',
		'show_summary' => false,
		'show_author' => false,
		'show_date' => false,
		'hide_title' => false
    ) );
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'WP_Widget_RSS', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}