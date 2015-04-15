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
	var element = $('.mce-edit-area')[0]
	var editAreaOffsetTop = element.offsetTop;
	while (element.parentNode) {
	    element = element.parentNode;
		
		// We found these elements to contribute to the correct value of $('.mce-edit-area').offset().top
		// via experimentation in the admin
		if ( $(element).is('#wp-content-wrap, #post-body-content, #poststuff, body') ) {
			if ( ! isNaN( element.offsetTop ) ) {
			    editAreaOffsetTop += element.offsetTop;
			}
		}
	}

	var mouseTop = editAreaOffsetTop + parseInt($('.mce-edit-area').css('paddingTop')) - $(window).scrollTop() + event.pageY;

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

		var element = $(this)[0];
		var childTop,
			childLeft,
			childHeight = element.offsetHeight,
			childWidth = element.offsetWidth;
		
		// Instead of using $(this).offset().top & $(this).offset().left, this is x10 FASTER!
		childTop = element.offsetTop;
		childLeft = element.offsetLeft;
		while (element.parentNode) {
		    element = element.parentNode;
			if ( isNaN( element.offsetTop ) || isNaN( element.offsetLeft ) ) {
				break;
			}
		    childTop += element.offsetTop;
		    childLeft += element.offsetLeft;
		}


		var childBottom = childTop + childHeight,
			childRight = childLeft + childWidth;

		// Check for element intersections
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
			} catch (error) { }

			parent.trigger('mouseup');

			try {
				$(editor.getBody()).sortable('enable');
				$(editor.getBody()).find('.pbsandwich_column td').sortable('enable');
			} catch (error) { }
			
		}
	})
	.on('mousemove', function(e) {
		
		var iframe = $(this).find('.wpview-wrap[data-mce-selected="1"] iframe');
		if ( iframe.length === 0 ) {
			return;
		}
		
		if ( e.which !== 1 ) {
		
			try {
				$(editor.getBody()).sortable('disable');
				$(editor.getBody()).find('.pbsandwich_column td').sortable('disable');
			} catch (error) { }

			iframe.parents('.wpview-wrap:eq(0)').trigger('mouseup');

			try {
				$(editor.getBody()).sortable('enable');
				$(editor.getBody()).find('.pbsandwich_column td').sortable('enable');
			} catch (error) { }
			
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
		fixShortcakeDragging( editor );
		sortableInit = true;
	}
} );


/**
 * Bug fixers
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
	

	/**
	 * Fixes a bug where clicking views/elements in some areas for the first time initiates a drag
	 */
	$( editor.getBody() ).on('mouseup', function(e) {
		
		if ( typeof e.sandwichStop !== 'undefined' ) {
			return;
		}
		
		e.preventDefault();
		var wrapper = null;
		if ( $(e.target).is('.wpview-wrap') ) {
			wrapper = $(e.target);
		} else if ( $(e.target).parents('.wpview-wrap:eq(0)').length > 0 ) {
			wrapper = $(e.target).parents('.wpview-wrap:eq(0)');
		}
		
		if ( wrapper === null ) {
			return;
		}
		
		// Stop the drag
		if ( wrapper.is('[data-mce-selected]') ) {
			e.stopPropagation();
			e.sandwichStop = true;
			$(this).trigger(e);
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
