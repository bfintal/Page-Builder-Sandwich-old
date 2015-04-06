/**
 * Clone button action handler
 */
editor.on('toolbar-clone', function(e) {
	var $ = jQuery;

	preUpdateSortable( editor );
	var newElement = $(e.target).clone();
	newElement.insertAfter( $(e.target) ).trigger('click');
	updateSortable( editor );

	// Cleanup to make views with iframes display again
	if ( newElement.find('iframe').length > 0 ) {
		editor.execCommand( 'mceCleanup' );
	}
});


/**
 * Image shape buttons
 */
editor.on('toolbar-image-circle', function(e) {
	var $ = jQuery;
	$(e.target).css('borderRadius', '100%');
});
editor.on('toolbar-image-rounded', function(e) {
	var $ = jQuery;
	$(e.target).css('borderRadius', '5px');
});
editor.on('toolbar-image-rectangle', function(e) {
	var $ = jQuery;
	$(e.target).css('borderRadius', '0px');
});


/**
 * Content alignment buttons
 */
editor.on('toolbar-align-left', function(e) {
	console.log('left');
	var $ = jQuery;
	$(e.target).removeClass('pbs-aligncenter');
	$(e.target).removeClass('pbs-alignright');
	$(e.target).addClass('pbs-alignleft');
	console.log(e.target);
	//$(e.target).find([data-wpview-type="pbs_button"]).replaceAlignAttribute( 'pbs-button', 'pbs-alignleft' );
});
editor.on('toolbar-align-center', function(e) {
	console.log('center');	
	var $ = jQuery;
	$(e.target).removeClass('pbs-alignleft');
	$(e.target).removeClass('pbs-alignright');
	$(e.target).addClass('pbs-aligncenter');	
	console.log(e.target);	
});
editor.on('toolbar-align-right', function(e) {
	console.log('right');	
	var $ = jQuery;
	$(e.target).removeClass('pbs-alignleft');
	$(e.target).removeClass('pbs-aligncenter');
	$(e.target).addClass('pbs-alignright');
	console.log(e.target);	
});

function replaceAlignAttribute( shortcode, alignment ) {

	var parts = shortcode.split( /%20align%3D%22(\w+)%22/i );
	
	// No alignment attribute yet
	if ( parts.length === 1 ) {
		parts = shortcode.split( /(%5D)/i );
		
		if ( parts.length > 1 ) {
			return parts[0] + "%20align%3D%22" + alignment + "%22" + parts[1];
		}
	} else {
		
		return parts[0] + "%20align%3D%22" + alignment + "%22" + parts[2];
	}
}