/**
 * For some reason, woocommerce stuff don't have a "product" class, 
 * because of that, styles are not being applied properly. Bring it back
 */
editor.on('PostProcess', function(e) {
	var $ = jQuery;
	
	$(editor.getBody()).find('.wpview-type-recent_products li.type-product').addClass('product');
});