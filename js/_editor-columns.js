/**
 * Adds the toolbar
 * @see http://wordpress.stackexchange.com/questions/74762/hook-for-image-edit-popup
 */
function _pbsandwich_addColumnToolbar( editor, node ) {
	var $ = jQuery;
	var rectangle, toolbarHtml, toolbar, left,
	dom = editor.dom;

	_pbsandwich_removeColumnToolbar( editor );
	
	// Don't create the toolbar if the column was just dragged
	if ( $(editor.getBody()).hasClass('pbsandwich_just_dragged') ) {
		return;
	}

	// Only add the toolbar for sandwich columns
	if ( $(node).parents('table:eq(0)').length === 0 ) {
		return;
	}
	if ( ! $(node).parents('table:eq(0)').is('.pbsandwich_column') ) {
		return;
	}
	if ( $(node).parents('.pbsandwich_column:eq(0)').length === 0 ) {
		return;
	}
	
	// Get the column selected
	if ( ! $(node).is('td') ) {
		node = $(node).parents('td:eq(0)')[0];
	}

	// Remember the column that has the toolbar
	$(editor.getBody()).find( '[data-wp-columnselect]' ).removeAttr( 'data-wp-columnselect' );
	dom.setAttrib( node, 'data-wp-columnselect', 1 );

	// Create the toolbar
	toolbar = dom.create( 'div', {
		'id': 'wp-column-toolbar',
		'data-mce-bogus': '1',
		'contenteditable': false
	});

	editor.getBody().appendChild( toolbar );
	
	// Dispatch toolbar show event
	editor.fire( 'show-toolbar-column', {
		'editor': editor,
		'target': $(editor.getBody()).find( '[data-wp-columnselect]' )[0],
		'toolbar': toolbar,
		'node': node
	} );
}


/**
 * Makes sure that the column toolbar is always visible
 */
editor.on('toolbar-column-buttons-done', function(e) {
	var $ = jQuery;
	var rectangle, left, 
	node = e.node,
	dom = editor.dom
	toolbar = e.toolbar,
	editorWidth = $(editor.getDoc()).width();
	
	// Get the column selected
	if ( ! $(node).is('td') ) {
		node = $(node).parents('td:eq(0)')[0];
	}
	
	// Get the column area
	rectangle = dom.getRect( node );
	
	// This is the left location of the toolbar
	left = rectangle.x + rectangle.w / 2;
		
	// Adjust the location if the toolbar goes past the right side
	if ( left + $(toolbar).width() - $(toolbar).width() / 2 > editorWidth ) {
		left -= ( left + $(toolbar).width() - $(toolbar).width() / 2 ) - editorWidth + 6;
		
	// Adjust the location if the toolbar goes past the left side
	} else if ( left - $(toolbar).width() / 2 < 0 ) {
		left += - ( left - $(toolbar).width() / 2 ) + 6;
	}
	
	// Adjust the location if the toolbar goes past the top of the editor
	var top = rectangle.y - 6;
	if ( top - $(toolbar).height() / 2 < 6 ) {
		top = rectangle.y;
	}
	
	// Position the column toolbar
	dom.setStyles( toolbar, {
		top: top,
		left: left
	});
});


/**
 * Remove the toolbar
 * @see http://wordpress.stackexchange.com/questions/74762/hook-for-image-edit-popup
 */
function _pbsandwich_removeColumnToolbar( editor ) {
	var toolbar = editor.dom.get( 'wp-column-toolbar' );

	if ( toolbar ) {
		editor.dom.remove( toolbar );
	}
}


/**
 * Forms the column labels for TinyMCE
 */
function _pbsandwich_columns_sprintf( format, etc ) {
    var arg = arguments;
    var i = 1;
    return format.replace(/%((%)|s)/g, function (m) { 
		return m[2] || arg[ i++ ];
	});
}


/**
 * Checks if the content selected is an existing column table.
 * If it is, get each column content
 */
function _pbsandwich_columns_formContent( content, numColumns ) {
	if ( content === '' ) {
		return pbsandwich_column.dummy_content;
	}
	
	var $ = jQuery;
	var contents = [];
	
	var $content = $('<div></div>').html(content);
	if ( $content.find('table.pbsandwich_column').length === 0 ) {
		return pbsandwich_column.dummy_content;
	}
	
	$content.find('table.pbsandwich_column td').each( function( i, e ) {
		if ( i >= numColumns ) {
			var content = $(e).html();
			if ( ! /^<p\s[^>]+>(\s|&nbsp;)*<\/p>$/.test( content ) ) {

				// Wrap the contents in paragraphs so we can edit the contents
				var innerHTML = '';
				try {
					if ( $(content).is('p') ) {
						innerHTML = content;
					} else {
						innerHTML = $('<p></p>').html(content);
					}
				} catch (error) {
					innerHTML = $('<p></p>').html(content);
				}
				
				// Don't add empty contents
				if ( $(innerHTML).text().trim() === '' ) {
					innerHTML = '';
				}
				
				contents[ contents.length - 1] += innerHTML;
			}
		} else {
			contents.push( $(e).html() );
		}
	} );
	
	return contents;
}


