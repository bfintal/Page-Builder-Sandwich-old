/**
 * This is the 4.1 method of adding toolbar buttons to WPViews & images.
 * This was changed in 4.2
 */
if ( pbsandwich_column.wp_version.match( /^4.1/ ) ) {
	
editor.on('init', function(e) {
	var $ = jQuery;
	
	
	/**
	 * Add the toolbar in views (shortcakes)
	 */
	$( editor.getBody() ).on('mousedown', function(e) {
		
		var wrapper = null;
		if ( $(e.target).is('.wpview-wrap') ) {
			wrapper = $(e.target);
		} else if ( $(e.target).parents('.wpview-wrap:eq(0)').length > 0 ) {
			wrapper = $(e.target).parents('.wpview-wrap:eq(0)');
		}
		
		if ( wrapper === null ) {
			return;
		}
		
		// Add the toolbar buttons
		var newButton, shortcode;
		if ( typeof pbsandwich_column.toolbar_buttons !== 'undefined' ) {
			$.each(pbsandwich_column.toolbar_buttons, function(i, button) {
				
				// Check if we should add in the button
				shortcode = wrapper.attr('data-wpview-type');
				if ( typeof button.shortcode === 'string' ) {
					if ( button.shortcode !== '' && button.shortcode !== shortcode ) {
						return;
					}
				} else { // it's an array
					if ( button.shortcode.indexOf( shortcode ) === -1 ) {
						return;
					}
				}
			
				// Add the actual button, don't add it if it already exists
				if ( wrapper.find('[data-hash="' + button.hash + '"]').length === 0 ) {

					if ( button.label === '|' ) {
						newButton = $('<div class="dashicons sep"></div>');
					
					} else if ( button.action === '' ) {
						newButton = $('<div class="toolbar-label"></div>').text( button.label );
					
					} else {
						newButton = $('<div class="' + button.icon + '" data-toolbar-action="' + button.action + '"></div>')
							.attr('aria-label', button.label)
							.attr('title', button.label);
					}
					newButton.attr('data-hash', button.hash);
				
					// Sort the buttons via priority
					if ( button.priority >= 100 ) { // Before the edit button
						newButton.insertBefore( wrapper.find('.toolbar > .dashicons:eq(-2)') );
					} else if ( button.priority >= 0 ) { // Between the edit button and the remove button
						newButton.insertBefore( wrapper.find('.toolbar > .dashicons:eq(-1)') );
					} else { // After the remove button
						newButton.insertAfter( wrapper.find('.toolbar > .dashicons:eq(-1)') );
					}
				
				}
				
			});

		}
		
		editor.fire( 'show-toolbar', {
			'editor': editor,
			'target': e.target,
			'shortcode': wrapper.attr('data-wpview-type'),
			'toolbar': wrapper.find('.toolbar')[0]
		} );
		
	});
	
	

	/**
	 * Add the clone button in image toolbars
	 */
	$( editor.getBody() ).on('mousedown', function(e) {
		if ( ! $(e.target).is('img.alignleft, img.alignright, img.aligncenter, img.alignnone') ) {
			return;
		}

		// Add the toolbar buttons
		if ( typeof pbsandwich_column.toolbar_buttons !== 'undefined' ) {
			$.each(pbsandwich_column.toolbar_buttons, function(i, button) {
				
				// Check if we should add in the button
				if ( typeof button.shortcode === 'string' ) {
					if ( button.shortcode !== '' && button.shortcode !== 'image' ) {
						return;
					}
				} else { // it's an array
					if ( button.shortcode.indexOf( 'image' ) === -1 ) {
						return;
					}
				}
				
				// Add the actual button, don't add it if it already exists
				if ( $('.mce-wp-image-toolbar .mce-btn-group.mce-container [data-hash="' + button.hash + '"]').length === 0 ) {

					var newButton;
					if ( button.label === '|' ) {
						newButton = $('<div class="mce-widget mce-btn sep"></div>');
						
					} else if ( button.action === '' ) {
						newButton = $('<div class="mce-widget mce-btn toolbar-label"></div>').text( button.label );
						
					} else {
						newButton = $('<div class="mce-widget mce-btn sandwich-toolbar-button" tabindex="-1" role="button" aria-pressed="false"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-dashicon ' + button.icon + '" data-toolbar-action="' + button.action + '"></i></button></div>')
							.attr('aria-label', button.label)
							.attr('title', button.label);
					}
					newButton.attr('data-hash', button.hash);
				
					// Sort the buttons via priority
					if ( button.priority >= 100 ) { // Before the edit button
						newButton.insertBefore( $('.mce-wp-image-toolbar .mce-btn-group.mce-container .mce-widget.mce-btn:eq(-2)') );
					} else if ( button.priority >= 0 ) { // Between the edit button and the remove button
						newButton.insertBefore( $('.mce-wp-image-toolbar .mce-btn-group.mce-container .mce-widget.mce-btn:eq(-1)') );
					} else { // After the remove button
						newButton.insertAfter( $('.mce-wp-image-toolbar .mce-btn-group.mce-container .mce-widget.mce-btn:eq(-1)') );
					}
					
				}
				
			});

		}
		
		editor.fire( 'show-toolbar-image', {
			'editor': editor,
			'target': e.target,
			'toolbar': $('.mce-wp-image-toolbar')[0]
		} );
		editor.fire( 'show-toolbar', {
			'editor': editor,
			'target': e.target,
			'shortcode': 'image',
			'toolbar': $('.mce-wp-image-toolbar')[0]
		} );
		
	});
});

}