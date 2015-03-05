/**
 * Make sure embedded videos have a correct height
 */
jQuery(document).ready(function($) {
	jQuery('.pbsandwich_column [class*=col-]').fitVids({
	  customSelector: "iframe"
	});
});