/**
 * Aligns objects to the left.
 */
editor.on('toolbar-column-align-left', function(e) {
	var $ = jQuery;
	var now = $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)');
	now.removeClass('pbs-aligncenter');
	now.removeClass('pbs-alignright');
	now.addClass('pbsp-alignleft');
	$(editor.getBody()).find('#pbs-align-center').css('opacity', '1');
	$(editor.getBody()).find('#pbs-align-right').css('opacity', '1');	
	$(editor.getBody()).find('#pbs-align-left').css('opacity', '0.5');	
});

/**
 * Aligns objects to the center.
 */
editor.on('toolbar-column-align-center', function(e) {
	var $ = jQuery;
	var now = $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)');
	now.removeClass('pbs-alignleft');
	now.removeClass('pbs-alignright');
	now.addClass('pbsp-aligncenter');
	$(editor.getBody()).find('#pbs-align-left').css('opacity', '1');
	$(editor.getBody()).find('#pbs-align-right').css('opacity', '1');	
	$(editor.getBody()).find('#pbs-align-center').css('opacity', '0.5');	
});

/**
 * Aligns objects to the right.
 */
editor.on('toolbar-column-align-right', function(e) {
	var $ = jQuery;
	var now = $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)');
	now.removeClass('pbs-alignleft');
	now.removeClass('pbs-aligncenter');
	now.addClass('pbsp-alignright');
	$(editor.getBody()).find('#pbs-align-left').css('opacity', '1');
	$(editor.getBody()).find('#pbs-align-center').css('opacity', '1');	
	$(editor.getBody()).find('#pbs-align-right').css('opacity', '0.5');	
});


editor.on( 'show-toolbar-column', function(e) { 
	var $ = jQuery;
	var now = $(e.target).parents('.pbsandwich_column:eq(0)');
	if ( now.hasClass('pbs-align-left') ) {
		$(editor.getBody()).find('#pbs-alignleft').css('opacity', '0.5');
	}
	if ( now.hasClass('pbs-align-center') ) {
		$(editor.getBody()).find('#pbs-aligncenter').css('opacity', '0.5');
	}
	if ( now.hasClass('pbs-align-right') ) {
		$(editor.getBody()).find('#pbs-alignright').css('opacity', '0.5');
	}	
});