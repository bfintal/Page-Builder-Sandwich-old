/**
 * Open the extensions link when the "Get more shortcodes" shortcode is clicked.
 *
 * Since we can't override the click event, we change the data-shortcode attribute to
 * invalidate the default Shortcake behavior first. Only then can we add a click handler
 */
jQuery(document).ready(function($) {
	
	// Change the data-shortcode to remove the click handler
	$('body').on('hover', '.shortcode-list-item[data-shortcode="pbs_get_more_shortcodes"]', function(e) {
		$(this).attr('data-shortcode', '__pbs_get_more_shortcodes');
	});
	
	// Open our extensions link when it's clicked
	$('body').on('click', '.shortcode-list-item[data-shortcode="__pbs_get_more_shortcodes"]', function(e) { 
		e.preventDefault();
		e.stopImmediatePropagation();
		window.open( 'http://pbsandwi.ch/downloads/?utm_source=plugin&utm_medium=get%20more%20shortcodes&utm_campaign=Plugin%20-%20Get%20More%20Shortcode', '_blank' );
	});
});