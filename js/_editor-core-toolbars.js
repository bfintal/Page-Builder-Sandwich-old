/**
 * Toolbar functionality
 */
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
				
				// Add the actual button
				if ( wrapper.find('[data-toolbar-action="' + button.action + '"]').length === 0 ) {
					newButton = $('<div class="' + button.icon + '" data-toolbar-action="' + button.action + '"></div>')
						.attr('title', button.tooltip);
					
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
		
	});
	
	

	/**
	 * Add the clone button in image toolbars
	 */
	$( editor.getBody() ).on('mousedown', function(e) {
		if ( ! $(e.target).is('img.alignleft, img.alignright, img.aligncenter, img.alignnone') ) {
			return;
		}

		// Add the toolbar buttons
		var newButton;
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
				
				// Add the actual button
				if ( $('.mce-wp-image-toolbar .mce-btn-group.mce-container [data-toolbar-action="' + button.action + '"]').length === 0 ) {
					newButton = $('<div class="mce-widget mce-btn" tabindex="-1" role="button" aria-pressed="false"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-dashicon ' + button.icon + '" data-toolbar-action="' + button.action + '"></i></button></div>')
						.attr('aria-label', button.tooltip)
						.attr('title', button.tooltip);
				
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
		
	});
});


/**
 * Fire toolbar actions (for images only)
 */
$('body').on('mouseup', '[data-toolbar-action]', function(e) {
	var action = $(e.target).attr('data-toolbar-action');
	var target = $(editor.getBody()).find('[data-mce-selected="1"]:not(.pbsandwich_column)');
	
	editor.fire( 'toolbar-' + action, {
		'action': action,
		'editor': editor,
		'type': 'image',
		'target': target
	} );
});


/**
 * Fire toolbar actions (for views only)
 */
editor.on('init', function(e) {
	$(editor.getBody()).on('mouseup', '[data-toolbar-action]', function(e) {
		
		var action = $(e.target).attr('data-toolbar-action');
		var target = $(editor.getBody()).find('[data-mce-selected="1"]:not(.pbsandwich_column)');
		
		editor.fire( 'toolbar-' + action, {
			'action': action,
			'editor': editor,
			'type': target.attr('data-wpview-type'),
			'target': target
		} );
		
	});
});


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