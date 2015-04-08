jQuery(document).ready(function($) {
		var doc = $('pbs-nnd-video-thumb', window.parent.document ).contents();
		var nur = doc.height('auto');
		console.log(nur);
});
/**
 * Click handler for the "Add Post Element" button. Basically we open the WP Media Manager then activate the shortcake state
 */
// jQuery(document).ready(function($) {
// 	$('body').on('click', '.sandwich-add-shortcode', function() {
// 		$(this).siblings('[id="insert-media-button"]').click();
// 		$('.media-menu .media-menu-item:contains("' + shortcodeUIData.strings.media_frame_menu_insert_label + '")').click();
// 		return false;
// 	});
// });
//
// $("#iframe-id").contents().find("img").attr("style","width:100%;height:100%")
//
// $("#iframe-id").contents().find("img").addClass("fancy-zoom")
//
// $("#iframe-id").contents().find("img").onclick(function(){ zoomit($(this)); });