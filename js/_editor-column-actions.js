/**
 * Column change toolbar button
 */
editor.on('toolbar-column-columns', function(e) {
	var colModal = editor.windowManager.open( {
		title: pbsandwich_column.change_column,
		buttons: [{
			text: pbsandwich_column.cancel,
			onclick: 'close'
		}],
		body: [{
			type: 'container',
			html: wp.template( 'pbs-column-change-modal' )( pbsandwich_column )
		}],
		onsubmit: function( e ) {
			preUpdateSortable( editor );
			editor.insertContent( _pbsandwich_columns_formTable( e.data.columns, editor.selection.getContent() ) );
			updateSortable( editor );
		}
	});
	
	_pbsandwich_removeColumnToolbar( editor );
});


/**
 * Change columns modal action handler. When a change column button is clicked
 */
jQuery('body').on('click', '#pbsandwich_column_change_modal button', function() {
	var $ = jQuery;
	
	// Get the column composition
	var columns = $(this).attr('data-columns');
	if ( typeof columns === 'undefined' ) {
		columns = $(this).parents('#pbsandwich_column_change_modal').find('input.custom_column').val();
	}
	
	// The column container will have the attribute data-wp-columnselect
	var selectedColumn = $(tinyMCE.activeEditor.getBody()).find('[data-wp-columnselect="1"]')[0];
	var columnIndexPrev = $(tinyMCE.activeEditor.getBody()).find('[data-wp-columnselect="1"]').index();
	tinyMCE.activeEditor.selection.select( $(selectedColumn).parents('.pbsandwich_column:eq(0)')[0] );

	// Change the column
	preUpdateSortable( tinyMCE.activeEditor );
	tinyMCE.activeEditor.insertContent( _pbsandwich_columns_formTable( columns, tinyMCE.activeEditor.selection.getContent() ) );
	updateSortable( tinyMCE.activeEditor );
	
	// Find out what column was previously selected
	var columnContainer = tinyMCE.activeEditor.selection.getSelectedBlocks();
	var columnToSelect = false;
	if ( typeof columnContainer !== 'undefined' ) {
		if ( $(columnContainer).parents('.pbsandwich_column:eq(0)').find('> tbody > tr > td').length - 1 >= columnIndexPrev ) {
			columnToSelect = $(columnContainer).parents('.pbsandwich_column:eq(0)').find('> tbody > tr > td:eq(' + columnIndexPrev + ')');
		} else {
			columnToSelect = $(columnContainer).parents('.pbsandwich_column > tbody > tr > td:eq(-1)');
		}
		
	}
	// Fix paragraphs
	fixTableParagraphs( tinyMCE.activeEditor );
	// Put the cursor in the new table
	if ( columnToSelect !== false ) {
		tinyMCE.activeEditor.selection.setCursorLocation(columnToSelect[0]);
	}
	
	// Close our modal window
	tinyMCE.activeEditor.windowManager.getWindows()[0].close();
});


/**
 * Column remove entire row toolbar button
 */
editor.on('toolbar-column-remove-row', function(e) {
	var $ = jQuery;
	preUpdateSortable( editor );
	$(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)').remove();
	updateSortable( editor );
	
	_pbsandwich_removeColumnToolbar( editor );
});


/**
 * Column clone entire row toolbar button
 */
editor.on('toolbar-column-clone-row', function(e) {
	var $ = jQuery;

	preUpdateSortable( editor );
	var newElement = $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)').clone();
	newElement.insertAfter( $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)') );
	updateSortable( editor );
	
	// Cleanup to make views with iframes display again
	if ( ( newElement.find('.wpview-wrap iframe').length > 0 ) ) {
		editor.execCommand( 'mceCleanup' );
	}
	
	_pbsandwich_removeColumnToolbar( editor );
});


/**
 * Renders the edit column modal
 */
