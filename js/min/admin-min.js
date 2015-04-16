jQuery(document).ready(function($) {
	
	/**
	 * Jetpack Contact Form
	 * replace Shortcake's UI with Jetpack's own UI for the contact form shortcode
	 */
	$('body').on('mousedown', '.media-frame [data-shortcode="contact-form"]', function(e) {
		e.preventDefault();
		wp.media.frame.close();
		$('#insert-jetpack-contact-form').trigger('click');
		return false;
	});
	
});

jQuery(document).ready(function($) {
	/**
	 * Tabs
	 */
	$('body').on('click', '.pbsandwich_modal_tab[data-for]', function() {
		var modal = $(this).parents('.mce-container:eq(0)');
		var tabContainer = $(this).parents('.pbsandwich_modal_tabs:eq(0)');
		tabContainer.siblings().hide();
		modal.find('#' + $(this).attr('data-for')).show();
		tabContainer.find('.pbsandwich_modal_tab').removeClass('active');
		$(this).addClass('active');
	});
});

function _gambit_microtime() {
	return ( new Date ).getTime() / 1000;
}

// @codekit-prepend "_admin-jetpack.js"
// @codekit-prepend "_admin-core.js"
// @codekit-prepend "_util.js"

