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
 * TODO:
 * * Remove image
 * * clean up code
 * * background position
 */
editor.on('toolbar-column-edit-area', function(e) {
	var $selectedColumn = $(editor.getBody()).find('[data-wp-columnselect="1"]');
	
	var $innerColumn = $selectedColumn.find('> .inner-column:eq(0)');
	
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
		background_color: $selectedColumn.css('backgroundColor') === 'rgba(0, 0, 0, 0)' ? '' : $selectedColumn.css('backgroundColor'),
		background_image: $selectedColumn.attr('data-background-image'),
		background_image_preview: $selectedColumn.css('background-image').replace( /url\(([^\)]+)\)/g, '$1' ),
		background_image_url: $selectedColumn.css('background-image').replace( /url\(([^\)]+)\)/g, '$1' ),
		background_size: $selectedColumn.css('backgroundSize'),
		background_repeat: $selectedColumn.css('backgroundRepeat')
		// padding_right: $innerColumn.length > 0 ? parseInt( $innerColumn.css('marginLeft') ) : 0
	};
	
	// Default
	// if ( ( $selectedColumn.css('borderStyle') === "dashed dashed dashed none" ) || $selectedColumn.css('borderStyle') === "dashed" ) &&
		// ( $selectedColumn.css('borderColor') === "rgb(204, 204, 204) rgb(204, 204, 204) rgb(204, 204, 204) rgb(51, 51, 51)" || $selectedColumn.css('borderColor') === "rgb(204, 204, 204)" ) {
		// pbsandwich_column.fields.border_
	// }
	// rgb(204, 204, 204) rgb(204, 204, 204) rgb(204, 204, 204) rgb(51, 51, 51)
	// rgb(204, 204, 204)
	
    var colModal = editor.windowManager.open( {
        title: pbsandwich_column.change_column,
		// buttons: [{
		//             text: pbsandwich_column.cancel,
		//             onclick: 'close'
		//         }],
        body: [{
			type: 'container',
			html: wp.template( 'pbs-column-area-edit-modal' )( pbsandwich_column )
		}],
        onsubmit: function( e ) {
			var $ = jQuery;
			
			var form = $('#pbsandwich_column_area_edit');
	
			// The column container will have the attribute data-wp-columnselect
			var $selectedColumn = $(tinyMCE.activeEditor.getBody()).find('[data-wp-columnselect="1"]');
			
			$selectedColumn.css('paddingTop', form.find('[name="padding_top"]').val() + 'px');
			$selectedColumn.css('paddingRight', form.find('[name="padding_right"]').val() + 'px');
			$selectedColumn.css('paddingBottom', form.find('[name="padding_bottom"]').val() + 'px');
			$selectedColumn.css('paddingLeft', form.find('[name="padding_left"]').val() + 'px');
			
			$selectedColumn.css('borderTopWidth', form.find('[name="border_top"]').val() + 'px');
			$selectedColumn.css('borderRightWidth', form.find('[name="border_right"]').val() + 'px');
			$selectedColumn.css('borderBottomWidth', form.find('[name="border_bottom"]').val() + 'px');
			$selectedColumn.css('borderLeftWidth', form.find('[name="border_left"]').val() + 'px');
			
			$selectedColumn.css('borderStyle', form.find('[name="border_style"]').val());
			$selectedColumn.css('borderColor', form.find('[name="border_color"]').val());
			
			$selectedColumn.css('backgroundColor', form.find('[name="background_color"]').val());
			$selectedColumn.css('backgroundImage', 'url(' + form.find('[name="background_image_url"]').val() + ')');
			$selectedColumn.attr( form.find('[name="background_image"]').val() );

			$selectedColumn.css('backgroundSize', form.find('[name="background_size"]').val());
			$selectedColumn.css('backgroundRepeat', form.find('[name="background_repeat"]').val());
			
			$selectedColumn.attr('data-mce-style', $selectedColumn.attr('style'));
			// .attr('style', $(this).attr('style').replace( /width:\s?[\d.]+\%/, 'width: ' + ( columnWidths[ i ] / 12 * 100 ) + '%' ) )
			// .attr('data-mce-style', $(this).attr('data-mce-style').replace( /width:\s?[\d.]+\%/, 'width: ' + ( columnWidths[ i ] / 12 * 100 ) + '%' ) );
			// console.log($('#pbsandwich_column_area_edit [name="test"]').val())
			// console.log($selectedColumn, form.find('[name="test"]').val());
			console.log('OK', e);
			// preUpdateSortable( editor );
            // editor.insertContent( _pbsandwich_columns_formTable( e.data.columns, editor.selection.getContent() ) );
			// updateSortable( editor );
        }
    });
	
	$('#pbsandwich_column_area_edit').find('#border_color, #background_color').wpColorPicker();
});


jQuery('body').on('click', '#pbsandwich_column_area_edit label[for="background_image"]', function(e) {
	e.preventDefault();
	e.stopPropagation();
	
	// uploader frame properties
	var frame = wp.media({
		title: 'Select Image',
		multiple: false,
		library: { type: 'image' },
		button : { text : 'Use image' }
	});
	
	frame.on('open',function() {
		var selection = frame.state().get('selection');
		ids = jQuery('#background_image').val().split(',');
		ids.forEach(function(id) {
			attachment = wp.media.attachment(id);
			attachment.fetch();
			selection.add( attachment ? [ attachment ] : [] );
		});
	});
	
	// get the url when done
	frame.on('select', function() {
		var selection = frame.state().get('selection');
		selection.each(function(attachment) {
			// if ( _input.length > 0 ) {
			// 	_input.val(attachment.id);
			// }
			//
			// if ( _preview.length > 0 ) {
			// 	// remove current preview
			// 	if ( _preview.find('img').length > 0 ) {
			// 		_preview.find('img').remove();
			// 	}
			// 	if ( _preview.find('i.remove').length > 0 ) {
			// 		_preview.find('i.remove').remove();
			// 	}

				// Get the preview image
				var image = attachment.attributes.sizes.full;
				if ( typeof attachment.attributes.sizes.thumbnail != 'undefined' ) {
					image = attachment.attributes.sizes.thumbnail;
				}
				var url = image.url;
				// var marginTop = ( _preview.height() - image.height ) / 2;
				// var marginLeft = ( _preview.width() - image.width ) / 2;

				var $ = jQuery;
				$('#background_image_preview').attr('src', image.url);
				$('#background_image_url').val( attachment.attributes.url );
				$('#background_image').val( attachment.id );
				// $("<img src='" + url + "'/>")
				// 	.css('marginTop', marginTop)
				// 	.css('marginLeft', marginLeft)
				// 	.appendTo(_preview);
				// $("<i class='dashicons dashicons-no-alt remove'></i>").prependTo(_preview);
			// }
			// we need to trigger a change so that WP would detect that we changed the value
			// or else the save button won't be enabled
			// _input.trigger('change');
			//
			// _remove.show();
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
});


/**
 * Column clone area/column toolbar button
 */
editor.on('toolbar-column-clone-area', function(e) {
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
});