editor.on('toolbar-column-edit-area', function(e) {
	var $ = jQuery;
	
	var $selectedColumn = $(editor.getBody()).find('[data-wp-columnselect="1"]');
	
	var $innerColumn = $selectedColumn.find('> .inner-column:eq(0)');
	
	var bgImageURL = $selectedColumn.css('background-image').replace( /url\(([^\)]+)\)/g, '$1' );
	
	if ( bgImageURL === 'none' ) {
		bgImageURL = '';
	}
	
	pbsandwich_column.fields = {
		padding_top: parseInt( $selectedColumn.css('paddingTop') ),
		padding_right: parseInt( $selectedColumn.css('paddingRight') ),
		padding_bottom: parseInt( $selectedColumn.css('paddingBottom') ),
		padding_left: parseInt( $selectedColumn.css('paddingLeft') ),
		border_top: parseInt( $selectedColumn.css('borderTopWidth') ),
		border_right: parseInt( $selectedColumn.css('borderRightWidth') ),
		border_bottom: parseInt( $selectedColumn.css('borderBottomWidth') ),
		border_left: parseInt( $selectedColumn.css('borderLeftWidth') ),
		border_style: $selectedColumn.css('borderStyle'),
		border_color: $selectedColumn.css('borderColor') === 'rgba(0, 0, 0, 0)' ? '' : $selectedColumn.css('borderColor'),
		border_radius: parseInt( $selectedColumn.css('borderRadius') ),
		background_color: $selectedColumn.css('backgroundColor') === 'rgba(0, 0, 0, 0)' ? '' : $selectedColumn.css('backgroundColor'),
		background_image: $selectedColumn.attr('data-background-image'),
		background_image_preview: bgImageURL,
		background_image_url: bgImageURL,
		background_size: $selectedColumn.css('backgroundSize'),
		background_repeat: $selectedColumn.css('backgroundRepeat'),
		background_position: $selectedColumn.css('backgroundPosition')
	};

	var colModal = editor.windowManager.open( {
		title: pbsandwich_column.column_settings,
		body: [{
			type: 'container',
			html: wp.template( 'pbs-column-area-edit-modal' )( pbsandwich_column )
		}],
		/**
		 * Apply all our new styles on submit
		 */
		onsubmit: function( e ) {
			var $ = jQuery;
			
			var form = $('#pbsandwich_column_area_edit');
	
			// The column container will have the attribute data-wp-columnselect
			var $selectedColumn = $(tinyMCE.activeEditor.getBody()).find('[data-wp-columnselect="1"]');
			
			// Styles that need the suffix 'px'
			var styles = {
				paddingTop: 'padding_top',
				paddingRight: 'padding_right',
				paddingBottom: 'padding_bottom',
				paddingLeft: 'padding_left',
				borderTopWidth: 'border_top',
				borderRightWidth: 'border_right',
				borderBottomWidth: 'border_bottom',
				borderLeftWidth: 'border_left',
				borderRadius: 'border_radius'
			};
			$.each(styles, function( style, name ) {
				var num = form.find('[name="' + name + '"]').val();
				if ( num.trim() !== '' ) {
					num += 'px';
				}
				$selectedColumn.css( style, num );
			});
			
			// Styles that just need the direct value
			styles = {
				borderStyle: 'border_style',
				borderColor: 'border_color',
				backgroundColor: 'background_color',
				backgroundSize: 'background_size',
				backgroundRepeat: 'background_repeat',
				backgroundPosition: 'background_position'
			};
			$.each(styles, function( style, name ) {
				$selectedColumn.css( style, form.find('[name="' + name + '"]').val() );
			});
			
			// Other styles
			var img = form.find('[name="background_image_url"]').val();
			if ( img.trim() !== '' ) {
				img = 'url(' + img.trim() + ')';
			}
			$selectedColumn.css('backgroundImage', img);
			$selectedColumn.attr('data-background-image', form.find('[name="background_image"]').val() );
			
			// Make the styles permanent
			$selectedColumn.attr('data-mce-style', $selectedColumn.attr('style'));
		}
	});
	
	$('#pbsandwich_column_area_edit').find('#border_color, #background_color').wpColorPicker();
	
	_pbsandwich_removeColumnToolbar( editor );
	
	editor.fire( 'pre-modal-create-tabs', {
		'editor': e.editor,
		'target': $('#pbsandwich_column_area_edit').parent()[0],
		'action': e.action,
		'shortcode': e.shortcode,
		'origin': e.target
	} );
});

/**
 * Select the whole field when the style area inputs are clicked
 */
