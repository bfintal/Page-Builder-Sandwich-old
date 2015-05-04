<?php

/**
 * Shortcode Template File for Events Manager
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Selection array for listing modes.
 */

function sandwich_events_manager_mode_selection() {
	$output = array();
	$output['daily'] = "Daily";
	$output['weekly'] = "Weekly";
	$output['monthly'] = "Monthly";
	$output['yearly'] = "Yearly";
	return $output;
}


/**
 * Create our shortcode for Events Manager
 */
add_action( 'init', 'sandwich_events_manager', 11 );
function sandwich_events_manager() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	// Ensure plugin runs only in admin mode. Do not continue if that's not the case.
	if ( ! is_admin() ) {
		return;
	}

	// Dependency check for Events Manager. Do not proceed if not found.
	if ( ! defined( 'EM_VERSION' ) ) {
		return;
	}

	// Register Shortcake UI for Events Manager Events List
	shortcode_ui_register_for_shortcode(
		'events_list',
		array(
			'label' => __( 'Events Manager Events List', 'pbsandwich' ),
			'listItemImage' => 'dashicons-calendar',
			'attrs' => array(
				array(
					'label' => __( 'Event listing limit', 'pbsandwich' ),
					'attr' => 'limit',
					'type' => 'text',
					'value' => '10',
					'description' => __( 'Show up to the specified amount of events.', 'pbsandwich' ),
				),
			),
			'inner_content' => array(
				'label' => __( 'Event List caption', 'pbsandwich' ),
				'value' => '',
				'type' => 'textarea',
				'description' => __( 'The Event List can be customized to include customized description. The following variables will be replaced with the actual relevant details:<br />#_EVENTNAME<br />#_EVENTLINK<br />#_LOCATIONLINK<br />#_EVENTDATES<br />#_EVENTTIMES', 'pbsandwich' ),
			),
		)
	);

	// Register Shortcake UI for Events Manager Grouped Events List
	shortcode_ui_register_for_shortcode(
		'events_list_grouped',
		array(
			'label' => __( 'Events Manager Grouped Events List', 'pbsandwich' ),
			'listItemImage' => 'dashicons-calendar',
			'attrs' => array(
				array(
					'label' => __( 'Event listing mode', 'pbsandwich' ),
					'attr' => 'mode',
					'type' => 'select',
					'options' => sandwich_events_manager_mode_selection(),
					'description' => __( 'Show events by these selections.', 'pbsandwich' ),
				),
			),
			'inner_content' => array(
				'label' => __( 'Grouped Event List caption', 'pbsandwich' ),
				'value' => '',
				'type' => 'textarea',
				'description' => __( 'The Grouped Events List can be customized to include customized description. The following variables will be replaced with the actual relevant details:<br />#_EVENTDATES<br />#_EVENTLINK<br />#_EVENTTIMES', 'pbsandwich' ),
			),
		)
	);

	// Register Shortcake UI for Events Manager Event Display
	shortcode_ui_register_for_shortcode(
		'event',
		array(
			'label' => __( 'Events Manager Event Display', 'pbsandwich' ),
			'listItemImage' => 'dashicons-calendar',
			'attrs' => array(
				array(
					'label' => __( 'Show Event', 'pbsandwich' ),
					'attr' => 'post_id',
					'type' => 'select',
					'options' => sandwich_functions_posttype_list( 'event' ),
				),
			),
			'inner_content' => array(
				'label' => __( 'Event caption', 'pbsandwich' ),
				'value' => '#_EVENTNAME',
				'type' => 'textarea',
				'description' => __( 'The Event display can be customized to include elements like description. The #_EVENTNAME  variable will be replaced with the actual content.', 'pbsandwich' ),
			),
		)
	);

	// Register Shortcake UI for Events Manager Event Form
	shortcode_ui_register_for_shortcode(
		'event_form',
		array(
			'label' => __( 'Events Manager Event Submission Form', 'pbsandwich' ),
			'listItemImage' => 'dashicons-calendar',
			'attrs' => array(),
		)
	);

	// Register Shortcake UI for Events Manager Event Search Form
	shortcode_ui_register_for_shortcode(
		'event_search_form',
		array(
			'label' => __( 'Events Manager Event Search Form', 'pbsandwich' ),
			'listItemImage' => 'dashicons-calendar',
			'attrs' => array(),
		)
	);
	
	// Register Shortcake UI for Events Manager Events Calendar
	shortcode_ui_register_for_shortcode(
		'events_calendar',
		array(
			'label' => __( 'Events Manager Events Calendar', 'pbsandwich' ),
			'listItemImage' => 'dashicons-calendar',
			'attrs' => array(
				array(
					'label' => __( 'Show Full-sized Calendar', 'pbsandwich' ),
					'attr' => 'full',
					'type' => 'checkbox',
					'value' => '0',
				),
				array(
					'label' => __( 'Show long events', 'pbsandwich' ),
					'attr' => 'long_events',
					'type' => 'checkbox',
					'value' => '0',
				),
				array(
					'label' => __( 'Category List', 'pbsandwich' ),
					'attr' => 'category',
					'type' => 'select',
					'options' => sandwich_functions_term_list( 'event-categories' ),
					'description' => __( 'Show events in the specified category.', 'pbsandwich' ),
				),
			),
		)
	);	
	
}

/**
 * Adds Events Manager styles into the preview
 */
add_action( 'init', 'sandwich_events_manager_add_editor_styles', 10 );
function sandwich_events_manager_add_editor_styles() {

	if ( ! is_admin() ) {
		return;
	}
	
	if ( ! class_exists( 'EM_Scripts_and_Styles' ) ) {
		return;
	} 

	if ( ! defined( 'EM_DIR_URI' ) ) {
		return;
	}
	
	add_editor_style( EM_DIR_URI . 'includes/css/events_manager.css' );
}