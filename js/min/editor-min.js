// @codekit-append "_editor-add-post-element.js";
// @codekit-append "_editor-start.js";
// @codekit-append "_editor-core.js";
// @codekit-append "_editor-toolbars.js";
// @codekit-append "_editor-toolbar-actions.js";
// @codekit-append "_editor-columns.js";
// @codekit-append "_editor-column-actions.js";
// @codekit-append "_editor-modal.js";
// @codekit-append "_editor-jetpack.js";

// Backward Compatibility: WP 4.1
// @codekit-append "backward-compatibility/4.1/_editor-toolbars.js"

// @codekit-append "_editor-end.js";
// @codekit-append "_util.js";

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
    tinyMCE.PluginManager.add( 'pbsandwich', function( editor, url ) {
		var $ = jQuery;

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
	var $ = jQuery;
	
	var editorBody = $( editor.getBody() );
	
	// Issue #141: Embeds cannot be dragged while it's selected, to make the experience better
	// unselect it so it can be dragged agian right away	
	setTimeout( function() {
		$( editor.getBody() ).find('.wpview-wrap[data-wpview-type="embed"][data-mce-selected="1"]').removeAttr('data-mce-selected');
	}, 1 );

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
 * jQuery Sortable doesn't scroll well while inside TinyMCE,
 * this is our custom scrolling function that makes the scrolling correct
 */
function enhancedSortableScroll( event, ui ) {
	var $ = jQuery;
	
	var editorTop = $('#wp-content-editor-tools').height() + parseInt($('#wp-content-editor-tools').css('paddingTop')) + parseInt($('.mce-edit-area').css('paddingTop')) + $('#wpadminbar').height();
	var editorBottom = $(window).height() - $('.mce-statusbar').height() - $('#post-status-info').height();
	
	// For faster performance, use this instead of $('.mce-edit-area').offset().top
	// var element = $('.mce-edit-area')[0]
	// var editAreaOffsetTop = element.offsetTop;
	// while (element.parentNode) {
	//     element = element.parentNode;
	//
	// 	// We found these elements to contribute to the correct value of $('.mce-edit-area').offset().top
	// 	// via experimentation in the admin
	// 	if ( $(element).is('#wp-content-wrap, #post-body-content, #poststuff, body') ) {
	// 		if ( ! isNaN( element.offsetTop ) ) {
	// 		    editAreaOffsetTop += element.offsetTop;
	// 		}
	// 	}
	// }

	var mouseTop = $('.mce-edit-area').offset().top + parseInt($('.mce-edit-area').css('paddingTop')) - $(window).scrollTop() + event.pageY;

	// Scroll up
	if ( mouseTop - editorTop < 60 ) {
		$(window).scrollTop( $(window).scrollTop() - 10 );
	}
	// Scroll down
	if ( editorBottom - mouseTop < 60 ) {
		$(window).scrollTop( $(window).scrollTop() + 10 );
	}
	
}


/**
 * jQuery Sortable doesn't perform well while inside TinyMCE,
 * this is our custom sorting function that makes the dragging
 * experience a million times better.
 */
function enhancedSortableSort( event, ui ) {
	var $ = jQuery;

	// Also perform an enhanced scroll
	enhancedSortableScroll( event, ui );
	
	var that = ui.item,
		closestDist = 9999999,
		closestElement = null,
		insertBefore = true,
		dist, dist2,
		// The current mouse position
		pointerTop = event.pageY,
		pointerLeft = event.pageX;
	
	// Find out the closest element from the one being dragged
	ui.item.parents('body:eq(0)').find('.ui-sortable-handle:not(.ui-sortable-helper)').each(function() {

		// Don't include the current one being dragged
		if ( $(this).parents('.ui-sortable-helper').length > 0 ) {
			return;
		}

		var element = $(this)[0],
			origElement = element,
			childTop,
			childLeft,
			childHeight = element.offsetHeight,
			childWidth = element.offsetWidth;
		
		// Instead of using $(this).offset().top & $(this).offset().left, this is x10 FASTER!
		childTop = element.offsetTop;
		childLeft = element.offsetLeft;
		
		while (element.parentNode) {
			
		    element = element.parentNode;
			
			if ( ! isNaN( element.offsetTop ) ) {
			    childTop += element.offsetTop;
			}
			
			// For views, childLeft is computed correctly,
			// Paragraph tags is incorrect though
			if ( origElement.tagName === 'P' ) {
				if ( ! isNaN( element.offsetLeft ) ) {
				    childLeft += element.offsetLeft;
				}
			}
			
		}
		
		var childBottom = childTop + childHeight,
			childRight = childLeft + childWidth;

		if ( childLeft <= pointerLeft && pointerLeft <= childRight ) {

			dist = Math.abs( childTop - pointerTop );
			dist2 = Math.abs( childBottom - pointerTop );
			
			if ( dist > dist2 ) {
				dist = dist2;
			}

			if ( closestDist > dist ) {
				closestDist = dist;
				closestElement = $(this);
				
				// If the mouse is > halfway through the element, insert it after
				insertBefore = true;
				if ( pointerTop > childTop + childHeight / 2 ) {
					insertBefore = false;
				}
			}
		}

	});
	
	// Move the placeholder to the correct location
	if ( closestElement !== null ) {
		var placeholder = that.parents('body:eq(0)').find('.sortable-placeholder');
		if ( insertBefore ) {
			placeholder.insertBefore(closestElement);
		} else {
			placeholder.insertAfter(closestElement);
		}
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
		opacity: 0.15,
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
		},
		// Makes sortable better at collision detection.
		sort: enhancedSortableSort
	});
	$(editor.getBody()).find('.pbsandwich_column td').sortable({ 
		scroll: false, 
		connectWith: jQuery(editor.getBody()).find('.pbsandwich_column td').add(jQuery(editor.getBody())), 
		placeholder: "sortable-placeholder",
		cancel: pbsandwich_column.non_sortable_elements,
		opacity: 0.1,
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
		},
		// Makes sortable better at collision detection.
		sort: enhancedSortableSort
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
 * Paragraph tags are being removed inside tables. Fix it
 * @see WordPress bug https://core.trac.wordpress.org/ticket/20943
 */
function fixTableParagraphs( editor ) {
	var $ = jQuery;
	
	
	// Issue #164 Sometimes, columns get wrapped inside paragraph tags. When this happens, 
	// the table becomes undraggable. Unwrap from paragraph tags.
	$(editor.getBody()).find('p .pbsandwich_column').unwrap();
	
	
	$(editor.getBody()).find('.pbsandwich_column td').each(function() {
		
		// @see http://stackoverflow.com/questions/20183324/javascript-wrapping-unwrapped-plain-text
		var elemCount = $(this).contents().length;
		var content = $(this).contents().filter( function() {
		    return this.nodeType === 3;
		} ).each( function() {
		    this.nodeValue = $.trim( this.nodeValue );
			if ( elemCount === 0 || ( this.nodeValue === '' && elemCount === 1 ) ) {
				this.nodeValue = '\u00a0';
			}
		} );
		if ( ! content.is('p') ) {
			content.wrap('<p></p>');
		}

		// Remove blank paragraphs in columns which have other contents
		if ( $(this).children().length > 1 ) {
			$(this).find('> p').each(function() {
				if ( $(this).html().trim() === '' ) {
					$(this).remove();
				}
			});
		}
		
		// Columns that get emptied should still have a paragraph
		if ( $(this).children().length === 0 ) {
			$(this).append('<p></p>');
		}
		
		// Columns with just a blank paragraph will not be edited unless they have a space
		if ( $(this).children().length === 1 ) {
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
		sortableInit = true;
	}
} );


/**
 * Bug fixers
 */
editor.on('init', function(e) {
	var $ = jQuery;
	
	var waitingForDrag = false;
	
	var waitingForEmbedDrag = false;
	var startEmbedDrag = false;
	
	$( editor.getBody() ).on('mousedown', function(e) {
		
		// Continue as normal when the toolbar is clicked
		if ( $(e.target).is('.toolbar .dashicons') || $(e.target).parents('.toolbar').length > 0 ) {
			return;
		}
		
		// Get the shortcode being dragged
		var wrapper = null;
		if ( $(e.target).is('.wpview-wrap') ) {
			wrapper = $(e.target);
		} else if ( $(e.target).parents('.wpview-wrap:eq(0)').length > 0 ) {
			wrapper = $(e.target).parents('.wpview-wrap:eq(0)');
		}
		if ( wrapper === null ) {
			return;
		}
		
		// This fixes embed dragging. Clicking embeds (mousedown then mouseup, no mousemove) triggers
		// a sortable drag. This fixes the problem.
		if ( wrapper.is('[data-wpview-type="embed"]') ) {
			e.stopPropagation();
		}
		
		waitingForDrag = true;
		return;
		
		/**
		 * Fixes the bug in Firefox when a view with an iframe is clicked, it
		 * always gets dragged
		 */
		if ( ! $(e.target).is('.toolbar .dashicons') && $(e.target).parents('.toolbar').length > 0 ) {
			if ( wrapper.find('iframe').length > 0 ) {
				e.stopPropagation();
				if ( $(this).is('[data-check-move="1"]') ) {
					$(this).trigger('mouseup');
				}
			}
		}
		
	});
	
	$( editor.getBody() ).on('mousemove', function(e) {
		// We dragged, don't stop 
		waitingForDrag = false;
	});
	

	/**
	 * Fixes a bug where clicking views/elements in some areas for the first time initiates a drag
	 */
	$( editor.getBody() ).on('mouseup', function(e) {
		
		// Continue as normal when the toolbar is clicked
		if ( $(e.target).is('.toolbar .dashicons') || $(e.target).parents('.toolbar').length > 0 ) {
			return;
		}
		
		// Get the shortcode being dragged
		var wrapper = null;
		if ( $(e.target).is('.wpview-wrap') ) {
			wrapper = $(e.target);
		} else if ( $(e.target).parents('.wpview-wrap:eq(0)').length > 0 ) {
			wrapper = $(e.target).parents('.wpview-wrap:eq(0)');
		}
		if ( wrapper === null ) {
			return;
		}

		// Bugfix: Firefox does not want to release the sortable even after mouseup, this fixes it
		$(editor.getBody()).mouseup();
		
		// Waiting for drag remains true, this means we just clicked on the shortcode,
		// don't initiate a drag
		if ( waitingForDrag ) {
			e.stopImmediatePropagation();

			$(this).trigger('mouseup');
			return;
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
 * Prevent row/column content deletion from making the column untypable
 */
editor.on('keyup', function(e) {
	fixTableParagraphs( editor );
});


/**
 * Prevent delete & backspace buttons from deleting single column rows
 */
editor.on('keydown', function(e) {
	var $ = jQuery;
	
	// If backspace is pressed and we are at the start of the line, ignore
	if ( e.keyCode === 8 ) {
		var range = editor.selection.getRng();
		if ( range.startOffset === 0 ) {
			return true;
		}
	}
	
	// If delete key is pressed and we have a single character, ignore (else the column will get deleted)
	if ( e.keyCode === 46 ) {
        var elem = editor.selection.getNode().parentNode;
        if ( $(editor.selection.getNode().parentNode).is('.pbsandwich_column > tbody > tr > td') ) {
			var range = editor.selection.getRng();
			
            if (range.startOffset === 1 && elem.textContent.length == 1) {
				return true;
            }
        }
	}
	
    // Prevent shift + delete & backspace from deleting with the whole row
    if ( ( e.metaKey || e.altKey || e.ctrlKey ) && ( e.keyCode === 8 || e.keyCode === 46 ) ) {
        try {
            var elem = editor.selection.getNode().parentNode;
            if ( $(editor.selection.getNode().parentNode).is('.pbsandwich_column > tbody > tr > td') ) {
                if ( elem.textContent.length >= 1 && elem.textContent.match( /^[0-9a-zA-Z_]*\s?$/ ) ) {
					editor.selection.getNode().textContent = '';
                    e.preventDefault();
                    return false;
                }
            }
        } catch (e) {}
    }
	
    // Prevent delete & backspace from deleting the whole row
    if ( e.keyCode === 8 || e.keyCode === 46 ) {
        try {
            var elem = editor.selection.getNode().parentNode;
            if ( $(editor.selection.getNode().parentNode).is('.pbsandwich_column > tbody > tr > td') ) {
                if (elem.textContent.length == 1) {
					editor.selection.getNode().textContent = '';
                    e.preventDefault();
                    return false;
                }
            }
        } catch (e) {}
    }
});


/**
 * Properly resize videos, fitvids don't work here, do things manually
 */
editor.on('init', function() {
	var $ = jQuery;
	
	$(editor.getBody()).on('DOMNodeInserted', function() {
		setTimeout( function() {
			$(editor.getBody()).find('[data-wpview-type="embed"] iframe').each(function() {
				$(this).height( $(this).width() * 9 / 16 );
			});
		}, 1 );
	});
	
});

/**
 * Toolbar functionality
 */

editor.on('wptoolbar', function(e) {
	if ( e.collapsed || typeof e.toolbar === 'undefined' ) {
		return;
	}
	
	var $ = jQuery;
	
	var wrapper, newButton, shortcode,
	toolbar = $( '#' + e.toolbar._id );
	
	
	// Get the name of the shortcode
	if ( $(e.element).is('.wpview-wrap') ) {
		shortcode = $(e.element).attr('data-wpview-type');
	} else if ( $(e.element).is('img.alignleft, img.alignright, img.aligncenter, img.alignnone') ) {
		shortcode = 'image';
	}
	
		
	// Add the toolbar buttons
	if ( typeof pbsandwich_column.toolbar_buttons !== 'undefined' ) {
		$.each(pbsandwich_column.toolbar_buttons, function(i, button) {
			
			// Check if we should add in the button
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
			if ( toolbar.find('[data-hash="' + button.hash + '"]').length ) {
				return;
			}
			
			// Create the button
			if ( button.label === '|' ) {
				newButton = $('<div class="mce-widget mce-btn sep"></div>');
			
			} else if ( button.action === '' ) {
				newButton = $('<div class="mce-widget mce-btn toolbar-label"></div>').text( button.label );
			
			} else {
				newButton = $('<div class="mce-widget mce-btn sandwich-toolbar-button" tabindex="-1" role="button" aria-pressed="false"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-dashicon ' + button.icon + '" data-toolbar-action="' + button.action + '"></i></button></div>')
					.attr('aria-label', button.label)
					.attr('title', button.label);
			}
			newButton.attr('data-hash', button.hash);
			
			// Sort the buttons via priority
			if ( button.priority >= 100 ) { // Before the edit button
					newButton.insertBefore( $('.mce-toolbar-grp.mce-inline-toolbar-grp:visible').find('.mce-widget.mce-btn:eq(-2)') );
			} else if ( button.priority >= 0 ) { // Between the edit button and the remove button
					newButton.insertBefore( $('.mce-toolbar-grp.mce-inline-toolbar-grp:visible').find('.mce-widget.mce-btn:eq(-1)') );
			} else { // After the remove button
					newButton.insertBefore( $('.mce-toolbar-grp.mce-inline-toolbar-grp:visible').find('.mce-widget.mce-btn:eq(-1)') );
			}
		});
	}
	
	// Fire toolbar events
	editor.fire( 'show-toolbar-' + shortcode, {
		'editor': editor,
		'target': e.element,
		'shortcode': shortcode,
		'toolbar': toolbar[0]
	} );
	editor.fire( 'show-toolbar', {
		'editor': editor,
		'target': e.element,
		'shortcode': shortcode,
		'toolbar': toolbar[0]
	} );
	
});


	
/**
 * Add the toolbar in columns
 */
editor.on('show-toolbar-column', function(e) {
	var $ = jQuery;
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
			if ( button.label === '|' ) {
				newButton = $('<div class="sep" data-mce-bogus="1"></div>');
				
			} else if ( button.action === '' ) {
				// Instead of outright printing the label, add it as a pseudo element so it won't get printed in the content
				newButton = $('<div class="toolbar-label" data-mce-bogus="1"></div>')
				.addClass('hash-' + button.hash)
				.append('<style>.hash-' + button.hash + ':before { content: "' + button.label.replace(/"/g, '\\\"') + '" }</style>');
				
				
			} else {

				// Create the button
				newButton = $('<div class="' + button.icon + '" data-toolbar-action="' + button.action + '" data-mce-bogus="1"></div>')
					.attr('aria-label', button.label)
					.attr('title', button.label);
			}
			newButton.attr('data-hash', button.hash);
			
			
			// Add the button to the toolbar
			if ( inColumn ) {
			
				// Sort the buttons via priority
				newButton.attr('data-shortcode', 'column');
				if ( button.priority >= 1000 ) {
					newButton.clone().prependTo( toolbar );
				} else if ( button.priority >= 100 ) { // Before the edit button
					newButton.clone().insertBefore( toolbar.find('[data-toolbar-action="column-edit-area"]') );
				} else if ( button.priority >= 0 ) { // Between the edit/clone button and the remove button
					newButton.clone().insertBefore( toolbar.find('[data-toolbar-action="column-remove-area"]') );
				} else { // After the remove button
					newButton.clone().insertAfter( toolbar.find('[data-toolbar-action="column-remove-area"]') );
				}
				
			}
			if ( inRow ) {
			
				// Sort the buttons via priority
				newButton.attr('data-shortcode', 'row');
				if ( button.priority >= 1000 ) {
					newButton.clone().prependTo( toolbar );
				} else if ( button.priority >= 100 ) { // Before the edit button
					newButton.clone().insertBefore( toolbar.find('[data-toolbar-action="column-edit-row"]') );
				} else if ( button.priority >= 0 ) { // Between the edit/clone button and the remove button
					newButton.clone().insertBefore( toolbar.find('[data-toolbar-action="column-remove-row"]') );
				} else { // After the remove button
					newButton.clone().insertAfter( toolbar.find('[data-toolbar-action="column-remove-row"]') );
				}
				
			}
			
		});

	}
	
	
	// Dispatch toolbar show event
	editor.fire( 'toolbar-column-buttons-done', {
		'editor': editor,
		'target': e.target,
		'toolbar': e.toolbar,
		'node': e.node
	} );

	editor.fire( 'show-toolbar', {
		'editor': editor,
		'target': e.target,
		'shortcode': 'column',
		'toolbar': e.toolbar,
	} );
	editor.fire( 'show-toolbar', {
		'editor': editor,
		'target': $(e.target).parents('.pbsandwich_column:eq(0)')[0],
		'shortcode': 'row',
		'toolbar': e.toolbar,
	} );
});


/**
 * Fire toolbar actions (for images only)
 */
jQuery('body').on('mousedown', '.mce-widget.mce-btn, .mce-widget.mce-btn button, [data-toolbar-action]', function(e) {
	var $ = jQuery;
	
	e.preventDefault();
	
	// If the button (not the icon) was clicked
	if ( $(e.target).is(':not([data-toolbar-action])') ) {
		if ( $(e.target).find('[data-toolbar-action]').length === 0 ) {
			return;
		}
		e.target = $(e.target).find('[data-toolbar-action]')[0];
	}
	
	var action = $(e.target).attr('data-toolbar-action');
	var target = $(editor.getBody()).find('[data-mce-selected="1"]:not(.pbsandwich_column)');
	
	editor.fire( 'toolbar-' + action, {
		'action': action,
		'editor': editor,
		'shortcode': 'image',
		'target': target
	} );
	
	e.stopPropagation();
});


/**
 * Fire toolbar actions (for views only & columns/rows)
 */
editor.on('init', function(e) {
	var $ = jQuery;
	
	$(editor.getBody()).on('mousedown', '[data-toolbar-action]', function(e) {
		
		e.preventDefault();
	
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
		
		e.stopPropagation();
		
	});
});

/**
 * Clone button action handler
 */
editor.on('toolbar-clone', function(e) {
	var $ = jQuery;

	preUpdateSortable( editor );
	var newElement = $(e.target).clone();
	newElement.insertAfter( $(e.target) ).trigger('click');
	updateSortable( editor );

	// Cleanup to make views with iframes display again
	if ( newElement.find('iframe').length > 0 ) {
		editor.execCommand( 'mceCleanup' );
	}
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

/**
 * Column change toolbar button
 */
editor.on('toolbar-column-columns', function(e) {
	var colModal = editor.windowManager.open( {
		id: 'pbs-modal',
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
	
	var bgImageURL = $selectedColumn.css('background-image').replace( /url\(('|")?([^\)"']+)('|")?\)/g, '$2' );
	
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
		id: 'pbs-modal',
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

	var bgImageURL = $selectedRow.css('background-image').replace( /url\(('|")?([^\)"']+)('|")?\)/g, '$2' );

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
		id: 'pbs-modal',
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

/**
 * Creates tabs for modal windows
 */
editor.on( 'pre-modal-create-tabs', function(e) {
	var $ = jQuery;
	
	if ( typeof pbsandwich_column.modal_tabs === 'undefined' ) {
		return;
	}
	
	if ( $(e.target).find('.pbsandwich_modal_tabs').length === 0 ) {
		return;
	}
	
	$.each( pbsandwich_column.modal_tabs, function(i, newTabInfo) {
		if ( e.shortcode !== newTabInfo.shortcode ) {
			return;
		}
		
		// Show the tab headings, since they're hidden by default
		$(e.target).find('.pbsandwich_modal_tabs').css('display', '');
		
		// Fire the event to handle template population
		pbs_modal_fields[ newTabInfo.template_id ] = {};
		editor.fire( 'modal-tab-populate-data', {
			'editor': editor,
			'target': e.origin,
			'modal': e.target,
			'template_id': newTabInfo.template_id
		} );
		
		// Add the tab
		$('<div></div>')
			.addClass('pbsandwich_modal_tab')
			.attr( 'data-for', newTabInfo.template_id )
			.text( newTabInfo.name )
			.appendTo( $(e.target).find('.pbsandwich_modal_tabs') );

		// Add the tab's contents
		$('<div></div>')
			.addClass('sandwich_modal')
			.attr( 'id', newTabInfo.template_id )
			.append( wp.template( newTabInfo.template_id )( pbs_modal_fields[ newTabInfo.template_id ] ) )
			.appendTo( $(e.target) )
			.hide();

	});
	
});


editor.on( 'modal-save', function(e) {
	var $ = jQuery;
	if ( $('.pbsandwich_modal_tabs:visible').length > 0 ) {
		$('.pbsandwich_modal_tabs .pbsandwich_modal_tab').each(function() {
			editor.fire( 'modal-tab-save', {
				'template_id': $(this).attr('data-for'),
				'target': e.target,
				'tab': $('#' + $(this).attr('data-for'))[0],
				'action': e.action,
				'shortcode': e.shortcode
			} );
		});
	}
});

/**
 * Jetpack Contact Form
 * Make Shortcake's edit button open up Jetpack's Contact Form UI instead
 */
editor.on('init', function() {
	var $ = jQuery;
	
	$( editor.getBody() ).on('mousedown', '[data-wpview-type="contact-form"] .toolbar .edit', function(e) {
		e.preventDefault();
		$('#insert-jetpack-contact-form').trigger('click');
		return false;
	});
});

/**
 * This is the 4.1 method of adding toolbar buttons to WPViews & images.
 * This was changed in 4.2
 */
if ( pbsandwich_column.wp_version.match( /^4.1/ ) ) {
	
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

					if ( button.label === '|' ) {
						newButton = $('<div class="dashicons sep"></div>');
					
					} else if ( button.action === '' ) {
						newButton = $('<div class="toolbar-label"></div>').text( button.label );
					
					} else {
						newButton = $('<div class="' + button.icon + '" data-toolbar-action="' + button.action + '"></div>')
							.attr('aria-label', button.label)
							.attr('title', button.label);
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
		
		editor.fire( 'show-toolbar', {
			'editor': editor,
			'target': e.target,
			'shortcode': wrapper.attr('data-wpview-type'),
			'toolbar': wrapper.find('.toolbar')[0]
		} );
		
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
					if ( button.label === '|' ) {
						newButton = $('<div class="mce-widget mce-btn sep"></div>');
						
					} else if ( button.action === '' ) {
						newButton = $('<div class="mce-widget mce-btn toolbar-label"></div>').text( button.label );
						
					} else {
						newButton = $('<div class="mce-widget mce-btn sandwich-toolbar-button" tabindex="-1" role="button" aria-pressed="false"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-dashicon ' + button.icon + '" data-toolbar-action="' + button.action + '"></i></button></div>')
							.attr('aria-label', button.label)
							.attr('title', button.label);
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
		
		editor.fire( 'show-toolbar-image', {
			'editor': editor,
			'target': e.target,
			'toolbar': $('.mce-wp-image-toolbar')[0]
		} );
		editor.fire( 'show-toolbar', {
			'editor': editor,
			'target': e.target,
			'shortcode': 'image',
			'toolbar': $('.mce-wp-image-toolbar')[0]
		} );
		
	});
});

}

/**
 * This just closes the TinyMCE plugin call, appended last in editor.js
 */
    });
})();

function _gambit_microtime() {
	return ( new Date ).getTime() / 1000;
}