jQuery('body').on('focus', '.sandwich_modal .style_area input', function(e) {
	e.preventDefault();
	jQuery(this).select();
});

/**
 * Remove the image when the X button is clicked
 */
jQuery('body').on('click', '.sandwich_modal .image_type [src!=""] + .remove_image', function(e) {
	e.preventDefault();
	e.stopPropagation();

	var $ = jQuery;
	var imageContainer = $(this).parents('.image_type:eq(0)');
	
	imageContainer.find('[id="background_image_preview"]').attr('src', '');
	imageContainer.find('[id="background_image_url"]').val( '' );
	imageContainer.find('[id="background_image"]').val( '' );
});

/**
 * Open the media manager when the background image field is clicked
 */
jQuery('body').on('click', '.sandwich_modal .image_type', function(e) {
	e.preventDefault();
	e.stopPropagation();
	
	var $ = jQuery;
	
	var imageContainer = $(this);
	if ( ! imageContainer.is('.image_type') ) {
		imageContainer = $(this).parents('.image_type:eq(0)');
	}
	
	// uploader frame properties
	var frame = wp.media({
		title: 'Select Image',
		multiple: false,
		library: { type: 'image' },
		button : { text : 'Use image' }
	});
	
	frame.on('open',function() {
		var selection = frame.state().get('selection');
		var ids = imageContainer.find('[id="background_image"]').val().split(',');
		ids.forEach(function(id) {
			var attachment = wp.media.attachment(id);
			attachment.fetch();
			selection.add( attachment ? [ attachment ] : [] );
		});
	});
	
	// get the url when done
	frame.on('select', function() {
		var selection = frame.state().get('selection');
		selection.each(function(attachment) {
		
			// Get the preview image
			var image = attachment.attributes.sizes.full;
			if ( typeof attachment.attributes.sizes.thumbnail !== 'undefined' ) {
				image = attachment.attributes.sizes.thumbnail;
			}
			var url = image.url;

			var $ = jQuery;
			imageContainer.find('[id="background_image_preview"]').attr('src', image.url);
			imageContainer.find('[id="background_image_url"]').val( attachment.attributes.url );
			imageContainer.find('[id="background_image"]').val( attachment.id );
			
		});
		frame.off('select');
	});
	
	frame.open();
});


/**
 * Column remove area/column toolbar button
 */
editor.on('toolbar-column-remove-area', function(e) {
	var $ = jQuery;
	
	preUpdateSortable( editor );
	
	var table = $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)');
	
	// Get the width of the column being deleted
	var widthRegex = /col-\w+-(\d+)/g;
	var match = widthRegex.exec( $(editor.getBody()).find('[data-wp-columnselect]').attr('class') );
	var columnIndex = $(editor.getBody()).find('[data-wp-columnselect]').index();
	var oldWidth = 0;
	
	if ( typeof match !== 'undefined' ) {
		if ( match.length > 1 ) {
			oldWidth = parseInt( match[1] );
		}
	}
	
	$(editor.getBody()).find('[data-wp-columnselect]').remove();
	
	updateSortable( editor );
	
	if ( oldWidth === 0 ) {
		return;
	}
	
	// Adjust the width of the other columns
	var columnWidths = [];
	var totalWidth = 0;
	var prevColumnWidth = -1;
	var allColumnsEven = true;
	var w;
	table.find('> tbody > tr > td').each(function() {
		widthRegex = /col-\w+-(\d+)/g;
		match = widthRegex.exec( $(this).attr('class') );
	
		if ( typeof match !== 'undefined' ) {
			if ( match.length > 1 ) {
				w = parseInt( match[1] );
				totalWidth += w;
				columnWidths.push( w );
				if ( prevColumnWidth > -1 && prevColumnWidth !== w ) {
					allColumnsEven = false;
				}
				prevColumnWidth = w;
			}
			
			if ( match.length > 0 ) {
				$(this).removeClass( match[0] );
			}
		}
	});
	
	// Same sized columns, distribute the width evenly
	var i;
	if ( allColumnsEven ) {
		var newWidth = 12 / columnWidths.length;
		for ( i = 0; i < columnWidths.length; i++ ) {
			columnWidths[ i ] = parseInt( newWidth );
		}
		// If there're some stray column width, add them to make the row uneven
		if ( parseInt( newWidth ) !== newWidth ) {
			if ( columnIndex > columnWidths.length - 1 ) {
				columnIndex = columnWidths.length - 1;
			}
			columnWidths[ columnIndex ] += 12 - parseInt( newWidth ) * columnWidths.length;
		}
	// Different sized columns
	} else {
		if ( columnIndex > columnWidths.length - 1 ) {
			columnIndex = columnWidths.length - 1;
		}
		columnWidths[ columnIndex ] += oldWidth;
	}
	
	// Apply new widths
	table.find('> tbody > tr > td').each(function(i, e) {
		$(this).addClass('col-sm-' + columnWidths[ i ])
		.attr('style', $(this).attr('style').replace( /width:\s?[\d.]+\%/, 'width: ' + ( columnWidths[ i ] / 12 * 100 ) + '%' ) )
		.attr('data-mce-style', $(this).attr('data-mce-style').replace( /width:\s?[\d.]+\%/, 'width: ' + ( columnWidths[ i ] / 12 * 100 ) + '%' ) );
	});
	
	_pbsandwich_removeColumnToolbar( editor );
});