/**
 * Forms the table "columns"
 */
function _pbsandwich_columns_formTable( columns, content ) {
	var $ = jQuery;
	
	var cols = columns.split('+');
	var table = '<table class="pbsandwich_column" style="width: 100%; height: auto; border: none;" border="0"><tbody class="row"><tr>';
	var columnContents = _pbsandwich_columns_formContent( content, cols.length );
	var columnContent = pbsandwich_column.dummy_content;
	var newTd;
	
	$.each( cols, function( i, e ) {
		
		// Get what content we will use
		if ( typeof columnContents === 'object' ) {
			if ( i >= columnContents.length ) {
				columnContent = '';
			} else {
				columnContent = columnContents[ i ];
			}
		} else if ( typeof columnContents === 'string' ) {
			if ( content.trim() !== '' ) {
				if ( i === 0 ) {
					columnContent = content;
				} else {
					columnContent = '';
				}
			}
		}
		
		// Compute our margins
		var fraction = e.split('/');
		var width = parseInt( fraction[0] ) / parseInt( fraction[1] ) * 100;
		var col = parseInt( parseInt( fraction[0] ) / parseInt( fraction[1] ) * 12 );

		// Create the new column
		newTd = $('<td></td>');
		
		// Retain current column styles
		try {
			if ( $(content).is('table') ) {
				var oldColumn = $(content).find('> tbody > tr > td:eq(' + i + ')');
				if ( oldColumn.length > 0 ) {
					newTd.attr('style', oldColumn.attr('style') );
					newTd.attr('data-mce-style', oldColumn.attr('data-mce-style') );
				}
			}
		} catch (error) {
		}
		
		// Wrap the contents in paragraphs so it can be edited
		var innerHTML = '';
		try {
			if ( $(columnContent).is('p') ) {
				innerHTML = columnContent;
			} else {
				innerHTML = $('<p></p>').html(columnContent);
			}
		} catch (error) {
			innerHTML = $('<p></p>').html(columnContent);
		}
		
		// Add the new contents and attributes
		newTd.addClass('col-sm-' + col)
		.html(innerHTML)
		.css('width', width + '%');
		
		table += newTd[0].outerHTML;
	} );
	
	table += '</tr></tbody></table>';
	
	// Copy the data/styles of the table to the new replacement one
	try {
		if ( $(content).is('table') ) {
			table = $(table);
			$.each( $(content)[0].attributes, function() {
				if ( this.specified ) {
					table.attr( this.name, this.value );
				}
			} );
			table = table[0].outerHTML;
		}
	} catch ( error ) {
	}
	
	return table;
}


/**
 * Paragraph tags are being removed inside tables. Fix it
 * @see WordPress bug https://core.trac.wordpress.org/ticket/20943
 */
editor.on('init', function(e) {
	fixTableParagraphs( editor );
});


/**
 * Hide the drag handles of tables when our columns are selected
 */
editor.on('mousedown', function(e) {
	var $ = jQuery;
	if ( $(e.target).parents('.pbsandwich_column:eq(0)').length > 0 ) {
		$(editor.getBody()).addClass('pbsandwich_column_selected');
	} else {
		$(editor.getBody()).removeClass('pbsandwich_column_selected');
	}

	_pbsandwich_removeColumnToolbar( editor );
});


/**
 * Show & bind or hide the column toolbar
 */
editor.on('mouseup', function(e) {
	var $ = jQuery;
	if ( $(e.target).parents('#wp-column-toolbar').length > 0 ) {
		
		var action = $(e.target).attr('data-column-action');
		
		editor.fire( 'toolbar-column-' + action, {
			'action': action,
			'editor': editor,
			'target': e.target
		} );

		// _pbsandwich_removeColumnToolbar( editor );
		
		return;
	}

	// If an image is clicked, then don't show the column toolbar
	if ( $(e.target).is('img.alignleft, img.alignright, img.aligncenter, img.alignnone') ) {
		return;
	}
	
	_pbsandwich_addColumnToolbar( editor, e.target );
});


/**
 * Hide the toolbar. We do it this way since when a view is clicked,
 * the normal mousedown/click handlers are not fired
 */
