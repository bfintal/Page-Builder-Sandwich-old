<?php
	
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class GambitPBSandwichToolbar {
	
	protected $toolbarButtons = array();
	
	function __construct() {
		add_filter( 'pbs_js_vars', array( $this, 'addToolbars' ) );
	}
	
	protected function clearToolbarButtonArgs( $args ) {
		return array(
			'action' => empty( $args['action'] ) ? '' : $args['action'],
			'icon' => empty( $args['icon'] ) ? '' : $args['icon'],
			'tooltip' => empty( $args['tooltip'] ) ? '' : $args['tooltip'],
			'shortcode' => empty( $args['shortcode'] ) ? '' : $args['shortcode'],
			'priority' => empty( $args['priority'] ) ? 10 : $args['priority'],
		);
	}
	
	public function addToolbars( $columnVars ) {
		$toolbarButtons = array();
		
		// Our core toolbar buttons
		$toolbarButtons[] = array(
			'action' => 'clone',
			'icon' => 'dashicons dashicons-images-alt',
			'tooltip' => __( 'Clone', 'pbsandwich' ),
			'priority' => 0
		);
		
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