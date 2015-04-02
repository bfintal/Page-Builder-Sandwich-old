<?php
	
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class GambitPBSandwichToolbar {
	
	protected $toolbarButtons = array();
	
	function __construct() {
		add_filter( 'pbs_js_vars', array( $this, 'addToolbars' ) );
	}
	
	
	/**
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
	protected function clearToolbarButtonArgs( $args ) {
		return array(
			'action' => empty( $args['action'] ) ? '' : $args['action'],
			'icon' => empty( $args['icon'] ) ? '' : $args['icon'],
			'label' => empty( $args['label'] ) ? '' : $args['label'],
			'shortcode' => empty( $args['shortcode'] ) ? '' : $args['shortcode'],
			'priority' => empty( $args['priority'] ) && (int) $args['priority'] != 0 ? 10 : $args['priority'],
			'hash' => substr( md5( microtime() ), 0, 8 ),
		);
	}
	
	public function addToolbars( $columnVars ) {
		$toolbarButtons = array();
		
		// Our core toolbar buttons
		$toolbarButtons[] = array(
			'action' => 'clone',
			'icon' => 'dashicons dashicons-images-alt',
			'label' => __( 'Clone', 'pbsandwich' ),
			'priority' => 0,
		);
		// $toolbarButtons[] = array(
		// 	'label' => '|',
		// 	'shortcode' => '',
		// 	'priority' => -10,
		// );
		
		$toolbarButtons[] = array(
			'label' => __( 'Column', 'pbsandwich' ),
			'shortcode' => 'column',
			'priority' => 1001,
		);
		$toolbarButtons[] = array(
			'action' => 'column-edit-area',
			'icon' => 'dashicons dashicons-edit',
			'label' => __( 'Edit Column', 'pbsandwich' ),
			'shortcode' => 'column',
			'priority' => 1002,
		);
		$toolbarButtons[] = array(
			'action' => 'column-clone-area',
			'icon' => 'dashicons dashicons-images-alt',
			'label' => __( 'Clone Column', 'pbsandwich' ),
			'shortcode' => 'column',
			'priority' => 1003,
		);
		$toolbarButtons[] = array(
			'action' => 'column-remove-area',
			'icon' => 'dashicons dashicons-no-alt',
			'label' => __( 'Delete Column', 'pbsandwich' ),
			'shortcode' => 'column',
			'priority' => 1004,
		);
		$toolbarButtons[] = array(
			'label' => '|',
			'shortcode' => 'column',
			'priority' => 1005,
		);
		
		$toolbarButtons[] = array(
			'label' => __( 'Row', 'pbsandwich' ),
			'shortcode' => 'row',
			'priority' => 1100,
		);
		$toolbarButtons[] = array(
			'action' => 'column-edit-row',
			'icon' => 'dashicons dashicons-edit',
			'label' => __( 'Edit Row', 'pbsandwich' ),
			'shortcode' => 'row',
			'priority' => 1101,
		);
		$toolbarButtons[] = array(
			'action' => 'column-columns',
			'icon' => 'dashicons dashicons-tagcloud',
			'label' => __( 'Change Columns', 'pbsandwich' ),
			'shortcode' => 'row',
			'priority' => 1102,
		);
		$toolbarButtons[] = array(
			'action' => 'column-clone-row',
			'icon' => 'dashicons dashicons-images-alt',
			'label' => __( 'Change Columns', 'pbsandwich' ),
			'shortcode' => 'row',
			'priority' => 1103,
		);
		$toolbarButtons[] = array(
			'action' => 'column-remove-row',
			'icon' => 'dashicons dashicons-no-alt',
			'label' => __( 'Delete Row', 'pbsandwich' ),
			'shortcode' => 'row',
			'priority' => 1104,
		);
		// $toolbarButtons[] = array(
		// 	'action' => 'test',
		// 	'icon' => 'dashicons dashicons-cloud',
		// 	'tooltip' => __( 'Clone', 'pbsandwich' ),
		// 	'shortcode' => array( 'column', 'row' ),
		// 	'priority' => 0
		// );
		
		// TODO move labels into the new toolbar API
		// TODO move column/row buttos to the new toolbar API
		
		// Allow others to add toolbar buttons
		$toolbarButtons = apply_filters( 'pbs_toolbar_buttons', $toolbarButtons );
			
		// Clean the toolbar button parameters
		foreach ( $toolbarButtons as $key => $args ) {
			$toolbarButtons[ $key ] = $this->clearToolbarButtonArgs( $args );
		}
		
		// Sort via priority (most to least)
		usort( $toolbarButtons, function( $a, $b ) {
		    return $b['priority'] - $a['priority'];
		});
		
		$columnVars['toolbar_buttons'] = $toolbarButtons;
		
		return $columnVars;
	}
	
}
new GambitPBSandwichToolbar();

?>