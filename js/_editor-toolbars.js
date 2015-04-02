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
				
				// Add the actual button, don't add it if it already exists
				if ( wrapper.find('[data-hash="' + button.hash + '"]').length === 0 ) {
					
					if ( button.icon === '|' ) {
						newButton = $('<div class="dashicons sep"></div>');
						
					} else {
						newButton = $('<div class="' + button.icon + '" data-toolbar-action="' + button.action + '"></div>')
							.attr('aria-label', button.tooltip)
							.attr('title', button.tooltip);
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
					if ( button.icon === '|' ) {
						newButton = $('<div class="mce-widget mce-btn sep"></div>');
						
					} else {
						newButton = $('<div class="mce-widget mce-btn sandwich-toolbar-button" tabindex="-1" role="button" aria-pressed="false"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-dashicon ' + button.icon + '" data-toolbar-action="' + button.action + '"></i></button></div>')
							.attr('aria-label', button.tooltip)
							.attr('title', button.tooltip);
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
		
	});
});

	
/**
 * Add the toolbar in columns
 */
editor.on('show-toolbar-column', function(e) {
	
	var toolbar = $(e.toolbar);
	
	// Add the toolbar buttons
	var inColumn, inRow;
	if ( typeof pbsandwich_column.toolbar_buttons !== 'undefined' ) {
		$.each(pbsandwich_column.toolbar_buttons, function(i, button) {
			
			// Check if we should add in the button
			if ( typeof button.shortcode === 'string' ) {
				inColumn = button.shortcode === 'column';
				inRow = button.shortcode === 'row';
			} else { // it's an array
				inColumn = button.shortcode.indexOf( 'column' ) !== -1;
				inRow = button.shortcode.indexOf( 'row' ) !== -1;
			}

			// Don't add it if it already exists
			if ( toolbar.find('[data-hash="' + button.hash + '"]').length > 0 ) {
				return;
			}

			// Create a button or a separator
			var newButton;			
			if ( button.icon === '|' ) {
				newButton = $('<div class="sep" data-mce-bogus="1"></div>');
				
			} else {

				// Create the button
				newButton = $('<div class="' + button.icon + '" data-toolbar-action="' + button.action + '" data-mce-bogus="1"></div>')
					.attr('aria-label', button.tooltip)
					.attr('title', button.tooltip);
			}
			newButton.attr('data-hash', button.hash);
			
			
			// Add the button to the toolbar
			if ( inColumn ) {
			
				// Sort the buttons via priority
				newButton.attr('data-shortcode', 'column');
				if ( button.priority >= 100 ) { // Before the edit button
					newButton.clone().insertBefore( toolbar.find('[data-column-action="edit-area"]') );
				} else if ( button.priority >= 0 ) { // Between the edit/clone button and the remove button
					newButton.clone().insertBefore( toolbar.find('[data-column-action="remove-area"]') );
				} else { // After the remove button
					newButton.clone().insertAfter( toolbar.find('[data-column-action="remove-area"]') );
				}
				
			}
			if ( inRow ) {
			
				// Sort the buttons via priority
				newButton.attr('data-shortcode', 'row');
				if ( button.priority >= 100 ) { // Before the edit button
					newButton.clone().insertBefore( toolbar.find('[data-column-action="edit-row"]') );
				} else if ( button.priority >= 0 ) { // Between the edit/clone button and the remove button
					newButton.clone().insertBefore( toolbar.find('[data-column-action="remove-row"]') );
				} else { // After the remove button
					newButton.clone().insertAfter( toolbar.find('[data-column-action="remove-row"]') );
				}
				
			}
			
		});

	}
});


/**
 * Fire toolbar actions (for images only)
 */
$('body').on('mousedown', '[data-toolbar-action]', function(e) {
	var action = $(e.target).attr('data-toolbar-action');
	var target = $(editor.getBody()).find('[data-mce-selected="1"]:not(.pbsandwich_column)');
	
	editor.fire( 'toolbar-' + action, {
		'action': action,
		'editor': editor,
		'shortcode': 'image',
		'target': target
	} );
});


/**
 * Fire toolbar actions (for views only & columns/rows)
 */
editor.on('init', function(e) {
	$(editor.getBody()).on('mousedown', '[data-toolbar-action]', function(e) {
		
		var action = $(e.target).attr('data-toolbar-action');
		var target = $(editor.getBody()).find('[data-mce-selected="1"]:not(.pbsandwich_column)');
		
		/**
		 * Handle colummn toolbar buttons
		 */
		if ( $(e.target).parents('#wp-column-toolbar').length > 0 ) {
			
			if ( $(e.target).attr('data-shortcode') === 'column' ) {
				target = $(editor.getBody()).find('[data-wp-columnselect="1"]');
			} else { // row
				target = $(editor.getBody()).find('[data-wp-columnselect="1"]').parents('.pbsandwich_column:eq(0)');
			}

			editor.fire( 'toolbar-' + action, {
				'action': action,
				'editor': editor,
				'shortcode': $(e.target).attr('data-shortcode'),
				'target': target
			} );
			
			return;
		}
		
		/**
		 * Normal column toolbar buttons
		 */
		editor.fire( 'toolbar-' + action, {
			'action': action,
			'editor': editor,
			'shortcode': target.attr('data-wpview-type'),
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