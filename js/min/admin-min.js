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

function _gambit_microtime() {
	return ( new Date ).getTime() / 1000;
}

// @codekit-prepend "_admin-jetpack.js"
// @codekit-prepend "_util.js"