editor.on('init', function(e) {
	var $ = jQuery;
	$( editor.getBody() ).on('mousedown', function(e) {
		
		// Don't remove the toolbar when the toolbar is clicked
		if ( $(e.target).parents('#wp-column-toolbar').length > 0 ) {
			e.stopPropagation();
			return;
		}
		
		_pbsandwich_removeColumnToolbar( editor );
	});
});


/**
 * Our column button itself, this method places the column/row into the editor. If something is selected,
 * it is placed in the first column of the new row
 */
editor._pbsCreateNewColumn = function( columnConfig ) {
	preUpdateSortable( editor );
	editor.insertContent( _pbsandwich_columns_formTable( columnConfig, editor.selection.getContent() ) );
	updateSortable( editor );
	fixTableParagraphs( editor );
}
editor.addButton( 'pbsandwich_column', {
    title: pbsandwich_column.modal_title,
    icon: 'wp_tagcloud',
	type: 'menubutton',
	menu: [
		{
            text: pbsandwich_column.column_1,
            value: '1/1',
            onclick: function() { editor._pbsCreateNewColumn( this.value() ); }
		}, {
            text: pbsandwich_column.column_2,
            value: '1/2+1/2',
            onclick: function() { editor._pbsCreateNewColumn( this.value() ); }
		}, {
            text: pbsandwich_column.column_3,
            value: '1/3+1/3+1/3',
            onclick: function() { editor._pbsCreateNewColumn( this.value() ); }
		}, {
            text: pbsandwich_column.column_4,
            value: '1/4+1/4+1/4+1/4',
            onclick: function() { editor._pbsCreateNewColumn( this.value() ); }
		}, {
            text: pbsandwich_column.column_1323,
            value: '1/3+2/3',
            onclick: function() { editor._pbsCreateNewColumn( this.value() ); }
		}, {
            text: pbsandwich_column.column_2313,
            value: '2/3+1/3',
            onclick: function() { editor._pbsCreateNewColumn( this.value() ); }
		}, {
            text: pbsandwich_column.column_141214,
            value: '1/4+1/2+1/4',
            onclick: function() { editor._pbsCreateNewColumn( this.value() ); }
		}, {
            text: pbsandwich_column.custom_columns,
			onclick: function() {
			    editor.windowManager.open( {
			        title: pbsandwich_column.custom_columns,
					id: 'pbs-modal',
			        body: [{
			            type: 'textbox',
			            name: 'columns',
			            label: pbsandwich_column.modal_title,
						value: '1/2+1/2'
			        },
					{
						type: 'container',
						html: wp.template( 'pbs-column-custom-modal-description' )( pbsandwich_column )
					}],
			        onsubmit: function( e ) { editor._pbsCreateNewColumn( e.data.columns ); }
			    });
			}
		}
	]
});


/**
 * Embeds display in the wrong aspect ratio inside columns.
 * This triggers whenever there's an embed, takes the iframe dimensions
 * and scales it down to make it look good inside columns
 */
editor.on('wp-body-class-change change', function(e) {
	var $ = jQuery;
	
	$(editor.getBody()).find('.pbsandwich_column .wpview-content.wpview-type-embed iframe:not(.resized)').each(function() {
		var ratio = parseInt( $(this).attr('height') ) / parseInt( $(this).attr('width') );
		
		var w = $(this).attr('width', '100%').width();
		
		$(this).attr('height', w * ratio).addClass('resized');
	});
});


/**
 * If the column is clicked, move the cursor location to before/after
 * the column so content can be added
 */
editor.on('mousedown', function(e) {
	var $ = jQuery;
	
	if ( ! $(e.target).is('.pbsandwich_column td') ) {
		return;
	}
	
	editor.focus();
	
	// Get whether the location is near the left or right of the row
	// We need to add a placeholder when doing this since it doesn't work right
	// @see http://blog.squadedit.com/tinymce-and-cursor-position/
	var table = $(e.target).parents('.pbsandwich_column:eq(0)');
	if ( (e.pageX - table.offset().left ) / table.width() > 0.5 ) {
		$(e.target).parents('.pbsandwich_column:eq(0)').after('<span data-mce-bogus="1" id="dummy_column_selector"></span>');
	} else {
		$(e.target).parents('.pbsandwich_column:eq(0)').before('<span data-mce-bogus="1" id="dummy_column_selector"></span>');		
	}
	
	// Move the cursor
	editor.selection.setCursorLocation( $(editor.getBody()).find('#dummy_column_selector')[0] );
	$(editor.getBody()).find('#dummy_column_selector').remove();
	
});
editor.on('keyup', function(e) {
	_pbsandwich_removeColumnToolbar( editor );
});