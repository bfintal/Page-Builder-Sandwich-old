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

// @codekit-prepend "_admin-jetpack.js"

