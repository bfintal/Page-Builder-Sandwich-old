// @codekit-append "_editor-add-post-element.js";
// @codekit-append "_editor-start.js";
// @codekit-append "_editor-core.js";
// @codekit-append "_editor-columns.js";
// @codekit-append "_editor-column-actions.js";
// @codekit-append "_editor-jetpack.js";
// @codekit-append "_editor-end.js";

/**
 * Click handler for the "Add Post Element" button. Basically we open the WP Media Manager then activate the shortcake state
 */
jQuery(document).ready(function($) {
	$('body').on('click', '.sandwich-add-shortcode', function() {
		$(this).siblings('[id="insert-media-button"]').click();
		$('.media-menu .media-menu-item:contains("' + shortcodeUIData.strings.media_frame_menu_insert_label + '")').click();
		return false;
	});
});


(function() {
	
	/**
	 * Add the button
	 */
    tinymce.PluginManager.add( 'pbsandwich', function( editor, url ) {
		
		
		
		

var sortableInit = false;


/**
 * Destroys all the sortables
 */
function preUpdateSortable( editor ) {
	var $ = jQuery;
	try {
		$(editor.getBody()).sortable('destroy');
		$(editor.getBody()).find('.pbsandwich_column td').sortable('destroy');
	} catch (e) { }
}


/**
 * Cancel sortable
 */
function cancelSortable( editor ) {
	var $ = jQuery;
	try {
		$(editor.getBody()).sortable('cancel');
		$(editor.getBody()).find('.pbsandwich_column td').sortable('cancel');
	} catch (e) { }
}


/**
 * Sortable start handler
 */
function sortStartHandler( editor ) {
	var editorBody = jQuery( editor.getBody() );
	editorBody.addClass('pbsandwich_just_dragged');
	_pbsandwich_removeColumnToolbar( editor );
	
	// Views with iframes (e.g. audio & video embeds) get very slow when dragging, hide them
	if ( editorBody.find('.wpview-wrap[data-mce-selected="1"] iframe').length > 0 ) {
		editorBody.find('.wpview-wrap[data-mce-selected="1"] iframe').css('visibility', 'hidden');
	}
}


/**
 * Sortable end handler
 */
function sortEndHandler( editor ) {
	var editorBody = jQuery( editor.getBody() );
	try {
		editorBody.sortable('refresh');
		editorBody.find('.pbsandwich_column td').sortable('refresh');
	} catch (e) { }
	
	editorBody.removeClass('pbsandwich_just_dragged');
	
	// Views with iframes do not refresh after sorting, mceCleanup fixes this (also brings back the visibility)
	if ( editorBody.find('.wpview-wrap[data-mce-selected="1"] iframe').length > 0 ) {
		editorBody.find('.wpview-wrap[data-mce-selected="1"]').removeAttr('data-mce-selected');
		editor.execCommand( 'mceCleanup' );
	}
}


/**
 * Create sortables
 */
function updateSortable( editor ) {
	var $ = jQuery;
	// fixTableParagraphs( editor );
	jQuery(editor.getBody()).sortable({
		scroll: false, 
		connectWith: jQuery(editor.getBody()).find('.pbsandwich_column td'), 
		placeholder: "sortable-placeholder",
		cancel: pbsandwich_column.non_sortable_elements,
		opacity: 0.7,
		forceHelperSize: true, // This is to help dragging
		tolerance: 'pointer',
		stop: function() {
			sortEndHandler( editor );
		},
		update: function() {
			fixTableParagraphs( editor );
		},
		start: function() {
			sortStartHandler( editor );
		}
	});
	$(editor.getBody()).find('.pbsandwich_column td').sortable({ 
		scroll: false, 
		connectWith: jQuery(editor.getBody()).find('.pbsandwich_column td').add(jQuery(editor.getBody())), 
		placeholder: "sortable-placeholder",
		cancel: pbsandwich_column.non_sortable_elements,
		opacity: 0.7,
		forceHelperSize: true, // This is to help dragging
		tolerance: 'pointer',
		stop: function() {
			sortEndHandler( editor );
		},
		update: function() {
			fixTableParagraphs( editor );
		},
		start: function() {
			sortStartHandler( editor );
		}
	});
}


/**
 * On save/preview, remove sortable. But still allow re-initialization
 */
jQuery('body').on('click', '[name="save"], #post-preview', function() { 
	var $ = jQuery;
	try {
		preUpdateSortable( tinyMCE.activeEditor );
		$( tinyMCE.activeEditor.getBody() ).find('[class=""]').removeAttr('class');
		sortableInit = false;
	} catch (e) { }
});


/**
 * Switching between different editors screw up our stuff.
 * 1. paragraphs are removed
 * 2. sortables become unsortable
 * @see WordPress bug https://core.trac.wordpress.org/ticket/20943
 */
jQuery('body').on('click', '.wp-switch-editor', function() {
	
	// 1. paragraphs are removed
	fixTableParagraphs( tinyMCE.activeEditor );
	
	// 2. sortables become unsortable
	preUpdateSortable( tinyMCE.activeEditor );
	updateSortable( tinyMCE.activeEditor );
});


/**
 * When unselected, single clicking Shortcake / TinyMCE views will somehow start a sortable drag event.
 * This makes the view hard to release. This function fixes it. 
 * When the view is clicked (mousedown then mouseup only), it doesn't trigger a sortable drag event
 * When the view is click dragged for the first time, normal sortable drag event is handled normally.
 */
function fixShortcakeDragging( editor ) {
	var $ = jQuery;

	// For views with iframes (e.g. video & audio), clicking on the iframe to play a preview
	// won't work with sortable sorting. Our semi work-around is to just deselect the iframe
	// so that it can still be dragged around after moving the mouse around/outside the iframe.
	$(editor.getBody())
	.on('mousemove', '.wpview-wrap[data-mce-selected="1"] .toolbar', function(e) {
		var $ = jQuery;

		var parent = $(this).parents('.wpview-wrap:eq(0)');
		if ( ! parent.is('[data-check-move="1"]') ) {
			return;
		}
		if ( parent.find('iframe').length === 0 ) {
			return;
		}
		
		if ( e.which !== 1 ) {
			try {
				$(editor.getBody()).sortable('disable');
				$(editor.getBody()).find('.pbsandwich_column td').sortable('disable');
			} catch (e) { }

			parent.trigger('mouseup');

			try {
				$(editor.getBody()).sortable('enable');
				$(editor.getBody()).find('.pbsandwich_column td').sortable('enable');
			} catch (e) { }
			
		}
	})
	.on('mousemove', function(e) {
		var $ = jQuery;
		
		var iframe = $(this).find('.wpview-wrap[data-mce-selected="1"] iframe');
		if ( iframe.length === 0 ) {
			return;
		}
		
		if ( e.which !== 1 ) {
		
			try {
				$(editor.getBody()).sortable('disable');
				$(editor.getBody()).find('.pbsandwich_column td').sortable('disable');
			} catch (e) { }

			iframe.parents('.wpview-wrap:eq(0)').trigger('mouseup');

			try {
				$(editor.getBody()).sortable('enable');
				$(editor.getBody()).find('.pbsandwich_column td').sortable('enable');
			} catch (e) { }
			
		}
	})
	
	
	/**
	 * All these below are weird hacky stuff that was written via trial and error
	 * These do/fix:
	 *	- clicking on a view should do nothing (sometimes, clicking starts a drag)
	 * 	- click + drag on a view should drag it
	 * 	- mouseup after a drag should release the drag (mouse up after a drag somehow doesn't work)
	 */
	.on('click', '.wpview-wrap', function(e) {
		if ( $(this).is('[data-check-move="1"]') ) {
			$(this).trigger('mouseup');
		}
	})
	.on('mousemove', '.wpview-wrap', function(e) {
		if ( $(this).is('[data-check-move="1"]') ) {
			$(this).removeAttr('data-check-move');
		}
	})
	.on('mouseup', '.wpview-wrap', function( e, stopRecurse ) {
		if ( stopRecurse ) {
			return false;
		}
		var $this = $(this);
		setTimeout(function() {
			$this.trigger('mousemove').trigger('mouseup', [ e, true ]);
		}, 100);
	})
	.on('mousedown', '.wpview-wrap', function(e) {
		$(this).attr('data-check-move', '1');
	});
	
}


/**
 * Paragraph tags are being removed inside tables. Fix it
 * @see WordPress bug https://core.trac.wordpress.org/ticket/20943
 */
function fixTableParagraphs( editor ) {
	var $ = jQuery;
	
	$(editor.getBody()).find('.pbsandwich_column td').each(function() {
		
		// @see http://stackoverflow.com/questions/20183324/javascript-wrapping-unwrapped-plain-text
		var elemCount = $(this).contents().length;
		$(this).contents().filter( function() {
		    return this.nodeType === 3;
		} ).each( function() {
		    this.nodeValue = $.trim( this.nodeValue );
			if ( elemCount === 0 || ( this.nodeValue === '' && elemCount === 1 ) ) {
				this.nodeValue = '\u00a0';
			}
		} ).wrap( '<p></p>' );

		// Remove blank paragraphs in columns which have other contents
		if ( $(this).children().length > 1 ) {
			$(this).find('> p').each(function() {
				if ( $(this).html().trim() === '' ) {
					$(this).remove();
				}
			});
		}
		
		// Columns that get emptied should still have a paragraph
		if ( $(this).children().length == 0 ) {
			$(this).append('<p></p>');
		}
		
		// Columns with just a blank paragraph will not be edited unless they have a space
		if ( $(this).children().length == 1 ) {
			var firstChild = $(this).children(':eq(0)');
			if ( firstChild.is('p') && firstChild.text() === '' ) {
				firstChild.text( '\u00a0' );
			}
		}
		
	});
}


/**
 * Sortable / drag and drop initializer
 */
editor.on('mousemove', function(e) {
	if ( ! sortableInit ) {
		updateSortable( editor );
		fixShortcakeDragging( editor );
		sortableInit = true;
	}
} );


/**
 * Adds a clone button in shortcake/view toolbars.
 * Add a click handler & clone method for the new clone button.
 */
editor.on('init', function(e) {
	var $ = jQuery;
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
		
		if ( wrapper.find('.clone').length === 0 ) {
			$('<div class="dashicons dashicons-images-alt clone" title="Clone"></div>').insertBefore( wrapper.find('.toolbar > .dashicons:eq(-1)') );
		}
		
		if ( $(e.target).is('.dashicons.clone') ) {
			// cancelSortable( editor );
			preUpdateSortable( editor );
			var newElement = wrapper.clone();
			newElement.insertAfter( wrapper ).trigger('click');
			updateSortable( editor );

			// Cleanup to make views with iframes display again
			if ( newElement.find('iframe').length > 0 ) {
				editor.execCommand( 'mceCleanup' );
			}
		}
		
		
		/**
		 * Fixes the bug in Firefox when a view with an iframe is clicked, it
		 * always gets dragged
		 */
		if ( ! $(e.target).is('.toolbar .dashicons') && ! $(e.target).parents('.toolbar').length > 0 ) {
			if ( wrapper.find('iframe').length > 0 ) {
				e.stopPropagation();
				if ( $(this).is('[data-check-move="1"]') ) {
					$(this).trigger('mouseup');
				}
			}
		}
		
	});
});


/**
 * When creating a column, then creating a shortcake/view inside it, we
 * get errors: the view cannot be dragged because the sortables aren't updated.
 * There's currently no event when shortcake adds an element, we can check for it
 * using this event instead, then refresh our sortables to make the view work
 * right away.
 */
var numShortcakes = -1;
editor.on('wp-body-class-change change', function(e) {
	var $ = jQuery;
	// At the start, remember the number of shortcakes/views
	if ( numShortcakes === -1 ) {
		numShortcakes = $(editor.getBody()).find('.wpview-wrap').length;
		return;
	}
	// When the number changes, update our sortables
	if ( numShortcakes !== $(editor.getBody()).find('.wpview-wrap').length ) {
		numShortcakes = $(editor.getBody()).find('.wpview-wrap').length;
		preUpdateSortable( editor );
		updateSortable( editor );
	}
});
editor.on('init', function(e) {
	var $ = jQuery;

	/**
	 * DOMNodeRemoved event can catch view removals
	 */
	$( editor.getBody() ).on('DOMNodeRemoved', function(e) {
		if ( numShortcakes === -1 ) {
			return;
		}
		if ( numShortcakes !== $(editor.getBody()).find('.wpview-wrap').length ) {
			numShortcakes = $(editor.getBody()).find('.wpview-wrap').length;
			preUpdateSortable( editor );
			updateSortable( editor );
		}
	});
});


/**
 * Embeds cannot be dragged since they do not have an overlay div. This adds that to all embeds
 */
editor.on('wp-body-class-change change', function(e) {
	var $ = jQuery;
	
	$(editor.getBody()).find('.wpview-body .wpview-content:has(iframe):not(:has( ~ .wpview-overlay))').after( '<div class="wpview-overlay"></div>' );

});

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
	toolbarHtml = wp.template( 'pbs-column-toolbar' );

	var editorWidth = $(editor.getDoc()).width();
		
	toolbar = dom.create( 'div', {
		'id': 'wp-column-toolbar',
		'data-mce-bogus': '1',
		'contenteditable': false
	}, toolbarHtml( pbsandwich_column ) );

	editor.getBody().appendChild( toolbar );
	rectangle = dom.getRect( node );
	
	var left = rectangle.x + rectangle.w / 2;
		
	// Adjust the location if the toolbar goes past the right side
	if ( left + $(toolbar).width() - $(toolbar).width() / 2 > editorWidth ) {
		left -= ( left + $(toolbar).width() - $(toolbar).width() / 2 ) - editorWidth + 6;
		
	// Adjust the location if the toolbar goes past the left side
	} else if ( left - $(toolbar).width() / 2 < 0 ) {
		left += - ( left - $(toolbar).width() / 2 ) + 6;
	}
	
	// Position the column toolbar
	dom.setStyles( toolbar, {
		top: rectangle.y - 6,
		left: left
	});
	
	// Dispatch toolbar show event
	editor.fire( 'show-toolbar-column', {
		'editor': editor,
		'target': $(editor.getBody()).find( '[data-wp-columnselect]' )[0]
	} );
}


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
			var content = $(e).html();
			if ( ! /^<p\s[^>]+>(\s|&nbsp;)*<\/p>$/.test( content ) ) {
				contents[ contents.length - 1] += '<p>' + content + '</p>';
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
				columnContent = '&nbsp;';
			} else {
				columnContent = columnContents[ i ];
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

		// Create the new column
		newTd = $('<td></td>');
		
		// Retain current column styles
		if ( $(content).is('table') ) {
			var oldColumn = $(content).find('> tbody > tr > td:eq(' + i + ')');
			if ( oldColumn.length > 0 ) {
				newTd.attr('style', oldColumn.attr('style') );
				newTd.attr('data-mce-style', oldColumn.attr('data-mce-style') );
			}
		}
		
		// Add the new contents and attributes
		newTd.addClass('col-sm-' + col)
		.html('<p>' + columnContent + '</p>')
		.css('width', width + '%');
		
		table += newTd[0].outerHTML;
	} );
	
	table += '</tr></tbody></table>';
	
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
	if ( $(e.target).is( '[data-column-action]' ) ) {
		
		var action = $(e.target).attr('data-column-action');
		
		editor.fire( 'toolbar-column-' + action, {
			'action': action,
			'editor': editor,
			'target': e.target
		} );

		// _pbsandwich_removeColumnToolbar( editor );
		
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
		if ( $(e.target).is( '[data-column-action]' ) ) {
			e.stopPropagation();
			return;
		}
	
		_pbsandwich_removeColumnToolbar( editor );
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
            text: pbsandwich_column.column_1,
            value: '1/1',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: pbsandwich_column.column_2,
            value: '1/2+1/2',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: pbsandwich_column.column_3,
            value: '1/3+1/3+1/3',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: pbsandwich_column.column_4,
            value: '1/4+1/4+1/4+1/4',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: pbsandwich_column.column_1323,
            value: '1/3+2/3',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: pbsandwich_column.column_2313,
            value: '2/3+1/3',
            onclick: function() {
				preUpdateSortable( editor );
                editor.insertContent( _pbsandwich_columns_formTable( this.value(), editor.selection.getContent() ) );
				updateSortable( editor );
				fixTableParagraphs( editor );
            }
		}, {
            text: pbsandwich_column.column_141214,
            value: '1/4+1/2+1/4',
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
						html: wp.template( 'pbs-column-custom-modal-description' )( pbsandwich_column )
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
		ids = imageContainer.find('[id="background_image"]').val().split(',');
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
		
			// Get the preview image
			var image = attachment.attributes.sizes.full;
			if ( typeof attachment.attributes.sizes.thumbnail != 'undefined' ) {
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
	var $selectedRow = $(editor.getBody()).find('[data-wp-columnselect="1"]').parents('.pbsandwich_column:eq(0)');

	var bgImageURL = $selectedRow.css('background-image').replace( /url\(([^\)]+)\)/g, '$1' );

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
		background_position: $selectedRow.css('backgroundPosition')
	};
	//
	var colModal = editor.windowManager.open( {
			title: pbsandwich_column.row_settings,
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

			// Make the styles permanent
			$selectedRow.attr('data-mce-style', $selectedRow.attr('style'));
		}
	});

	$('#pbsandwich_column_row_edit').find('#border_color, #background_color').wpColorPicker();
	
	_pbsandwich_removeColumnToolbar( editor );
});



/**
 * Close the modal window when the enter key is pressed
 */
jQuery('body').on('keypress', '.sandwich_modal input, .sandwich_modal select', function(e) {
	if ( e.which === 13 ) {
		var $ = jQuery;
		$(this).parents('.mce-window').find('.mce-primary button').trigger('click');
	}
});

/**
 * Jetpack Contact Form
 * Make Shortcake's edit button open up Jetpack's Contact Form UI instead
 */
editor.on('init', function(e) {
	var $ = jQuery;
	
	$( editor.getBody() ).on('mousedown', '[data-wpview-type="contact-form"] .toolbar .edit', function(e) {
		e.preventDefault();
		$('#insert-jetpack-contact-form').trigger('click');
		return false;
	});
});

/**
 * This just closes the TinyMCE plugin call, appended last in editor.js
 */
    });
})();

