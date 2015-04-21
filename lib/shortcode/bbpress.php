<?php

/**
 * Shortcode Template File for BBPress
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class GambitPBSandwichShortcodeBBPress {
	
	function __construct() {
		if ( is_admin() ) {
			add_filter( 'bbp_default_styles', array( $this, 'sandwich_bbpress_enqueue_editor_styles' ) );
			add_action( 'init', array( $this, 'sandwich_bbp_shortcodes' ), 11 );
		}
	}

	/**
	 * Adds the default bbPress styles into the editor
	 *
	 * @see bbp_default_styles filter
	 * @see BBP_Default->enqueue_styles()
	 */
	public function sandwich_bbpress_enqueue_editor_styles( $styles ) {
	
		foreach ( $styles as $handle => $attributes ) {
		
			$styleUrl = bbp_enqueue_style( $handle, $attributes['file'], $attributes['dependencies'], bbp_get_version(), 'screen' );
			add_editor_style( $styleUrl );
		
		}
	
		return $styles;
	}
	
	
	/**
	 * Create our shortcode for BBPress 
	 */
	public function sandwich_bbp_shortcodes() {

		// Check if Shortcake exists
		if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
			return;
		}

		// Check if bbPress is active. Terminate if not.
		if ( ! class_exists( 'bbPress' ) ) {
			return;
		}
	
		// Force bbPress to enqueue styles so we can add them in the editor
		if ( class_exists( 'BBP_Default' ) ) {
			$o = new BBP_Default();
			$o->enqueue_styles();
		}

		// Register Shortcake UI for BBPress Forum Index
		shortcode_ui_register_for_shortcode( 'bbp-forum-index', 
			array(
				'label' => __( 'BBPress Forum Index', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);
	
		// Register Shortcake UI for BBPress Forum Form
		shortcode_ui_register_for_shortcode( 'bbp-forum-form', 
			array(
				'label' => __( 'BBPress Forum Form', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);

		// Register Shortcake UI for BBPress Single Forum Display
		shortcode_ui_register_for_shortcode(
			'bbp-single-forum',
			array(
				'label' => __( 'BBPress Single Forum Display', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
				'attrs' => array(
					array(
						'label' => __( 'Select Forum to display', 'pbsandwich' ),
						'attr' => 'id',
						'type' => 'select',
						'options' => sandwich_functions_posttype_list( 'forum' ),
					),
				),
			)
		);

		// Register Shortcake UI for BBPress Topic Index
		shortcode_ui_register_for_shortcode( 'bbp-topic-index', 
			array(
				'label' => __( 'BBPress Topic Index', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);
	
		// Register Shortcake UI for BBPress Topic Form
		shortcode_ui_register_for_shortcode(
			'bbp-topic-form',
			array(
				'label' => __( 'BBPress Topic Form', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
				'attrs' => array(
					array(
						'label' => __( 'Select Forum to display the New Topic form in', 'pbsandwich' ),
						'attr' => 'forum_id',
						'type' => 'select',
						'options' => sandwich_functions_posttype_list( 'forum' ),
					),
				),
			)
		);

		// Register Shortcake UI for BBPress Single Topic
		shortcode_ui_register_for_shortcode(
			'bbp-single-topic',
			array(
				'label' => __( 'BBPress Single Topic display', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
				'attrs' => array(
					array(
						'label' => __( 'Select the Topic to display', 'pbsandwich' ),
						'attr' => 'id',
						'type' => 'select',
						'options' => sandwich_functions_posttype_list( 'topic' ),
					),
				),
			)
		);

		// Register Shortcake UI for BBPress Reply Form 
		shortcode_ui_register_for_shortcode( 'bbp-reply-form', 
			array(
				'label' => __( 'BBPress Reply Form', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);

		// Register Shortcake UI for BBPress Single Reply
		shortcode_ui_register_for_shortcode(
			'bbp-single-reply',
			array(
				'label' => __( 'BBPress Single Reply display', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
				'attrs' => array(
					array(
						'label' => __( 'Select the Reply to display', 'pbsandwich' ),
						'attr' => 'id',
						'type' => 'select',
						'options' => sandwich_functions_posttype_list( 'reply' ),
					),
				),
			)
		);

		// Register Shortcake UI for BBPress Topic Tags 
		shortcode_ui_register_for_shortcode( 'bbp-topic-tags', 
			array(
				'label' => __( 'BBPress Topic Tags', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);

		// Register Shortcake UI for BBPress Single Tag
		shortcode_ui_register_for_shortcode(
			'bbp-single-tag',
			array(
				'label' => __( 'BBPress Single Tag display', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
				'attrs' => array(
					array(
						'label' => __( 'Select the Tag to display topics associated with it', 'pbsandwich' ),
						'attr' => 'id',
						'type' => 'select',
						'options' => sandwich_functions_term_list( 'topic-tag' ),
					),
				),
			)
		);

		// Register Shortcake UI for BBPress Single View	
		shortcode_ui_register_for_shortcode(
			'bbp-single-view',
			array(
				'label' => __( 'BBPress Single View', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
				'attrs' => array(
					array(
						'label' => __( 'Select the viewing type to display topics according to a certain attribute.', 'pbsandwich' ),
						'attr' => 'id',
						'type' => 'select',
						'options' => array(
							'popular' => __( 'Popular', 'pbsandwich' ),
							'no-replies' => __( 'No Replies', 'pbsandwich' ),
						),
					),
				),
			)
		);

		// Register Shortcake UI for BBPress Search Input Form	
		shortcode_ui_register_for_shortcode( 'bbp-search', 
			array(
				'label' => __( 'BBPress Search Input Form', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);

		// Register Shortcake UI for BBPress Search Input Form Template
		shortcode_ui_register_for_shortcode( 'bbp-search-form', 
			array(
				'label' => __( 'BBPress Search Input Template', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);

		// Register Shortcake UI for BBPress Login Form 
		sandwich_add_logged_out_shortcode( 'bbp-login' );
		shortcode_ui_register_for_shortcode( 'bbp-login', 
			array(
				'label' => __( 'BBPress Login Form', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);

		// Register Shortcake UI for BBPress Register Form	
		sandwich_add_logged_out_shortcode( 'bbp-register' );
		shortcode_ui_register_for_shortcode( 'bbp-register', 
			array(
				'label' => __( 'BBPress Registration Screen', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);

		// Register Shortcake UI for BBPress Lost Password Form 
		sandwich_add_logged_out_shortcode( 'bbp-lost-pass' );
		shortcode_ui_register_for_shortcode( 'bbp-lost-pass', 
			array(
				'label' => __( 'BBPress Lost Password Form', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);
	
		// Register Shortcake UI for BBPress Statistics 
		shortcode_ui_register_for_shortcode( 'bbp-stats', 
			array(
				'label' => __( 'BBPress Statistics', 'pbsandwich' ),
				'listItemImage' => 'dashicons-wordpress dashicons-bbpress-logo',
			) 
		);
	}
}
new GambitPBSandwichShortcodeBBPress();