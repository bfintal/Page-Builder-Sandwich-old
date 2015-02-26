/**
 * Click handler for the "Add Post Element" button. Basically we open the WP Media Manager then activate the shortcake state
 */
jQuery(document).ready(function($) {
	$('body').on('click', '.sandwich-add-shortcode', function() {
		$(this).siblings('[id="insert-media-button"]').click();
		wp.media.frame.setState('shortcode-ui');
		return false;
	});
});