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