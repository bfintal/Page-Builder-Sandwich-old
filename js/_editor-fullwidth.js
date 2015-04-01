/**
 * Aligns objects to the right.
 */
editor.on('toolbar-column-fullwidth', function(e) {
	var $ = jQuery;
	var now = $(editor.getBody()).find('.row').parents('.pbsandwich_column:eq(0)');
	if ( now.hasClass('pbs-row-breakout') ) {
		now.removeClass('pbs-row-breakout')
		$(editor.getBody()).find('#pbs-fullwidth').css('opacity', '1');
	} else {
		now.addClass('pbs-row-breakout');
		$(editor.getBody()).find('#pbs-fullwidth').css('opacity', '0.3');
	}
});


editor.on( 'show-toolbar-column', function(e) { 
	var $ = jQuery;
	var now = $(e.target).parents('.pbsandwich_column:eq(0)');
	if ( now.hasClass('pbs-row-breakout') ) {
		$(editor.getBody()).find('#pbs-fullwidth').css('opacity', '0.3');
	}
});