/**
 * Column clone area/column toolbar button
 */
editor.on('toolbar-column-clone-area', function(e) {
	var $ = jQuery;
	
	preUpdateSortable( editor );
	
	var table = $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)');
	
	// Get the width of the column being deleted
	var widthRegex = /col-\w+-(\d+)/g;
	var match = widthRegex.exec( $(editor.getBody()).find('[data-wp-columnselect]').attr('class') );
	var columnIndex = $(editor.getBody()).find('[data-wp-columnselect]').index();
	var currentWidth = 0;
	
	if ( typeof match !== 'undefined' ) {
		if ( match.length > 1 ) {
			currentWidth = parseInt( match[1] );
		}
	}

	
	// Get the widths of the other columns
	var columnWidths = [];
	var totalWidth = 0;
	var prevColumnWidth = -1;
	var allColumnsEven = true;
	var w;
	table.find('> tbody > tr > td').each(function() {
		widthRegex = /col-\w+-(\d+)/g;
		match = widthRegex.exec( $(this).attr('class') );
	
		if ( typeof match !== 'undefined' ) {
			if ( match.length > 1 ) {
				w = parseInt( match[1] );
				totalWidth += w;
				columnWidths.push( w );
				if ( prevColumnWidth > -1 && prevColumnWidth !== w ) {
					allColumnsEven = false;
				}
				prevColumnWidth = w;
			}
			
			if ( match.length > 0 ) {
				$(this).removeClass( match[0] );
			}
		}
	});
	
	
	// Don't clone if we are at the max number of columns
	if ( columnWidths.length >= 12 ) {
		return;
	}
	
	
	// Clone the column
	var newElement = $(editor.getBody()).find('[data-wp-columnselect]').clone();
	newElement.insertAfter( $(editor.getBody()).find('[data-wp-columnselect]') );
	
	// Add the new column
	columnIndex++;
	columnWidths.splice(columnIndex, 0, currentWidth);
	
	
	// Same sized columns, distribute the width evenly
	var i;
	if ( allColumnsEven ) {
		var newWidth = 12 / columnWidths.length;
		for ( i = 0; i < columnWidths.length; i++ ) {
			columnWidths[ i ] = parseInt( newWidth );
		}
		// If there're some stray column width, add them to make the row uneven
		if ( parseInt( newWidth ) !== newWidth ) {
			columnWidths[ columnIndex ] += 12 - parseInt( newWidth ) * columnWidths.length;
		}
		
	// Different sized columns
	} else {
		var remainingWidth = currentWidth;
		// Make the stuff on the right smaller
		var spread = columnWidths.length - 1 - columnIndex;
		spread = currentWidth / spread;
		for ( i = columnWidths.length - 1; i > columnIndex; i-- ) {
			if ( columnWidths[ i ] - parseInt( spread ) < 0 ) {
				
			} else {
				columnWidths[ i ] -= parseInt( spread );
			}
		}
		
	
		// Make sure the column width count is correct
		var total = 0;
		for ( i = 0; i < columnWidths.length; i++ ) {
			total += parseInt( columnWidths[ i ] );
		}
		if ( total > 12 ) {
			var widthOffset = ( total - 12 ) / 2;
			columnWidths[ columnIndex ] -= parseInt( widthOffset );
			columnWidths[ columnIndex - 1 ] -= parseInt( widthOffset );
			if ( parseInt( widthOffset ) !== widthOffset ) {
				columnWidths[ columnIndex ] -= total - 12 - parseInt( widthOffset ) * 2;				
			}
		}
		
		
		// Make sure all columns have at least 1 width
		for ( i = 0; i < columnWidths.length; i++ ) {
			if ( columnWidths[ i ] === 0 ) {
				columnWidths[ i ]++;
				var maxIndex = columnWidths.indexOf( Math.max.apply( Math, columnWidths ) );
				columnWidths[ maxIndex ]--;
			}
		}
	}
	

	// Apply new widths
	table.find('> tbody > tr > td').each(function(i, e) {
		$(this).addClass('col-sm-' + columnWidths[ i ])
		.attr('style', $(this).attr('style').replace( /width:\s?[\d.]+\%/, 'width: ' + ( columnWidths[ i ] / 12 * 100 ) + '%' ) )
		.attr('data-mce-style', $(this).attr('data-mce-style').replace( /width:\s?[\d.]+\%/, 'width: ' + ( columnWidths[ i ] / 12 * 100 ) + '%' ) );
	});

	updateSortable( editor );
	
	// Cleanup to make views with iframes display again
	if ( ( newElement.find('.wpview-wrap iframe').length > 0 ) ) {
		editor.execCommand( 'mceCleanup' );
	}
	
	_pbsandwich_removeColumnToolbar( editor );
});




