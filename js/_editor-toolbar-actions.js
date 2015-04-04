/**
 * Clone button action handler
 */
editor.on('toolbar-clone', function(e) {

	preUpdateSortable( editor );
	var newElement = $(e.target).clone();
	newElement.insertAfter( $(e.target) ).trigger('click');
	updateSortable( editor );

	// Cleanup to make views with iframes display again
	if ( newElement.find('iframe').length > 0 ) {
		editor.execCommand( 'mceCleanup' );
	}
});

editor.on('toolbar-image-circle', function(e) {
	var $ = jQuery;
	console.log($(e.target));
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