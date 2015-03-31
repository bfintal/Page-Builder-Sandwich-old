<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
	
/**
 * Add capability of setting alignments.
 *
 * @return	void
 */
add_filter( 'pbs_column_toolbar_post', 'pbs_alignment_buttons' );
function pbs_alignment_buttons( $toolbarHtml ) {
	echo '<div class="sep" data-mce-bogus="1"></div>';
	echo '<div id="pbs-align-left" class="dashicons dashicons-align-left" data-column-action="align-left" data-mce-bogus="1" title="{{ data.align_left }}"></div>';
	echo '<div id="pbs-align-center" class="dashicons dashicons-align-center" data-column-action="align-center" data-mce-bogus="1" title="{{ data.align_center }}"></div>';
	echo '<div id="pbs-align-right" class="dashicons dashicons-align-right" data-column-action="align-right" data-mce-bogus="1" title="{{ data.align_right }}"></div>';
}


/**
 * Add the strings associated with alignment toolbar.
 *
 * @return	void
 */
add_filter( 'pbs_column_vars', 'pbs_alignment_strings' );
function pbs_alignment_strings( $vars ) {
    $vars['align_left'] = __( 'Align Left', 'pbsandwich' );
    $vars['align_center'] = __( 'Align Center', 'pbsandwich' );
    $vars['align_right'] = __( 'Align Right', 'pbsandwich' );
    return $vars;
}
	
?>