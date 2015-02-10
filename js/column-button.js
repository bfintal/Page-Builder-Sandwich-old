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
		var contents = [];
		
		var $content = $('<div></div>').html(content);
		if ( $content.find('table.scless_column').length == 0 ) {
			return scless_column.dummy_content;
		}
		
		$content.find('table.scless_column td').each( function( i, e ) {
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
			
			table += '<td style="width: ' + width + '%; ' + marginStyle + '"><p>' + columnContent + '</p></td>';
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
	 * Destroys all the sortables
	 */
	function preUpdateSortable( editor ) {
		var $ = jQuery;
		$(editor.getBody()).sortable('destroy');
		$(editor.getBody()).find('.scless_column td').sortable('destroy');
		
	}
	
	
	/**
	 * Create sortables
	 */
	function updateSortable( editor ) {
		var $ = jQuery;
		jQuery(editor.getBody()).sortable({
			scroll: false, 
			connectWith: jQuery(editor.getBody()).find('.scless_column td'), 
			placeholder: "sortable-placeholder",
			cancel: 'p,code,blockquote,span,pre,td:not(.scless_column td),th,h1,h2,h3,h4,h5,h6,dt,dd,li,a,address,img',
			stop:function() {
				jQuery(editor.getBody()).sortable('refresh');
				jQuery(editor.getBody()).find('.scless_column td').sortable('refresh');
			}
		});
		$(editor.getBody()).find('.scless_column td').sortable({ 
			scroll: false, 
			connectWith: jQuery(editor.getBody()).find('.scless_column td').add(jQuery(editor.getBody())), 
			placeholder: "sortable-placeholder",
			cancel: 'p,code,blockquote,span,pre,td:not(.scless_column td),th,h1,h2,h3,h4,h5,h6,dt,dd,li,a,address,img',
			stop:function() {
				$(editor.getBody()).sortable('refresh');
				$(editor.getBody()).find('.scless_column td').sortable('refresh');
			}
		});
	}
	
	
	/**
	 * When unselected, single clicking Shortcake / TinyMCE views will somehow start a sortable drag event.
	 * This makes the view hard to release. This function fixes it. 
	 * When the view is clicked (mousedown then mouseup only), it doesn't trigger a sortable drag event
	 * When the view is click dragged for the first time, normal sortable drag event is handled normally.
	 */
	function fixShortcakeDragging( editor ) {
		var $ = jQuery;
		$(editor.getBody()).on('mousemove', '.wpview-wrap', function(e) {
			if ( $(this).is('[data-check-move="1"]') ) {
				$(this).removeAttr('data-check-move');
			}
		});
		$(editor.getBody()).on('mouseup', '.wpview-wrap', function(e) {
			if ( $(this).is('[data-check-move="1"]') ) {
				$(this).removeAttr('data-check-move');
				$(this).trigger('mousemove').trigger('mouseup');
			}
		});
		$(editor.getBody()).on('mousedown', '.wpview-wrap', function(e) {
			if ( ! $(this).is('[data-mce-selected="1"]') ) {
				$(this).attr('data-check-move', '1');
			}
		});
	}
	
	
	/**
	 * Add the button
	 */
    tinymce.PluginManager.add( 'scless_column', function( editor, url ) {
		
		/**
		 * Sortable / drag and drop initializer
		 */
		var sortableInit = false;
		editor.on('mousemove', function(e) {
			if ( ! sortableInit ) {
				updateSortable( editor );
				fixShortcakeDragging( editor );
				sortableInit = true;
			}
		} );
		

        editor.addButton( 'scless_column', {
            title: scless_column.modal_title,
            icon: 'wp_tagcloud',
			type: 'menubutton',
			menu: [
				{
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '2' ),
	                value: '1/2+1/2',
	                onclick: function() {
						preUpdateSortable( editor );
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
						updateSortable( editor );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '3' ),
	                value: '1/3+1/3+1/3',
	                onclick: function() {
						preUpdateSortable( editor );
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
						updateSortable( editor );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '4' ),
	                value: '1/4+1/4+1/4+1/4',
	                onclick: function() {
						preUpdateSortable( editor );
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
						updateSortable( editor );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '1/3 + 2/3' ),
	                value: '1/3+2/3',
	                onclick: function() {
						preUpdateSortable( editor );
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
						updateSortable( editor );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '2/3 + 1/3' ),
	                value: '2/3+1/3',
	                onclick: function() {
						preUpdateSortable( editor );
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
						updateSortable( editor );
	                }
				}, {
	                text: _shortcodeless_columns_sprintf( scless_column.columns, '1/4 + 1/2 + 1/4' ),
	                value: '1/4+2/4+1/4',
	                onclick: function() {
						preUpdateSortable( editor );
	                    editor.insertContent( _shortcodeless_columns_formTable( this.value(), editor.selection.getContent() ) );
						updateSortable( editor );
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
								preUpdateSortable( editor );
			                    editor.insertContent( _shortcodeless_columns_formTable( e.data.columns, editor.selection.getContent() ) );
								updateSortable( editor );
					        }
					    });
					}
				}
			]
        });
    });
})();