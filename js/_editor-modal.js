/**
 * Creates tabs for modal windows
 */
editor.on( 'pre-modal-create-tabs', function(e) {
	var $ = jQuery;
	
	if ( typeof pbsandwich_column.modal_tabs === 'undefined' ) {
		return;
	}
	
	if ( $(e.target).find('.pbsandwich_modal_tabs').length === 0 ) {
		return;
	}
	
	$.each( pbsandwich_column.modal_tabs, function(i, newTabInfo) {
		if ( e.shortcode !== newTabInfo.shortcode ) {
			return;
		}
		
		// Show the tab headings, since they're hidden by default
		$(e.target).find('.pbsandwich_modal_tabs').css('display', '');
		
		// Fire the event to handle template population
		pbs_modal_fields[ newTabInfo.template_id ] = {};
		editor.fire( 'modal-tab-populate-data', {
			'editor': editor,
			'target': e.origin,
			'modal': e.target,
			'template_id': newTabInfo.template_id
		} );
		
		// Add the tab
		$('<div></div>')
			.addClass('pbsandwich_modal_tab')
			.attr( 'data-for', newTabInfo.template_id )
			.text( newTabInfo.name )
			.appendTo( $(e.target).find('.pbsandwich_modal_tabs') );

		// Add the tab's contents
		$('<div></div>')
			.addClass('sandwich_modal')
			.attr( 'id', newTabInfo.template_id )
			.append( wp.template( newTabInfo.template_id )( pbs_modal_fields[ newTabInfo.template_id ] ) )
			.appendTo( $(e.target) );

	});
	
});


editor.on( 'modal-save', function(e) {
	var $ = jQuery;
	if ( $('.pbsandwich_modal_tabs:visible').length > 0 ) {
		$('.pbsandwich_modal_tabs .pbsandwich_modal_tab').each(function() {
			editor.fire( 'modal-tab-save', {
				'template_id': $(this).attr('data-for'),
				'target': e.target,
				'tab': $('#' + $(this).attr('data-for'))[0],
				'action': e.action,
				'shortcode': e.shortcode
			} );
		});
	}
});