/**
 * Embeds cannot be dragged since they do not have an overlay div. This adds that to all embeds
 */
editor.on('wp-body-class-change change', function(e) {
	var $ = jQuery;
	
	$(editor.getBody()).find('.wpview-body .wpview-content.wpview-type-embed:not(:has( ~ .wpview-overlay))').after( '<div class="wpview-overlay"></div>' );

});