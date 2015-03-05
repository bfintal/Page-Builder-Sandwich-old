/**
 * Forms the column labels for TinyMCE
 */
function _pbsandwich_columns_sprintf( format, etc ) {
    var arg = arguments;
    var i = 1;
    return format.replace(/%((%)|s)/g, function (m) { return m[2] || arg[i++] })
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
	if ( $content.find('table.pbsandwich_column').length == 0 ) {
		return pbsandwich_column.dummy_content;
	}
	
	$content.find('table.pbsandwich_column td').each( function( i, e ) {
		if ( i >= numColumns ) {
			contents[ contents.length - 1] += '<p>' + $(e).html() + '</p>';
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
	
	$.each( cols, function( i, e ) {
		
		// Get what content we will use
		if ( typeof columnContents === 'object' ) {
			if ( i >= columnContents.length ) {
				columnContent = '&nbsp;';
			} else {
				columnContent = columnContents[ i ]
			}
		} else if ( typeof columnContents === 'string' ) {
			if ( content.trim() !== '' ) {
				if ( i === 0 ) {
					columnContent = content;
				} else {
					columnContent = '&nbsp;';
				}
			}
		}
		
		// Compute our margins
		var fraction = e.split('/');
		var width = parseInt( fraction[0] ) / parseInt( fraction[1] ) * 100;
		var col = parseInt( parseInt( fraction[0] ) / parseInt( fraction[1] ) * 12 );
		
		// the style: width is only used in the backend since the table WON'T allow us to FIX it's width
		table += '<td class="col-sm-' + col + '" style="width: ' + width + '%;"><p>' + columnContent + '</p></td>';
	} );
	
	table += '</tr></tbody></table>';
	
	return table;
}


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
	tinyMCE.activeEditor.selection.select( $(tinyMCE.activeEditor.getBody()).find('[data-wp-columnselect="1"]')[0] );
	
	// Change the column
	preUpdateSortable( tinyMCE.activeEditor );
    tinyMCE.activeEditor.insertContent( _pbsandwich_columns_formTable( columns, tinyMCE.activeEditor.selection.getContent() ) );
	updateSortable( tinyMCE.activeEditor );
	
	// Close our modal window
	tinyMCE.activeEditor.windowManager.getWindows()[0].close();
});


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
	_pbsandwich_removeToolbar( editor );
});


/**
 * Show the toolbar
 */
editor.on('mouseup', function(e) {
	var $ = jQuery;
	if ( $(e.target).is( '[data-column-action]' ) ) {
		
		var action = $(e.target).attr('data-column-action');
		
		if ( action === 'columns' ) {

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
			
		} else {
			_pbsandwich_do_action( editor, e.target, action );
		}
		
		return;
	}
	_pbsandwich_addToolbar( editor, e.target );
});


/**
 * Hide the toolbar. We do it this way since when a view is clicked,
 * the normal mousedown/click handlers are not fired
 */
editor.on('init', function(e) {
	var $ = jQuery;
	$( editor.getBody() ).on('mousedown', function(e) {
		
		// Don't remove the toolbar when the toolbar is clicked
		if ( $(e.target).is( '[data-column-action]' ) ) {
			e.stopPropagation();
			return;
		}
	
		_pbsandwich_removeToolbar( editor );
	});
});


/**
 * Our column button itself
 */
editor.addButton( 'pbsandwich_column', {
    title: pbsandwich_column.modal_title,
    icon: 'wp_tagcloud',
	type: 'menubutton',
	menu: [
		{
            text: _pbsandwich_columns_sprintf( pbsandwich_column.columns, '2' ),
            value: '1/2+1/2',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: _pbsandwich_columns_sprintf( pbsandwich_column.columns, '3' ),
            value: '1/3+1/3+1/3',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: _pbsandwich_columns_sprintf( pbsandwich_column.columns, '4' ),
            value: '1/4+1/4+1/4+1/4',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: _pbsandwich_columns_sprintf( pbsandwich_column.columns, '1/3 + 2/3' ),
            value: '1/3+2/3',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: _pbsandwich_columns_sprintf( pbsandwich_column.columns, '2/3 + 1/3' ),
            value: '2/3+1/3',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: _pbsandwich_columns_sprintf( pbsandwich_column.columns, '1/4 + 1/2 + 1/4' ),
            value: '1/4+2/4+1/4',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: pbsandwich_column.custom_columns,
			onclick: function() {
			    editor.windowManager.open( {
			        title: pbsandwich_column.custom_columns,
			        body: [{
			            type: 'textbox',
			            name: 'columns',
			            label: pbsandwich_column.modal_title,
						value: '1/2+1/2'
			        },
					{
						type: 'container',
						html: '<p style="line-height: 1.6em;">' + pbsandwich_column.modal_description + '<code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/2+1/2</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/3+1/3+1/3</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/4+2/4+1/4</code></p>'
					}],
			        onsubmit: function( e ) {
						preUpdateSortable( editor );
	                    editor.insertContent( _pbsandwich_columns_formTable( e.data.columns, editor.selection.getContent() ) );
						updateSortable( editor );
						fixTableParagraphs( editor );
			        }
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