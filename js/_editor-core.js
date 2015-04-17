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
		
		e.preventDefault();
		
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