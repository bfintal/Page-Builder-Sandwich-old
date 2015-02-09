(function() {

	
	/**
	 * Checks if the content selected is an existing column table.
	 * If it is, get each column content
	 */
	function _shortcodeless_columns_formContent( content, numColumns ) {
		if ( content === '' ) {
			return scless_column.dummy_content;
		}
		
		var $ = jQuery;
		
		if ( ! $(content).is('table.scless_column') ) {
			return scless_column.dummy_content;
		}
		
		var contents = [];
		
		$(content).find('td').each( function( i, e ) {
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
	function _shortcodeless_columns_formTable( columns, content ) {
		var $ = jQuery;
		
		var cols = columns.split('+');
		var margin = 3.5;
		var table = '<table class="scless_column" style="width: 100%; height: auto; border: none;" border="0"><tbody><tr>';
		var widthOffset = 100 - ( cols.length - 1 ) * margin;
		var columnContents = _shortcodeless_columns_formContent( content, cols.length );
		var columnContent = scless_column.dummy_content;
		
		$.each( cols, function( i, e ) {
			
			// Get what content we will use
			if ( typeof columnContents === 'object' ) {
				if ( i >= columnContents.length ) {
					columnContent = '';
				} else {
					columnContent = columnContents[ i ]
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
			var width = parseInt( fraction[0] ) / parseInt( fraction[1] ) * widthOffset;
			
			var marginStyle = '';
			if ( i !== cols.length - 1 ) {
				marginStyle = 'margin-right: ' + margin + '%;';
			}
			
			table += '<td style="width: ' + width + '%; ' + marginStyle + '">' + columnContent + '</td>';
		} );
		
		table += '</tr></tbody></table>';
		
		return table;
	}
	
	
	/**
	 * Forms the column labels for TinyMCE
	 */
	function _shortcodeless_columns_sprintf( format, etc ) {
	    var arg = arguments;
	    var i = 1;
	    return format.replace(/%((%)|s)/g, function (m) { return m[2] || arg[i++] })
	}
	
	
	/**
	 * Add the button
	 */
    tinymce.PluginManager.add( 'scless_column', function( editor, url ) {
        editor.addButton( 'scless_column', {
            title: scless_column.modal_title,
            icon: 'wp_tagcloud',
			type: 'menubutton',
			menu: [
				{
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '2' ),
	                value: '1/2+1/2',
	                onclick: function() {
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '3' ),
	                value: '1/3+1/3+1/3',
	                onclick: function() {
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '4' ),
	                value: '1/4+1/4+1/4+1/4',
	                onclick: function() {
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '1/3 + 2/3' ),
	                value: '1/3+2/3',
	                onclick: function() {
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '2/3 + 1/3' ),
	                value: '2/3+1/3',
	                onclick: function() {
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '1/4 + 1/2 + 1/4' ),
	                value: '1/4+2/4+1/4',
	                onclick: function() {
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
	                }
				}, {
	                text: scless_column.custom_columns,
					onclick: function() {
					    editor.windowManager.open( {
					        title: scless_column.custom_columns,
					        body: [{
					            type: 'textbox',
					            name: 'columns',
					            label: scless_column.modal_title,
								value: '1/2+1/2'
					        },
							{
								type: 'container',
								html: '<p style="line-height: 1.6em;">' + scless_column.modal_description + '<code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/2+1/2</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/3+1/3+1/3</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/4+2/4+1/4</code></p>'
							}],
					        onsubmit: function( e ) {
			                    editor.insertContent( _shortcodeless_columns_formTable( e.data.columns, editor.selection.getContent() ) );
					        }
					    });
					}
				}
			]
        });
    });
})();