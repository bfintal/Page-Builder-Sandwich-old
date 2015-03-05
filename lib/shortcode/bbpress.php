<?php

/**
 * Shortcode Template File for BBPress
 */

/**
 * Commonly-used variables stored here for better management.
 */

function sandwich_bbpress_display_view () {
	$output['popular'] = __( 'Popular', 'pbsandwich' );
	$output['no-replies'] = __( 'No Replies', 'pbsandwich' );
	return $output;
}

/**
 * Encodes the list of products into an array variable.
 * Choose between array, comma-separated string or slug.
 */

function sandwich_bbpress_posttype_list ( $type = "forum", $id = "false" ) {
	$args = array(
		'post_type' => $type,
		'posts_per_page' => '-1'
	);
	$loop = new WP_Query( $args );
	
	$output = array(
		0 => sprintf( '— %s —', __( 'Select', 'pbsandwich' ) )
	);
	
	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post();
			$fieldout = get_the_title();
			if ( $id != "false" ) {
				$fieldout .= " (" . get_the_ID() . ")";
			}
			$output[ get_the_ID() ] = $fieldout;
		endwhile;
	}
	wp_reset_postdata();

	return $output;
}

function sandwich_bbpress_term_list( $taxonomyName ) {
	$terms = get_terms( $taxonomyName, array( 'parent' => 0 ) );
	$output[0] = sprintf( '— %s —', __( 'Select', 'pbsandwich' ) );
	
	foreach( $terms as $term ) {
		
		$output[ $term->slug ] = $term->name;
		$term_children = get_term_children( $term->term_id, $taxonomyName );
		
		foreach( $term_children as $term_child_id ) {
			$term_child = get_term_by( 'id', $term_child_id, $taxonomyName );
			$output[ $term_child->slug ] = "-" . $term_child->name;
		}
		
	}
	
	return $output;
}


/**
 * Adds the default bbPress styles into the editor
 *
 * @see bbp_default_styles filter
 * @see BBP_Default->enqueue_styles()
 */
add_filter( 'bbp_default_styles', 'sandwich_bbpress_enqueue_editor_styles' );
function sandwich_bbpress_enqueue_editor_styles( $styles ) {
	
	if ( ! is_admin() ) {
		return $styles;
	}
	
	foreach ( $styles as $handle => $attributes ) {
		
		$styleUrl = bbp_enqueue_style( $handle, $attributes['file'], $attributes['dependencies'], bbp_get_version(), 'screen' );
		add_editor_style( $styleUrl );
		
	}
	
	return $styles;
}


/**
 * Create our shortcode for BBPress 
 */
add_action( 'init', 'sandwich_bbp_shortcodes', 11 );
function sandwich_bbp_shortcodes() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	// Check if bbPress is active. Terminate if not.
	if ( ! class_exists( 'bbPress' ) ) {
		return;
	}
	
	// We need to trigger the addition of styles so that our preview would work correctly
	if ( ! is_admin() ) {
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
					'options' => sandwich_bbpress_posttype_list( 'forum' ),
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
					'options' => sandwich_bbpress_posttype_list( 'forum' ),
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
					'options' => sandwich_bbpress_posttype_list( 'topic' ),
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
					'options' => sandwich_bbpress_posttype_list( 'reply' ),
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
					'options' => sandwich_bbpress_term_list( 'topic-tag' ),
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
					'options' => sandwich_bbpress_display_view(),
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