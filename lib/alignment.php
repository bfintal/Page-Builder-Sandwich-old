<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
	
/**
 * Add capability of setting alignments.
 *
 * @return	void
 */
add_filter( 'pbs_toolbar_buttons', 'pbs_alignment_buttons' );
function pbs_alignment_buttons( $toolbarButtons ) {

    // Add separator
    $toolbarButtons[] = array(
        'label' => '|',
		'shortcode' => 'pbs_button',
    );

    // Add align left button
    $toolbarButtons[] = array(
        'action' => 'align-left',
        'icon' => 'dashicons dashicons-align-left',
        'label' => __( 'Align Left', 'pbsandwich' ),
		'shortcode' => 'pbs_button',
    );

    // Add align center button
    $toolbarButtons[] = array(
        'action' => 'align-center',
        'icon' => 'dashicons dashicons-align-center',
        'label' => __( 'Align Center', 'pbsandwich' ),
		'shortcode' => 'pbs_button',
    );
	
    // Add align right button
    $toolbarButtons[] = array(
        'action' => 'align-right',
        'icon' => 'dashicons dashicons-align-right',
        'label' => __( 'Align Right', 'pbsandwich' ),
		'shortcode' => 'pbs_button',		
    );	

    return $toolbarButtons;
}

?>