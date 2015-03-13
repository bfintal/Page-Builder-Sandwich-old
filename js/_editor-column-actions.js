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
			html: '<div id="pbsandwich_column_change_modal"><h4>' + pbsandwich_column.preset + '</h4><p class="desc">' + pbsandwich_column.preset_desc + '</p>' +
				'<p class="mce-btn"><button data-columns="1/2+1/2">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '2' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/3+1/3+1/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/4+1/4+1/4+1/4">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '4' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/3+2/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '1/3 + 2/3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="2/3+1/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '2/3 + 1/3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/4+2/4+1/4">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '1/4 + 2/4 + 1/4' ) + '</button></p> ' + 
				'<hr>' +
				'<h4>' + pbsandwich_column.custom + '</h4><input type="text" class="mce-textbox custom_column" value="1/2+1/2"><p class="mce-btn"><button>' + pbsandwich_column.use_custom + '</button></p><p class="desc">' + pbsandwich_column.modal_description + '<code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/2+1/2</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/3+1/3+1/3</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/4+2/4+1/4</code></p></div>'
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
 * TODO: Column change toolbar button
 */
editor.on('toolbar-column-edit-area', function(e) {
    var colModal = editor.windowManager.open( {
        title: pbsandwich_column.change_column,
		// buttons: [{
		//             text: pbsandwich_column.cancel,
		//             onclick: 'close'
		//         }],
        body: [{
			type: 'container',
			html: '<div id="dsadsa"><h4>' + pbsandwich_column.preset + '</h4><p class="desc">' + pbsandwich_column.preset_desc + '</p>' +
				'<p class="mce-btn"><button data-columns="1/2+1/2">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '2' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/3+1/3+1/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/4+1/4+1/4+1/4">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '4' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/3+2/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '1/3 + 2/3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="2/3+1/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '2/3 + 1/3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/4+2/4+1/4">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '1/4 + 2/4 + 1/4' ) + '</button></p> ' + 
				'<hr>' +
				'<h4>' + pbsandwich_column.custom + '</h4><input type="text" class="mce-textbox custom_column" value="1/2+1/2"><p class="mce-btn"><button>' + pbsandwich_column.use_custom + '</button></p><p class="desc">' + pbsandwich_column.modal_description + '<code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/2+1/2</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/3+1/3+1/3</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/4+2/4+1/4</code></p></div>'
		}],
        onsubmit: function( e ) {
			console.log('OK');
			// preUpdateSortable( editor );
            // editor.insertContent( _pbsandwich_columns_formTable( e.data.columns, editor.selection.getContent() ) );
			// updateSortable( editor );
        }
    });
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
		$(this).addClass('col-sm-' + columnWidths[ i ]).css('width', ( columnWidths[ i ] / 12 * 100 ) + '%' );
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
	updateSortable( editor );
	
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
		$(this).addClass('col-sm-' + columnWidths[ i ]).css('width', ( columnWidths[ i ] / 12 * 100 ) + '%' );
	});
	
	// Cleanup to make views with iframes display again
	if ( ( newElement.find('.wpview-wrap iframe').length > 0 ) ) {
		editor.execCommand( 'mceCleanup' );
	}
});