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