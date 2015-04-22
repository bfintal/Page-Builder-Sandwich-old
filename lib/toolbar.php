<?php
	
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class GambitPBSandwichToolbar {
	
	protected $toolbarButtons = array();
	
	function __construct() {
		add_filter( 'pbs_js_vars', array( $this, 'addToolbars' ) );
		add_filter( 'pbs_toolbar_buttons', array( $this, 'addCoreToolbarButtons' ), 1 );
	}
	
	
	/**
	 * Filter: pbs_toolbar_buttons
	 *
	 * action - the name of the action, a javascript event will fire when the button is clicked
	 * icon	- dashicon icon classes
	 * label - a label to show when the button is hovered on, or '|' to make it into a separator (no triggers)
	 * shortcode - the shortcode which this toolbar should appear, leave blank to apply to ALL shortcodes.
	 *             columns & rows are now included when left blank
	 * priority - the location of the button, defaults to 10.
	 *            >= 100 is to the left of the edit button
	 *            >= 0 is to the left of the remove button
	 *            < 0 is to the right of the remove button
	 * hash - auto generated unique ID
	 */
	public function clearToolbarButtonArgs( $args ) {
		return array(
			'action' => empty( $args['action'] ) ? '' : $args['action'],
			'icon' => empty( $args['icon'] ) ? 'dashicons dashicons-edit' : $args['icon'],
			'label' => empty( $args['label'] ) ? '' : $args['label'],
			'shortcode' => empty( $args['shortcode'] ) ? '' : $args['shortcode'],
			'priority' => empty( $args['priority'] ) ? 10 : ( (int) $args['priority'] === 0 ? 10 : $args['priority'] ),
			'hash' => substr( md5( microtime() ), 0, 8 ),
		);
	}
	
	public function addToolbars( $columnVars ) {
		if ( empty( $columnVars ) ) {
			$columnVars = array();
		}
		
		$toolbarButtons = array();
		
		// Allow others to add toolbar buttons
		$toolbarButtons = apply_filters( 'pbs_toolbar_buttons', $toolbarButtons );
			
		// Clean the toolbar button parameters
		foreach ( $toolbarButtons as $key => $args ) {
			$toolbarButtons[ $key ] = $this->clearToolbarButtonArgs( $args );
		}
		
		// Sort via priority (most to least)
		usort( $toolbarButtons, array( $this, 'toolbarPrioritySort' ) );
		
		$columnVars['toolbar_buttons'] = $toolbarButtons;
		
		return $columnVars;
	}
	
	
	public function toolbarPrioritySort( $a, $b ) {
	    return $b['priority'] - $a['priority'];
	}
	
	
	/**
	 * Add core toolbar buttons
	 */
	public function addCoreToolbarButtons( $toolbarButtons ) {
		
		/**
		 * All-purpose clone button
		 */
		$toolbarButtons[] = array(
			'action' => 'clone',
			'icon' => 'dashicons dashicons-images-alt',
			'label' => __( 'Clone', 'pbsandwich' ),
			'priority' => 0,
		);
		
		return $toolbarButtons;
	}
	
}
new GambitPBSandwichToolbar();

?>