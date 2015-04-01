/*
 * Break out the sides of the row, retain the content placement
 */
jQuery(document).ready(function($) {
	var applyBreakOut = function() {
		"use strict";
		var $ = jQuery;

		$('.pbs-row-breakout').each(function() {
			var $row = $(this).next();

			if ( $row.length == 0 ) {
				return;
			}

			if ( typeof $(this).attr('data-break-parents') === 'undefined' ) {
				return;
			}

			var breakNum = parseInt( $(this).attr('data-break-parents') );
			if ( isNaN( breakNum ) ) {
				return;
			}

			// Find the parent we're breaking away to
			var $parent = $row.parent();
			for ( var i = 0; i < breakNum; i++ ) {
				if ( $parent.is('html') ) {
					break;
				}
				$parent = $parent.parent();
			}

			// Remember the original margin & paddings, OR bring them back to their defaults
			if ( typeof $row.attr('data-orig-margin-left') === 'undefined' ) {
				$row.attr('data-orig-margin-left', $row.css('marginLeft'));
				$row.attr('data-orig-padding-left', $row.css('paddingLeft'));
				$row.attr('data-orig-margin-right', $row.css('marginRight'));
				$row.attr('data-orig-padding-right', $row.css('paddingRight'));
			} else {
				// we need to do it this way since !important cannot be placed by jQuery
				$row[0].style.removeProperty( 'margin-left' );
				$row[0].style.removeProperty( 'padding-left' );
				$row[0].style.removeProperty( 'margin-right' );
				$row[0].style.removeProperty( 'padding-right' );
				$row[0].style.setProperty( 'margin-left', $row.attr('data-orig-margin-left'), 'important' );
				$row[0].style.setProperty( 'padding-left', $row.attr('data-orig-padding-left'), 'important' );
				$row[0].style.setProperty( 'margin-right', $row.attr('data-orig-margin-right'), 'important' );
				$row[0].style.setProperty( 'padding-right', $row.attr('data-orig-padding-right'), 'important' );
			}

			// Compute dimensions & location
			var parentWidth = $parent.width() +
				              parseInt( $parent.css('paddingLeft') ) +
				              parseInt( $parent.css('paddingRight') );
			var rowWidth = $row.width() +
				           parseInt( $row.css('paddingLeft') ) +
				           parseInt( $row.css('paddingRight') );

			var left = $row.offset().left - $parent.offset().left;
			var right = ( $parent.offset().left + parentWidth ) - ( $row.offset().left + rowWidth );

			var marginLeft = parseFloat( $row.css('marginLeft') );
			var marginRight = parseFloat( $row.css('marginRight') );
			var paddingLeft = parseFloat( $row.css('paddingLeft') );
			var paddingRight = parseFloat( $row.css('paddingRight') );

			marginLeft -= left;
			paddingLeft += left;
			marginRight -= right;
			paddingRight += right;

			// Apply the new margin & paddings, we need to do it this way since !important cannot be
			// placed by jQuery
			$row[0].style.removeProperty( 'margin-left' );
			$row[0].style.removeProperty( 'padding-left' );
			$row[0].style.removeProperty( 'margin-right' );
			$row[0].style.removeProperty( 'padding-right' );
			$row[0].style.setProperty( 'margin-left', marginLeft + 'px', 'important' );
			$row[0].style.setProperty( 'padding-left', paddingLeft + 'px', 'important' );
			$row[0].style.setProperty( 'margin-right', marginRight + 'px', 'important' );
			$row[0].style.setProperty( 'padding-right', paddingRight + 'px', 'important' );

			$row.addClass( 'broke-out broke-out-' + breakNum );
		});
	};
	$(window).resize(applyBreakOut);
	applyBreakOut();
});	