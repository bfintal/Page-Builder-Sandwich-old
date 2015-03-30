<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_jetpack_subscriptions_widget', 11 );

function sandwich_jetpack_subscriptions_widget() {
	
	add_shortcode( 'pbs_jetpack_subscriptions_widget', 'sandwich_jetpack_subscriptions_widget_shortcode' );

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
        'pbs_jetpack_subscriptions_widget',
        array(
            'label' => __( 'Jetpack Widget - Subscriptions', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress jetpack-logo',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Subscribe to Blog via Email', 'jetpack' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
				array(
					'label' => __( 'Subscribe Text', 'pbsandwich' ),
					'attr' => 'subscribe_text',
					'type' => 'textarea',
					'value' => __( 'Enter your email address to subscribe to this blog and receive notifications of new posts by email.', 'jetpack' ),
					'description' => __( 'This is the text body that is shown to your visitors.', 'pbsandwich' ),
				),
				array(
					'label' => __( 'Subscribe Text (when Logged in)', 'pbsandwich' ),
					'attr' => 'subscribe_logged_in',
					'type' => 'textarea',
					'value' => __( 'Click to subscribe to this blog and receive notifications of new posts by email.', 'jetpack' ),
					'description' => __( 'This is the text body that is shown to your visitors if they are logged in.', 'pbsandwich' ),
				),
				array(
					'label' => __( 'Email field placeholder', 'pbsandwich' ),
					'attr' => 'subscribe_placeholder',
					'type' => 'text',
					'value' => __( 'Email Address', 'jetpack' ),
				),
				array(
					'label' => __( 'Button label', 'pbsandwich' ),
					'attr' => 'subscribe_button',
					'type' => 'text',
					'value' => __( 'Subscribe', 'jetpack' ),
				),
				array(
					'label' => __( 'Hide Subscriber Count', 'pbsandwich' ),
					'attr' => 'show_subscribers_total',
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
	
	if ( ! class_exists( 'Jetpack_Subscriptions_Widget' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_subscriptions_widget_shortcode_disabled' );		
		return;
	}
}


function sandwich_jetpack_subscriptions_widget_shortcode( $attr, $content ) {
	
	if ( ! class_exists( 'Jetpack_Subscriptions_Widget' ) ) {
		return '';
	}
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Display WordPress Posts', 'jetpack' ),
		'subscribe_text' => __( 'Enter your email address to subscribe to this blog and receive notifications of new posts by email.', 'jetpack' ),
		'subscribe_logged_in' => __( 'Click to subscribe to this blog and receive notifications of new posts by email.', 'jetpack' ),
		'subscribe_placeholder' => __( 'Email Address', 'jetpack' ),
		'subscribe_button' => __( 'Subscribe', 'jetpack' ),
		'show_subscribers_total' => false,
		'hide_title' => false
    ) );
	
	$attr['show_subscribers_total'] = $attr['show_subscribers_total'] === 'true' || $attr['show_subscribers_total'] === true ? true : false;
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'Jetpack_Subscriptions_Widget', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}


function sandwich_jetpack_subscriptions_widget_shortcode_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'pbs_jetpack_subscriptions_widget', __( "Requires Jetpack's Subscriptions module", 'pbsandwich' ) );
}