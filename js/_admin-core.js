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