/**
 * Renders the edit column modal
 */
editor.on('toolbar-column-edit-row', function(e) {	
	var $ = jQuery;
	
	var $selectedRow = $(editor.getBody()).find('[data-wp-columnselect="1"]').parents('.pbsandwich_column:eq(0)');

	var bgImageURL = $selectedRow.css('background-image').replace( /url\(([^\)]+)\)/g, '$1' );

	var action = e.action,
		shortcode = e.sortcode,
		origin = e.target;
	
	if ( bgImageURL === 'none' ) {
		bgImageURL = '';
	}

	pbsandwich_column.fields = {
		padding_top: parseInt( $selectedRow.css('paddingTop') ),
		padding_right: parseInt( $selectedRow.css('paddingRight') ),
		padding_bottom: parseInt( $selectedRow.css('paddingBottom') ),
		padding_left: parseInt( $selectedRow.css('paddingLeft') ),
		border_top: parseInt( $selectedRow.css('borderTopWidth') ),
		border_right: parseInt( $selectedRow.css('borderRightWidth') ),
		border_bottom: parseInt( $selectedRow.css('borderBottomWidth') ),
		border_left: parseInt( $selectedRow.css('borderLeftWidth') ),
		border_style: $selectedRow.css('borderStyle'),
		border_color: $selectedRow.css('borderColor') === 'rgba(0, 0, 0, 0)' ? '' : $selectedRow.css('borderColor'),
		border_radius: parseInt( $selectedRow.css('borderRadius') ),
		margin_top: parseInt( $selectedRow.css('marginTop') ),
		margin_right: parseInt( $selectedRow.css('marginRight') ),
		margin_bottom: parseInt( $selectedRow.css('marginBottom') ),
		margin_left: parseInt( $selectedRow.css('marginLeft') ),
		background_color: $selectedRow.css('backgroundColor') === 'rgba(0, 0, 0, 0)' ? '' : $selectedRow.css('backgroundColor'),
		background_image: $selectedRow.attr('data-background-image'),
		background_image_preview: bgImageURL,
		background_image_url: bgImageURL,
		background_size: $selectedRow.css('backgroundSize'),
		background_repeat: $selectedRow.css('backgroundRepeat'),
		background_position: $selectedRow.css('backgroundPosition'),
		full_width: $selectedRow.attr('data-break-out')
	};

	//
	var colModal = editor.windowManager.open( {
			title: pbsandwich_column.row_settings,
			height: $(window).height() * .8,
			width: $(window).width() * .7 > 900 ? 900 : $(window).width() * .7,
			body: [{
				type: 'container',
				html: wp.template( 'pbs-column-row-edit-modal' )( pbsandwich_column )
			}],
		/**
		 * Apply all our new styles on submit
		 */
		onsubmit: function( e ) {
			var $ = jQuery;

			var form = $('#pbsandwich_column_row_edit');
			
			// The column container will have the attribute data-wp-columnselect
			var $selectedRow = $(editor.getBody()).find('[data-wp-columnselect="1"]').parents('.pbsandwich_column:eq(0)');

			// Styles that need the suffix 'px'
			var styles = {
				paddingTop: 'padding_top',
				paddingRight: 'padding_right',
				paddingBottom: 'padding_bottom',
				paddingLeft: 'padding_left',
				borderTopWidth: 'border_top',
				borderRightWidth: 'border_right',
				borderBottomWidth: 'border_bottom',
				borderLeftWidth: 'border_left',
				borderRadius: 'border_radius',
				marginTop: 'margin_top',
				marginRight: 'margin_right',
				marginBottom: 'margin_bottom',
				marginLeft: 'margin_left'
			};
			var calcWidth = 0;
			$.each(styles, function( style, name ) {
				var num = form.find('[name="' + name + '"]').val();
				if ( num.trim() !== '' ) {

					if ( style === 'marginLeft' || style === 'marginRight' ) {
						calcWidth += parseInt( num );
					}
					
					num += 'px';
				}
				$selectedRow.css( style, num );
			});
			$selectedRow.css('width', 'calc( 100% - ' + calcWidth + 'px )');

			// Styles that just need the direct value
			styles = {
				borderStyle: 'border_style',
				borderColor: 'border_color',
				backgroundColor: 'background_color',
				backgroundSize: 'background_size',
				backgroundRepeat: 'background_repeat',
				backgroundPosition: 'background_position'
			};
			$.each(styles, function( style, name ) {
				$selectedRow.css( style, form.find('[name="' + name + '"]').val() );
			});

			// Other styles
			var img = form.find('[name="background_image_url"]').val();
			if ( img.trim() !== '' ) {
				img = 'url(' + img.trim() + ')';
			}
			$selectedRow.css('backgroundImage', img);
			$selectedRow.attr('data-background-image', form.find('[name="background_image"]').val() );
			$selectedRow.attr('data-break-out', form.find('[name="full_width"]').val() );

			// Make the styles permanent
			$selectedRow.attr('data-mce-style', $selectedRow.attr('style'));
			
			editor.fire( 'modal-save', {
				'editor': editor,
				'target': $selectedRow[0],
				'action': action,
				'shortcode': 'row',
				'origin': origin
			} );
		}
	});

	$('#pbsandwich_column_row_edit').find('#border_color, #background_color').wpColorPicker();
	
	_pbsandwich_removeColumnToolbar( editor );
	
	editor.fire( 'pre-modal-create-tabs', {
		'editor': e.editor,
		'target': $('#pbsandwich_column_row_edit').parent()[0],
		'action': e.action,
		'shortcode': e.shortcode,
		'origin': e.target
	} );
});



/**
 * Close the modal window when the enter key is pressed
 */
jQuery('body').on('keypress', '.sandwich_modal input, .sandwich_modal select', function(e) {
	var $ = jQuery;
	if ( e.which === 13 ) {
		$(this).parents('.mce-window').find('.mce-primary button').trigger('click');
	}
});


/**
 * Content alignment buttons
 */
editor.on('toolbar-row-align-left', function(e) {
	var $ = jQuery;
	$(e.target).removeClass( 'pbs-align-right pbs-align-center' );
	$(e.target).addClass('pbs-align-left');
});
editor.on('toolbar-row-align-center', function(e) {
	var $ = jQuery;
	$(e.target).removeClass('pbs-align-left pbs-align-right');
	$(e.target).addClass('pbs-align-center');	
});
editor.on('toolbar-row-align-right', function(e) {
	var $ = jQuery;
	$(e.target).removeClass('pbs-align-left pbs-align-center');
	$(e.target).addClass('pbs-align-right');
});
editor.on('toolbar-row-align-none', function(e) {
	var $ = jQuery;
	$(e.target).removeClass('pbs-align-left pbs-align-center pbs-align-right');
});