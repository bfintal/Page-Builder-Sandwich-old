<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
	
/**
 * Add capability of setting full width.
 *
 * @return	void
 */
add_filter( 'pbs_column_toolbar_post', 'pbs_fullwidth_buttons' );
function pbs_fullwidth_buttons( $toolbarHtml ) {
	echo '<div class="sep" data-mce-bogus="1"></div>';
	echo '<div id="pbs-fullwidth" class="dashicons dashicons-editor-justify" data-column-action="fullwidth" data-mce-bogus="1" title="{{ data.full_width }}"></div>';
}

/**
 * Add the strings associated with full width toolbar.
 *
 * @return	void
 */
add_filter( 'pbs_column_vars', 'pbs_fullwidth_strings' );
function pbs_fullwidth_strings( $vars ) {
    $vars['full_width'] = __( 'Full width', 'pbsandwich' );
    return $vars;
}
	
?>